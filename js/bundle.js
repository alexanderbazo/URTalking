var speak = true;
var debug = false;
var topics_json;
var current_topic;

function init()
{
	setupDatasets();
	$('#input').focus();
	$('#myonoffswitch').change(switchDebugState);
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

	getAndProcessAimlXML();
}

function getAndProcessAimlXML() {
	$.ajax({
		type: 'POST',
		url: 'php/api.php',
		data: {request: 'xml', query: 'xml'},
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
					templates.push(tmp);
				});
			} else {
				templates.push($(template_node).html());
			}

			category = {index:index,topic:topic,pattern:pattern,that:that,templates:templates};
			addCategory(topic, index, category);
	});
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

function addTopic(topic) {
	if(topics_json.topics[topic] == undefined) {
		topics_json.topics[topic] = [];
	}
}

function switchDebugState()
{
	debug = !debug;
	if(debug == false) {
		$("#output .debug").hide();
	} else {
		$("#output .debug").show();
	}
}

function getCategoryForPattern(pattern) {
	for (var category in topics_json.topics) {
	  	for (var index in topics_json.topics[category]) {
				if(topics_json.topics[category].hasOwnProperty(index)) {
					if(pattern == topics_json.topics[category][index].pattern) {
							return category;
					}
				}
			}
	}
	return "";
}

function processInput(input)
{
	var div = document.getElementById('input');
	div.value = "";


	if(input == "")
	{
		return;
	}

	if(input == "clear")
	{
		clearOutput();
		return;
	}

	writeToOutput(input, 'me');
	sendAimlRequestToServer(input);
}

function sendAimlRequestToServer(query)
{
	$.ajax({
		   type: "POST",
		   url: 'php/api.php',
		   data: 'request=aiml&query='+query+'&file=uni_regensburg.aiml',
		   success: processServerResponse
		   });
}

function queryDatabase(query, value, callback) {
	$.ajax({
		   type: "POST",
		   url: 'php/api.php',
		   data: 'request=database&query='+query+'&value='+value,
		   success: callback
		   });

}

function processServerResponse(response)
{
	console.log(response);
	writeToOutput(decodeURI(response), 'elise');
}

function setupCourseList() {
	$('.course').mouseenter(function(e) {
		$('#course_info').removeClass('hidden');
		$('#course_info .course_nr').html($(e.target).attr('nr'));
		$('#course_info').css('top', event.pageY + 20);
		$('#course_info').css('left', event.pageX);
		queryDatabase("COURSE_TITLE", $(e.target).attr('nr'), function(data) {
			$('#course_info .course_title').html(data);
		});
		queryDatabase("COURSE_MODULE", $(e.target).attr('nr'), function(data) {
			data = data.substring(0, data.length - 2)
			$('#course_info .course_modules').html(data);
		});
	});
	$('.course').mouseleave(function() {
		$('#course_info').addClass('hidden');
	});
}

function clearOutput()
{
	var div = document.getElementById('output');
	div.innerHTML = '';
	div.scrollTop = 0;
}

function writeToOutput(msg, source)
{
	var pattern = msg.substring(msg.lastIndexOf("aimlized query: ")+16,msg.lastIndexOf(" ["));
	var category = getCategoryForPattern(pattern);

	var div = document.getElementById('output');
	div.innerHTML = div.innerHTML + '<span class="line"><span class="' + source + '">' + source + ': </span>' + msg;
	if(source == 'elise') {
		div.innerHTML = div.innerHTML + '<span class="debug"> Kategorie: ' + category + '</span>';
	}
	div.innerHTML = div.innerHTML + '</span>';
	div.scrollTop = div.scrollHeight;
	$("#output .line a").attr("target","_blank");
	if(debug == true) {
		$("#output .debug").hide();
	}
	setupCourseList();
	if(source == "elise" && speak == true) {
	}
<<<<<<< HEAD
}
	
=======

}
>>>>>>> 8990bb41d2526fb4ff43a2069afac0007b418dec
