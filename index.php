
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
		#camera, .viewer, #camera--sensor, #interactive{
			position: fixed;
			height: 100%;
			width: 100%;
			object-fit: cover;
		}
		.viewer{
			z-index: -1;
		}
		#camera--view, #camera--sensor{
			width: 100%;
			height: 100%;
			
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
		.inputfile {
			width: 0.1px;
			height: 0.1px;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			z-index: -1;

		}
		.inputfile + label {
			font-size: 1.25em;
			font-weight: 700;
			color: white;
			background-color: black;
			display: inline-block;
			padding: 10px;
			width: 50%;
			text-align: center;
			margin-left: 25%;
			margin-top: 10%;
		}

		.inputfile:focus + label,
		.inputfile + label:hover {
			background-color: rgb(29, 55, 95);
		}
		.inputfile + label {
			cursor: pointer; /* "hand" cursor */
		}
		.inputfile:focus + label {
			outline: 1px dotted #000;
			outline: -webkit-focus-ring-color auto 5px;
		}
		.inputfile + label * {
			pointer-events: none;
		}
		.inputfile:focus + label,
		.inputfile.has-focus + label {
			outline: 1px dotted #000;
			outline: -webkit-focus-ring-color auto 5px;
		}
		.imgBuffer, .drawingBuffer{
			display: none;
		}


		label > strong{
			margin: 5%;
		}
	</style>
</head>
<body>

	<!-- Camera -->
	<main id="camera" class="camera">
		<div id="interactive" class="viewport">
			<img class="viewer" id="viewer" src="">
			<div class="myinput">
				<input type="file" name="file" id="file" class="inputfile" accept="image/*;capture=camera"/>
				<label for="file"><i class="fa fa-camera"></i><strong>Сканирование штрих-кода</strong></label>
			</div>
			<canvas id="camera--sensor" class="drawingBuffer"></canvas>
		</div>
		<!-- Camera output --> 
		<!-- Camera trigger -->
		<div id="camera--trigger" class="col-12 col-sm-12 h-30">
			<hr id="button-slider" class=""></hr>
			<div class="row">
				<div class="col-sm-12 col-md-6" id="server_response">
					<div class="product">
						<h3 class="h3"></h3>
						<div class="price"></div>
					</div>
				</div>
				<div class="col-sm-12 col-md-6" id="result"></div>
			</div>
			
		</div>

	</main>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.4/polyfill.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/js/all.js"></script>
	<script type="text/javascript" src="quagga.min.js"></script>
	<script type="text/javascript" src="JOB.js"></script>
	<script type="text/javascript">
		var takePicture = document.querySelector("#file");
		var Result = document.querySelector("#result");
		var takePicture2 = "";
		var showPicture = document.getElementById("viewer");
		var _result = "";
		JOB.Init()
		JOB.SetImageCallback(function(result) {
			console.log(result);
			if(result.length > 0){
				var tempArray = [];
				for(var i = 0; i < result.length; i++) {
					tempArray.push(result[i].Format+" : "+result[i].Value);
					send_to_server(result[i].Value)
				}
				Result.innerHTML=tempArray.join("<br />");
				//Quagga.stop();
			}else{
				if(result.length === 0) {
					Result.innerHTML="Расшифровка не удалась.";
				}
			}
		});
		takePicture.onchange = function (event) {
			var files = event.target.files;
			
			if (files && files.length > 0) {
				file = files[0];
				try {
					console.log("Using createObjectURL")
					var URL = window.URL || window.webkitURL;
					showPicture.onload = function(event) {
						console.log("Using createObjectURL")
						Result.innerHTML="";
						var canvas = document.createElement('CANVAS');
						var ctx = canvas.getContext('2d');
						var dataURL;
						canvas.height = this.naturalHeight;
						canvas.width = this.naturalWidth;
						ctx.drawImage(this, 0, 0);
						dataURL = canvas.toDataURL("image/png");
						canvas = null;
						Quagga.decodeSingle(
						{
							decoder: {
								readers: ["ean_reader"]
							},
							locate: true,
							src: dataURL
						},
						function(result){
							if(result && result.codeResult && result.codeResult.code)
							{
								console.log(result.codeResult.format +' : '+result.codeResult.code);
								send_to_server(result.codeResult.code)
								$(`#result`).text(result.codeResult.format +' : '+result.codeResult.code);
							}else{

								JOB.DecodeImage(showPicture);
							}
						});

						//URL.revokeObjectURL(showPicture.src);
					};
					showPicture.src = URL.createObjectURL(file);
				}
				catch (e) {
					console.log(e, "Using FileReader")
					try {
						var fileReader = new FileReader();
						fileReader.onload = function (event) {
							showPicture.onload = function(event) {
								Result.innerHTML="";
								var canvas = document.createElement('CANVAS');
								var ctx = canvas.getContext('2d');
								var dataURL;
								canvas.height = this.naturalHeight;
								canvas.width = this.naturalWidth;
								ctx.drawImage(this, 0, 0);
								dataURL = canvas.toDataURL("image/png");
								Quagga.decodeSingle(
								{
									decoder: {
										readers: ["ean_reader"]
									},
									locate: true,
									src: dataURL
								},
								function(result){
									if(result && result.codeResult && result.codeResult.code)
									{
										console.log(result.codeResult.format +' : '+result.codeResult.code);
										send_to_server(result.codeResult.code)
										$(`#result`).text(result.codeResult.format +' : '+result.codeResult.code);
									}else{

										JOB.DecodeImage(showPicture);
									}
								});
							};
							showPicture.src = event.target.result;
						};
						fileReader.readAsDataURL(file);
					}
					catch (e) {
						console.log(e," Neither createObjectURL or FileReader are supported");
					}
				}
			}
			$("label > strong").text("Сканирование другого штрих-кода");
		};
		function send_to_server(code) {
			try{
				$.ajax({
					url : 'ajax.php',
					type : 'POST',
					data: {'code' : code},
					dataType : 'json',
					success : function(data){
						if(data.data != ""){
							if (data.data.items.length > 0) {
								data.data.items.forEach(function(element) {
									console.log(element);
									$("#server_response").append('<div class="product">\
										<h3 class="h3">'+element.title+'</h3>\
										<div class="price">$ '+element.lowest_recorded_price+'</div>\
										</div>')
								});
							}else{
								$("#server_response").text("Нет такого товара в магазине")
							}
							
						}
					},

				})
			}catch(e){
				console.log(e)
			}
			
		}
	</script>
</body>
</html>

