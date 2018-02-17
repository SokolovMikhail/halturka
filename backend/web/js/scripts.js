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
						url: '/quiz/delete/?id=' + deleteId,
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
	 Удаление Вопроса
	 --------------------------------------------- */	
	$('body').on('click', '[data-question-delete]', function(){
		var deleteId = $(this).data('question-delete');
		var parentWrap = $('[data-question-row="'+deleteId+'"]');
			
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление темы',
			message: 'Вы действительно хотите удалить опрос?',
			callback: function(result){
				if (result){					
					$.ajax({
						url: '/question/delete/?id=' + deleteId,
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
	 Удаление ответа
	 --------------------------------------------- */	
	$('body').on('click', '[data-answer-delete]', function(){
		var deleteId = $(this).data('answer-delete');
		var parentWrap = $('[data-answer-row="'+deleteId+'"]');
			
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление темы',
			message: 'Вы действительно хотите удалить ответ?',
			callback: function(result){
				if (result){					
					$.ajax({
						url: '/answer/delete/?id=' + deleteId,
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