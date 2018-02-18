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
			title: 'Переход на другой опрос',
			message: 'Че то про переход',
			buttons: {
				confirm: {
					label: 'Переход',
					className: 'btn-primary'
				},
			},			
			callback: function(result){
				if (result){					
					url = "/quiz/index/?quizId="+ quizRedirectId;
					$( location ).attr("href", url);					
				}else{
					console.log('Ну ладно');
				}
			}
		});		
	});		 
});
