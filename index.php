<?php
function be($apiName,$parameter){}
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
if(($_SERVER["SERVER_NAME"]=="localhost")) $root="http://localhost/photox1/";
else $root="http://www.PHOTOx1.com/";
$title="PHOTOx1 攝影展覽";
$imgBanner="";
$hrefBanner="";
$description="攝影展覧網站";
$uid="cappucat"; // 預設的uid，影響 mainPage 顯示的內容
$explode=explode("/",$_SERVER["REQUEST_URI"]);
$page=$explode[1];
$object=null;
$oid="";
if(array_key_exists("page",$_GET))$page=$_GET["page"];
switch($page){
case"object":
	$oid=$explode[2];
	if(array_key_exists("oid",$_GET))$oid=$_GET["oid"];
	break;
	$json=be("getSingleObject",array("oid"=>$oid,"count"=>true));
	$object=$json;
	$title=$object["title"];
	$description=$object["description"];
	break;
case"user":
	$uid=$explode[2];
	break;
}
if(array_key_exists("uid",$_GET))$uid=$_GET["uid"];
switch($uid){
case"eric.cc.hsu":
	$description="陽明山秘境 - Eric the Traveler";
	$imgBanner=$root."content/bannerFrame.jpg";
	$hrefBanner="user/eric.cc.hsu/";
	break;
case"nelson0719":
	$description="那一年 我到過的尼泊爾 - Nelson Wong";
	$imgBanner=$root."content/nelson.jpg";
	$hrefBanner="http://www.facebook.com/nelson0719";
	break;
case"kinghand.wang":
	$description="賞喵悅目 小賢豆豆媽";
	$imgBanner=$root."content/bannerFrameKinghand.jpg";
	$hrefBanner="https://www.facebook.com/kinghand.wang";
	break;
case"cappucat":
	$description="Suka Bali 卡布媽 NINI";
	$imgBanner=$root."content/bannerCappucat.jpg";
	$hrefBanner="http://www.facebook.com/cappucat";
	break;
}
// $firephp->log($page);
?>
<!DOCTYPE html>
<html lang='zh-TW'>
<head>
	<!-- <base href="http://www.PHOTOx1.com/" />  -->
	<base href="<?php echo $root ?>" />
	<title><?php echo $title ?></title>
	<!-- IRMsQ8KTqnGZthsWBda2YjDXTdU --> 
	<!-- Alexa for PHOTOx1.com -->
	<link href='css/han.min.css' rel='stylesheet'/>
	<link href='css/layout.css' rel='stylesheet'/>
	<LINK REL="SHORTCUT ICON" HREF="favicon.gif" />
	<meta name="keywords" content="photo,photography,photograph,photographer,exhibition,expo,curation,攝影展,攝影,展覧">
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
			<a class="photo">
				<img src="content/nelson.jpg" alt="objectPhoto" />
				<div class="loading">
					<div class="vamWrapper">
						<span class="vam">loading...</span>
					</div>
				</div>
			</a>
			<h1 class="title">陽明山秘境</h1>
			<div class="actions">
				<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
			</div>
			<div>
				<span class="description">Eric the Traveler 攝影展 - 陽明山秘境</span>
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
<div id="pageMain">
	<div id="header">
		<header class="center relative">
			<a id="banner" class="" title="<?php echo $description?>" href="<?php echo $hrefBanner?>">
				<img class="" alt="banner" src ="<?php echo $imgBanner?>" />
			</a>
			<div id="sales">
				<a id="buynow" href="/"><img src="icon/iconBuyNow.png" alt="buynow" /></a>
				<a id="applaynow" href="mailto:PHOTOx1@voo.com.tw"><img src="icon/iconApplyNow.png" alt="applaynow" /></a>
			</div>
		</header>
	</div>
	<div id="mainSection" class="pushDown">
		<div class="framesContainer center mainPage">
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
					<div class="footer">
						<menu class="actions">
							<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
							<!-- <img class="column fbAction" src="icon/fbIcon.jpg" alt="imgbutton"/> -->
							<!-- <img class="column" src="icon/fbShareicon.jpg" alt="imgbutton"/> -->
							<!-- <div class="fb-like column" data-href="http://photox1.com/user/eric.cc.hsu/" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div> -->
							
						</menu>
						<div class="info">
							<div class="avatarPhotoWrapper">
								<img src="favicon.gif" alt="thumbnail" />
							</div>
							<div id="score" class="score column">★★★★☆</div>
							<div class="views column">12345</div>
							<span class="column">views</span>
						</div>
					</div>
					<hr />
				</div>
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
<div id="topBar">
	<div class="container">
		<div id="" class="left">
			<button id="categoryButton" class="button"> 
				選單
			</button>
			<input id="search" class="column inputText button" type="text" value="search" />
		</div>
		<div id="acount" class="">
			<div id="avatarPhotoWrapper" class="column">
				<!-- <img id="avatarPhoto" src="favicon.gif" alt="avatarPhoto" /> -->
			</div>
			<a href="" id="acountName" class="column button">Maria S.</a>
		</div>
		<div class="right">
			<!-- <button id="" class="button">範例按鈕</button> -->
			<button id="setting" class="button" href="contact.html">
				設定
			</button>
		</div>
		<h1 id="siteTitleWrapper" ><a class="header column" id="siteTitle" href="/" title="PHOTOx1"></a></h1>
	</div>
</div>
<div id="panel" class="slideDown">
	<div class="">
		<div class="background left">
			<div class="">
				<h2>展覽</h2>
				<h3>攝影</h3>
				<ul>
                                        <li><a href="user/cappucat/">Suka Bali 卡布媽 NINI</a></li>
					<li><a href="user/kinghand.wang/">賞喵悅目 - 小賢豆豆媽</a></li>
					<li><a href="user/nelson0719/">那一年 我到過的尼泊爾 - Nelson Wong</a></li>
					<li><a href="user/eric.cc.hsu/">陽明山秘境 - Eric the Traveler</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div id="settingSlidedown" class="slideDown">
	<ol class="background right">
		<li><a href="mailto:PHOTOx1@voo.com.tw">報名展出 </a>
		<li><a href="mailto:PHOTOx1@voo.com.tw">聯絡我們</a>
		<li><a href="http://www.facebook.com/PHOTOx1">關於我們</a>
	</ol>
</div>
<script src="script/jquery-1.10.1.min.js"></script>
<script src="script/Utility.js"></script>
<script src="script/VooProjectB.js"></script>
<script>
VooProjectB.oid="<?php echo $oid ?>";
VooProjectB.uid="<?php echo $uid ?>";
VooProjectB.page="<?php echo $page ?>";
VooProjectB.start();
</script>
</body>
</html>