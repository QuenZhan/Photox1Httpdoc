<?php
function be($apiName,$parameter){
	global $root;
	$beApiRoot='http://54.199.160.200/voo/index.php/dummyApi/';
	$beApiRoot=$root."dummyBackend.php?api=";
	$beApiRoot='http://test.talkin.cc/voo/Dummyapi/';
	$ch = curl_init($beApiRoot.$apiName);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameter));
	$result=curl_exec($ch);
	curl_close($ch);
	return json_decode($result);
}
?>