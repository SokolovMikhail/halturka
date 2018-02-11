$('body').on('click', '[data-help]', function(){
	var moduleName = $(this).data('module-name');
	var file = $(this).data('file-name');
	$(this).attr('disabled', true);
	var helpButton = $(this);
	$(this).removeClass('clickable');
	// $(this).addClass('fa fa-spinner fa-pulse fa-3x fa-fw fnt-16 help-icon');		
	var url = '/help/interactive-help/?module=' + moduleName + '&file=' + file;
	$.ajax({
		url: url,
		success: function(result){
			if(result){
				// helpButton.removeClass();
				helpButton.addClass('clickable');
				helpButton.attr('disabled', false);
				var modalBody = $('[data-modal-body]');
				modalBody.empty();
				modalBody.html(result);
				$('#helpModal').modal('show');
			}else{
				console.log('Справка не нашлась');
			}
		},			
	});		
	
});