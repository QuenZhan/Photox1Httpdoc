<?php
session_start();
include_once "VooProjectBFrontend.php";
$vbfe=new VooProjectBFrontend();
$option="";		if(array_key_exists("option",$_POST))$option=$_POST["option"];
$user=null;		if(array_key_exists("user",$_POST))$user=$_POST["user"];
switch($option){
case"login":
	$_SESSION['voofeUserLogin']=$user;
	echo" login ";
	break;
case"logout":
	$_SESSION['voofeUserLogin']=null;
	echo" logout ";
	break;
case"upload":
	// echo $_FILES['photoFile']['tmp_name'];
	if(!file_exists("/uploads"))mkdir("/uploads");
	// echo move_uploaded_file($_FILES['photoFile']['tmp_name'][0],"/uploads/tmp.jpg");
	// var_dump($_FILES['photoFile']['tmp_name']);
	// move_uploaded_file($_FILES['photoFile']['tmp_name'],"/uploads/tmp.jpg");
	echo "/uploads/tmp.jpg?".rand();
	break;
}
?>