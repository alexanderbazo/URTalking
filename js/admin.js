var topics;
var current_topic;
var current_category_index;

function init()
{
	topics = new Array();
	getAimlXML();
}

function getAimlXML() {
	$.ajax({
		   type: 'POST',
		   url: 'php/api.php',
		   data: 'request=xml',
		   success: parseXML
		   });
	
}

function parseXML(data) {
	xmlDoc = $.parseXML(data);
	$xml = $(xmlDoc);
	$categories = $xml.find('category');
	$.each($categories, function(index,value) {
		topic = $(value).attr('topic');
		pattern = $(value).find('pattern').html();
		that = $(value).find('that').html();
		
		templates = new Array();
		template_node = $(value).find('template');
		
		if($(template_node).find('li').length > 0) {
		   	$.each($(template_node).find('li'), function(index,value) {
				tmp = $(value).html().trim();
				templates.push(tmp);
			});
		} else {
			templates.push($(template_node).html());
		}
		
		
		category = {index:index,topic:topic,pattern:pattern,that:that,templates:templates};

				
		
		if(topic != undefined && topics[topic] != undefined) {
			topics[topic].push(category);
		} else {
			topics[topic] = new Array();
			topics[topic].push(category);
		}
	});
	
	showTopicList();
}

function showTopicList() {
	list = $('#topic_list ul');
	$(list).empty();
	$.each(Object.keys(topics), function(index, value) {
		$(list).append('<li topic="'+value+'" class="topic_entry">'+value+'</li>');
	});
	
	$(list).children().click(function(e) {
		$('#topic_list ul').children().removeClass('selected');
		$(e.target).addClass('selected');
		topicChanged($(e.target).attr('topic'));
	});
}

function topicChanged(topic) {
	current_topic = topic;
	showCategoryList();
}

function showCategoryList() {
	topic = current_topic;
	list = $('#category_list ul');
	$(list).empty();
	$.each(topics[topic], function(index, value) {
			indexstring = index;
			if(index < 10) {
		   		indexstring = "0"+index;
		   	}
		    $(list).append('<li topic ="'+value.topic+'" index="'+value.index+'" class="category_entry">'+indexstring+'|'+value.pattern+'</li>');
	});
	
	$(list).children().click(function(e) {
		$('#category_list ul').children().removeClass('selected');
		$(e.target).addClass('selected');
		
		topic = $(e.target).attr('topic');
		index = $(e.target).attr('index');
		categoryChanged(index);
	 });
}

function categoryChanged(category_index) {
	current_category_index = category_index;
	showCategory();
}

function showCategory() {
	$('#editor').css('visibility', 'visible');
	category = getCategoryByIndex(current_topic, current_category_index)[0];
	$('#editor #pattern').val(category.pattern);
	$('#editor #that').val(category.that);
	
	$('#editor').find('.template').remove();
	$.each(category.templates, function(index, value) {
		tmp = value.replace(/"/g, '&quot;').trim();
		template = '<input class="single_line template" index='+index+' type="text" value="'+tmp+'" /><span class="input_label template">Template '+index+'</span>';
		$('#editor').append(template);
	});
}

function getCategoryByIndex(topic, index) {
	return $.grep(topics[topic], function(e) { return e.index == index });
}

