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

function writeToOutput(msg, source)
{
	var div = document.getElementById('output');
	div.innerHTML = div.innerHTML + '<span class="line"><span class="' + source + '">' + source + ': </span>' + msg + '</span>';
	div.scrollTop = div.scrollHeight;
}

function clearOutput()
{
	var div = document.getElementById('output');
	div.innerHTML = '';
	div.scrollTop = 0;
}

function sendAimlRequestToServer(request)
{
	$.ajax({
		   type: "POST",
		   url: 'api.php',
		   data: 'request='+request+'&aiml=uni_regensburg.aiml',
		   success: processServerResponse
		   });
}


function processServerResponse(response)
{
	writeToOutput(response, 'elies');
}