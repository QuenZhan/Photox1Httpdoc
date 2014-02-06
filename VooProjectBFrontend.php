<?php
class VooProjectBFrontend{
	public $isAliasDone=false;
	public $root="";
	public $beApiRoot='http://54.199.160.200/voo_stg/index.php/api/';
	function __construct(){
		$this->root=$this->getRoot();
		$this->beApiRoot='http://54.199.160.200/voo_stg/index.php/api/';
		// $this->beApiRoot='dummyBackend.php?api=';
   }
	public function be($apiName,$parameter){
		$root=$this->root;
		$beApiRoot=$this->beApiRoot;
		$ch=curl_init($beApiRoot.$apiName);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameter));
		$result=curl_exec($ch);
		curl_close($ch);
		return json_decode($result);
	}
	function getRoot(){
		$SERVER_NAME=$_SERVER["SERVER_NAME"];
		switch($SERVER_NAME){
		case"www.photox1.com":
		case"www.ibloghub.com":
			$root="http://".$SERVER_NAME."/";
			break;
		case"localhost":
		case"54.199.160.200":
		default:
			$root="http://".$SERVER_NAME."/photox1/";
			break;
		}
		return $root;
	}
	function getUrl($page,$parameter){
		$root=$this->root;
		$isAliasDone=$this->isAliasDone;
		switch($page){
		case"upload":
			return $root."?page=".$page;
		case"userUploads":
		case"userCuration":
		case"user":
			if($isAliasDone)return $root."user/".$parameter;
			return $root."?page=".$page."&uid=".$parameter;
		case"category":
			if($isAliasDone)return $root."tw/all/".$parameter;
			return $root."?page=".$page."&category=".$parameter;
		case"object":
			if($isAliasDone)return $root."object/".$parameter;
			return $root."?page=".$page."&oid=".$parameter;
		case"root":
			return $root;
		default:
			$pageURL = 'http';
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
	}
	function parseUrl($option){
		$isAliasDone=$this->isAliasDone;
		$result="";
		$explode=explode("/",$_SERVER["REQUEST_URI"]);
		switch($option){
		case"page":
			if(array_key_exists("page",$_GET))$result=$_GET["page"];
			if($isAliasDone)$result=$explode[1];
			break;
		case"uid":
			if(array_key_exists("uid",$_GET))$result=$_GET["uid"];
			if($isAliasDone)$result=$explode[2];
			break;
		case"oid":
			if(array_key_exists("oid",$_GET))$result=$_GET["oid"];
			if($isAliasDone)$result=$explode[2];
			break;
		case"category":
			if(array_key_exists("category",$_GET))$result=$_GET["category"];
			if($isAliasDone)$result=$explode[3];
			break;
		}
		return $result;
	}
}
?>