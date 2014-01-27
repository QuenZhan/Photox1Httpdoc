<?php
class VooProjectBFrontend{
	public $root="";
	public $beApiRoot='http://54.199.160.200/voo_stg/index.php/api/';
	function __construct(){
		$this->root=$this->getRoot();
		$this->beApiRoot='http://54.199.160.200/voo_stg/index.php/api/';
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
		case"localhost":
		case"54.199.160.200":
			$root="http://".$SERVER_NAME."/photox1/";
			break;
		default:
			$root="http://".$SERVER_NAME."/";
			break;
		}
		return $root;
	}
}
?>