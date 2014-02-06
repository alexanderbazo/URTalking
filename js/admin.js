var topics;

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
				templates.push($(value).html());
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
		showCategoryList($(e.target).attr('topic'));
	});
}

function showCategoryList(topic) {
	list = $('#category_list ul');
	$(list).empty();
	$.each(topics[topic], function(index, value) {
		   $(list).append('<li index="'+value.index+'" class="category_entry">'+value.pattern+'</li>');
	});
}

