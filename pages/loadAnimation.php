<div id="loadAnimWrapper" style="position:fixed;top:0;left:0;z-index:1;width:100vw;height:100vh">
<div id="loadAnimWrapperInner" style="position:fixed;top:50%;left:50%;z-index:2;margin-top: -200px;margin-left: -200px;">
	<div class="animWrap" style="margin: 10px;display: block;min-height: 100px;min-width: 100px;height: 400px;width: 400px;">
		<canvas id="loadcanvas" style="height: inherit;width: inherit;">null</canvas>
	</div>
	<script>
		var load = document.getElementById("loadcanvas");
		var lctx = load.getContext("2d");
		var angle = 0;
		var frame = 0;
		var unitX = load.width / 400;
		var unitY = load.height / 400;
		lctx.font = "30px monotype";
		lctx.textAlign = "center";
		function drawTriangle() {
			lctx.beginPath();
			lctx.moveTo((((250 / Math.tan(60 * Math.PI/180))/120)*angle + 200) * unitX, (50 + (250 / 120) * angle) * unitY); //B
			lctx.lineTo((200 + (150 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (100 + (100 + 200 / 3)/120*angle) * unitY); //b
			lctx.lineTo((200 + 150 / Math.tan(60 * Math.PI/180) - (300 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (200 + 200 / 3) * unitY); //c
			lctx.lineTo((200 + 250 / Math.tan(60 * Math.PI/180)-(500 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, 300 * unitY); //C
			lctx.closePath();
			lctx.fillStyle = "black";
			lctx.fill();
			lctx.beginPath();
			lctx.moveTo((200 + 250 / Math.tan(60 * Math.PI/180)-(500 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, 300 * unitY); //C
			lctx.lineTo((200 + 150 / Math.tan(60 * Math.PI/180) - (300 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (200 + 200 / 3) * unitY); //c
			lctx.lineTo((200 - 150 / Math.tan(60 * Math.PI/180) + (150 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (200 + 200 / 3 - (100 + 200 / 3)/120*angle) * unitY); //d
			lctx.lineTo((200 - 250 / Math.tan(60 * Math.PI/180) + (250 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (300 - 250/120*angle) * unitY); //D
			lctx.closePath();
			lctx.fillStyle = "black";
			lctx.fill();
			lctx.beginPath();
			lctx.moveTo((200 - 250 / Math.tan(60 * Math.PI/180) + (250 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (300 - 250/120*angle) * unitY); //D
			lctx.lineTo((200 - 150 / Math.tan(60 * Math.PI/180) + (150 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (200 + 200 / 3 - (100 + 200 / 3)/120*angle) * unitY); //d
			lctx.lineTo((200 + (150 / Math.tan(60 * Math.PI/180))/120*angle) * unitX, (100 + (100 + 200 / 3)/120*angle) * unitY); //b
			lctx.lineTo((((250 / Math.tan(60 * Math.PI/180))/120)*angle + 200) * unitX, (50 + (250 / 120) * angle) * unitY); //B
			lctx.closePath();
			lctx.fillStyle = "black";
			lctx.fill();
			//text
			lctx.lineWidth = 3;
			lctx.strokeText("loading page", 200 * unitX, 200 * unitY);
			lctx.fillStyle = "white";
			lctx.fillText("loading page", 200 * unitX, 200 * unitY);
		}
		function clearAnim() {
			lctx.clearRect(0, 0, load.width, load.height);
		}
		function animateLoad() {
			clearAnim();
			if(frame < 30) {
				drawTriangle();
				frame++;
			} else if(frame < 60) {
				drawTriangle();
				angle += 4;
				frame++;
			} else {
				drawTriangle();
				angle = 0;
				frame = 0;
			}
		}
		setTimeout(function () {setInterval(animateLoad, 1000 / 60)},1000);
	</script>
</div>
</div>