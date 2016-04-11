
$(document).ready(function(){

	// start function when button clicked
	$('#roll').click(function(){
		$('.dice img').remove();
		var numDice = $('.numDice').length;
		for(var i = 1; i <= numDice; i++){
			var outcome = Math.floor(Math.random()*6) + 1;
			if(outcome == 1){
				$('#output #p' + i).prepend('<img src="images/dice1.png" />');
			}else if(outcome == 2){
				$('#output #p' + i).prepend('<img src="images/dice2.png" />');
			}else if(outcome == 3){
				$('#output #p' + i).prepend('<img src="images/dice3.png" />');
			}else if(outcome == 4){
				$('#output #p' + i).prepend('<img src="images/dice4.png" />');
			}else if(outcome == 5){
				$('#output #p' + i).prepend('<img src="images/dice5.png" />');
			} else {
				$('#output #p' + i).prepend('<img src="images/dice6.png" />');
			}

			//$('#output #p' + i).text("You rolled a " + outcome + "!");
		}		
	});


});