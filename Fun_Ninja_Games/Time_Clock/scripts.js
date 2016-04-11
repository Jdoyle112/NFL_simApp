var time = 0;
var running = 0;
var value = '';
var clickedId;	
var html = "";	


function startPause(){
	if(running == 0){
		running = 1;
		var classN = document.getElementById('output').className;
		if(classN == "stop"){
			increment();
		} else{
			decrement();
		}
	
		document.getElementById("startPause").innerHTML = "Pause";
	}else{
		running = 0;
		document.getElementById("startPause").innerHTML = "Resume";
	}
}

function reset(){
	running = 0;
	time = 0;
	document.getElementById("startPause").innerHTML = "Start";
	document.getElementById("output").innerHTML = "00:00:00";
}

function increment(){
	if(running == 1){
	setTimeout(function(){
	time++;
	var mins = Math.floor(time/10/60);
	var secs = Math.floor(time/10 % 60);
	var tenths = time % 10;
						
	if(mins < 10){
		mins = "0" + mins;
	}
	if(secs < 10){
		secs = "0" + secs;
	}
	document.getElementById("output").innerHTML = mins + ":" + secs + ":" + "0" + tenths;
	increment();
						
		},100);
	}
}

function decrement(){

	if(running == 1){
		// get min, and sec
		current = document.getElementById("output").innerHTML;
		//current = parseInt(current);
		var mins = current.slice(0, 2);
		var secs = current.slice(3, 5);
		console.log(mins);
		console.log(secs);
		time = 10;
		var interval = setInterval(function(){
			time--;
			sec = Math.floor(time/10 % 60);
			var tenths = time % 10;
			//console.log(mins);
			//console.log(secs);	
			if(time == 0){
				time = 10;
				secs--;
			}

			if(secs == 0 && mins > 0){
				mins--;
			}

			if(mins < 1){
				mins = '00';
			} else if(mins < 10){
				mins = "0" + mins;
			}

			if(secs < 1 && mins > 0){
				secs = 59;
			} 


			if(secs < 1 && mins < 1 && tenths < 1){
				clearInterval(interval);
				secs = '00';
				mins = '00';
			}


			document.getElementById("output").innerHTML = mins + ":" + secs + ":" + "0" + tenths;

		}, 100);
		interval();
	}
}


$(document).ready(function(){

var init = 0;
var counter = 0;

/*
startPause();
reset();	
increment();	
decrement();*/

var min = 0;
var newSec = 59;

	$('#up').click(function(){
		// add time in sec	
		var inner = $('#output').text();
		var sec = inner.substring(3,5);
		sec = parseInt(sec);
		sec++;
		if(sec < 10){
			var out = "0" + min + ":0" + sec + ":" + "00";
			$('#output').text(out);
		} else if(sec > 9 && sec < 60){
			var out = "0" + min + ":" + sec + ":" + "00";
			$('#output').text(out);
		} else if(sec > 59){
			sec = 0;
			min++;
			var out =  "0" + min +  ":" + sec + ":" + "00";
			$('#output').text(out);
		}
	});

	$('#down').click(function(){
		// subtract time in sec
		var inner = $('#output').text();
		var sec = inner.substring(3,5);		
		sec = parseInt(sec);
		sec--;
		if(sec < 10 && sec > -1){
			var out = "0" + min + ":0" + sec + ":" + "00";
			$('#output').text(out);
		} else if(sec > 9 && sec < 60){
			var out = "0" + min + ":" + sec + ":" + "00";
			$('#output').text(out);
		} else if(min > 0 && sec < 1){
			min--;
			var out =  "0" + min +  ":" + newSec + ":" + "00";
			$('#output').text(out);
			newSec--;
		} else if(min < 1 && sec < 0){
			sec = 0;
			var out =  "0" + min +  ":00" + ":" + "00";
			$('#output').text(out);
		}

	});


	$('.points button').click(function(){
		// get button id
		var str = $(this).attr('id');	
		var id = str.slice(-1);
		id = parseInt(id);

		// get val of correspondign p element
		var pId = $('#p' + id).text();
		if(pId == ""){
			pId = 0;
		}else{
			pId = parseInt(pId);
		}
		
		var score = (pId + 1);
		$('#p' + id).text(score);
	});


	// thumbnail click 
	$('.thumbnail').click(function(){
		$('.thumbnail').removeClass('selected');
		$(this).addClass('selected');
	});

	// reset thumbnail click
	$('#reset').click(function(){
		$('.thumbnail').removeClass('selected');
	});

	//var par = [];

	// add delete ability
	$(document).on('click', '.close', function(){
		par = $(this).next().attr('src');
		
		$('<input type="hidden" id="hidden" name="hidden[]" value="'+par+'">').appendTo('.settings');
		
		$(this).parent().remove();
	});

});


	








