
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
				<span id="detected"></span>
			</div>
			
		</div>
		<div class="error"></div>
	</main>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.4/polyfill.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/js/all.js"></script>
	<script src="//unpkg.com/javascript-barcode-reader/dist/javascript-barcode-reader.min.js"></script>
	<script type="text/javascript" src="app4.js"></script>
	
</body>
</html>
