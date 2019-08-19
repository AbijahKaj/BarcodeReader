<?php
$directory = 'samples/';
$html = "";
$images = array_diff(scandir($directory), array('..', '.'));
$i = 1;
foreach ($images as $image) {
	$html .= <<<EOFILE
	<tr>
	<th scope="row"><img id="img-{$i}" style='height: 100px!important; width: 100px!important;' src='samples/{$image}'></th>
	<td>{$image}<br>
	<span id="result-1-{$i}"></span><br>
	<span id="result-2-{$i}"></span>
	</td>
	<td><button id="{$i}" class="btn btn-outline-primary action-1">Test 1</button></td>
	<td><button id="{$i}" class="btn btn-outline-primary action-2">Test 2</button></td>
	</tr>
EOFILE;
	$i++;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Barcode</title>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
	<style type="text/css">
		
	</style>
</head>
<body>
	<div class="container">
		<table class="table">
			<thead class="">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Image</th>
					<th scope="col">Algorithm 1</th>
					<th scope="col">Algorithm 2</th>
				</tr>
			</thead>
			<tbody>
				<?= $html; ?>
				
			</tbody>
		</table>
	</div>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="quagga.min.js"></script>
	<script type="text/javascript" src="JOB.js"></script>
	<script type="text/javascript">
		$(".action-1").on('click', function(e){
			e.stopPropagation();
			e.stopImmediatePropagation();
			var id = $(this).attr("id");
			var img = $(`#img-${id}`).attr("src");
			Quagga.decodeSingle(
			{
				decoder: {
					readers: ["ean_reader"]
				},
				locate: true,
				src: encodeURI(img)
			},
			function(result){
				if(result && result.codeResult && result.codeResult.code)
				{
					$(`#result-1-${id}`).text(result.codeResult.format +' : '+result.codeResult.code);
				}else{
					$(`#result-1-${id}`).text("Quagga.JS : unable to read");
				}
			});
		});

		JOB.Init();
		$(".action-2").on('click', function(e){
			e.stopPropagation();
			e.stopImmediatePropagation();
			var id = $(this).attr("id");
			var img = document.getElementById(`img-${id}`)
			JOB.SetImageCallback(function(result) {
				//console.log(result);
				if(result.length > 0){
					var tempArray = [];
					for(var i = 0; i < result.length; i++) {
						tempArray.push(result[i].Format+" : "+result[i].Value);
					}
					$(`#result-2-${id}`).text(tempArray.join("<br />"));
				}else{
					if(result.length === 0) {
						$(`#result-2-${id}`).text("JOB.js : Decoding failed.");
					}
				}
			});
			JOB.DecodeImage(img);
		});
	</script>
</body>
</html>
