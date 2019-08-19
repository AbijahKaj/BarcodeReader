<?php
if(isset($_POST['code'])){
	$code = (int) htmlspecialchars($_POST['code']);
	// Get cURL resource
	$curl = curl_init();
// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, [
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => "https://api.upcitemdb.com/prod/trial/lookup?upc=$code",
		CURLOPT_USERAGENT => 'Codular Sample cURL Request'
	]);
// Send the request & save response to $resp
	$resp = curl_exec($curl);
// Close request to clear up some resources
	curl_close($curl);
	$response = array('msg' => "Сервер получил код: $code");
	$response['data'] = isset($resp) ? json_decode($resp) : null;
	echo json_encode($response);
}