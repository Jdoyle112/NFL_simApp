$(document).ready(function(){

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