$( document ).ready(function() {
	/* ---------------------------------------------
	 Логика чекбоксов
	--------------------------------------------- */

	$('body').on('click', '[data-day-week]', function(){
		var row = $(this).parents('[data-hours-row]');
		var checkboxes = row.find('[data-hour-checkbox]');
		// console.log(checkboxes);
		setAllHoursLine(checkboxes);
		
	});
	
	function setAllHoursLine(row) {
		var flag = true;
		row.each(function(){
			var bufFlag = $(this).prop("checked");
			if(bufFlag){
				flag = false;
			}
		});	
		row.each(function(){
			if(flag){
				$(this).prop('checked', true);
			}else{
				$(this).prop('checked', false);
			}			
		});
	}

	$('body').on('click', '[data-hour-day]', function(){
		var hourId = parseInt($(this).text());
		
		var parentWrap = $(this).parents('.row');
		var rows = parentWrap.find('[data-hours-row]');
		
		var flag = true;

		rows.each(function(){
			var checkboxes = $(this).find('[data-hour-checkbox]');
			var i = 0;
			checkboxes.each(function(){
				if(i == hourId){
					var bufFlag = $(this).prop("checked");
					if(bufFlag){
						flag = false;
					}					
				}
				i++;
			});
		});
		
		rows.each(function(){
			var checkboxes = $(this).find('[data-hour-checkbox]');
			var i = 0;
			checkboxes.each(function(){
				if(i == hourId){
					if(flag){
						$(this).prop('checked', true);
					}else{
						$(this).prop('checked', false);
					}
				}
				i++;
			});
		});
	});

	$('body').on('click', '[data-clear-hours]', function(){
		var parentWrap = $(this).parents('.row');
		var rows = parentWrap.find('[data-hours-row]');

		rows.each(function(){
			var checkboxes = $(this).find('[data-hour-checkbox]');
			checkboxes.each(function(){
				$(this).prop('checked', false);
			});
		});		
	});
	
	$('body').on('click', '[data-fill-hours]', function(){
		var parentWrap = $(this).parents('.row');
		var rows = parentWrap.find('[data-hours-row]');

		rows.each(function(){
			var checkboxes = $(this).find('[data-hour-checkbox]');
			checkboxes.each(function(){
				$(this).prop('checked', true);
			});
		});		
	});	
});