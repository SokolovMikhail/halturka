$( document ).ready(function() {
	
	bootbox.setLocale("ru");
	/* ---------------------------------------------
	 Floating Save button
	 --------------------------------------------- */		
	$('body').on('click', '[data-float-button-target]', function(){
		var target = $(this).data('float-button-target');
		var buttonToPress = $('[' + target + ']');
		buttonToPress.trigger( "click" );

		console.log($('[' + target + ']'), target);
	});	
	
	$('body').on('click', '[data-topic-delete]', function(){
		var deleteId = $(this).data('topic-delete');
		var parentWrap = $('[data-topic-row="'+deleteId+'"]');
			
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление темы',
			message: 'Вы действительно хотите удалить тему и все связанные с ней опросы?',
			callback: function(result){
				if (result){					
					$.ajax({
						url: '/site/delete/?id=' + deleteId,
						success: function(result){
							if(result){
								parentWrap.remove();
							}				
						}
					});					
				}else{
					console.log('Ну ладно');
				}
			}
		});		
	});	
});