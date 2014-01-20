<?php
session_start();
function getUrl($page,$parameter){
	global $root,$isAliasDone;
	switch($page){
	case"userUploads":
	case"userCuration":
	case"user":
		if($isAliasDone)return $root."user/".$parameter;
		return $root."?page=".$page."&uid=".$parameter;
	case"category":
		if($isAliasDone)return $root."object/".$parameter;break;
		return $root."?page=".$page."&cate=".$parameter;
	case"object":
		if($isAliasDone)return $root."category/".$parameter;
		return $root."?page=".$page."&category=".$parameter;
	case"root":
		return $root;
	default:
		return curPageURL();
	}
}
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
$isAliasDone=false;
$title="PHOTOx1 攝影展覽";
if(($_SERVER["SERVER_NAME"]=="localhost")){
	$root="http://localhost/photox1/";
	$title="local:".$title;
}
else $root="http://photox1.com/";
$beApiRoot='http://54.199.160.200/voo/index.php/dummyApi/';
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
$streamLayout="curation";
$isPublic=true;
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
case"userUploads":
case"userCuration":
case"user":
	$uid=$explode[2]; 	if(array_key_exists("uid",$_GET))$uid=$_GET["uid"];
	$result=be("getUser",array("uid"=>$uid,"count"=>true));
	$user=$result->{'user'};
	switch($page){
	case"userUploads":
		$streamLayout="category";
		$title=$uid."上傳的物件";
		break;
	case"userCuration":
	default:
		$streamLayout="curation";
		$result=be("getSingleObject",array("oid"=>0));
		$object=$result->{'targetObject'};
		$imgBanner=$object->{'photoCuration'}->{"url"};
		$hrefBanner=$object->{'hyperllink'};
		$description=$object->{'description'};
		break;
	}
	if(isset($_SESSION['uid'])&&$_SESSION['uid']==$uid){
		$isPublic=false;
	}
	break;
case"category":
	$streamLayout="category";
	$title=$category;
	$result=be("getSingleObject",array("oid"=>0));
	$object=$result->{'targetObject'};
	$description=$object->{'description'};
	break;
default:
	$page="";
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
	<base_ href="<?php echo getUrl("root",""); ?>" />
	<title><?php echo $title ?></title>
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
</head>
<body>
<!-- ======================================================================== start of plugins -->
<!-- facebook plugin  -->
<div id="fb-root"></div>
<script>
  

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));

</script>
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
<?php
 switch($page): 
 case"object":
 ?>
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
				<div class="fb-like" data-href="<?php echo getUrl("",""); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>			</div>
			<div>
				<a class="userInfo column" href="<?php echo getUrl("user",$object->{"user"}->{"uid"}); ?>">
					<div class="avatarPhotoWrapper column">
						<img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg " alt="userPhoto" />
					</div>
					<span class="firstName"><?php echo $object->{"user"}->{"firstName"} ?></span>
					<span class="lastName"><?php echo $object->{"user"}->{"lastName"} ?></span>
				</a>
				<div class="column description">
					<span class=""><?php echo $description ?></span>
				</div>
			</div>
		</div>
	</article>
</div>
<?php 
	break;
case"userUploads":
case"userCuration":
case"user":
?>
<div id="user" class="center">
	<div>
		<img class="column" src="http://graph.facebook.com/<?php echo $uid ?>/picture?width=180&height=180" alt="photoUser" style="height:180px;width:180px;" />
		<div class="column description">
			<div>
				<dfn>個人網址</dfn>：<a href="<?php echo $user->{'website'} ?>"><?php echo $user->{'website'} ?></a>
			</div>
			<p><?php echo $user->{'introduction'} ?></p>
		</div>
		<aside id="userFb" class="column right">
			<div class="fb-like" data-href="<?php echo getUrl("","");?>" data-width="300" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
		</aside>
	</div>
<?php if(!$isPublic):?>
	<form>
		<div>
			<label for="website">個人網址</label>
			<input name="website" type="url" />
		</div>
		<div>
			<label for="ad">專頁廣告網址</label>
			<input name="ad" type="url" />
		</div>
		<div>
			<label for="ad">分享收藏</label>
			<input name="ad" type="checkbox" />
		</div>
		<input type="submit" />
	</form>
<?php endif;?>
	<aside>
		<h2 style="background:#ddd;height:100px">一些廣告</h2>
	</aside>
	<nav>
		<a class="button" href="<?php echo getUrl("userCuration",$uid);?>">展覽</a>
		<a class="button" href="<?php echo getUrl("userUploads",$uid);?>">上傳的物件</a>
	</nav>
</div>
<?php 
	break;
endswitch;
switch($page):
case"":
case"category":
case"userUploads":
case"userCuration":
case"user":
?>
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
<div id="pageMain" class="">
	<div id="mainSection" class="pushDown framesContainer center <?php echo $streamLayout ?>">
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
				</menu>
				<a class="userInfo" href="?page=user&uid=">
					<div class="avatarPhotoWrapper column">
						<img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg " alt="userPhoto" />
					</div>
					<span class="firstName">firstName</span>
					<span class="lastName">lastName</span>
				</a>
				<hr />
			</div>
		</div>
	</div>
</div>
<?php 
	break;
endswitch;
?>
<footer>
	<div class="footer  endOfPage">
		<hr>
		<div class="endOfPage">
			END OF PAGE
		</div>
	</div>
</footer>
<div id="topBar" style="display:d none">
	<div class="container">
		<div id="" class="left">
			<button id="categoryButton" class="button">導覽</button>
		</div>
		<div class="right">
			<figure class="column afterLogin" style="display:none">
				<button onclick="VooProjectB.gotoMyPage();" style="padding:0;border:0;">
					<img id="userPicture" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg" style="height:34px" alt="userPicture" />
				</button>
				<button id="userSetting" class="column button">使用者名稱</button>
			</figure>
			<div class="beforeLogin">
				<button onclick="VooProjectB.login();" class="button" >登入</button>
				<button id="setting" class="button" >設定</button>
			</div>
		</div>
		<h1 id="siteTitleWrapper" >
			<a class="header column" id="siteTitle" href="<?php echo getUrl("root","") ?>" title="PHOTOX1"></a>
		</h1>
	</div>
</div>
<div id="panel" class="slideDown">
	<div class="background left">
		<ul class="column">
			<li><a href="<?php echo getUrl("user","kinghand.wang");?>">賞喵悅目 - 小賢豆豆媽</a></li>
			<li><a href="<?php echo getUrl("user","nelson0719");?>">那一年 我到過的尼泊爾 - Nelson Wong</a></li>
			<li><a href="<?php echo getUrl("user","eric.cc.hsu");?>">陽明山秘境 - Eric the Traveler</a></li>
		</ul>
		<ul class="column">
			<li><a href="<?php echo getUrl("category","類別");?>">類別bla</a>
			<li>類別
			<li>類別
		</ul>
	</div>
</div>
<div id="settingSlidedown" class="slideDown">
	<ol class="background right">
		<li class="afterLogin"><button onclick="VooProjectB.gotoMyPage();">個人首頁</button>
		<li class="afterLogin"><button onclick="VooProjectB.logout();">登出</button>
		<li><a href="mailto:PHOTOx1@voo.com.tw">報名展出 </a>
		<li><a href="mailto:PHOTOx1@voo.com.tw">聯絡我們</a>
		<li><a href="http://www.facebook.com/PHOTOx1">關於我們</a>
		<li><a >隱私權政策</a>
	</ol>
</div>
<script src="script/cookie.min.js"></script>
<script src="script/jquery-1.10.1.min.js"></script>
<script src="script/Utility.js"></script>
<script src="script/InfinityScroll.js"></script>
<script src="script/VooProjectB.js"></script>
<script src="script/UI.js"></script>
<script>
window.fbAsyncInit = function(){
	  FB.init({
		appId      : '1429498433953219',
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
	  });
	VooProjectB.subscribeFbAuthResponseChange();
};
VooProjectB.isAliasDone="<?php echo $isAliasDone ?>";
VooProjectB.root="<?php echo $root ?>";
VooProjectB.beApiRoot="<?php echo $beApiRoot ?>";
VooProjectB.oid="<?php echo $oid ?>";
VooProjectB.page="<?php echo $page ?>";
VooProjectB.streamLayout="<?php echo $streamLayout ?>";
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
VooProjectB.uiAuthStatus();
console.log("<?php echo $_SESSION['uid'];?>")
</script>
</body>
</html>