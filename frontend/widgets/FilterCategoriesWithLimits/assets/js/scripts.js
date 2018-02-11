//
// Логика табов
//
$( document ).ready(function() {
	
	$('body').on('click', '[data-label-cat]', function(){
		var checkb = $(this).children('[data-input-cat]');
		var id = $(this).data('label-cat');
		var flag = checkb.prop("checked");
		// console.log(flag);
		if(flag){
			var tab = $('[data-button-cat-tab=' + id + ']');
			var content = $('[data-button-cat-tab-content=' + id + ']');
			tab.addClass('dsp-n');
			content.addClass('dsp-n');
			//Передача active
			if(tab.hasClass('active')){
				
				tab.removeClass('active');
				content.removeClass('active');
				
				var allTabs = $('[data-button-cat-tab]');
				// console.log(allTabs);
				allTabs.each(function(){
					if(!$(this).hasClass('dsp-n')){
						$(this).addClass('active');
						var tabId = $(this).data('button-cat-tab');
						var tabContent = $('[data-button-cat-tab-content=' + tabId + ']');
						tabContent.addClass('active');
						// console.log(tabContent);
						return false; 
					}
				});
			}
		}else{
			var tab = $('[data-button-cat-tab=' + id + ']');
			var content = $('[data-button-cat-tab-content=' + id + ']');
			
			var allTabs = $('[data-button-cat-tab]');
			console.log(allTabs);
			var count = 0;
			allTabs.each(function(){
				if(!$(this).hasClass('dsp-n')){
					count++;
				}
			});
			console.log(count);
			if(count == 0){
				tab.addClass('active');
				content.addClass('active');				
			}
			
			tab.removeClass('dsp-n');
			content.removeClass('dsp-n');
		}
	});	
});


	