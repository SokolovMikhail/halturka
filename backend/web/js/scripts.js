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


	/* ---------------------------------------------
	 Удаление темы
	 --------------------------------------------- */		
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
						url: '/topic/delete/?id=' + deleteId,
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


	/* ---------------------------------------------
	 Удаление опроса
	 --------------------------------------------- */	
	$('body').on('click', '[data-quiz-delete]', function(){
		var deleteId = $(this).data('quiz-delete');
		var parentWrap = $('[data-quiz-row="'+deleteId+'"]');
			
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление темы',
			message: 'Вы действительно хотите удалить опрос?',
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