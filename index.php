<?php
include_once "VooProjectBFrontend.php";
session_start();
$vbfe=new VooProjectBFrontend();
//----------------------------------------------- Open Graph Setting
$title="PHOTOx1 攝影展覽";
$imgBanner="";
$hrefBanner="";
$description="「攝影展覧」與「照片社群」網站";
//----------------------------------------------- User Login
$userLogin=null;		if(isset($_SESSION['voofeUserLogin']))$userLogin=$_SESSION['voofeUserLogin'];
$uidLogin=""; 			if($userLogin!=null)$uidLogin=$userLogin["uid"];
// var_dump($userLogin);
//----------------------------------------------- getCategory
if(isset($_SESSION['voofeCategories'])&&$_SESSION['voofeCategories']!=null)$result=$_SESSION['voofeCategories'];
else {
	$result=$vbfe->be("getCategories",array());
	$_SESSION['voofeCategories']=$result;
}
// var_dump($result);
$curationList=array();
$cateDisplay=array();
$cateUnlock=array();
foreach($result->categories as &$value){
	if($value->id=="900000")$curationList=$value->children;
	else array_push($cateDisplay,$value);
	if(!$value->lock)array_push($cateUnlock,$value);
}
//----------------------------------------------- for Layout 
$hyperlink="";
$targetUid="";
$uid="";
$cid="";
$object=null;
$streamLayout="curation";
$page=$vbfe->parseUrl("page");
$adsSrc='';
$adsLink='';
switch($page){
case"servicePolicy":
case"privatePolicy":
case"upload":
	break;
case"object":
	$result=$vbfe->be("getSingleObject",array("oid"=>$vbfe->parseUrl("oid"),"count"=>true));
	if(($result->error)!="0")break;
	$object=$result->{'targetObject'};
	$title=$object->{'title'};
	$description=$object->{'description'};
	$hyperlink=$object->{'hyperlink'};
	$imgBanner=$object->{'photoObject'}->{"url"};
	$uid=$object->user->uid;
	// $uid=$object->user;
	break;
case"userUploads":
case"userCuration":
case"user":
	$uid=$vbfe->parseUrl("uid");
	$result=$vbfe->be("getUser",array("uid"=>$uid,"count"=>true));
	if($result==null||$result->error!=0){
		$user=null;
		break;
	}
	$user=$result->{'user'};
	// var_dump($user);
	$targetUid=$user->uid;
	switch($page){
	case"userUploads":
		$streamLayout="category";
		$title=$user->nickName."上傳的照片";
		break;
	case"userCuration":
	default:
		$streamLayout="curation";
		$oid="";
		foreach($curationList as &$e){
			if($e->uid==$uid){
				$oid=$e->oidBanner;
				$cid=$e->id;
			}
		}
		$result=$vbfe->be("getSingleObject",array("oid"=>$oid));
		if(($result->error)!="0")break;
		// var_dump($result);
		$object=$result->{'targetObject'};
		if($object->photoObject!=null)$imgBanner=$object->{'photoObject'}->{"url"};
		$hrefBanner=$object->{'hyperlink'};
		$description=$object->{'description'};
		break;
	}
	break;
case"category":
	$cate=$cateDisplay[0];
	$streamLayout="category";
	$title=$vbfe->parseUrl("category");
	$oid="";
	foreach($cateDisplay as &$value){
		if($value->name==$title){
			$cid=$value->id;
			$oid=$value->oidBanner;
		}
	}
	
	if($oid=="")break;
	$result=$vbfe->be("getSingleObject",array("oid"=>$oid));
	if($result->error!="0")break;;
	$object=$result->{'targetObject'};
	$description=$object->{'description'};
	$imgBanner=$object->{'photoObject'}->{"url"};
	break;
default:
	$page="";
	// var_dump($curationList);
	if(count($curationList)<1)break;
	$cate=$curationList[0]; // 第一個 curation 預設為首頁
	$result=$vbfe->be("getSingleObject",array("oid"=>$cate->oidAds));
	if(isset($result->targetObject)){
		$adsSrc=$result->targetObject->photoObject->url;
		$adsLink=$result->targetObject->hyperlink;
	}
	$cid=$cate->id;
	$result=$vbfe->be("getSingleObject",array("oid"=>$cate->oidBanner));
	// var_dump($result);
	if($result==null)break;
	$isokay=true;
	switch($result->error){
	case"0":
		break;
	default:
		$isokay=false;
	}
	if(!$isokay)break;
	$object=$result->{'targetObject'};
	$imgBanner=$object->{'photoObject'}->{"url"};
	$hrefBanner=$object->{'hyperlink'};
	$description=$object->{'description'};
	break;
}
?>
<!DOCTYPE html>
<html lang='zh-TW'>
<head>
	<title><?php echo $title ?></title>
	<link rel="stylesheet" media="all" href="//cdnjs.cloudflare.com/ajax/libs/Han/2.2.3/han.css">
	<link href='css/bootstrap.min.css' rel='stylesheet'/>
	<link href='css/layout.css' rel='stylesheet'/>
	<LINK REL="SHORTCUT ICON" HREF="favicon.gif" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<meta name="keywords" content="photo,photograph,hub,攝影,Curation,photo,photography,photograph,photographer,exhibition,expo,curation,攝影展,攝影,展覧">
	<meta name="author" content="voo.com.tw">
	<meta name="description" content="<?php echo $description ?>">
	<meta property="og:url" content="<?php echo $vbfe->getUrl("current",""); ?>" />
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
		appId      : '234543406726639', // William 管理的 facebook app id。
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
<?php
switch($page):
case"object":?>
<div id="pageObject">
	<article>
		<div class="frame object container center" style="width:830px;">
<?php if($object==null):?>
			<p>Ooops ! Page NOT found! Error Code (1001) </p>
<?php else:?>
			<a class="photo" href="<?php echo $object->hyperlink ;?>" target="_blank">
				<img src="<?php echo $imgBanner ?>" alt="<?php echo $title ?>" />
				<div class="loading">
					<div class="vamWrapper">
						<span class="vam">loading...</span>
					</div>
				</div>
			</a>
			<h1 class="title"><?php echo $title ?></h1>
			<div class="actions">
				<div class="fb-like" data-href="<?php echo $vbfe->getUrl("current",""); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="row">
				<a class="col-sm-3" href="<?php echo $vbfe->getUrl("user",$object->user->uid); ?>">
					<div class="avatarPhotoWrapper column">
						<img class="userPhoto" src="http://graph.facebook.com/<?php echo $object->user->uid;?>/picture?type=small" alt="userPhoto" />
					</div>
					<span class="nickName"><?php echo $object->user->nickName;?></span>
				</a>
				<div class="col-sm-9 description">
					<span class=""><?php echo $description ?></span>
				</div>
			</div>
<?php endif;?>
		</div>
	</article>
</div>
<?php break;
case"":
?>

<?php break;
case"privatePolicy":
?>
<div class="container"></div>
<?php break;
case"upload":
?>
<div class="center">
	<form class="form-horizontal" role="form" id="upload" action="beProxy.php?option=upload" method="post">
		<div id="forFilesDrop" 
		ondrop="TrnthDragAndDrop.onDropFile(event)" 
		ondragover="TrnthDragAndDrop.onDragOver(event);"
		style="height:200px;border:1px solid #ddd;display:none"
		>
			abc
		</div>
	  <div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">照片檔案</label>
		<div class="col-sm-6">
		  <input name="photoFile[]" _multiple type="file" placeholder="file" onchange="VooProjectB.uploadFile();" value="選擇檔案" />
		</div>
	  </div>
	  <div class="form-group">
		<div class="col-sm-offset-3 col-sm-8">
			<button type="button" class="btn btn-primary" onclick="VooProjectB.newObject();" >上傳全部</button>
			<a class="btn btn-link" href='<?php echo $vbfe->getUrl("user",$uidLogin);?>'>取消</a>
		</div>
	  </div>
	  <div>
			<fieldset class="form-group">
				<div class="col-sm-3" >
					
						<output class="photo" >
							<img alt="上傳的圖片" src="content/bannerFrameKinghand.jpg" />
							<div class="loading">
								<div class="vamWrapper">
									<span class="vam">loading...</span>
								</div>
							</div>
						</output>
					<!--
					-->
				</div>
				<div class="col-sm-9">
					<select class="category form-control">
						<option>全站分類</option>
<?php foreach($cateUnlock as &$e):?>
						<option><?php echo $e->name;?></option>
<?php endforeach;?>
					</select>
					<input type="text" class="form-control" id="inputPassword3" placeholder="照片標題">
					<textarea class="form-control" rows="3" placeholder="對於這張照片的描述"></textarea>
					<input type="url" class="form-control" id="inputPassword3" placeholder="延伸連結">
					<div class="">
						<a class="btn btn-danger" href='#'>刪去</a>
					</div>
				</div>
				
			</fieldset>
	  </div>
	
	</form>
</div>
<?php 
	break;
case"userUploads":
case"userCuration":
case"user":
?>
<div id="user" class="center container" style="margin-bottom:10px;">
<?php if($user==null):?>
	<p>Ooops ! Page NOT found! Error Code (1002) </p>
<?php else:?>
	<article style="" class="row">
		<div class="col-sm-3">
			<img class="column" src="http://graph.facebook.com/<?php echo $user->uid ?>/picture?width=180&height=180" alt="photoUser" style="height:180px;width:180px;" />
		</div>
		<div class="column description col-sm-9">
			<h1 class="username">
				<span class="nickName"><?php echo ($user->nickName) ?></span>
			</h1>
			<div>
				<!--
				<dfn>個人網址</dfn> ： 
				-->
				<a href="<?php echo $user->{'website'} ?>"><?php echo $user->{'website'} ?></a>							
			</div>
			<p><?php echo $user->{'introduction'} ?></p>
		</div>
		<aside id="userFb" class="column right" style="display:  none;">
			<div>
			<div class="fb-like" data-href="https://www.facebook.com/PHOTOx1" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
			</div>
			<div class="fb-facepile" data-app-id="234543406726639" data-href="https://www.facebook.com/PHOTOx1" data-max-rows="2" data-colorscheme="light" data-size="medium" data-show-count="false"></div>
		</aside>
	</article>
<?php endif;?>
<?php 
if($uidLogin!=""
	&&$uid==$uidLogin
	&&$user!=null):
?>
	<form class="editForm form-horizontal" role="form">
		<output>
			<aside id="alertEditSuccess" class="alert alert-success">儲存成功！</aside>
			<aside id="alertEditFail" class="alert alert-danger">儲存失敗！</aside>
		</output>
		<div class="form-group">
			<!---->
			<!--
			-->
			<label for="website" class="col-sm-2 control-label">個人網址</label>
			<div class="col-sm-10">
				<input id="website" name="website" type="url" class="form-control" placeholder="編輯：個人網址" />
			</div>
		</div>
		<div class="form-group" style="display:none">
				<label for="website" class="col-sm-2 control-label">專頁廣告網址</label>
			<!--
			-->
			<div class="col-sm-10">
				<input name="website" type="url" class="form-control" placeholder="編輯：專頁廣告網址" />
			</div>
		</div>
		<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<script>
			</script>
			<button class="btn btn-primary" type="button" value="" onclick="VooProjectB.editUser();">儲存</button>
			<a class="btn btn-link" href="<?php echo $vbfe->getUrl("upload","");?>">上傳新照片</a>
		</div>
	  </div>
	</form>
<?php endif;?>
	<aside style="display: none;">
		<img src="content/bannerFrameKinghand.jpg" alt="ads" />
	</aside>
</div>
<?php if($user!=null): ?>
<div class="center dynamicWidth container" style="margin-bottom:10px;">
	<ul class="nav nav-tabs">
		<li class="<?php if($page=="user"||$page=="userCuration")echo 'active';?>"><a href="<?php echo $vbfe->getUrl("userCuration",$uid);?>">展覽照片</a></li>
		<li class="<?php if($page=="userUploads")echo 'active';?>"><a href="<?php echo $vbfe->getUrl("userUploads",$uid);?>">所有照片</a></li>
	</ul>
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
	<header class="center relative container" style="width:837px;">
<?php if($imgBanner!=""):?>
		<a id="banner" class="" title="<?php echo $description?>" href="<?php echo $hrefBanner?>">
			<img class="img-responsive" alt=" banner" src ="<?php echo $imgBanner?>" />
		</a>
<?php else:?>
		<h1 title="<?php echo $description?>"><?php echo $title ?></h1>
<?php endif;?>
	</header>
</div>
<?php
if(($page==""||$page=="category")
	&&$adsSrc!=''):
?>
<div id="sales" class="" style="position:absolute;top:60px;padding:0 15px;">
	<a class="" href="<?php echo $adsLink;?>"><img src="<?php echo $adsSrc;?>" alt="sale" /></a>
</div>
<?php endif;?>
<div id="pageMain" class="">
	<div id="mainSection" class="pushDown framesContainer center dynamicWidth container <?php echo $streamLayout ?> container">
		<div class="stream">
			<div id="frame" class="frame"> <!-- 這裡是 frame 的種子 -->
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
				<h2 class="title">種子標題</h2>
				<div class="actions">
					<span class="fbAction" target="" href="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21"><img src="icon/fbIcon.jpg" alt="fbActions" /></span>
					<!--
					<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
					-->
				</div>
				<a class="userInfo" href="?page=user&uid=">
					<div class="avatarPhotoWrapper column">
						<img class="userPhoto" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c25.0.81.81/s50x50/252231_1002029915278_1941483569_s.jpg " alt="userPhoto" />
					</div>
					<span class="username">
						<span class="nickName">使用者暱稱</span>
					</span>
				</a>
				<hr />
			</div> <!-- end of  frame 的種子 -->
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
<footer class="center container">
<?php 
switch($page):
case"":
case"category":
?>
	<div style="height:340px">
	<div class="fb-like-box" data-href="https://www.facebook.com/PHOTOx1" data-width="807" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>		
	</div>
<?php break;endswitch;?>
	<div style="margin:10px 0;"><center>Copyright © 2014 VOO Inc. All rights reserved.</center></div>
</footer>
<!-- 上方 Fixed topbar -->
<!-- 上方 Fixed topbar -->
<!-- 上方 Fixed topbar -->
<?php 
?>
<div id="topBar" style="display:d none">
	<div class="" style="position:relative">
		<h1 id="siteTitleWrapper" >
			<a class="header column" id="siteTitle" href="<?php echo $vbfe->getUrl("root","") ?>" title="PHOTOX1"></a>
		</h1>
		<!-- Single button -->
		<div id="categoryDropdown" class="btn-group">
		  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			分類 <span class="caret"></span>
		  </button>
		  <div class="dropdown-menu" role="menu" style="width:400px">
			<ul class="column">
<?php foreach($curationList as &$value):?>
				<li><a class="" href="<?php echo $vbfe->getUrl("user",$value->uid);?>"><?php echo $value->name; ?></a>
				</li>
<?php endforeach; ?>
			</ul>
			<ul class="column">
<?php foreach($cateDisplay as &$value): ?>
				<li><a href="<?php echo $vbfe->getUrl("category",$value->name);?>"><?php echo $value->name;?></a>
<?php endforeach; ?>
			</ul>
		  </div>
		</div>
		<div class="right">
<?php if($uidLogin!=""):?>
			<figure class="column afterLogin">
				<button onclick="VooProjectB.gotoMyPage();" style="padding:0;border:0;">
					<img id="userPicture" src="http://graph.facebook.com/<?php echo $userLogin["uid"]; ?>/picture?type=small" style="height:34px" alt="userPicture" />
				</button>
				<button onclick="VooProjectB.gotoMyPage();" class="btn btn-primary"><?php echo $userLogin["firstName"]; ?></button>
			</figure>
<?php else:?>
			<div class="beforeLogin column">
				<button class="btn btn-primary hidden" onclick="VooProjectB.login();">Log in</button>
			</div>
<?php endif;?>
			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				設定 <span class="caret"></span>
			  </button>
				<ol class="dropdown-menu pull-right" role="menu">
			<?php if($uidLogin!=""):?>
					<li class="afterLogin"><a href="<?php echo $vbfe->getUrl("upload",""); ?>">上傳新照片</a>
					<li class="afterLogin"><a href="#" onclick="VooProjectB.gotoMyPage();">個人首頁</a>
					<li class="afterLogin"><a href="#" onclick="VooProjectB.logout();">登出</a>
					<li class="divider"></li>
			<?php endif;?>
					<li><a href="mailto:PHOTOx1@voo.com.tw">報名展出 </a>
					<li><a href="mailto:PHOTOx1@voo.com.tw">聯絡我們</a>
					<li><a href="http://www.facebook.com/PHOTOx1">關於我們</a>
					<li><a href="privatePolicy.htm">隱私權政策</a>
					<li><a href="servicePolicy.htm">服務條款</a>
				</ol>
			</div>
		</div>
	</div>
</div>
<form role="form" id="form" style="display:none" method="post">
	<input name="photoFile" type="file" />
</form>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="script/UI.js"></script>
<script src="script/TrnthDragAndDrop.js"></script>
<script>
VooProjectB.isAliasDone="<?php echo $vbfe->isAliasDone ?>";
VooProjectB.root="<?php echo $vbfe->root ?>";
VooProjectB.page="<?php echo $page ?>";
VooProjectB.streamLayout="<?php echo $streamLayout ?>";
VooProjectB.uid="<?php echo $uidLogin; ?>";
VooProjectB.beApiRoot="<?php echo $vbfe->beApiRoot;?>";
VooProjectB.cid="<?php echo $cid; ?>";
VooProjectB.targetUid="<?php echo $targetUid; ?>";
<?php 
switch($page):
case"object":?>
VooProjectB.objectPage();
<?php
	break;
case"category":?>
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