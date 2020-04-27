jQuery( document ).ready( function( $ ) {
	console.log( "ready!" );
	// attr() method applied here 
	$(window).load( function() {
		if($("input#title").val()==""){ 
			$("input#publish").prop('disabled', true);
		}else{
			$("input#publish").prop('disabled', false);
		}
		$("input#title").on("keyup", function(){
			if($(this).val()!="") { $("input#publish").prop('disabled', false); }
		}) 
	});

});



