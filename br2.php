
<!DOCTYPE html>
<html>
<head>
	<title>Barcode Scanner</title>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">

	<script type="text/javascript">

	</script>
	<style type="text/css">
		html, body{
			margin: 0;
			padding: 0;
			height: 100%;
			width: 100%;
		}
		#camera, #camera--view, #camera--sensor{
			position: fixed;
			height: 100%;
			width: 100%;
			object-fit: cover;
		}
		#camera--view, #camera--sensor{
			width: 85%;
			height: 60%;
			left: 7.5%;
			top: 5%;
			border-radius: 4%;
		}
		#camera--trigger{
			background-color: white;
			color: black;
			font-size: 16px;
			border-top-right-radius: 30px;
			border-top-left-radius: 30px;
			padding-top: 5px;
			text-align: center;
			box-shadow: 0 5px 10px 0 rgba(0,0,0,0.2);
			position: fixed;
			bottom: 0;
		}
		.taken{
			height: 100px!important;
			width: 100px!important;
			transition: all 0.5s ease-in;
			border: solid 3px white;
			box-shadow: 0 5px 10px 0 rgba(0,0,0,0.2);
			top: 20px;
			right: 20px;
			z-index: 2;
		}
		.hidden{
			display: none;
		}
		.camera {
			background-color: #c9d3db;
		}
		.viewport{

		}
		#camera--output{
			height: 100px!important;
			width: 100px!important;
		}
		#button-slider{
			width: 30%;
			background-color: #bcd0bd;
			height: 10px;
			margin-top: 0;
			border-radius: 10px;
			text-align: center;
		}
		#detected{
			border: 0;
		}
	</style>
</head>
<body>

	<!-- Camera -->
	<main id="camera" class="camera">
		<div id="interactive" class="viewport">
			<video id="camera--view" class="videoCamera" autoplay="true" preload="auto" muted="true"
			playsinline="true"></video>
			<canvas id="camera--sensor" class="drawingBuffer"></canvas>
		</div>
		<!-- Camera output --> 
		<!-- Camera trigger -->
		<div id="camera--trigger" class="col-12 col-sm-12 h-30">
			<hr id="button-slider" class=""></hr>
			<div class="row">
				<div class="h3 camera--output taken" >
					<img id="camera--output" src="">
				</div>
				<div class="col-sm-8">
					<input id="Take-Picture" type="file" accept="image/*;capture=camera" />
				</div>
				<span id="detected"></span>
			</div>
			
		</div>

		<div id="container">
			<div class="error"></div>
		</main>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.4/polyfill.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/js/all.js"></script>
		<script type="text/javascript" src="JOB.js"></script>
		<script type="text/javascript">
			var takePicture = document.querySelector("#Take-Picture"),
			showPicture = document.getElementById("camera--output");
			Result = document.querySelector("#detected");
			var canvas =document.getElementById("camera--sensor");
			var ctx = canvas.getContext("2d");
			JOB.Init();
			JOB.SetImageCallback(function(result) {
				console.log(result);
				if(result.length > 0){
					var tempArray = [];
					for(var i = 0; i < result.length; i++) {
						tempArray.push(result[i].Format+" : "+result[i].Value);
					}
					Result.innerHTML=tempArray.join("<br />");
				//Quagga.stop();
			}else{
				if(result.length === 0) {
					Result.innerHTML="Decoding failed.";
				}
			}
		});
			JOB.SetStreamCallback(function(result) {
				console.log(result);
				if(result.length > 0){
					var tempArray = [];
					for(var i = 0; i < result.length; i++) {
						tempArray.push(result[i].Format+" : "+result[i].Value);
					}
					Result.innerHTML=tempArray.join("<br />");
				//Quagga.stop();
			}else{
				if(result.length === 0) {
					Result.innerHTML="Decoding Stream failed.";
				}
			}
		});
			JOB.PostOrientation = true;
			JOB.OrientationCallback = function(result) {
				console.log(result);
				canvas.width = result.width;
				canvas.height = result.height;
				var data = ctx.getImageData(0,0,canvas.width,canvas.height);
				for(var i = 0; i < data.data.length; i++) {
					data.data[i] = result.data[i];
				}
				ctx.putImageData(data,0,0);
			};
			JOB.SwitchLocalizationFeedback(true);
			JOB.SetLocalizationCallback(function(result) {
				console.log(result);
				ctx.beginPath();
				ctx.lineWIdth = "2";
				ctx.strokeStyle="red";
				for(var i = 0; i < result.length; i++) {
					ctx.rect(result[i].x,result[i].y,result[i].width,result[i].height); 
				}
				ctx.stroke();
			});
			if(takePicture && showPicture) {
				takePicture.onchange = function (event) {
					var files = event.target.files;
					if (files && files.length > 0) {
						file = files[0];
						try {
							var URL = window.URL || window.webkitURL;
							showPicture.onload = function(event) {
								Result.innerHTML="";
								JOB.DecodeImage(showPicture);
								URL.revokeObjectURL(showPicture.src);
							};
							showPicture.src = URL.createObjectURL(file);
						}
						catch (e) {
							try {
								var fileReader = new FileReader();
								fileReader.onload = function (event) {
									showPicture.onload = function(event) {
										Result.innerHTML="";
										JOB.DecodeImage(showPicture);
									};
									showPicture.src = event.target.result;
								};
								fileReader.readAsDataURL(file);
							}
							catch (e) {
								console.log("Neither createObjectURL or FileReader are supported");
							}
						}
					}
				};
			}
			var cameraSensor = document.querySelector("#camera--sensor"), 
			cameraView = document.querySelector("#camera--view"), 
			cameraOutput = document.querySelector("#camera--output");

			const constraints = {
				video: {
					facingMode: 'environment', //Or user for the front camera 'user'
				}

			};

			const video = document.querySelector('#camera--view');
			var streaming = false;
			navigator.mediaDevices.getUserMedia(constraints).
			then((mediaStream) => {
			// Older browsers may not have srcObject
			if ("srcObject" in video) {
				video.srcObject = mediaStream;
			} else {
				video.src = window.URL.createObjectURL(mediaStream);
			}
			video.onloadedmetadata = e => video.play();
			streaming = true;
		});
			function Decode() {
				if (!streaming) return;
				JOB.DecodeStream(video);
			}
			function StopDecode() {
				JOB.StopStreamDecode();
			}
			Decode();
		</script>
	</body>
	</html>

