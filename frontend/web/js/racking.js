$( document ).ready(function() {
	var canvas = document.getElementById("canvas");
	var ctx = canvas.getContext("2d");
	var canvasOffset = $("#canvas").offset();
	var offsetX = canvasOffset.left;
	var offsetY = canvasOffset.top;
	var pic = new Image(); 
	var rects = [];

	//
	var rect = (function () {

		// constructor
		function rect(data) {
				this.x = data.x;
				this.y = data.y;
				this.id = data.id;
				this.width = data.width;
				this.height = data.height;
				this.status = data.status;
				this.redraw(this.x, this.y);
				return (this);
		}
		rect.prototype.redraw = function (x, y) {
			this.x = x || this.x;
			this.y = y || this.y;
			this.draw(false);
			return (this);
		}
		//
		rect.prototype.highlight = function (x, y) {
			this.x = x || this.x;
			this.y = y || this.y;
			this.draw(true);
			return (this);
		}
		//
		rect.prototype.draw = function (hover) {
			ctx.save();
			ctx.beginPath();
			if(hover){
				opacity = "0.6";
			}
			else{
				opacity = "0.3";
			}
			
			if(this.status=='normal'){
				ctx.fillStyle = "rgba(0, 255, 0, "+opacity+")";
			}
			
			if(this.status=='alarm'){
				ctx.fillStyle = "rgba(255,0,0,"+opacity+")";
			}
			ctx.rect(this.x, this.y, this.width, this.height);
			ctx.fill();
			ctx.restore();
		}
		//
		rect.prototype.isPointInside = function (x, y) {
			return (x >= this.x && x <= this.x + this.width && y >= this.y && y <= this.y + this.height);
		}


		return rect;
	})();


	//
	function handleMouseDown(e) {
		mouseX = parseInt(e.clientX - offsetX);
		mouseY = parseInt(e.clientY - offsetY);
		// console.log('handleMouseDown');
		// Put your mousedown stuff here
		var clicked = "";
		for (var i = 0; i < rects.length; i++) {
			if (rects[i].isPointInside(mouseX, mouseY)) {
				clicked += rects[i].id + " "
			}
		}
		if (clicked.length > 0) {
			alert("Clicked rectangles: " + clicked);
		}
	}

	//
	function handleMouseMove(e) {
		mouseX = parseInt(e.clientX - offsetX);
		mouseY = parseInt(e.clientY - offsetY);

		// Put your mousemove stuff here
		// ctx.clearRect(0, 0, canvas.width, canvas.height);
		ctx.drawImage(pic, 0, 0);
		for (var i = 0; i < rects.length; i++) {
			if (rects[i].isPointInside(mouseX, mouseY)) {
				rects[i].highlight();
			} else {
				rects[i].redraw();
			}
		}
	}
	// x, y, width, height, fill, stroke, strokewidth
	dataset = $('#canvas').data('rack-dataset');
	pic.src    = '/img/storage.jpg';  // Источник изображения, позаимствовано на хабре
	pic.onload = function() {    // Событие onLoad, ждём момента пока загрузится изображение
		ctx.drawImage(pic, 0, 0);  // Рисуем изображение от точки с координатами 0, 0
		for (var i = 0; i < dataset.length; i++) {
			rects.push(new rect(dataset[i]));
		}
		console.log(rects);
		// rects.push(new rect("Red-Rectangle", 15, 35, 65, 60, "red", "black", 10));
		// rects.push(new rect("Green-Rectangle", 60, 80, 70, 50, "green", "black", 10));
		// rects.push(new rect("Blue-Rectangle", 125, 25, 25, 25, "blue", "black", 10));
		$("#canvas").click(handleMouseDown);
		$("#canvas").mousemove(handleMouseMove);
    }

	//
	// var rects = [];
	
	// rects.push(new rect("Red-Rectangle", 15, 35, 65, 60, "red", "black", 10));
	// rects.push(new rect("Green-Rectangle", 60, 80, 70, 50, "green", "black", 10));
	// rects.push(new rect("Blue-Rectangle", 125, 25, 25, 25, "blue", "black", 10));

	
	// $("#canvas").click(handleMouseDown);
	// $("#canvas").mousemove(handleMouseMove);
});