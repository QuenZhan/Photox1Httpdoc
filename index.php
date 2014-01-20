﻿<?php
function jsonError(){
	switch (json_last_error()){
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
}
function be($apiName,$parameter){
	global $beApiRoot;
	$ch = curl_init($beApiRoot.$apiName);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameter));
	$result=curl_exec($ch);
	curl_close($ch);
	return json_decode($result);
	// return ($result);
}
function curPageURL(){
	$pageURL = 'http';
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
$title="PHOTOx1 攝影展覽";
if(($_SERVER["SERVER_NAME"]=="localhost")){
	$root="http://localhost/photox1/";
	$title="local:".$title;
}
else $root="http://photox1.com/";
$beApiRoot='http://test.talkin.cc/voo/api/';
$beApiRoot=$root."dummyBackend.php?api=";
$imgBanner="";
$hrefBanner="";
$hyperllink="";
$description="這是一個「攝影展覧」網站";
$uid="kinghand.wang"; // 預設的uid，影響 mainPage 顯示的內容
$explode=explode("/",$_SERVER["REQUEST_URI"]);
$page=$explode[1];		if(array_key_exists("page",$_GET))$page=$_GET["page"];
$object=array();
$oid="";
$category="";			if(array_key_exists("category",$_GET))$category=$_GET["category"];
switch($page){
case"object":
	$oid=$explode[2];
	if(array_key_exists("oid",$_GET))$oid=$_GET["oid"];
	$result=be("getSingleObject",array("oid"=>$oid,"count"=>true));
	// var_dump ($result);
	// jsonError();
	$object=$result->{'targetObject'};
	$title=$object->{'title'};
	$description=$object->{'description'};
	$hyperllink=$object->{'hyperllink'};
	$imgBanner=$object->{'photoObject'}->{"url"};
	$oid=$object->{'oid'};
	$uid=$object->{'user'}->{"uid"};
	break;
case"user":
	$uid=$explode[2];
case"category":
	$title=$category;
	$result=be("getSingleObject",array("oid"=>0));
	$object=$result->{'targetObject'};
	$description=$object->{'description'};
	$result=be("getObjects",array("categoies"=>array($category)));
	break;
default:
	$result=be("getSingleObject",array("oid"=>0));
	$object=$result->{'targetObject'};
	$imgBanner=$object->{'photoCuration'}->{"url"};
	$hrefBanner=$object->{'hyperllink'};
	$description=$object->{'description'};
	$result=be("getObjects",array("categoies"=>array($category)));
	break;
}
?>
<!DOCTYPE html>
<html lang='zh-TW'>
<head>
	<!-- <base href="http://photox1.com/" />  -->
	<base href="<?php echo $root ?>" />
	<title><?php echo $title ?></title>
	<!-- IRMsQ8KTqnGZthsWBda2YjDXTdU --> 
	<!-- Alexa for PHOTOx1.com -->
	<link href='css/han.min.css' rel='stylesheet'/>
	<link href='css/layout.css' rel='stylesheet'/>
	<LINK REL="SHORTCUT ICON" HREF="favicon.gif" />
	<meta name="keywords" content="photo,photograph,hub,攝影,Curation">
	<meta name="author" content="voo.com.tw">
	<meta name="description" content="<?php echo $description ?>">
	<meta property="og:url" content="<?php echo curPageURL() ?>" />
	<meta property="og:title" content="<?php echo $title ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?php echo $imgBanner ?>" />
	<meta property="og:description" content="<?php echo $description ?>" />
	<meta property="og:site_name" content="PHOTOx1" />
	<style>
		// #topBar{display:none;}
	</style>
</head>
<body>
<!-- ======================================================================== start of plugins -->
<!-- facebook plugin  -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"RDKMi1a4ZP0085", domain:"photox1.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="" style="display:none" height="1" width="1" alt="" /></noscript>
 <!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46646053-1', 'photox1.com');
  ga('send', 'pageview');

</script>
<!-- ======================================================================== end of plugins -->
<div id="paddingSpace"></div>
<?php if($page=="object"): ?>
<div id="pageObject">
	<article>
		<div class="center frame object">
			<a class="photo" href="<?php echo $hyperllink ?>">
				<img src="<?php echo $imgBanner ?>" alt="<?php echo $title ?>" />
				<div class="loading">
					<div class="vamWrapper">
						<span class="vam">loading...</span>
					</div>
				</div>
			</a>
			<h1 class="title"><?php echo $title ?></h1>
			<div class="actions">
				<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
			</div>
			<div>
				<div class="userInfo column">
					<div class="avatarPhotoWrapper column">
						<img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg " alt="userPhoto" />
					</div>
					<span class="firstName"><?php echo $object->{"user"}->{"firstName"} ?></span>
					<span class="lastName"><?php echo $object->{"user"}->{"lastName"} ?></span>
				</div>
				<div class="column description">
					<span class=""><?php echo $description ?></span>
				</div>
			</div>
		</div>
	</article>
</div>
<?php endif;?>
<?php 
switch($page):
case"object":break;
default:
?>
<div id="pageMain" class="<?php echo $page ?>">
	<div id="header">
		<header class="center relative">
<?php if($imgBanner!=""):?>
			<a id="banner" class="" title="<?php echo $description?>" href="<?php echo $hrefBanner?>">
				<img class="" alt="banner" src ="<?php echo $imgBanner?>" />
			</a>
<?php else:?>
			<h1 title="<?php echo $description?>"><?php echo $title ?></h1>
<?php endif;?>
			<div id="sales" >
				<a id="buynow" href="/" style="display:none"><img src="icon/iconBuyNow.png" alt="buynow" /></a>
				<a id="applaynow" href="mailto:PHOTOx1@voo.com.tw"><img src="icon/iconApplyNow.png" alt="applaynow" /></a>
			</div>
		</header>
	</div>
	<div id="mainSection" class="pushDown framesContainer center">
		<div class="stream">
			<div id="frame" class="frame">
				<a class="photo" href="objectPage.html">
					<div class="rectify ">
						<img src="" alt="thumbnail" />
					</div>
					<div class="loading">
						<div class="vamWrapper">
							<span class="vam">loading...</span>
						</div>
					</div>
				</a>
				<h2 class="title">陽明山秘境</h2>
				<menu class="actions">
					<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
					<!-- <img class="column fbAction" src="icon/fbIcon.jpg" alt="imgbutton"/> -->
					<!-- <img class="column" src="icon/fbShareicon.jpg" alt="imgbutton"/> -->
					<!-- <div class="fb-like column" data-href="http://photox1.com/user/eric.cc.hsu/" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div> -->
					
				</menu>
				<div class="userInfo">
					<div class="avatarPhotoWrapper column">
						<img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg " alt="userPhoto" />
					</div>
					<span class="firstName">firstName</span>
					<span class="lastName">lastName</span>
				</div>
				<hr />
			</div>
		</div>
	</div>
</div>
<?php endswitch;?>
<footer>
	<div class="footer  endOfPage">
		<hr>
		<div class="endOfPage">
			END OF PAGE
		</div>
	</div>
</footer>
<div class="debug">
	<input type="button" onclick="$('.debug').toggle()" value="hide"/>
	debug area
	<button onclick="$('#header img').toggle()">banner toggle</button>

</div>
<div id="topBar" style="display:d none">
	<div class="container">
		<div id="" class="left">
			<button id="categoryButton" class="button"> 
				選單
			</button>
		</div>
		<div class="right">
			<button id="setting" class="button" href="contact.html">
				設定
			</button>
		</div>
		<h1 id="siteTitleWrapper" >
			<a class="header column" id="siteTitle" href="<?php echo $root ?>" title="PHOTOX1"></a>
		</h1>
	</div>
</div>
<div id="panel" class="slideDown">
	<div class="background left">
		<ul class="column">
			<li><a href="user/kinghand.wang/">賞喵悅目 - 小賢豆豆媽</a></li>
			<li><a href="user/nelson0719/">那一年 我到過的尼泊爾 - Nelson Wong</a></li>
			<li><a href="user/eric.cc.hsu/">陽明山秘境 - Eric the Traveler</a></li>
		</ul>
		<ul class="column">
			<li><a href="?page=category&category=類別">類別bla</a>
			<li>類別
			<li>類別
		</ul>
	</div>
</div>
<div id="settingSlidedown" class="slideDown">
	<ol class="background right">
		<li><a >登入</a>
		<li><a href="mailto:PHOTOx1@voo.com.tw">報名展出 </a>
		<li><a href="mailto:PHOTOx1@voo.com.tw">聯絡我們</a>
		<li><a href="http://www.facebook.com/PHOTOx1">關於我們</a>
		<li><a >隱私權政策</a>
	</ol>
</div>
<script src="script/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="script/jquery.endless-scroll.js"></script>
<script src="script/Utility.js"></script>
<script src="script/InfinityScroll.js"></script>
<script src="script/VooProjectB.js"></script>
<script src="script/UI.js"></script>
<script>
VooProjectB.beApiRoot="<?php echo $beApiRoot ?>";
VooProjectB.oid="<?php echo $oid ?>";
VooProjectB.uid="<?php echo $uid ?>";
VooProjectB.page="<?php echo $page ?>";
<?php 
switch($page):
case"object":?>
VooProjectB.objectPage();
<?php
	break;
default:?>
VooProjectB.mainPage();
<?php
endswitch;
?>
</script>
</body>
</html>