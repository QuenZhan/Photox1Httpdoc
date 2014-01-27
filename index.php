<?php
session_start();
function getUrl($page,$parameter){
	global $root,$isAliasDone;
	switch($page){
	case"upload":
		return $root."?page=".$page;
	case"userUploads":
	case"userCuration":
	case"user":
		if($isAliasDone)return $root."user/".$parameter;
		return $root."?page=".$page."&uid=".$parameter;
	case"category":
		if($isAliasDone)return $root."category/".$parameter;
		return $root."?page=".$page."&category=".$parameter;
	case"object":
		if($isAliasDone)return $root."object/".$parameter;
		return $root."?page=".$page."&oid=".$parameter;
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
	global $root,$beApiRoot;
	$ch = curl_init($beApiRoot.$apiName);
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
function parseUrl($option){
	global $isAliasDone;
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
		break;
	}
	return $result;
}
function getPage(){
	global $isAliasDone;
	$page="";
	return $page;
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
//----------------------------------------------- Init. Setting
//----------------------------------------------- Init. Setting
//----------------------------------------------- Init. Setting
$isAliasDone=false;		// 若 Alias 設定已經完成，設之為 true
$root=getRoot();		// 根 Url
$beApiRoot=$root."dummyBackend.php?api=";
$beApiRoot='http://54.199.160.200/voo_stg/index.php/api/';
//----------------------------------------------- Open Graph Setting
$title="PHOTOx1 攝影展覽";
$imgBanner="";
$hrefBanner="";
$hyperllink="";
$description="這是一個「攝影展覧」網站";
//----------------------------------------------- User Login
$userLogin=null;		if(isset($_SESSION['voofeUserLogin']))$userLogin=$_SESSION['voofeUserLogin'];
$uidLogin=""; 			if($userLogin!=null)$uidLogin=$userLogin["uid"];
// var_dump($userLogin);
//----------------------------------------------- for Layout 
$uid="";
$object=null;
$streamLayout="curation";
$page=parseUrl("page");
switch($page){
case"upload":
	break;
case"object":
	$result=be("getSingleObject",array("oid"=>parseUrl("oid"),"count"=>true));
	if(($result->error)!="0")break;
	$object=$result->{'targetObject'};
	$title=$object->{'title'};
	$description=$object->{'description'};
	$hyperllink=$object->{'hyperllink'};
	$imgBanner=$object->{'photoObject'}->{"url"};
	$uid=$object->{'user'}->{"uid"};
	break;
case"userUploads":
case"userCuration":
case"user":
	$uid=parseUrl("uid");
	$result=be("getUser",array("uid"=>$uid,"count"=>true));
	if($result==null||$result->error!=0){
		$user=null;
		break;
	}
	$user=$result->{'user'};
	switch($page){
	case"userUploads":
		$streamLayout="category";
		$title=$user->uid."上傳的照片";
		break;
	case"userCuration":
	default:
		$streamLayout="curation";
		$result=be("getSingleObject",array("specialOid"=>$uid));
		if(($result->error)!="0")break;
		$object=$result->{'targetObject'};
		$imgBanner=$object->{'photoCuration'}->{"url"};
		$hrefBanner=$object->{'hyperllink'};
		$description=$object->{'description'};
		break;
	}
	break;
case"category":
	$streamLayout="category";
	$title=parseUrl("category");
	$result=be("getSingleObject",array("oid"=>$title));
	if($result->error!="0")break;;
	$object=$result->{'targetObject'};
	$description=$object->{'description'};
	break;
default:
	$page="";
	$result=be("getSingleObject",array("specialOid"=>"main"));
	if($result==null)break;
	$isokay=true;
	switch($result->error){
	case"wrong oid":
		$isokay=false;
		break;
	default:
	}
	if(!$isokay)break;
	$object=$result->{'targetObject'};
	$imgBanner=$object->{'photoCuration'}->{"url"};
	$hrefBanner=$object->{'hyperllink'};
	$description=$object->{'description'};
	break;
}
?>
<!DOCTYPE html>
<html lang='zh-TW'>
<head>
	<title><?php echo $title ?></title>
	<link rel="stylesheet" media="all" href="//cdnjs.cloudflare.com/ajax/libs/Han/2.2.3/han.css">
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
	<script src="script/cookie.min.js"></script>
	<script src="script/jquery-1.10.1.min.js"></script>
	<script src="http://malsup.github.com/jquery.form.js"></script>
	<script src="script/Utility.js"></script>
	<script src="script/InfinityScroll.js"></script>
	<script src="script/VooProjectB.js"></script>
</head>
<body>
<!-- ======================================================================== start of plugins -->
<!-- ======================================================================== start of plugins -->
<!-- ======================================================================== start of plugins -->
<!-- facebook plugin  -->
<div id="fb-root"></div>
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
<!-- ======================================================================== end of plugins -->
<!-- ======================================================================== end of plugins -->
<div id="paddingSpace"></div>
<div id="sales" class="center relative" style="display:none">
	<a id="buynow" href="/" style="display:none"><img src="icon/iconBuyNow.png" alt="buynow" /></a>
	<a id="applaynow" href="mailto:PHOTOx1@voo.com.tw"><img src="icon/iconApplyNow.png" alt="applaynow" /></a>
	<a href="#nogo"><img src="" alt="sale" /></a>
</div>
<?php
switch($page):
case"object":?>
<div id="pageObject">
	<article>
		<div class="center frame object">
<?php if($object==null):?>
			<p>Ooops ! Bad object id request </p>
<?php else:?>
			<a class="photo" href="<?php echo $hyperllink ?>" target="_blank">
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
				<a class="userInfo column" href="<?php echo getUrl("user",$object->{"user"}->{"uid"}); ?>"  >
					<div class="avatarPhotoWrapper column">
						<img class="userPhoto" src="<?php echo $object->{"user"}->{"photoUrl"}; ?>" alt="userPhoto" />
					</div>
					<span class="username">
						<span class="firstName"><?php echo $object->{"user"}->{"firstName"} ?></span>
						<span class="lastName"><?php echo $object->{"user"}->{"lastName"} ?></span>
					</span>
				</a>
				<div class="column description">
					<span class=""><?php echo $description ?></span>
				</div>
			</div>
<?php endif;?>
		</div>
	</article>
</div>
<?php break;
case"upload":
?>
<div class="center">
	<form id="upload" class="editForm" action="dummyBackend.php?api=upload" method="post">
		<output class="photo" >
			<img alt="上傳的圖片" />
			<div class="loading">
				<div class="vamWrapper">
					<span class="vam">loading...</span>
				</div>
			</div>
		</output>
		<div class="group">
			<label>照片檔案</label>
			<input name="photoFile" class="file " type="file" onchange="VooProjectB.uploadFile();"/>
		</div>
		<div class="group">
			<label>全站分類</label>
			<select class="category">
				<option>請選擇分類</option>
				<option>美食</option>
			</select>
		</div>
		<div class="group">
			<label>照片標題</label>		<input class="title" type="text" />
		</div>
		<div class="group">
			<label>詳細說明</label>
			<textarea class="description" placeholder="一些描述"></textarea>
		</div>
		<div class="group">
			<label>延伸連結</label>		<input class="hyperlink" type="url" />
		</div>
		<div class="group">
			<button type="button" class="button" onclick='VooProjectB.newObject();'>上傳</button>
			<a class="button" href='<?php echo getUrl("user","");?>"'>取消</a>
		</div>
	</form>
</div>
<?php 
	break;
case"userUploads":
case"userCuration":
case"user":
?>
<div id="user" class="center" style="margin-bottom:10px;">
<?php if($user==null):?>
	<p>Oops , 沒有此使用者</p>
<?php else:?>
	<article>
		<img class="column" src="http://graph.facebook.com/<?php echo $user->uid ?>/picture?width=180&height=180" alt="photoUser" style="height:180px;width:180px;" />
		<div class="column description">
			<h1 class="username">
				<span class="firstName"><?php echo ($user->firstName) ?></span>
				<span class="lastName"><?php echo ($user->lastName); ?></span>
				
			</h1>
			<div>
				<dfn>個人網址</dfn> ： <a href="<?php echo $user->{'website'} ?>"><?php echo $user->{'website'} ?></a>							
			</div>
			<p><?php echo $user->{'introduction'} ?></p>
		</div>
		<aside id="userFb" class="column right" style="display: dsf none;">
			<!--
			<div class="fb-like-box" data-href="http://www.facebook.com/<?php echo $user->uid;?>" data-height="180" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="true"></div>
			-->
		</aside>
	</article>
<?php endif;?>
<?php if($uidLogin!=""&&$uid==$uidLogin&&$user!=null):?>
	<form class="editForm">
		<div>
			<label for="website">個人網址</label>
			<input name="website" type="url" />
		</div>
		<div>
			<label for="ad">專頁廣告網址</label>
			<input name="ad" type="url" />
		</div>
		<div style="display:none">
			<label for="ad">分享收藏</label>
			<input name="ad" type="checkbox" />
		</div>
		<button class="button" type="button" value="" >送出</button>
		<a class="button" href="<?php echo getUrl("upload","");?>">上傳新照片</a>
	</form>
<?php endif;?>
	<aside style="display:none;">
		<h2 style="background:#ddd;height:100px">一些廣告</h2>
	</aside>
</div>
<?php if($user!=null): ?>
<div class="center dynamicWidth" style="margin-bottom:10px;">
	<nav class="tabWrapper">
		<a class="button <?php if($page=="user"||$page=="userCuration")echo 'selected';?>" href="<?php echo getUrl("userCuration",$uid);?>">展覽照片</a>
		<a class="button <?php if($page=="userUploads")echo 'selected';?>" href="<?php echo getUrl("userUploads",$uid);?>">所有照片</a>
	</nav>
</div>
<?php endif;?>
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
	</header>
</div>
<div id="pageMain" class="">
	<div id="mainSection" class="pushDown framesContainer center dynamicWidth <?php echo $streamLayout ?>">
		<div class="stream">
			<div id="frame" class="frame">
				<a class="photo" href="objectPage.html" target="_blank">
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
						<img class="userPhoto" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg " alt="userPhoto" />
					</div>
					<span class="username">
						<span class="firstName">firstName</span>
						<span class="lastName">lastName</span>
					</span>
				</a>
				<hr />
			</div>
		</div>
	</div>
</div>
<footer>
	<div class="footer endOfPage">
		<hr>
		<div class="endOfPage">
			END OF PAGE
		</div>
	</div>
</footer>
<?php 
	break;
endswitch;
?>
<!-- 上方 Fixed topbar -->
<!-- 上方 Fixed topbar -->
<!-- 上方 Fixed topbar -->
<div id="topBar" style="display:d none">
	<div class="container">
		<div id="" class="left">
			<button id="categoryButton" class="button">分類</button>
		</div>
		<div class="right">
<?php if($uidLogin!=""):?>
			<figure class="column afterLogin">
				<button onclick="VooProjectB.gotoMyPage();" style="padding:0;border:0;">
					<img id="userPicture" src="http://graph.facebook.com/<?php echo $userLogin["uid"]; ?>/picture?type=small" style="height:34px" alt="userPicture" />
				</button>
				<button id="userSetting" class="column button"><?php echo $userLogin["firstName"].$userLogin["lastName"]; ?></button>
			</figure>
<?php else:?>
			<div class="beforeLogin">
				<button class="button" onclick="VooProjectB.login();">Log in</button>
				<!--
					<div class="fb-login-button" data-max-rows="1" data-show-faces="false"></div>
				-->
				<button id="setting" class="button" >設定</button>
			</div>
<?php endif;?>
		</div>
		<h1 id="siteTitleWrapper" >
			<a class="header column" id="siteTitle" href="<?php echo getUrl("root","") ?>" title="PHOTOX1"></a>
		</h1>
	</div>
</div>
<?php 
$result=be("getCategories",array());
$curationList=array();
$cateDisplay=array();
foreach($result->categories as &$value){
	if($value->id=="900000")$curationList=$value->children;
	if(!$value->lock)array_push($cateDisplay,$value);
}
?>
<!-- 分類的下拉選單 -->
<!-- 分類的下拉選單 -->
<!-- 分類的下拉選單 -->
<div id="panel" class="slideDown">
	<div class="background left">
		<ul class="column">
<?php foreach($curationList as &$value):?>
			<li><a href="<?php echo getUrl("category",$value->name);?>"><?php echo $value->name; ?></a></li>
<?php endforeach; ?>
		</ul>
		<ul class="column">
<?php foreach($cateDisplay as &$value): ?>
			<li><a href="<?php echo getUrl("category",$value->name);?>"><?php echo $value->name;?></a>
<?php endforeach; ?>
		</ul>
	</div>
</div>
<!-- 右邊選單的下拉選單 -->
<!-- 右邊選單的下拉選單 -->
<!-- 右邊選單的下拉選單 -->
<div id="settingSlidedown" class="slideDown">
	<ol class="background right">
<?php if($uidLogin!=""):?>
		<li class="afterLogin"><a href="<?php echo getUrl("upload",""); ?>">上傳新照片</a>
		<li class="afterLogin"><button onclick="VooProjectB.gotoMyPage();">個人首頁</button>
		<li class="afterLogin"><button onclick="VooProjectB.logout();">登出</button>
<?php endif;?>
		<li><a href="mailto:PHOTOx1@voo.com.tw">報名展出 </a>
		<li><a href="mailto:PHOTOx1@voo.com.tw">聯絡我們</a>
		<li><a href="http://www.facebook.com/PHOTOx1">關於我們</a>
		<li><a >隱私權政策</a>
	</ol>
</div>
<form role="form" id="form" style="display:none" method="post">
<input name="photoFile" type="file" />
</form>
<script src="script/UI.js"></script>
<script>
VooProjectB.isAliasDone="<?php echo $isAliasDone ?>";
VooProjectB.root="<?php echo $root ?>";
VooProjectB.page="<?php echo $page ?>";
VooProjectB.streamLayout="<?php echo $streamLayout ?>";
VooProjectB.uid="<?php echo $uidLogin; ?>";
VooProjectB.beApiRoot="<?php echo $beApiRoot; ?>";
<?php 
switch($page):
case"object":?>
VooProjectB.objectPage();
<?php
	break;
case"category":?>
VooProjectB.cld="<?php echo parseUrl("category"); ?>";
<?php
case"":
case"userUploads":
case"userCuration":
case"user":
?>
VooProjectB.mainPage();
<?php
	break;
endswitch;
?>
</script>
</body>
</html>
