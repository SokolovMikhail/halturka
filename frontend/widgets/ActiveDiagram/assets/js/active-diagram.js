//
// Диаграмма активности оператора/устройства
//
$( document ).ready(function() {
	var safeColors = [
		'#99CC99','#99FFFF','#CC33FF','#FF9966',
		'#CCFF00','#0066CC','#CC6666','#FFFF00',
		'#CCCC33','#66CCCC','#FF99FF','#FF9933',
		'#33CC99','#9999FF','#FF9999','#330066',
		'#99FF33','#33CCFF','#FF00CC','#FFCC00',
		'#66FFCC','#9966FF','#CC3300','#339900',
		'#00FF33','#3399FF','#FF3366','#CC9900',	
	];
	$('[data-operator-diagram]').each(function()
	{
		var self = this,
			useActive = $(this).data('use-active') ? 1 : 0,
			useWork = $(this).data('use-work') ? 1 : 0,
			useVoltage = $(this).data('use-voltage') ? 1 : 0,
			useIdle = $(this).data('use-idle') ? 1 : 0,
			dayStart = parseInt($(this).data('start-hour')),
			shiftStart  = parseInt($(this).data('shift-start')),
			arActiveLog = $(this).data('active-log'),
			arWorkLog = $(this).data('work-log'),
			arIdleLog = $(this).data('idle-log'),
			arVoltageLog = $(this).data('voltage-log'),
			arServiceLog = $(this).data('service-log'),
			arHiCrashLog = $(this).data('hi-crash-log'),
			arMiddleCrashLog = $(this).data('middle-crash-log'),
			startDayDate = $(this).data('start-day'),
			endDayDate = $(this).data('end-day'),
			currentHour = $(this).data('current-hour'),
			arDevicesLog = $(this).data('devices-log')
			;
		console.log(useIdle);
		xStart = 0;
		yStart = yZero = 50;
		ctx = this.getContext('2d');
		curTime = new Date();
		curTime.setHours(dayStart);		
		// делим диаграмму на 12-часовые интервалы
		yMax = 50*useIdle+50*useActive+50*useWork+50*useVoltage;
		ctx.fillStyle = "rgba(0,0,0,.03)";
		if(dayStart == shiftStart){
			ctx.fillRect(xStart+480, yStart-35, 480, yMax+35);
		}
		else{
			shiftEnd = (shiftStart+12)%24;
			endShiftTime = new Date();
			endShiftTime.setHours(shiftStart+12);
			endShiftTime.setMinutes(0);
			endShiftTime.setSeconds(0);
			endDay = new Date();
			endDay.setHours(dayStart-1);
			endDay.setMinutes(59);
			endDay.setSeconds(59);
			if(((dayStart-shiftStart)*(dayStart-shiftEnd)) >= 0){
				if(shiftStart<dayStart)
					firstPartWitdh = xStart+(shiftStart-dayStart+12)*40;
				else
					firstPartWitdh = (xStart+(shiftStart-dayStart+12)*40)-960*2;
				ctx.fillRect(xStart+(shiftStart-dayStart+12)*40, yStart-35, 480, yMax+35);
				ctx.fillRect(960+firstPartWitdh, yStart-35, 480, yMax+35);
			}
			else{
				ctx.fillRect(xStart+(shiftStart-dayStart+12)*40, yStart-35, 480, yMax+35);
			}
		}	
		// Столкновения
		if(arMiddleCrashLog.length){
			arMiddleCrashLog.forEach(function(a) {
				if(a){
					ctx.fillStyle = "#3183a3";
					ctx.fillRect(a, yStart-17, 1, 10);
				}
			});
		}
		if(arHiCrashLog.length){
			arHiCrashLog.forEach(function(a) {
				if(a){
					ctx.fillStyle = "#F7464A";
					ctx.fillRect(a, yStart-17, 1, 10);
				}
			});
		}
		// Использованые сущности
		if(typeof(arDevicesLog) == 'object'){
			if(isNotEmptyObject(arDevicesLog)){
				i=0;
				arDevicesLog.forEach(function(a) {
					arPoints = a.points;
					if(a.id == 0){
						color = '#CECECE';
					}
					else{
						color = safeColors[i];
						i++;
					}
					ctx.fillStyle = color;
					$(self).parents('.rating-panel').find('.entity-'+a.id).css("color", color);
					a.points.forEach(function(p) {
						ctx.fillRect(p, yStart-34, 1, 10);
					});
				});
			}
		}
		// Шапка разметки
		ctx.fillStyle = "rgba(0,0,0,.1)";
		ctx.fillRect(xStart, yStart, this.width, 1);
		ctx.fillRect(xStart, yStart-35, this.width, 1);
		ctx.fillStyle = "rgba(0,0,0,.05)";
		for(i=0; i<960; i+=40){
			ctx.fillRect(i, yStart-35, 1, yMax+35);
		}
		// Напряжение
		if(useVoltage){
			yStart=yStart+50;
			if(isNotEmptyObject(arVoltageLog)){
				first = true;
				ctx.beginPath();
				ctx.strokeStyle = "#F7464A";
				for (var index in arVoltageLog){
					if(first){
						ctx.moveTo(index, parseInt(yStart-arVoltageLog[index]*0.5));
						// ctx.moveTo(index, parseInt(yStart-20));
						first = false;
					}
					else{
						ctx.lineTo(index, parseInt(yStart-arVoltageLog[index]*0.5));
						// ctx.lineTo(index, parseInt(yStart-10));
					}		
				}
				ctx.stroke();
			}
			ctx.fillStyle = "rgba(0,0,0,.1)";
			ctx.fillRect(xStart, yStart, this.width, 1);
		}
		// Работа
		if(useWork){
			if(isNotEmptyObject(arWorkLog)){
				for (var index in arWorkLog){
					ctx.fillStyle = "#3183a3";
					ctx.fillRect(index, yStart+50, 1, -(49/100)*arWorkLog[index]);
					ctx.fillStyle = "rgba(49, 131, 163, 0.3)";
					ctx.fillRect(index, yStart+1, 1, 49);
				}
			}
			yStart=yStart+50;
			ctx.fillStyle = "rgba(0,0,0,.1)";
			ctx.fillRect(xStart, yStart, this.width, 1);
		}
		// Передвижение
		if(useActive){
			if(isNotEmptyObject(arActiveLog)){
				for (var index in arActiveLog){
					ctx.fillStyle = "#c3d935";
					ctx.fillRect(index, yStart+50, 1, -(49/100)*arActiveLog[index]);
					ctx.fillStyle = "rgba(195, 217, 53, 0.3)";
					ctx.fillRect(index, yStart+1, 1, 49);
				}
			}
			yStart=yStart+50;
			ctx.fillStyle = "rgba(0,0,0,.1)";
			ctx.fillRect(xStart, yStart, this.width, 1);
		}
		// Простои
		if(useIdle){
			if(isNotEmptyObject(arIdleLog)){
				for (var index in arIdleLog){
					ctx.fillStyle = "#ff4e4c";
					ctx.fillRect(index, yStart+50, 1, -(49/100)*arIdleLog[index]);
					ctx.fillStyle = "rgba(255, 70, 70, 0.3)";
					ctx.fillRect(index, yStart+1, 1, 49);
				}
			}
			yStart=yStart+50;
			ctx.fillStyle = "rgba(0,0,0,.1)";
			ctx.fillRect(xStart, yStart, this.width, 1);
		}
		// Футер разметки
		ctx.fillStyle = "rgba(0,0,0,.1)";
		ctx.font = "italic 8pt Arial";
		for(i=0; i<960; i+=10){
			if(i % 40){
				ctx.fillStyle = "rgba(0,0,0,.2)";
				ctx.fillRect(i, yStart+1, 1, 5);
			}
			else{
				ctx.fillStyle = "rgba(0,0,0,.3)"
				ctx.fillRect(i, yStart+1, 1, 8);
				ctx.fillStyle = "rgba(0,0,0,.6)"
				ctx.fillText(curTime.getHours()+':00', i, yStart+20);
				curTime.setHours(curTime.getHours()+1);
			}
		}
		if(startDayDate && endDayDate){
			ctx.font = "bold 10pt Arial";
			ctx.fillStyle = "rgba(0,0,0,.7)"
			ctx.fillText(startDayDate, 0, yStart+45);
			ctx.textAlign = "right";
			ctx.fillText(endDayDate, this.width, yStart+45);
		}
		// Сервис
		if(arServiceLog){
			ctx.fillStyle = "rgba(255,0,0,.1)";
			arServiceLog.forEach(function(a) {
				if(a){
					ctx.fillRect(a, yZero, 1, yMax);
				}
			});
		}
		//метка текущего времени
		if(currentHour){
			curMinutes = parseInt(currentHour.substr(3,2));
			xCord = 960-parseInt((60-curMinutes)*60/90);
			if(xCord>958)
				xCord=958;
			ctx.fillStyle = "#FDB45C";
			ctx.fillRect(xCord, yZero-35, 2, yMax+35);
			ctx.textAlign = "right";
			ctx.fillText(currentHour, xCord, yZero-40);
		}
		//перебор многомерного массива
		function iterator(array, callback){
			var item, index = 0;
		}
	});
	
	var colorset = {},
		colorNumber=0;
	$('[data-activity-diagram]').each(function()
	{	
		var self = this;
		ctx = this.getContext('2d');
		arActivities = $(this).data('activities')
		if(isNotEmptyObject(arActivities)){
			for (var activity in arActivities){
				activityName = '.activity-'+activity.replace(/ /ig, '-');
				if(colorset[activityName] === undefined){
					colorset[activityName] = safeColors[colorNumber++];
				}
				color = colorset[activityName];
				console.log(color,activityName);
				ctx.fillStyle = color;
				for (var index in arActivities[activity]){
					ctx.fillRect(arActivities[activity][index], 0, 1, 20);
				}
				$(self).parents('.active-diagram').find(activityName).css("color", color);
				
			}
		}
	});
	
	
	
	
	//
	// Диаграмма активности оператора/устройства за час
	//
	$('[data-operator-diagram-hourly]').each(function(){
		var self = this,
			safeColors = ['#A4A8DA', '#e8cce1', '#fec8ad', '#fefdc7', '#bcffaa', '#aadeff', '#ffe4aa', '#deffaa', '#ffaaaa',],
			dayStart = parseInt($(this).data('start-hour')),
			shiftStart  = parseInt($(this).data('shift-start')),
			arActiveLog = $(this).data('active-log'),
			arWorkLog = $(this).data('work-log'),
			arServiceLog = $(this).data('service-log'),
			arHiCrashLog = $(this).data('hi-crash-log'),
			arMiddleCrashLog = $(this).data('middle-crash-log'),
			startDayDate = $(this).data('start-day'),
			endDayDate = $(this).data('end-day'),
			currentHour = $(this).data('current-hour'),
			arDevicesLog = $(this).data('devices-log'),
			levelsDiagram = $(this).data('operator-diagram')
			;
		xStart = 0;
		yStart = 20;
		ctx = this.getContext('2d');
		curTime = new Date();
		curTime.setHours(dayStart);		
		curTime.setMinutes(0);	
		// разметка
		ctx.fillStyle = "rgba(0,0,0,.1)";
		ctx.font = "italic 8pt Arial";
		ctx.fillRect(xStart, yStart, this.width, 1);
		ctx.fillRect(xStart, yStart+30, this.width, 1);
		ctx.fillRect(xStart, yStart+80, this.width, 1);
		ctx.fillRect(xStart, yStart+130, this.width, 1);
		for(i=0; i<960; i+=8){
			if(i % 40){
				ctx.fillStyle = "rgba(0,0,0,.2)";
				ctx.fillRect(i, yStart+131, 1, 5);
			}
			else{
				ctx.fillStyle = "rgba(0,0,0,.1)";
				ctx.fillRect(i, yStart-5, 1, 5);
				ctx.fillStyle = "rgba(0,0,0,.3)"
				ctx.fillRect(i, yStart+131, 1, 8);
				ctx.fillStyle = "rgba(0,0,0,.6)";
				curMinutes = curTime.getMinutes();
				if(curMinutes<10)
					curMinutes = '0'+curMinutes
				ctx.fillText(curTime.getHours()+':'+curMinutes, i, yStart+150);
				curTime.setMinutes(curTime.getMinutes()+5);
			}
		}
		if(startDayDate && endDayDate){
			ctx.font = "bold 10pt Arial";
			ctx.fillStyle = "rgba(0,0,0,.7)"
			ctx.fillText(startDayDate, 0, yStart+195);
			ctx.textAlign = "right";
			ctx.fillText(endDayDate, this.width, yStart+195);
		}
		
		// Движение
		if(isNotEmptyObject(arActiveLog)){
			for (var index in arActiveLog){
				ctx.fillStyle = "#9bbb59";
				for(i=0;i<12;i++){
					ctx.fillRect(index*12+i, yStart+130, 1, -(49/100)*(getRandomArbitary(arActiveLog[index]*0.6, arActiveLog[index])));
				}
				// ctx.fillRect(index*12, yStart+130, 12, -(49/100)*arActiveLog[index]);
				ctx.fillStyle = "rgba(195, 217, 53, 0.3)";
				ctx.fillRect(index*12, yStart+81, 12, 49);
			}
		}
		
		// Работа		
		if(isNotEmptyObject(arWorkLog)){
			for (var index in arWorkLog){
				ctx.fillStyle = "#3183a3";
				for(i=0;i<12;i++){
					ctx.fillRect(index*12+i, yStart+80, 1, -(49/100)*(getRandomArbitary(arWorkLog[index]*0.6, arWorkLog[index])));
				}
				ctx.fillStyle = "rgba(49, 131, 163, 0.3)";
				ctx.fillRect(index*12, yStart+31, 12, 49);
			}
		}
		
		if(arServiceLog){
			ctx.fillStyle = "rgba(255,0,0,.1)";
			arServiceLog.forEach(function(a) {
				if(a){
					ctx.fillRect(a*12, yStart, 12, 150);
				}
			});
		}
		if(arMiddleCrashLog.length){
			arMiddleCrashLog.forEach(function(a) {
				if(a){
					ctx.fillStyle = "#3183a3";
					ctx.fillRect(a*12, yStart+1, 2, 29);
				}
			});
		}
		if(arHiCrashLog.length){
			arHiCrashLog.forEach(function(a) {
				if(a){
					ctx.fillStyle = "#F7464A";
					ctx.fillRect(a*12, yStart+1, 2, 29);
				}
			});
		}
		
		//использованые оператором устройства
		if(typeof(arDevicesLog) == 'object'){
			if(isNotEmptyObject(arDevicesLog)){
				i=0;
				arDevicesLog.forEach(function(a) {
					arPoints = a.points;
					ctx.fillStyle = safeColors[i];
					$(self).parents('.rating-panel').find('.entity-'+a.id).css("color", safeColors[i]);
					a.points.forEach(function(p) {
						ctx.fillRect(p*12, yStart-5, 12, 5);
					});
					i++;
				});
			}
		}
		
		//метка текущего времени
		if(currentHour){
			curMinutes = parseInt(currentHour.substr(3,2));
			xCord = 480+curMinutes*8;
			if(xCord>948){
				xCord=948;
			}
			xCord = xCord%12<6 ? xCord-xCord%12 : xCord-xCord%12+12;
			ctx.fillStyle = "rgba(243, 78, 49, 1)";
			ctx.fillText(currentHour, xCord, yStart-10);
			ctx.fillStyle = "rgba(243, 78, 49, 0.5)";
			ctx.fillRect(xCord, yStart-5, 12, 135);
			ctx.textAlign = "right";
		}
	});
	
	
	/* ---------------------------------------------
	  Обработка нажатия разблокировки техники. AJAX
	 --------------------------------------------- */
	$('[data-unlock-url]').on('click', function(e){
		e.preventDefault();
		var target_url = $(this).data('unlock-url');
		var device_id = $(this).data('unlock-id');
		$('.loading').show();
		var icon = $(this).parents('[data-status]').find('[data-status-icon]');
		var title = $(this).parents('[data-status]').find('[data-status-text]');
		$.ajax({
			url: target_url+device_id,
			success: function(result){
				if(result){
					$('.loading').hide();
					icon.removeClass().addClass("event-statement orange-bg inline-statement");
					title.text("Ожидает разблокировки");
					bootbox.alert({ 
						size: "small",
						title: "Успешно",
						message: "Запрос на разблокировку отправлен.", 
						callback: function(){ /* your callback code */ }
					});					
				}
				else{
					$('.loading').hide();
					bootbox.alert({ 
						size: "small",
						title: "Ошибка",
						message: "Не удалось отправить запрос.", 
						callback: function(){ /* your callback code */ }
					});				
				}
				
			}
		});
	});
});


	