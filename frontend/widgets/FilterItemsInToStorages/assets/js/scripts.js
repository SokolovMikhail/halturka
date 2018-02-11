$( document ).ready(function() {
	// $('body').on('click', '[data-storage-checkbox]', function(){
		// var flag = $(this).prop("checked");
		// var parentStorages = $(this).parents('[data-check-list-container]');
		// recalculateItemsCount(parentStorages, flag);
	// });


	// function recalculateItemsCount(parentStorages, flag) {
		// parentStorages.each(function(){
			// var parentSpan = $(this).children('.icon_inline-control-wrap');
			// var storage = parentSpan.children('[data-title-count-items]');
			// var countItems = storage.data('title-count-items');
			
			// if(flag){
				// countItems ++;
			// }else{
				// countItems --;
			// }
			// storage.data('title-count-items', countItems);
			// storage.text('(' + countItems + ')');
		// });		
	// }


	// $('body').on('click', '[data-ok-check-list-widget]', function(){
		// var checkboxes = $(this).closest('[data-check-list-container]').find('input:checkbox');
		// checkboxes.each(function(){
			// if(!$(this).prop("checked")){
				// var parentStorages = $(this).parents('[data-check-list-container]');
				// recalculateItemsCount(parentStorages, 1);
			// }
		// });		
		// checkboxes.prop("checked", true);
	// });	
	// $('body').on('click', '[data-cancel-check-list-widget]', function(){
		// var checkboxes = $(this).closest('[data-check-list-container]').find('input:checkbox');
		// checkboxes.each(function(){
			// if($(this).prop("checked")){
				// var parentStorages = $(this).parents('[data-check-list-container]');
				// recalculateItemsCount(parentStorages, 0);
			// }
		// });
		// checkboxes.removeAttr("checked");
	// });	
});