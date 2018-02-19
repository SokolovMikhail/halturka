$( document ).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
	
	/* ---------------------------------------------
	 Floating Save button
	 --------------------------------------------- */		
	$('body').on('click', '[data-float-button-target]', function(){
		var target = $(this).data('float-button-target');
		var buttonToPress = $('[' + target + ']');
		buttonToPress.trigger( "click" );

		console.log($('[' + target + ']'), target);
	});		
});

