var speak = true;
var debug = false;

function init()
{
	$('#input').focus();
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
	var div = document.getElementById('output');
	div.innerHTML = div.innerHTML + '<span class="line"><span class="' + source + '">' + source + ': </span>' + msg + '</span>';
	div.scrollTop = div.scrollHeight;
	$("#output .line a").attr("target","_blank");
	if(debug == true) {
		$("#output .debug").hide();
	}
	setupCourseList();
	if(source == "elise" && speak == true) {
	}
}
	
