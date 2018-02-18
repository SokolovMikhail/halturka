$( document ).ready(function() {
	/* ---------------------------------------------
	 Инициализация bootbox
	 --------------------------------------------- */
	bootbox.setLocale("ru");
	
	/* ---------------------------------------------
	 Инициализация bootbox
	 --------------------------------------------- */	
	$('body').on('click', '[data-redirect-id]', function(){
		var answer = $(this);
		
		var quizRedirectId = $(this).data('redirect-id');
			
		bootbox.confirm({ 
			size: 'small',
			title: 'Пожалуйста перейдите на другую форму иска',
			message: 'Вы выбрали не ту форму иска.<br>Мы подобрали более подходящую для вас.',
			buttons: {
				confirm: {
					label: 'Переход',
					className: 'btn-primary'
				},
			},			
			callback: function(result){
				if (result){					
					url = "/process/index/?quizId="+ quizRedirectId;
					$( location ).attr("href", url);					
				}else{
					console.log('Ну ладно');
				}
			}
		});		
	});		 
});
