$( document ).ready(function() {
	$('.loading').hide();
	$('[data-toggle="tooltip"]').tooltip();
	
	var appColors = {
		red:'#de5957',
		green:'#c3d935',
		blue:'#3183a3',
		orange:'#f3d131',
		grey:'#cecece',
	};


	/* ---------------------------------------------
	 Инициализация bootbox
	 --------------------------------------------- */
	bootbox.setLocale("ru");

	
	/* ---------------------------------------------
	 Перезагрузка страницы по таймауту
	 --------------------------------------------- */
	if($('[data-reload-page]').length){
		setInterval(function() {
			location.reload();
		}, $('[data-reload-page]').data('reload-page'));
	}
	
	
	/* ---------------------------------------------
	 Дерево
	 --------------------------------------------- */
	if($('#tree1').length){
		$('#tree1').treed();
	}
	
	
	/* ---------------------------------------------
	 Открытие вкладок по url
	 --------------------------------------------- */
	var hash = window.location.hash;
    hash && $('[data-nav-tabs-linked]').find('a[href="' + hash + '"]').tab('show');
	$('[data-nav-tabs-linked]').find('a').on('click', function(e){
		if(history.pushState) {
			history.pushState(null, null, $(this).attr('href'));
		}
		else {
			location.hash = $(this).attr('href');
		}
	});
	
	
	/* ---------------------------------------------
	 Плавный переход к якорю с отступом
	 --------------------------------------------- */
	if ($(hash).length){
		hash && $('body,html').animate({scrollTop: $(hash).offset().top}, 300);
	}
	
	
	
	/* ---------------------------------------------
	 Открытие вкладок по ссылкам
	 --------------------------------------------- */
	$('[data-link-to-tab]').on('click', function(){
		$('a[href="'+$(this).data('link-to-tab')+'"]').tab('show');
	});
	
	
	/* ---------------------------------------------
	 Установка текущей вкладки в форме
	 --------------------------------------------- */
	$('[data-tabs-in-form] a').on('click', function(){
		$('[data-active-tab-id]').val($(this).attr('href').slice(1));
	});
	
	
	/* ---------------------------------------------
	 Присвоение класса родительскому пункту меню
	 --------------------------------------------- */
	$('.dropdown-menu .active').parents('.dropdown').addClass('active open');
	
	
	/* ---------------------------------------------
	 Контролы со слайдером
	 --------------------------------------------- */
	$('.slider-input').slider({tooltip: 'always',tooltip_position:'bottom'});
	$('.slider-input-float').slider({
		tooltip: 'always',
		tooltip_position:'bottom',
		formater: function(value) {
			return value.toFixed(1);
		}
	});
	
	
	/* ---------------------------------------------
	 Контролы: поведение checkbox как radiogroup
	 --------------------------------------------- */
	$('body').on('click', '[data-radio-checkbox]', function(){
		var checked = $(this).prop("checked");
		$(this).closest('[data-checkbox-group]').find('[data-radio-checkbox]').prop("checked", false);
		$(this).prop("checked", checked);
	});
	
	
	/* ---------------------------------------------
	 Контролы: эксклюзивный checkbox
	 --------------------------------------------- */
	$('body').on('click', '[data-exclusive-checkbox]', function(){
		var checked = $(this).prop("checked");
		if(checked){
			$(this).closest('[data-checkbox-group]').find('input:checkbox').prop("checked", false);
		}
		$(this).prop("checked", checked);
	});
	
	$('body').on('click', '[data-checkbox-group-item]', function(){
		var checked = $(this).prop("checked");
		if(checked){
			$(this).closest('[data-checkbox-group]').find('[data-exclusive-checkbox]').prop("checked", false);
		}
	});
	
	
	/* ---------------------------------------------
	 Запуск генерации .xls
	 --------------------------------------------- */
	$('[data-get-xls-link]').on('click', function(){
		$('[data-get-xls]').val(1);
		$(this).parents('form').submit();
	});
	
	$('[data-no-xls-link]').on('click', function(e){
		e.preventDefault();
		$('[data-get-xls]').val(0);
		$(this).parents('form').submit();
	});
	
	
	/* ---------------------------------------------
	 Фильтр: очиcтка и установка списка checkbox 
	 --------------------------------------------- */
	$('[data-clear-filter-check-list]').on('click', function(e){
		$(this).parent().find('input:checkbox').removeAttr("checked");
	});	
	$('[data-set-filter-check-list]').on('click', function(e){
		$(this).parent().find('input:checkbox').prop("checked", true);
	});

	
	/* ---------------------------------------------
	 Фильтр: очитстка и установка списка checkbox
	 --------------------------------------------- */
	$('body').on('click', '[data-ok-check-list]', function(){
		$(this).closest('[data-check-list-container]').find('input:checkbox').prop("checked", true);
	});	
	$('body').on('click', '[data-cancel-check-list]', function(){		
		$(this).closest('[data-check-list-container]').find('input:checkbox').removeAttr("checked");
	});
	
	$('body').on('change', '[data-related-checkbox]', function(){
		console.log('['+$(this).data('related-checkbox')+']');
		console.log($('body').find('['+$(this).data('related-checkbox')+']'));
		var checkboxes = $('body').find('['+$(this).data('related-checkbox')+']');
		var flag = $(this).prop('checked');
		checkboxes.each(function(){
			if(flag){
				if(!$(this).prop("checked")){
					var parentStorages = $(this).parents('[data-check-list-container]');
					recalculateItemsCount(parentStorages, 1);
				}
			}else{
				if($(this).prop("checked")){
					var parentStorages = $(this).parents('[data-check-list-container]');
					recalculateItemsCount(parentStorages, 0);
				}				
			}
		});			
		checkboxes.prop('checked', flag);
	});
	
	
	/* ---------------------------------------------
	 Фильтр: сортировка по полю таблицы
	 --------------------------------------------- */
	$('body').on('click', '[data-sort-table-by]', function(){
		$('.loading').show();
		$('[data-get-xls]').val(0);
		$('[data-filter-sorting]').val($(this).data('sort-table-by'));
		$('[data-filter-form]').submit();
	});	
	
	
	/* ---------------------------------------------
	 Очистка дочерних чекбоксов в дереве и скрытие дочернего блока
	 --------------------------------------------- */
	$('[data-tree-node-checkbox]').change(function(){
		if($(this).prop('checked')) {
			$(this).closest('[data-tree-node-checkbox-container]').find('[data-tree-node-child]').removeClass('hide').slideDown();
		}
		else{
			$(this).closest('[data-tree-node-checkbox-container]').find('[data-tree-node-child]').slideUp(300);
			$(this).closest('[data-tree-node-checkbox-container]').find('input:checkbox').removeAttr("checked");
		}
	});
	
	
	/* ---------------------------------------------
	 Отправка формы при изменении значения поля
	 --------------------------------------------- */
	$('[data-page-reloader-form]').on('change', function(){
		$('[data-get-xls]').val(0);
		$('.loading').show();
		$(this).parents('form').submit();
	});
	
	
	/* ---------------------------------------------
	 Оброботка ajax-формы
	 --------------------------------------------- */
	$('body').on('submit', '[data-ajax-form]', function(e){
		e.preventDefault();
		var index = $(this).data('ajax-form'),
			successCallback = $(this).data('ajax-success-callback');
		$('.loading').show();
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			data: $(this).serialize(),
			success: function(result){
				if(successCallback.length){
					eval(successCallback)(index, result);
				}
				$('.loading').hide()
			}
		});
	});
	
	
	/* ---------------------------------------------
	 Обновление формы при изменении связанного контрола
	 --------------------------------------------- */
	$('body').on('change', '[data-ajax-control]', function(){
		currentIndex = $(this).data('ajax-control');
		$('.loading').show();
		$.ajax({
			url: $(this).closest('form').data('ajax-url'),
			type: 'POST',
			data: $(this).closest('form').serialize(),
			success: function(data){
				$('[data-ajax-container-'+currentIndex+']').replaceWith(data);
				$('.loading').hide();
			}
		});
	});

	
	/* ---------------------------------------------
	 Обновление связанного блока посредством pjax при изменении значения control
	 --------------------------------------------- */
	$('body').on('change', '[data-pjax-control]', function(){
		currentIndex = $(this).data('pjax-control');
		$.pjax.reload({
			container:'#pjax-control-container-'+currentIndex,
			history: false,
			type: 'POST',
			data: $(this).closest('form').serialize(),
			url: $(this).data('pjax-url')
		});
	});
	

	/* ---------------------------------------------
	 Прелоадер pjax
	 --------------------------------------------- */
	$(document).on('pjax:send', function() {
		$('.loading').show()
	})
	$(document).on('pjax:complete', function() {
		$('.loading').hide()
	})
	
	
	
	//
	// Диалог добавления карты
	//
	$('[data-create-operator-with-card]').on('click', function(){
		var cardId = $(this).data('create-operator-with-card');
		var storageId = $(this).data('create-operator-on-storage');
		bootbox.confirm({ 
			size: 'small',
			title: 'Добавить оператора',
			message: 'Создать нового оператора и привязать к нему карту № '+cardId,
			callback: function(result){
				if (result){
					document.location.href = '/operators/create/?card_id='+cardId+'&storage_id='+storageId;
				}
			}
		});
	});
	
	
	//
	// Админка: запрос карты на СУ
	//
	$('[data-get-card-btn]').on('click', function(e){
		e.preventDefault();
		$(this).hide();
		$('.preloader').show();
		$('[data-get-card-error]').hide(300);
		data = $('[data-get-card-form]').serialize();
		$.getJSON('/operators/get-card/', data, function(response){
			if(response.error){
				$('[data-get-card-error-text]').html(response.error);
				$('[data-get-card-error]').show(300);
			}
			else{
				bootbox.confirm({ 
					size: 'small',
					title: 'Карта успешно добавлена',
					message: 'Карта была успешно добавлена',
					callback: function(){
						location='/operators/update/'+response.operator+'/';
					}
				});
			}
			$(this).show();
			$('.preloader').hide();
		});
	});
	
	// $('body').scrollspy({ target: '#help', offset:85 });
	
	
	/* ---------------------------------------------
	 Переход по якорю в справке
	 --------------------------------------------- */
	$("[data-help-nav]").on("click","a", function (event) {
		event.preventDefault();
		var id  = $(this).attr('href'),
		top = $(id).offset().top-80;
		$('body,html').animate({scrollTop: top}, 300);
	});
	
	
	/* ---------------------------------------------
	 Toggle
	 --------------------------------------------- */
	$('body').on('click', '[data-custom-toggle]', function(){
		if($(this).data('custom-toggle') == 'off'){
			$(this).data('custom-toggle', 'on');
			$($(this).data('target')).show();
		}
		else{
			$(this).data('custom-toggle', 'off');
			$($(this).data('target')).hide();
		}
	});
	
	
	/* ---------------------------------------------
	Динамические фильтры в создании техники
	--------------------------------------------- */
	$('[data-ajax-form-brand]').on('change', function(e){
		var model_id = document.getElementById('maindevices-model_id');
		model_id.value = null;
		$('[data-config-import-button]').addClass('invisible-form-wrap');
		
		var modelLinkRow = $('[data-model-link]');
		if(modelLinkRow && !modelLinkRow.hasClass('model-link-hidden')){
			modelLinkRow.addClass('model-link-hidden');
		}
		
		var target_url = '/admin/main-devices/brand/?brand=';
		var brand = $('[data-ajax-form-brand] option:selected').text();
		var years = $(this).parents('.dsp-inline1').find('[data-ajax-form-year]');
		years.find('option').remove().end();				
		var models = $('[data-ajax-form-model]');
		var parentModelWrap = models.parents('.model-select');
		var listOut = parentModelWrap.children('.dropdown-menu');
		var list = listOut.children('.dropdown-menu');
		// console.log(list);
		models.find('option').remove().end();
		var series = $(this).parents('.dsp-inline1').find('[data-ajax-form-series]');
		series.find('option').remove().end();
		$.ajax({
			url: target_url+brand,
			success: function(result){
				if(result){
					$('.filter-option').text('');
					models.find('option').remove().end();				
					models.prepend(result);
					list.find('li').remove().end();	
					var elems = prepareList(models);
					list.prepend(elems);
				}	
			}
		});	
	});
	
	function prepareList(selectList) {
		var result = [];
		// console.log(selectList);
		var options = selectList.children();
		var i = 0;
		selectList.children().each(function() {
			var selected = 'false';
			// var className = '';
			if(i == 0){
				selected = 'true';
				// className = '"selected active"';
			}
			var text = $(this).text();
			var val = $(this).val();
			var buf = '<li data-original-index="' + i + '" class=""><a tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="' + selected + '"><span class="text">' + val + '</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
			var buf2 ='<li data-original-index="' + i + '" class=""><a tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="' + selected + '"><span class="text">' + val + '</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
			result.push(buf2)
			
			i++;
		});
		// console.log(result);
		return result;
	}	
	
	$('[data-ajax-form-model]').on('change', function(e){
		
		var modelLinkRow = $('[data-model-link]');
		if(modelLinkRow && !modelLinkRow.hasClass('model-link-hidden')){
			modelLinkRow.addClass('model-link-hidden');
		}		
		
		$('[data-config-import-button]').addClass('invisible-form-wrap');
		var model_id = document.getElementById('maindevices-model_id');
		model_id.value = null;		
		var target_url = '/admin/main-devices/brand/?brand=';
		var brand = $('[data-ajax-form-brand] option:selected').text();
		var model = $('[data-ajax-form-model] option:selected').text();
		var condition = '&model=';
		var brands_list = $(this).data('[data-ajax-form-brand]');
		var years = $(this).parents('.dsp-inline1').find('[data-ajax-form-year]');
		var series = $(this).parents('.dsp-inline1').find('[data-ajax-form-series]');
		series.find('option').remove().end();
		years.find('option').remove().end();
		if(model.length != 0){
			$.ajax({
				url: target_url+brand+condition+model,
				success: function(result){
					if(result){
						years.find('option').remove().end();				
						years.prepend(result);
					}
					else{
					}
				}
			});
		}		
	});
	
	$('[data-ajax-form-year]').on('change', function(e){
		var modelLinkRow = $('[data-model-link]');
		if(modelLinkRow && !modelLinkRow.hasClass('model-link-hidden')){
			modelLinkRow.addClass('model-link-hidden');
		}		
		
		$('[data-config-import-button]').addClass('invisible-form-wrap');
		var model_id = document.getElementById('maindevices-model_id');
		model_id.value = null;		
		var target_url = '/admin/main-devices/brand/?brand=';
		var brand = $('[data-ajax-form-brand] option:selected').text();
		var model = $('[data-ajax-form-model] option:selected').text();
		var year = $('[data-ajax-form-year] option:selected').text();
		var condition1 = '&model=';
		var condition2 = '&year=';
		var brands_list = $(this).data('[data-ajax-form-brand]');
		var years = $(this).parents('.dsp-inline1').find('[data-ajax-form-year]');
		var series = $(this).parents('.dsp-inline1').find('[data-ajax-form-series]');
		series.find('option').remove().end();
		if(year.length != 0){
			$.ajax({
				url: target_url + brand + condition1 + model + condition2 + year,
				success: function(result){
					if(result){
						series.find('option').remove().end();				
						series.prepend(result);					
					}				
				}
			});
		}		
	});	
	
	$('[data-ajax-form-series]').on('change', function(e){
		var modelLinkRow = $('[data-model-link]');
		if(modelLinkRow && modelLinkRow.hasClass('model-link-hidden')){
			modelLinkRow.removeClass('model-link-hidden');
		}		
		
		var model_id = document.getElementById('maindevices-model_id');
		model_id.value = null;		
		var target_url = '/admin/main-devices/brand/?brand=';
		var brand = $('[data-ajax-form-brand] option:selected').text();
		var model = $('[data-ajax-form-model] option:selected').text();
		var year = $('[data-ajax-form-year] option:selected').text();
		var ser = $('[data-ajax-form-series] option:selected').text();
		
		var condition1 = '&model=';
		var condition2 = '&year=';
		var condition3 = '&series=';
		var brands_list = $(this).data('[data-ajax-form-brand]');
		var years = $(this).parents('.dsp-inline1').find('[data-ajax-form-year]');
		var series = $(this).parents('.dsp-inline1').find('[data-ajax-form-series]');
		
		var startUrl = target_url + brand + condition1 + model + condition2 + year + condition3 + ser;
		
		var finishedUrl = '';
		
		for (i = 0; i < startUrl.length; i++) { 
			if(startUrl[i] != '#'){
				finishedUrl += startUrl[i];
			}else{
				finishedUrl += '%23';
			}
		}		
		console.log(startUrl, finishedUrl);
		$.ajax({
			url: finishedUrl,
			success: function(result){
				if(result){
					$('[data-config-import-button]').removeClass('invisible-form-wrap');
					// console.log(target_url + brand + condition1 + model + condition2 + year + condition3 + ser);
					var model_id = document.getElementById('maindevices-model_id');
					
					var modelLinkLink = $('[data-model-href]');
					modelLinkLink.attr("href", "/admin/brands/update/?id=" + result)
		 
					model_id.value = result;					
				}				
			}
		});	
	});	
	
	
	/* ---------------------------------------------
	Инициализация датапикера
	--------------------------------------------- */
	$('[data-daterangepicker]').each(function(index, element){
		var startDate = $(element).data('start-date');
		var endDate = $(element).data('end-date');
		var timePicker = $(element).data('use-time-picker');
		var singleDatePicker = $(element).data('single-date-picker');
		var format = "DD.MM.YYYY";
		if(timePicker){
			format = "DD.MM.YYYY HH:00";
		}
		if($(this).data('show-ranges')){//Инициализация дефолтных рейнджей
			var rangesArray = {
				"Сегодня": [moment().startOf('day'),moment().add(1, 'hour')],
				"Вчера": [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day').add(1, 'hour')],
				"Эта неделя": [moment().startOf('isoweek').startOf('day'), moment().add(1, 'hour')],
				"Прошлая неделя": [moment().subtract(7, 'days').startOf('isoweek').startOf('day'), moment().subtract(7, 'days').endOf('isoweek').add(1, 'hour')],
				"Этот месяц": [moment().startOf('month'), moment().add(1, 'hour')],
				"Прошлый месяц": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month').add(1, 'hour')]
			};
		}else{
			var rangesArray = {};
		}
		$(element).daterangepicker({
			"singleDatePicker": singleDatePicker,
			"timePicker": timePicker,
			"timePicker24Hour": true,
			"timePickerIncrement": 60,
			"startDate": startDate,
			"endDate": endDate,
			'parentEl': '.data-picker-wrap',
			"locale": {
				"format": format,
				"separator": " - ",
				"applyLabel": "Ок",
				"cancelLabel": "Отмена",
				"fromLabel": "С",
				"toLabel": "По",
				"customRangeLabel": "Custom",
				"weekLabel": "Нед.",
				"daysOfWeek": [
					"Вс",
					"Пн",
					"Вт",
					"Ср",
					"Чт",
					"Пт",
					"Сб"
				],
				"monthNames": [
					"Январь",
					"Февраль",
					"Март",
					"Апрель",
					"Май",
					"Июнь",
					"Июль",
					"Август",
					"Сентябрь",
					"Октябрь",
					"Ноябрь",
					"Декабрь"
				],
				"firstDay": 1
			},
			"ranges": rangesArray,
			"showCustomRangeLabel": false,
			"alwaysShowCalendars": true,
		});
	});

	
	
	/* ---------------------------------------------
	 Интерфейс: Пакетное редактирование элементов
	 --------------------------------------------- */
	 
		/* ---------------------------------------------
		 Видимость блока настроек при выборе элемента
		 --------------------------------------------- */
		$('body').on('change', '[data-batch-device-id]', function(){
			var selectedItems = false;
			$('[data-batch-device-id]').each(function(i) {
				if ($(this).prop("checked")){
					selectedItems = true;
					return false;
				}
			});
			if(selectedItems){
				$('[data-batch-update-settings]').fadeIn(300);
			}
			else{
				$('[data-batch-update-settings]').fadeOut(300);
			}
		});
	
		/* ---------------------------------------------
		 Видимость отдельной настройки
		 --------------------------------------------- */
		$('body').on('click', '[data-batch-settings-list] input', function(){
			settingName = $(this).val();
			if($(this).prop("checked")){
				$('[data-batch-setting-'+settingName+']').fadeIn(300);
			}
			else{
				$('[data-batch-setting-'+settingName+']').fadeOut(300);
			}
			
			var selectedItems = false;
			$('[data-batch-settings-list] input').each(function(i) {
				if ($(this).prop("checked")){
					selectedItems = true;
					return false;
				}
			});
			if(selectedItems){
				$('[data-batch-setting-additional]').fadeIn(300);
			}
			else{
				$('[data-batch-setting-additional]').fadeOut(300);
			}
		});
	
		/* ---------------------------------------------
		 Выбор всех элементов
		 --------------------------------------------- */
		$('body').on('click', '[data-batch-all-devices]', function(){
			if ($(this).prop("checked")){
				$('[data-batch-device-id]').prop("checked", true);
			}
			else{
				$('[data-batch-device-id]').prop("checked", false);
			}
			$('[data-batch-device-id]').filter( ':first' ).trigger('change');
		});


	/* ---------------------------------------------
	 Техника: Получение данных с сервера устройств при открытии страницы
	 --------------------------------------------- */	
	var dataServerForm = $('[data-server-form]');
	// console.log(dataServerForm.length);
	if(dataServerForm.length != 0){
		var target_url = '/devices/get-data/?deviceId=';
		var id = document.getElementById('devices-id');
		var deviceId = id.value;
		$.ajax({
			url: target_url + deviceId,
			success: function(result){
				if(result){
					var container = $('[data-server-form]');
					var software_version = document.getElementById('maindevices-software_version');
					var sim_id = document.getElementById('maindevices-sim_id');
					var serial_number = document.getElementById('maindevices-serial_number_terminal');
					var terminal_id = document.getElementById('maindevices-ext_id'); 
					var release_date = document.getElementById('maindevices-release_date');
					var update_date = document.getElementById('maindevices-update_date');					
					var response = jQuery.parseJSON(result);
					
					$('[data-message-server]').remove();				
					if(response.id && response.id!='')
					{
						container.prepend( '<div class="alert alert-success alert-dismissible" role="alert" data-message-server><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Данные успешно получены</strong> </div>' );
						if(terminal_id.value != response.id)
						{
							terminal_id.value = response.id;
							$( "#maindevices-ext_id" ).css( "background-color", "#dff0d8" );
						}
						var now = new Date();
						if(response.sim_id)
						{
							if(sim_id.value != response.sim_id)
							{
								sim_id.value = response.sim_id;
								$( "#maindevices-sim_id" ).css( "background-color", "#dff0d8" );
							}
						}
						if(response.sw)
						{	
							if(software_version.value != response.sw)
							{
								software_version.value = response.sw;
								$( "#maindevices-software_version" ).css( "background-color", "#dff0d8" );
							}
						}	
						if(response.pd)
						{
							if(release_date.value != response.pd)
							{
								release_date.value = response.pd;
								$( "#maindevices-release_date" ).css( "background-color", "#dff0d8" );
							}
						}	
						if(response.sn)
						{
							if(serial_number.value != response.sn)
							{
								serial_number.value = response.sn;
								$( "#maindevices-serial_number_terminal" ).css( "background-color", "#dff0d8" );
							}
						}
											
						update_date.value = response.date;
						$( "#maindevices-update_date" ).css( "background-color", "#dff0d8" );
					}
					else
					{					
						container.prepend( '<div class="alert alert-danger alert-dismissible" role="alert" data-message-server><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Терминал не заведен в систему</strong> </div>' );						
					}
				
				}
				else
				{
					var container = $('[data-server-form]');
					container.prepend( '<div class="alert alert-danger alert-dismissible" role="alert" data-message-server><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Данные с сервера не были получены</strong> </div>' );						
				}					
			}
		});
	}
	
	
	/* ---------------------------------------------
	 Техника: Получение данных с сервера устройств
	 --------------------------------------------- */
	$('[data-get-server-devices]').on('click', function(e){
		var target_url = '/devices/get-data/?deviceId=';
		var id = document.getElementById('devices-id');
		var deviceId = id.value;
		$.ajax({
			url: target_url + deviceId,
			success: function(result){
				if(result){
					var container = $('[data-server-form]');	
					var software_version = document.getElementById('maindevices-software_version');
					var sim_id = document.getElementById('maindevices-sim_id');
					var serial_number = document.getElementById('maindevices-serial_number_terminal');
					var terminal_id = document.getElementById('maindevices-ext_id'); 
					var release_date = document.getElementById('maindevices-release_date');
					var update_date = document.getElementById('maindevices-update_date');					
					var response = jQuery.parseJSON(result);
					$('[data-message-server]').remove();
					
					if(response.id && response.id!='')
					{
						container.prepend( '<div class="alert alert-success alert-dismissible" role="alert" data-message-server><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Данные успешно получены</strong> </div>' );
						if(terminal_id.value != response.id)
						{
							terminal_id.value = response.id;
							$( "#maindevices-ext_id" ).css( "background-color", "#dff0d8" );
						}
						var now = new Date();
						if(response.sim_id)
						{
							if(sim_id.value != response.sim_id)
							{
								sim_id.value = response.sim_id;
								$( "#maindevices-sim_id" ).css( "background-color", "#dff0d8" );
							}
						}
						if(response.sw)
						{	
							if(software_version.value != response.sw)
							{
								software_version.value = response.sw;
								$( "#maindevices-software_version" ).css( "background-color", "#dff0d8" );
							}
						}	
						if(response.pd)
						{
							if(release_date.value != response.pd)
							{
								release_date.value = response.pd;
								$( "#maindevices-release_date" ).css( "background-color", "#dff0d8" );
							}
						}	
						if(response.sn)
						{
							if(serial_number.value != response.sn)
							{
								serial_number.value = response.sn;
								$( "#maindevices-serial_number" ).css( "background-color", "#dff0d8" );
							}
						}							
						update_date.value = response.date;
						$( "#maindevices-update_date" ).css( "background-color", "#dff0d8" );
					}
					else
					{					
						container.prepend( '<div class="alert alert-danger alert-dismissible" role="alert" data-message-server><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Терминал не заведен в систему</strong> </div>' );						
					}
				
				}
				else
				{
					var container = $('[data-server-form]');
					container.prepend( '<div class="alert alert-danger alert-dismissible" role="alert" data-message-server><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Данные с сервера не были получены</strong> </div>' );						
				}					
			}
		});		
	});	
	
	/* ---------------------------------------------
	 Открытие табов по куки
	 --------------------------------------------- */	
	var cookieWraps = $('[data-cookie-name]');
	cookieWraps.each(function(){
		if($.cookie($(this).data('cookie-name'))){
			var tabId = $.cookie($(this).data('cookie-name'));
			var tabs = $(this).find('[data-cookie-tab]');
			var tabsContent = $(this).find('[data-tab-content]');
			var parentTab = $(this).find('[data-cookie-tab='+ tabId +']');
			var childTab = $(this).find('[data-tab-content='+ tabId +']');			
			if(parentTab.length > 0 && childTab.length > 0){
				tabs.removeClass('active');			
				tabsContent.removeClass('active');
				
				if($('[data-chart-js-tab]')){
					tabs.attr('data-chart-js-tab','');
					tabsContent.attr('data-chart-js-tab','');
				}
				
				var parentTab = $(this).find('[data-cookie-tab='+ tabId +']');
				var childTab = $(this).find('[data-tab-content='+ tabId +']');
				parentTab.addClass('active');			
				childTab.addClass('active');
				
				if($('[data-chart-js-tab]')){
					parentTab.removeAttr('data-chart-js-tab');
					childTab.removeAttr('data-chart-js-tab');
				}
			}
		}
	});
	
	$('body').on('click', '[data-cookie-tab]', function(e){
		var tab = $(this).data('cookie-tab');
		var wrap = $(this).parents('[data-cookie-name]');
		if(wrap){
			$.cookie(wrap.data('cookie-name'), tab);
		}
	});

	
	/* ---------------------------------------------
	 Диаграмма загрузки в таблице
	 --------------------------------------------- */
	$('[data-load-bar-diagram]').each(function(){
		var self = this,
			barData = $(self).data('load-bar-diagram'),
			ctx = self.getContext('2d'),
			xUnit = +self.clientWidth/100,
			yUnit = +self.clientHeight/28;
		
		ctx.fillStyle = appColors.red;
		ctx.fillRect(0*xUnit, 10*yUnit, 100*xUnit, 14*yUnit);
		
		ctx.fillStyle = appColors.green;
		ctx.fillRect(0*xUnit, 10*yUnit, barData.ignition*xUnit, 14*yUnit);
		
		ctx.fillStyle = appColors.blue;
		ctx.fillRect(0*xUnit, 14*yUnit, barData.active*xUnit, 6*yUnit);
		
		ctx.beginPath();
		ctx.moveTo(barData.activeKpi*xUnit-4, 4*yUnit);
		ctx.lineTo(barData.activeKpi*xUnit+4, 4*yUnit);
		ctx.lineTo(barData.activeKpi*xUnit, 9*yUnit);
		ctx.fill();
		
		if(barData.activeKpi<=barData.active) {
			ctx.fillStyle = 'rgba(255,255,255,0.7)';
			ctx.fillRect(0*xUnit, 10*yUnit, 100*xUnit, 14*yUnit);
		}
	});

	
	/* ---------------------------------------------
	 Ресайз картинок
	 --------------------------------------------- */	
	var max_w=117.5+'px';
	var max_h=148.39+'px';
	$("[data-img-responsive]").each(function(i) {
		var this_w=$(this).height();
		var this_h=$(this).width();
		if (this_w/this_h < max_w/max_h) {
			var h = max_h;
			var w = Math.ceil(max_h/this_h * this_w);
		} else {
			var w = max_w;
			var h = Math.ceil(max_w/this_w * this_h);
		}

		// console.log(this_h, this_w);
		$(this).css('height', h);
		$(this).css('width', w);		
		var parentWrap = $(this).parents('[data-parent-wrap]');
		// parentWrap.css({ height: h, width: w });
		parentWrap.css('height', h);
		parentWrap.css('width', w);
	});	
	/* ---------------------------------------------
	 Удаление из карточки техники картинок
	 --------------------------------------------- */
	$('body').on('click', '[data-main-device-delete]', function(e){
		var id = $(this).data('main-device-delete');
		var parentWrap = $(this).parents('[data-parent-wrap]');
		var allIcons = $('[data-main-device-delete]');
		// $(this).addClass('invisible-form-wrap');
		allIcons.each(function(){
			$(this).addClass('invisible-form-wrap');
		});
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление картинки',
			message: 'Вы действительно хотите удалить данное изображение?',
			callback: function(result){
				if (result){
					allIcons.each(function(){
						$(this).removeClass('invisible-form-wrap');
					});					
					$.ajax({
						url: '/rest/images/delete/?id=' + id,
						success: function(result){
							if(result){
								parentWrap.remove();
							}				
						}
					});					
				}else{
					allIcons.each(function(){
						$(this).removeClass('invisible-form-wrap');
					});					
				}
			}
		});	
		
	});

	/* ---------------------------------------------
	 Scroll_On_Top_Icon
	 --------------------------------------------- */
	$(function(){
	
		$(document).on( 'scroll', function(){
	
			if ($(window).scrollTop() > 100) {
				$('.scroll-top-wrapper').addClass('show');
			} else {
				$('.scroll-top-wrapper').removeClass('show');
			}
		});
	
		$('.scroll-top-wrapper').on('click', scrollToTop);
	});
	
	function scrollToTop() {
		verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
		element = $('body');
		offset = element.offset();
		offsetTop = offset.top;
		$('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
	}
	
	
	/* ---------------------------------------------
	 Полсчет количества чекбоксов в фильтре FilterItemsInToStoragesWidget
	 --------------------------------------------- */	
	$('body').on('click', '[data-storage-checkbox]', function(){
		var flag = $(this).prop("checked");
		var parentStorages = $(this).parents('[data-check-list-container]');
		recalculateItemsCount(parentStorages, flag);
	});


	$('body').on('click', '[data-ok-check-list-widget]', function(){
		var checkboxes = $(this).closest('[data-check-list-container]').find('input:checkbox');
		checkboxes.each(function(){
			if(!$(this).prop("checked")){
				var parentStorages = $(this).parents('[data-check-list-container]');
				recalculateItemsCount(parentStorages, 1);
			}
		});		
		checkboxes.prop("checked", true);
	});	
	$('body').on('click', '[data-cancel-check-list-widget]', function(){
		var checkboxes = $(this).closest('[data-check-list-container]').find('input:checkbox');
		checkboxes.each(function(){
			if($(this).prop("checked")){
				var parentStorages = $(this).parents('[data-check-list-container]');
				recalculateItemsCount(parentStorages, 0);
			}
		});
		checkboxes.removeAttr("checked");
	});	
	

	function recalculateItemsCount(parentStorages, flag) {
		parentStorages.each(function(){
			var parentSpan = $(this).children('.icon_inline-control-wrap');
			var storage = parentSpan.children('[data-title-count-items]');
			var countItems = storage.data('title-count-items');
			
			if(flag){
				countItems ++;
			}else{
				countItems --;
			}
			storage.data('title-count-items', countItems);
			storage.text('(' + countItems + ')');
		});		
	}	
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


//Хэлперы: Проверка наличия свойств объекта
function isNotEmptyObject(obj) {
    for (var i in obj) {
        return true;
    }
    return false;
}


//Хэлперы: Случайное число между min и max
function getRandomArbitary(min, max)
{
  return Math.random() * (max - min) + min;
}