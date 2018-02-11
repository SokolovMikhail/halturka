$( document ).ready(function() {

	/* ---------------------------------------------
	 PopUp Menu для смены статуса заявки из общего списка
	 --------------------------------------------- */
	$('body').on('click', '[data-yandex-disk-upload]', function(){
		var orderId = $(this).data('yandex-disk-upload');
		$('.loading').show();
		var url = '/admin/order/yandex/?id=' + orderId;
		
		$.ajax({
			url: url,
			success: function(result){
				if(result){
					bootbox.alert({ 
						size: 'small',
						title: 'Сообщение',
						message: result,
						callback: function(result){
							if (result){
								
							}
						}
					});
					$('.loading').hide();
				}else{
					bootbox.alert({ 
						size: 'small',
						title: 'Ошибка',
						message: 'Непредвиденная ошибка',
						callback: function(result){
							if (result){
								
							}
						}
					});
					$('.loading').hide();
				}										
			}
		});		
	}); 
	
	
	/* ---------------------------------------------
	 Изменение даты из списка заявок в Плане установок
	 --------------------------------------------- */
	$('[data-daterangepicker]').on('hide.daterangepicker', function(ev, picker) {
		var target = $(ev.delegateTarget);
		if(typeof(target.data('order-id')) != "undefined"){
			var currentDate = target.text();
			var newDateString = picker.startDate.format('DD.MM.YYYY');
			if(currentDate != newDateString){
				var orderId = target.data('order-id');
				var fieldName = target.data('status-name');
				var url = '/admin/order/update-date/?orderId=' + orderId + '&field=' + fieldName + '&val=' + newDateString;
				$('.loading').show();
				$.ajax({
					url: url,
					success: function(result){
						if(result){
							target.text(newDateString);
							$('.loading').hide();
						}else{
							console.log('Нет связи с сервером?');
							$('.loading').hide();
						}										
					}
				});				
			}
		}
	});


	/* ---------------------------------------------
	 Новый год
	 --------------------------------------------- */
		// var collectBlocks = $('.collectonme');
		// if(collectBlocks.length > 0){
			// $(document).snowfall({flakeColor: '#c5e7f5', minSize: 1, maxSize:4, round: true, collection : '.collectonme', flakeCount : 100});
		// }
		
		// $(document).snowfall({image :"/images/flake.png", minSize: 10, maxSize: 20, flakeCount : 50});
		
		// $(function() {
			// var d = function() {};
			// $(document).delegate(".b-ball_bounce", "mouseenter", function() {
				// b(this);
				// m(this)
			// }).delegate(".b-ball_bounce .b-ball__right", "mouseenter", function(i) {
				// i.stopPropagation();
				// b(this);
				// m(this)
			// });
			
			// function f() {
				// var i = "ny2012.swf";
				// i = i + "?nc=" + (new Date().getTime());
				// swfobject.embedSWF(i, "z-audio__player", "1", "1", "9.0.0", null, {}, {
				// allowScriptAccess: "always",
				// hasPriority: "true"
				// })
			// }
			
			// function h(i) {
				// if ($.browser.msie) {
				// return window[i]
				// } else {
				// return document[i]
				// }
			// }
			// window.flashInited = function() {
				// d = function(j) {
				// try {
					// h("z-audio__player").playSound(j)
				// } catch (i) {}
				// }
			// };
			// if (window.swfobject) {
				// window.setTimeout(function() {
				// $("body").append('<div class="g-invisible"><div id="z-audio__player"></div></div>');
				// f()
				// }, 100)
			// }
			// var l = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "-", "=", "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "[", "]", "a", "s", "d", "f", "g", "h", "j", "k", "l", ";", "'", "\\"];
			// var k = ["z", "x", "c", "v", "b", "n", "m", ",", ".", "/"];
			// var g = 36;
			// var a = {};
			// for (var e = 0, c = l.length; e < c; e++) {
				// a[l[e].charCodeAt(0)] = e
			// }
			// for (var e = 0, c = k.length; e < c; e++) {
				// a[k[e].charCodeAt(0)] = e
			// }
			// $(document).keypress(function(j) {
				// var i = $(j.target);
				// if (!i.is("input") && j.which in a) {
				// d(a[j.which])
				// }
			// });
			
			// function b(n) {
				// if (n.className.indexOf("b-ball__right") > -1) {
				// n = n.parentNode
				// }
				// var i = /b-ball_n(\d+)/.exec(n.className);
				// var j = /b-head-decor__inner_n(\d+)/.exec(n.parentNode.className);
				// if (i && j) {
				// i = parseInt(i[1], 10) - 1;
				// j = parseInt(j[1], 10) - 1;
				// d((i + j * 9) % g)
				// }
			// }
			
			// function m(j) {
				// var i = $(j);
				// if (j.className.indexOf(" bounce") > -1) {
				// return
				// }
				// i.addClass("bounce");
			
				// function n() {
				// i.removeClass("bounce").addClass("bounce1");
			
				// function o() {
					// i.removeClass("bounce1").addClass("bounce2");
			
					// function p() {
					// i.removeClass("bounce2").addClass("bounce3");
			
					// function q() {
						// i.removeClass("bounce3")
					// }
					// setTimeout(q, 300)
					// }
					// setTimeout(p, 300)
				// }
				// setTimeout(o, 300)
				// }
				// setTimeout(n, 300)
			// }
		// });			
	/* ---------------------------------------------
	 PopUp Menu для смены статуса заявки из общего списка
	 --------------------------------------------- */	
	(function($) {	
		$.fn.popr = function(options) {			
			var set = $.extend( {
				
				'speed'        : 200,
				'mode'         : 'bottom'
			
			}, options);
	
			return this.each(function() {			
				var popr_cont = '.popr_container_' + set.mode;
				var popr_show = true;
	
				$(this).click(function(event)
				{					
					if(typeof($(event.target).data('status-item')) != "undefined"){
						var chosenElem = $(event.target);
						
						var parentTr = chosenElem.parents('[data-order-tr]');
						var parentWrap = chosenElem.parent('[data-popr-order-id]');
						var orderId = parentWrap.data('popr-order-id');
						var uniqueId = parentWrap.data('popr-unique-id');
						var statusName = parentWrap.data('popr-status-name');
						
						var chosenVal = chosenElem.data('val');
						var chosenText = chosenElem.text();
						
						var url = '/admin/order/status-update/?orderId=' + orderId + '&status=' + statusName + '&val=' + chosenVal;
						$('.loading').show();
						$.ajax({
							url: url,
							success: function(result){
								if(result){
									var editingStatus = $('[data-unique-id=' + uniqueId + ']');
									editingStatus.text(chosenText);
									updateRowColor(parentTr, result);
									$('.loading').hide();
								}else{
									console.log('Нет связи с сервером?');
									$('.loading').hide();
								}										
							}
						});							
						
					}
					$('.popr_container_top').remove();
					$('.popr_container_bottom').remove();
					
					if (popr_show)
					{
						event.stopPropagation();
						popr_show = false;
					}
					else
					{
						popr_show = true;
					}                   
					
					var d_m = set.mode;
					if ($(this).attr('data-mode'))
					{
						d_m = $(this).attr('data-mode')
						popr_cont = '.popr_container_' + d_m;   
					}
	
					var orderId = $(this).data('order-id');
					var uniqueId = $(this).data('unique-id');
					var statusName = $(this).data('status-name');

					var out = '<div class="popr_container_' + d_m + '"><div class="popr_point_' + d_m + '"><div class="popr_content" data-popr-order-id="' + orderId + '" data-popr-unique-id="' + uniqueId + '" data-popr-status-name="' + statusName + '">' + $('div[data-box-id="' + $(this).attr('data-id') + '"]').html() + '</div></div></div>';
					
					$(this).append(out);
				
					var w_t = $(popr_cont).outerWidth();
					var w_e = $(this).width();
					var m_l = (w_e / 2) - (w_t / 2);
				
					$(popr_cont).css('margin-left', m_l + 'px');
					$(this).removeAttr('title alt');
					
					if (d_m == 'top')
					{
						var w_h = $(popr_cont).outerHeight() + 39;
						$(popr_cont).css('margin-top', '-' + w_h + 'px');    
					}
					
					$(popr_cont).fadeIn(set.speed);   
				});
								
				$('html').click(function()
				{
						$('.popr_container_top').remove();
						$('.popr_container_bottom').remove();
						popr_show = true;
				});                           
			});
		};
		
	})(jQuery);
	
	$('.popr').popr();

	/* ---------------------------------------------
	 Смена статуса состояния Согласования с установщиком/клиентом
	 --------------------------------------------- */
	$('body').on('click', '[data-agreement-status]', function(){
		var parentTr = $(this).parents('[data-order-tr]');
		var statusName = $(this).data('agreement-status');
		var parentWrap = $(this).parent('[data-order-id]');
		var orderId = parentWrap.data('order-id');
		var icon = $(this);
		var newValue = 0;
		if($(this).hasClass('dark-green-clr')){
			newValue = 0;
		}else{
			newValue = 1;
		}
		var url = '/admin/order/status-update/?orderId=' + orderId + '&status=' + statusName + '&val=' + newValue;
		$('.loading').show();
		$.ajax({
			url: url,
			success: function(result){
				if(result){
					if(icon.hasClass('dark-green-clr')){
						icon.removeClass('dark-green-clr');
						icon.addClass('dark-red-clr');
					}else{
						icon.removeClass('dark-red-clr');
						icon.addClass('dark-green-clr');
					}
					updateRowColor(parentTr, result);
					$('.loading').hide();
				}else{
					console.log('Нет связи с сервером?');
					$('.loading').hide();
				}										
			}
		});			

	});

	/* ---------------------------------------------
	 Смена статуса "Установлено"
	 --------------------------------------------- */
	$('body').on('click', '[data-installed-status]', function(){
		var parentTr = $(this).parents('[data-order-tr]');
		var statusName = $(this).data('installed-status');
		var parentWrap = $(this).parent('[data-order-id]');
		var orderId = parentWrap.data('order-id');
		var icon = $(this);	
		var newValue = 0;
		if($(this).hasClass('fa-check')){
			newValue = 0;
		}else{
			newValue = 1;
		}

		var url = '/admin/order/status-update/?orderId=' + orderId + '&status=' + statusName + '&val=' + newValue;
		$('.loading').show();
		$.ajax({
			url: url,
			success: function(result){
				if(result){
					if(icon.hasClass('fa-check')){
						icon.removeClass('dark-green-clr');
						icon.removeClass('fa-check');
						
						icon.addClass('dark-red-clr');
						icon.addClass('fa-times');
						
						// parentTr.removeClass('green-background');
						
						// parentTr.addClass('');
					}else{
						icon.removeClass('dark-red-clr');
						icon.removeClass('fa-times');
						
						icon.addClass('dark-green-clr');
						icon.addClass('fa-check');
						
						// parentTr.removeClass('yellow-background');
						// parentTr.removeClass('pink-background');
						
						// parentTr.addClass('green-background');
					}
					updateRowColor(parentTr, result);
					updateDevicesAmount(parentTr);
					$('.loading').hide();
				}else{
					console.log('Нет связи с сервером?');
					$('.loading').hide();
				}										
			}
		});		
	});	

	function updateRowColor(row, className) {
		row.removeClass('yellow-background');
		row.removeClass('pink-background');	
		row.removeClass('green-background');
		row.removeClass('white-background');
		
		row.addClass(className);
	}
	
	function updateDevicesAmount(row) {
		var tabWrap = row.parents('[data-tab-date]');
		var tabDate = tabWrap.data('tab-date');
		console.log(tabDate);
		var url = '/admin/order/devices-amount/?monthFilter=' + tabDate;
		$.ajax({
			url: url,
			success: function(result){
				if(result){
					var devicesAmount = JSON.parse(result);
					console.log(devicesAmount);
					var totalDevicesWrap = $('[data-devices-total-amount]');
					var installedDevicesWrap = $('[data-devices-installed-amount]');
					var inWorkDevicesWrap = $('[data-devices-in-work-amount]');
					var undefinedDevicesWrap = $('[data-devices-undefined-amount]');
					
					totalDevicesWrap.text('Всего машин: ' + devicesAmount.total_devices_count);
					installedDevicesWrap.text('Установлено: ' + devicesAmount.installed_count);
					inWorkDevicesWrap.text('В работе: ' + devicesAmount.work_count);
					undefinedDevicesWrap.text('Не распределены: ' + devicesAmount.others_count);
				}else{
					console.log('Нет связи с сервером?');
				}										
			}
		});			
	}
	/* ---------------------------------------------
	 Инициалиация стандартных popover Bootstrap
	 --------------------------------------------- */	
	$(function () {
		$('[data-toggle="popover"]').popover()
	})	
	
	/* ---------------------------------------------
	 Подсвечивает настоящий год
	 --------------------------------------------- */	
	$('[data-main-devices-year]').children().each(
			function (){
				if($(this).val() == 2017){
					$(this).css({'background-color':'#a0d2e6'});
				}
			}
	);
	
	/* ---------------------------------------------
	 Сортировка
	 --------------------------------------------- */		
	$('body').on('change', '[data-order-devices-sort]', function(e){
		$('.loading').show();
		var result = $(this).prop("checked");
		var id = $('[date-order-id]').val();
		var sort = result ? 1 : 0;
		// console.log(sort);
		window.location.replace("/admin/order/update/?id=" + id + "&sort=" + sort);
	});	
	
	/* ---------------------------------------------
	 Добавление жгутов
	 --------------------------------------------- */
	$('body').on('change', '[data-ajax-form-harness-type]', function(e){
		var selectedHarness = $(this).val();		
		var harnesses = $('[data-all-harnesses]');
		var harnesses = harnesses.data('all-harnesses');//Список всех жгутов		
		var row1 = $(this).parents('[data-ajax-form-harness-row]');
		// console.log(row1);
		var select2 = row1.find('[data-ajax-form-harness-name]');		
		select2.find('option').remove().end();
		// console.log(harnesses[selectedHarness]);
		select2.prepend(prepareListForSelect(harnesses[selectedHarness]));
	});
	
	$('body').on('change', '[data-ajax-form-harness-name]', function(e){
		var row1 = $(this).parents('[data-ajax-form-harness-row]');
		var hiddenId = row1.find('[data-harness-id]');
		hiddenId.val($(this).val());
	});		
	
	$('body').on('click', '[data-harness-add]', function(){
		var visWrap = $(this).parents('[data-wrap-config]');
		var hiddenWrap = $('[data-hidden-select]');
		var hiddenSelect = hiddenWrap.find('[data-all-harnesses]');
		hiddenSelect.clone().appendTo(visWrap);
		var newRow = $('body').find($('[data-wrap-config]')).find($('[data-all-harnesses]'));
		newRow.removeClass("invisible");
		var equipmentCount = visWrap.find('[data-config-count]');		
		equipmentCount.val(parseInt(equipmentCount.val()) + 1);
	});	
	
	$('body').on('click', '[data-config-add]', function(){
		var visWrap = $('[data-form-wrap]');
		var hiddenWrap = $('[data-hidden-block-config]');
		var hiddenBlock = hiddenWrap.find('[data-wrap-config]');
		hiddenBlock.clone().prependTo(visWrap);
		var newRow = $('body').find($('[data-form-wrap]')).find($('[data-wrap-config]'));
		newRow.removeClass("invisible");		
	});	
	
	$('body').on('click', '[data-delete-harness]', function(){
		var visWrap = $(this).parents('[data-wrap-config]');
		var equipmentCount = visWrap.find('[data-config-count]');		
		equipmentCount.val(parseInt(equipmentCount.val()) - 1);		
		var row = $(this).parents('[data-ajax-form-harness-row]');
		row.remove();
	});	
	
	$('body').on('click', '[data-delete-config]', function(){
		var visWrap = $(this).parents('[data-wrap-config]');
		visWrap.remove();
		//console.log(row);
	});
	
	$('body').on('click', '[data-copy-config]', function(){
		var copyBlock = $(this).parents('[data-wrap-config]');
		var hiddenId = copyBlock.find('[data-config-id]');
		var wrap = $(this).parents('[data-form-wrap]');
		copyBlock.clone().appendTo(wrap);
	});	
	
	function prepareListForSelect(list) {
		var result = '<option value="0"></option>';
		
		for (var i in list)
		{	
			result += '<option value="' + i + '">' + list[i] + '</option>';
		}
		
		return result;
	}
	
	//Работа с этапами в инстуркции к конфигурации техники
	$('body').on('click', '[data-stage-add-button]', function(){//Скрывает или показывает форму добавления этапа
		var wrap = $('[data-ivisible-stage-form]');
		if(wrap.hasClass('invisible-form-wrap')){
			wrap.removeClass('invisible-form-wrap');
		}else{
			wrap.addClass('invisible-form-wrap');
		}
	});	
	/* ---------------------------------------------
	 Коммит всех форм редактирования этапов
	 --------------------------------------------- */	
	$('body').on('click', '[data-stage-save-all]', function(){
		var stageForms = $('[data-stage-form]');
		var amount = stageForms.length;
		// console.log(amount);
		var i = 0;
		if($('[data-stage-form]').length > 0){
			$('.loading').show();
		}
		$('[data-stage-form]').each(function(){
			console.log(i++);
			var url = $(this).data('stage-submit-url');
			var formData = new FormData(this);
			// console.log('Ща будит');
			// console.log(this);
			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				success: function(result){
					if(result){
						// console.log(result, 'OK');
						if(amount == i){													
							location.reload();
						}
					}else{
						// console.log(result, 'Not_OK');
					}
				},
				cache: false,
				contentType: false,
				processData: false				
			});			
		});
		
	});
	/* ---------------------------------------------
	 Редактирование этапа
	 --------------------------------------------- */		
	$('body').on('click', '[data-edit-stage-button]', function(){
		var buttonSave = $('[data-stage-save-all]');
		if(buttonSave.hasClass('invisible-form-wrap')){
			buttonSave.removeClass('invisible-form-wrap');
		}		

		var floatingButtonSave = $('[data-float-button-target]');
		if(floatingButtonSave.hasClass('invisible-form-wrap')){
			floatingButtonSave.removeClass('invisible-form-wrap');
		}
		
		var wrap = $(this).closest('[data-stage-wrap]');
		
		var idWrap = wrap.children('[data-stage-id]');
		// console.log(idWrap);
		var id = idWrap.data('stage-id');
		// console.log(id);
		$.ajax({
			url: '/admin/configuration/update-stage/?id=' + id,
			success: function(result){
				if(result){
					// console.log(result);
					wrap.html(result);
				}				
			}
		});		
	});		
	
	$('body').on('click', '[data-delete-stage-button]', function(){//Удаляет этап
		var wrapParent = $(this).closest('[data-stage-wrap-parent]');
		var wrap = $(this).closest('[data-stage-wrap]');
		var idWrap = wrap.children('[data-stage-id]');
		var id = idWrap.data('stage-id');
		console.log(id);
		$.ajax({
			url: '/admin/configuration/delete-stage/?id=' + id,
			success: function(result){
				if(result){
					wrapParent.remove();
				}				
			}
		});		
	});	
	
	/* ---------------------------------------------
	 Удаление конфигурации
	 --------------------------------------------- */	
	$('body').on('click', '[data-delete-config]', function(){//Удаляет этап
		var id = $(this).data('delete-config');
		var wrapParent = $(this).closest('[data-config-wrap]');
		var name = $(this).data('delete-config-name');
		console.log(id, wrapParent);
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление конфигурации',
			message: 'Удалить конфигурацию ' + name + '?',
			callback: function(result){
				if (result){
					$.ajax({
						url: '/admin/configuration/delete/?id=' + id,
						success: function(result){
							if(result){
								wrapParent.remove();
							}				
						}
					});	
				}
			}
		});			
	});	

	/* ---------------------------------------------
	 Подсвечивает селекты зеленым, если выбран последний
	 --------------------------------------------- */	
	$('[data-select-last-green]').each(function(){
		var count = $(this)[0].length;
		var lastElem = $(this).children()[count-1];
		if($(this).val() == $(lastElem).val()){
			$(this).css('background-color', '#dff0d8');
		}			
	});
	$('body').on('change', '[data-select-last-green]', function(e){
		var count = $(this)[0].length;
		var lastElem = $(this).children()[count-1];
		if($(this).val() == $(lastElem).val()){
			$(this).css('background-color', '#dff0d8');
		}else{
			$(this).css('background-color', '#ffffff');
		}			
	});
	
	$('[data-select-not-first-green]').each(function(){
		if($(this).val() != 0){
			$(this).css('background-color', '#dff0d8');
		}			
	});
	$('body').on('change', '[data-select-not-first-green]', function(e){
		if($(this).val() != 0){
			$(this).css('background-color', '#dff0d8');
		}else{
			$(this).css('background-color', '#ffffff');
		}			
	});	
	
	$('[data-select-harness-green]').each(function(){
		if($(this).val() != 0 && $(this).val() != 10){
			$(this).css('background-color', '#dff0d8');
		}			
	});
	$('body').on('change', '[data-select-harness-green]', function(e){
		if($(this).val() != 0 && $(this).val() != 10){
			$(this).css('background-color', '#dff0d8');
		}else{
			$(this).css('background-color', '#ffffff');
		}			
	});		
	/* ---------------------------------------------
	 Удаление техники из заявки
	 --------------------------------------------- */
	$('body').on('click', '[data-delete-device-order-name]', function(){
		var name = $(this).data('delete-device-order-name');
		var url = $(this).data('delete-device-order');
		bootbox.confirm({ 
			size: 'small',
			title: 'Удаление техники',
			message: 'Удалить технику ' + name + '?',
			callback: function(result){
				if (result){
					$('.loading').show();
					$.ajax({
						url: url,
						success: function(result){
							if(result){
								
							}				
						}
					});	
				}
			}
		});		
	}); 
	/* ---------------------------------------------
	 Скрытие формы редактирования этапа инстуркции
	 --------------------------------------------- */
	$('body').on('click', '[data-stage-hide]', function(){
		var wrap = $(this).parents('[data-stage-wrap]');
		var id = $(this).data('stage-hide');
		$.ajax({
			url: '/admin/configuration/get-stage/?id=' + id,
			success: function(result){
				if(result){
					console.log(result);
					console.log(wrap);
					wrap.empty();
					wrap.html(result);
				}				
			}
		});			
	});
	
	/* ---------------------------------------------
	 Запоминаение открытых табов в заявках
	 --------------------------------------------- */
	$('body').on('click', '[data-collapsable-title]', function(){//Запись состояния в куки
		var name = $(this).data('collapsable-title');
		$.cookie(name, $(this).hasClass('collapsed'));
		// console.log($.cookie(name));
	});
	
	var titles = $('[data-collapsable-title]');
	titles.each(function(){
		if($.cookie($(this).data('collapsable-title'))){
			var condition = $.cookie($(this).data('collapsable-title'));

			if(condition == 'false'){//Если последнее состояния было отрицательным, то скрыть 
				var content = $('[data-collapsable-content=' + $(this).data('collapsable-title') + ']');
				$(this).attr('area-expanded', false);
				$(this).addClass('collapsed');
				content.removeClass('in');
				content.attr('area-expanded', false);
				content.css('height', 0);
			}

		}
	});

	
	/* ---------------------------------------------
	 Свернуть все заголовки в заявке
	 --------------------------------------------- */	
	$('body').on('click', '[data-hide-all]', function(){
		var titles = $('[data-collapsable-title]');
		titles.each(function(){
			if(!$(this).hasClass('collapsed')){
				var name = $(this).data('collapsable-title');
				$.cookie(name, $(this).hasClass('collapsed'));			
				
				var content = $('[data-collapsable-content=' + $(this).data('collapsable-title') + ']');
				$(this).attr('area-expanded', false);
				$(this).addClass('collapsed');
				content.removeClass('in');
				content.attr('area-expanded', false);
				content.css('height', 0);
			}			
		});
	});	

	/* ---------------------------------------------
	 Импорт конфигурации
	 --------------------------------------------- */	
	$('body').on('click', '[data-config-import-button]', function(){
		var idSelect = $('[data-ajax-form-series]');
		var id = idSelect.val();
		console.log(id);
		if(id){
			$.ajax({
				url: '/admin/configuration/get-equipment/?id=' + id,
				success: function(result){
					if(result){
						
						var visWrap = $('[data-wrap-config]');
						visWrap.append(result);
						var equipmentCount = visWrap.find('[data-config-count]');		
						equipmentCount.val(parseInt(equipmentCount.val()) + 1);						
						console.log(visWrap);
						// wrap.html(result);
					}				
				}
			});				
		}else{
			bootbox.alert({ 
				size: 'small',
				title: 'Ошибка',
				message: 'Выберите конфигурацию',
				callback: function(result){
					if (result){
						
					}
				}
			});				
		}			
	});

	/* ---------------------------------------------
	 Тултипы
	 --------------------------------------------- */		
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

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
	 Добавление услуги
	 --------------------------------------------- */	

	$('body').on('click', '[data-service-add]', function(){
		var wrap = $(this).parents('[data-service-list]');
		var type = $(this).data('service-add');
		$.ajax({
			url: '/admin/service/get-form/?type=' + type,
			success: function(result){
				if(result){
					// var wrap = $('[data-service-list]');
					wrap.append(result);
				}				
			}
		});			
	});	

	/* ---------------------------------------------
	 Удаление услуги
	 --------------------------------------------- */	

	$('body').on('click', '[data-delete-service]', function(){
		var wrap = $(this).parents('[data-service-wrap]');
		var parentWrap = wrap.parents('[data-service-list]');
		wrap.remove();
		
		calculateServiceTotals(parentWrap);
	});
	
	/* ---------------------------------------------
	 Подсчет суммарной стоимости услуги заявки
	 --------------------------------------------- */
	$('body').on('change', '[data-service-amount]', function(){
		var wrap = $(this).parents('[data-service-wrap]');
		var price = wrap.find('[data-service-price]');
		var totalPrice = wrap.find('[data-service-totalPrices]');
		totalPrice.val(price.val() * $(this).val());
		
		var parentWrap = wrap.parents('[data-service-list]');
		calculateServiceTotals(parentWrap);
	});

	$('body').on('change', '[data-service-price]', function(){
		var wrap = $(this).parents('[data-service-wrap]');
		var amount = wrap.find('[data-service-amount]');
		var totalPrice = wrap.find('[data-service-totalPrices]');
		totalPrice.val(amount.val() * $(this).val());
		
		var parentWrap = wrap.parents('[data-service-list]');
		calculateServiceTotals(parentWrap);
	});

	$('body').on('change', '[data-service-totalprices]', function(){
		var wrap = $(this).parents('[data-service-wrap]');
		var parentWrap = wrap.parents('[data-service-list]');
		calculateServiceTotals(parentWrap);
	});
	
	function calculateServiceTotals(parentWrap) {
		var resultWrap = parentWrap.next('[data-service-result]');
		var amounts = parentWrap.find('[data-service-amount]');
		var prices = parentWrap.find('[data-service-totalprices]');
		var sumAmountResult = resultWrap.find('[data-amount-result]');
		var sumPriceResult = resultWrap.find('[data-sum-result]');
		
		sumAmount = 0;
		sumPrice = 0;
		$.each(amounts, function(){
			sumAmount += parseInt($(this).val());
		});
		$.each(prices, function(){
			sumPrice += parseInt($(this).val());
		});		
		sumAmountResult.text(sumAmount);
		sumPriceResult.text(sumPrice);		
	}	
	/* ---------------------------------------------
	 Подставление дефолтной стоимости услуги
	 --------------------------------------------- */	
	$('body').on('change', '[data-service-select]', function(){
		var formWrap = $(this).parents('[data-service-wrap]');
		var priceListWrap = $(this).parents('[data-price-list]');
		var serviceName = $(this).val();
		priceList = priceListWrap.data('price-list');
		var servicePrice = priceList[serviceName];
		var priceInput = formWrap.find('[data-service-price]');
		priceInput.val(servicePrice);
		// console.log(formWrap, priceInput);
	});

	/* ---------------------------------------------
	 Яндекс карта
	 --------------------------------------------- */
	if(typeof(ymaps) != "undefined"){
		var map;
		$('[data-yandex-map]').each(function(index, element) {
			var container = $(this);
			var dataSet = $(this).data('yandex-map');
			ymaps.ready(function () {  
				map = new ymaps.Map("map", {
					center: [56.8575, 60.6125], 
					zoom: 3
				});
				$.each(dataSet.all[dataSet.current_month], function(){
					if($(this)[0].installed){
						return true;
					}				
					var myGeocoder = ymaps.geocode($(this)[0].city);
					var city = $(this)[0].city;
					var name = $(this)[0].name;
					var content = $(this)[0].content;
					var icon = 'islands#blueIcon';
					if($(this)[0].installed){
						icon = 'islands#darkGreenIcon';
					}else{
						icon = 'islands#redIcon';
					}
					myGeocoder.then(
						function (res) {
							var placeMark = new ymaps.Placemark(res.geoObjects.get(0).geometry.getCoordinates(), { hintContent: name, balloonContent: content, month: '08-11' }, {preset: icon});
							map.geoObjects.add(placeMark);
						},
						function (err) {
							console.log(city);
						}
					);				
				});
			});		
		}); 
	}
	$('body').on('click', '[data-show-cities]', function(){
		$(this).attr('disabled', true);
		var flag = $('[data-show-installed]').prop("checked");
		map.geoObjects.removeAll();
		var dataSet = $('[data-yandex-map]');
		dataSet = dataSet.data('yandex-map'); 
		var monthsSelect = $('[data-select-picker]');
		var chosenList = [];
		$.each(monthsSelect[0].children, function(){
			if($(this)[0].selected){
				var elems = dataSet.all[$(this)[0].label];
				$.each(elems, function(){
					if($(this)[0].installed && !flag){
						return true;
					}					
					var myGeocoder = ymaps.geocode($(this)[0].city);
					var city = $(this)[0].city;
					var name = $(this)[0].name;
					var content = $(this)[0].content;
					var icon = 'islands#blueIcon';
					if($(this)[0].installed){
						icon = 'islands#darkGreenIcon';
					}else{
						icon = 'islands#redIcon';
					}
					myGeocoder.then(
						function (res) {
							var placeMark = new ymaps.Placemark(res.geoObjects.get(0).geometry.getCoordinates(), { hintContent: name, balloonContent: content, month: '08-11' }, {preset: icon});
							map.geoObjects.add(placeMark);
						},
						function (err) {
							console.log(city);
						}
					);
					
				});				
			}
		});
		$(this).attr('disabled', false);
	}); 

	/* ---------------------------------------------
	 Изменение статуса наличия фотографии
	 --------------------------------------------- */	
	$('body').on('click', '[data-device-photo-status]', function(){
		var icon = $(this);
		var photoStatus = parseInt($(this).attr('data-device-photo-status'));
		var deviceId = parseInt($(this).attr('data-device-id'));
		// console.log(icon, photoStatus, deviceId);
		var url = '/admin/main-devices/photo/?deviceId=' + deviceId + '&status=' + photoStatus;
		$('.loading').show();
		$.ajax({
			url: url,
			success: function(result){
				if(result){
					if(photoStatus){
						icon.attr('data-device-photo-status', 0);
						
						icon.removeClass('green-clr');
						icon.addClass('red-clr');
						
						icon.attr('data-original-title', 'Нет фото');
					}else{
						icon.attr('data-device-photo-status', 1);
						
						icon.removeClass('red-clr');
						icon.addClass('green-clr');
												
						icon.attr('data-original-title', 'Есть фото');						
					}
				}
				$('.loading').hide();
			},				
		});
	});	
});