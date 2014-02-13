var topics_json;
var current_topic;
var current_category_index;
var current_action;


/*
	SETUP
*/

function init()
{
	setupDatasets();
	setupUI();
	getAndProcessAimlXML();
}

function setupDatasets() {
	topics_json = {'topics': {}};
	
	Array.prototype.clean = function(deleteValue) {
		for (var i = 0; i < this.length; i++) {
			if (this[i] == deleteValue) {
				this.splice(i, 1);
				i--;
			}
		}
		return this;
	};
}

function setupUI() {
	$('#save_changes').click(updateAimlOnServer);
	$('#new_entry .cancel').click(cancelCurrentAction);
	$('#new_entry .add').click(completeCurrentAction);
	
	$('#topic_list .add').click(function(){
		startNewAction("Thema");
	});
	$('#category_list .add').click(function(){
		startNewAction("Frage");
	});
	
	
}

/*
	Topic List
*/

function showTopicList() {
	list = $('#topic_list ul');
	$(list).empty();
	
	$.each(Object.keys(topics_json.topics), function(index, value) {
		$(list).append('<li topic="'+value+'" class="topic_entry">'+value+'</li>');
	});
	
	$(list).children().unbind();
	$(list).children().click(function(e) {
		$('#topic_list ul').children().removeClass('selected');
		$(e.target).addClass('selected');
		topicChanged($(e.target).attr('topic'));
	});
}

function topicChanged(topic) {
	current_topic = topic;
	showCategoryList();
	$('#editor').css('visibility', 'hidden');
}

function topicAdded(topic) {
	current_topic = topic;
	showTopicList();
	showCategoryList();
	$('#topic_list ul').children().removeClass('selected');
	$('#topic_list ul li').last().addClass('selected');
}


function addTopic(topic) {
	if(topics_json.topics[topic] == undefined) {
		topics_json.topics[topic] = [];
	}
}

/*
	Category List
*/

function showCategoryList() {
	topic = current_topic;
	list = $('#category_list ul');
	$(list).empty();
	
	index = 0;
	for(var value in topics_json.topics[topic]) {
		value = topics_json.topics[topic][value];
		if(typeof(value) == 'function') {
			continue;
		}
		indexstring = index;
		if(index < 10) {
			indexstring = "0"+index;
		}
		$(list).append('<li topic ="'+value.topic+'" index="'+value.index+'" class="category_entry">'+indexstring+'|'+value.pattern+'</li>');
		
		index++;
	}
	
	
	$(list).children().unbind();
	$(list).children().click(function(e) {
		$('#category_list ul').children().removeClass('selected');
		$(e.target).addClass('selected');
		
		topic = $(e.target).attr('topic');
		index = $(e.target).attr('index');
		categoryChanged(index);
	 });
}

/*
	Category
*/

function showCategory() {
	$('#editor').css('visibility', 'visible');
	category = getCategoryByIndex(current_topic, current_category_index);
	$('#editor #pattern').val(category.pattern);
	$('#editor #that').val(category.that);
	
	$('#editor').find('.template').remove();
	$.each(category.templates, function(index, value) {
		label_index = index+1;
		tmp = value.replace(/"/g, '&quot;').trim();
		template = '<input class="single_line template" id="template-'+index+'" type="text" value="'+tmp+'" /><span class="input_label template">Template '+label_index+'</span>';
		$(template).insertBefore('#editor .newtemplate');
	});
	
	$('#editor input').unbind();
	$('#editor input').keyup(function(e) {
		onInputValueChanged(e);
	});
	
}

function categoryChanged(category_index) {
	current_category_index = category_index;
	showCategory();
}
		   
function categoryAdded(category_index) {
   current_category_index = category_index;
   showCategoryList();
   showCategory();
   $('#category_list ul').children().removeClass('selected');
   $('#category_list ul li').last().addClass('selected');
}

function getCategoryByIndex(topic, index) {
	for(var value in topics_json.topics[topic]) {
		   value = topics_json.topics[topic][value];
		   if(value.index == index) {
		   	return value;
		   }
	}
	return undefined;
}
		   
function addCategory(topic, index, category) {
	if(topics_json.topics[topic] == undefined) {
		addTopic(topic);
		topics_json.topics[topic].clean();
	}
	if(topics_json.topics[topic][index] == undefined) {
		topics_json.topics[topic][index] = category;
	}
}
		   
function removeCurrentCategory() {
	removeCategoryIdentifiedByIndex(current_topic, current_category_index);
}

function removeCategoryIdentifiedByIndex(topic, index) {
	topics_json.topics[topic].splice(index,1);
	if(Object.keys(topics_json.topics[topic]).length == 0) {
		current_category_index = 0;
		showCategoryList();
		$('#editor').css('visibility', 'hidden');
		return;
	}
	showCategoryList();
	current_category_index = 0;
	showCategory();
	$('#category_list ul').children().removeClass('selected');
	$('#category_list ul li').first().addClass('selected');
}

		   
/*
	Server Actions
*/

function updateAimlOnServer() {
	$('#upload .button').css('visibility', 'hidden');
	$('#upload').css('visibility', 'visible');
	$('body #loading').css('visibility', 'visible');
	
	passwordhash = $.md5($('#password').val());
	categoriesonly = {};
	index = 0;
	for(var topic in topics_json.topics) {
		for(cat in topics_json.topics[topic]) {
			value = topics_json.topics[topic][cat];
		   	if(typeof(value) == 'function') {
		   		continue;
		   	}
			categoriesonly[index] = value;
			index++;
		}
	}

	$.ajax({
		type: "POST",
		url: 'php/api.php',
		data: {'request': 'update', 'passwordhash': passwordhash, 'categories': JSON.stringify(categoriesonly)},
		success: onServerUpdateResult
	});
}

function getAndProcessAimlXML() {
	$.ajax({
		type: 'POST',
		url: 'php/api.php',
		data: 'request=xml',
		cache: false,
		success: parseXML
	});
}
		   
function parseXML(data) {
	xmlDoc = $.parseXML(data);
	$xml = $(xmlDoc);
	$categories = $xml.find('category');
	$.each($categories, function(index,value) {
		topic = $(value).attr('topic');
			if(current_topic == undefined) {
				current_topic = topic;
			}
			pattern = $(value).find('pattern').html();
			that = $(value).find('that').html();
				  
			templates = new Array();
			template_node = $(value).find('template');
				  
			if($(template_node).find('li').length > 0) {
				$.each($(template_node).find('li'), function(index,value) {
					tmp = $(value).html().trim();
					
					templates.push(htmlDecode(tmp));
				});
			} else {
				templates.push(htmlDecode($(template_node).html()));
			}
				  
			category = {index:index,topic:topic,pattern:pattern,that:that,templates:templates};
			addCategory(topic, index, category);
	});
		   
	showTopicList();
	$('#topic_list ul li').first().addClass('selected');
	showCategoryList();
	
	delayUIafterLoading(1);
}

function htmlDecode(input){
	var e = document.createElement('div');
	e.innerHTML = input;
	return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}
		   
function onServerUpdateResult(data) {
	console.log(data);
	delayUIafterSaving(data, 1);
}

/*
	UI Callbacks
*/

function onInputValueChanged(e) {
	cat = getCategoryByIndex(current_topic, current_category_index);
	newValue = $(e.target).val();
	id = $(e.target).attr('id');
	switch(id) {
		case 'pattern':
			cat.pattern = newValue;
			break;
		case 'that':
			cat.that = newValue;
			break;
		default:
			if(id.indexOf('template') != -1) {
				templateIndex = id.split('-')[1];
				if(newValue == "") {
					cat.templates.splice(templateIndex,1);
					return;
				}
				cat.templates[templateIndex] = newValue;
			}
			break;
	}
}


/*
	UI Actions
*/

function delayUIafterLoading(seconds) {
	setTimeout(function() {
		$('body #loading').css('visibility', 'hidden');
	},seconds*1000);
}

function delayUIafterSaving(data, seconds) {
	setTimeout(function() {
		switch(data) {
			case '1':
			   $('#upload .text').html('Daten wurden auf dem Server gespeichert.');
			   break;
			case '-1':
			   $('#upload .text').html('Fehler bei der Aktualisierung: Falsches Passwort.');
			   break;
			default:
			   $('#upload .text').html('Fehler bei der Aktualisierung: Unbekannter Fehler.');
			   break;
		}
		$('#upload .button').css('visibility', 'visible');
		$('body #loading').css('visibility', 'hidden');
	},seconds*1000);
}

function addNewTemplateToEditor() {
	index = $('#editor .single_line.template').length;
	label_index = index+1;
	template = '<input class="single_line template" id="template-'+index+'" type="text" value="" /><span class="input_label template">Template '+label_index+'</span>';
	$(template).insertBefore('#editor .newtemplate');
		   
	$('#editor input').unbind();
	$('#editor input').keyup(function(e) {
		onInputValueChanged(e);
	});
}
		   
function discardUploadDialog() {
	$('#upload').css('visibility', 'hidden');
	$('#upload .button').css('visibility', 'hidden');
}
		   
function startNewAction(type) {
	current_action = type;
	$('#new_entry').css("visibility", "visible");
	$('#new_entry #entry').focus();
	switch(type) {
		case 'Thema':
			$('#new_entry h2 .type').html('Neues Thema');
			break;
		case 'Frage':
			$('#new_entry h2 .type').html('Neue Frage');
			break;
		default:
			break;
	}
	
	$('#new_entry #entry').val('');
}

function cancelCurrentAction() {
	current_action = undefined;
	$('#new_entry').css("visibility", "hidden");
}

function completeCurrentAction() {
	current_input_value = $('#new_entry #entry').val();
	switch(current_action) {
		case 'Thema':
		   	addTopic(current_input_value);
			topicAdded(current_input_value);
			break;
		case 'Frage':
			index = topics_json.topics[current_topic].length;
			category = {index:index,topic:current_topic,pattern:current_input_value,that:"",templates:[""]};
			addCategory(current_topic, index, category);
			categoryAdded(index);
		   	break;
		default:
		 	break;
	}
	cancelCurrentAction();
}
