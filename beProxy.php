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
}
?>