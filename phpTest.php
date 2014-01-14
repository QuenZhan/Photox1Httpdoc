<?php 

// $ch = curl_init("http://localhost/photox1/dummyBackend.php");
$ch = curl_init("http://photox1.com/dummyBackend.php");
// $fp = fopen("example_homepage.txt", "w");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

echo curl_exec($ch);
curl_close($ch);
// fclose($fp);

?>