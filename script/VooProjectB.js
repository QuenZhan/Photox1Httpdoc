var VooProjectB={first:false
	,isAliasDone:false
	,root:""
	,index:0
	,indexPage:0
	,page:""
	,beApiRoot:"beApiRoot"
	,uid:"uid"
	,oid:"oid"
	,streams:[]
	,jFrameSeed:false
	,data:[]
	,be:function(apiName,parameter){
		
	}
	,getObjects:function(){
		// this.data=
	}
	,jImgLoadBuffer:function jImgLoadBuffer(jObj,url){
		jObj.each(function(){
				this.onload=function(index){
					$(this).prop("frame").find(".loading").fadeOut();
				};
				this.src=url;
			})
			.prop("frame",jObj.parent())
	}
	,widthRectify:function widthRectify(){
		var num=Math.floor((window.innerWidth-70)/$(".stream").outerWidth());
		if(num<1)num=1;
		if($(".mainPage").length>0)num=2;
		$(".center").css("width",num*($(".stream").outerWidth()+7)-7)
		$(".stream").remove();
		this.streamPush(num);
	}
	,streamPush:function(num){
		var i
			,jWrapper=$("<div class=''></div>").appendTo($(".framesContainer"));
		this.streams=[];
		for(i=0;i<num;i+=1){
			this.streams.push($("<div class='stream'></div>").appendTo(jWrapper));
		}
		this.streams[num-1].addClass("right");
	}
	,streamClear:function(){
		switch(this.page){
		case"category":
		case"user":
			return;
		}
		this.streamPush(2);
	}
	,getUrl:function(page,parameter){
		switch(page){
		case"user":
			if(this.isAliasDone)return this.root+"user/"+parameter;
			return this.root+"?page="+page+"&uid="+parameter;
		case"category":
			if(this.isAliasDone)return this.root+"object/"+parameter;break;
			return this.root+"?page="+page+"&cate="+parameter;
		case"object":
			if(this.isAliasDone)return this.root+"category/"+parameter;
			return this.root+"?page="+page+"&category="+parameter;
		default:
			return this.root;
		}
	}
	,loadPage:function(i){
		var a
			,streams=this.streams
			,frame=this.data[i]
			,stream
			,i
			;
		if(i%4==3)this.streamClear();
		switch(this.page){
		case"category":
			stream=streams[0];
			for(i=0;i<streams.length;i+=1){
				if(stream.height()<streams[i].height())continue;;
				stream=streams[i];
			}
			break;
		default:
			stream=streams[i%streams.length];
		}
		var jFrame=this.jFrameSeed.clone();
		jFrame
			.appendTo(stream).end()
			.find(".photo img")
				.each(function(){
					var photo;
					this.onload=function(index){
						$(this).prop("frame").find(".loading").fadeOut()
					}
					switch(VooProjectB.page){
					case"user":
					case"category":		photo=frame.photoCategory;break;
					default:			photo=frame.photoCuration;break;
					}
					this.src=photo.url;
					this.width=photo.width;
					this.height=photo.height;
				})
				.prop("frame",jFrame)
				.end()
			.find("a").attr("href","?page=object&oid="+frame.oid).end()
			.find(".firstName").text(frame.user.firstName).end()
			.find(".lastName").text(frame.user.lastName).end()
			.find(".title").text(frame.title).end()
			.find("iframe").attr("src",this.fbReplace(this.getUrl("user",frame.user.uid))).end()
			.find(".userInfo").attr("href",this.getUrl("user",frame.user.uid)).end()
			;
	}
	,fbReplace:function(url){
		var fbUrl="https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2F&width&layout=button_count&action=like&show_faces=false&share=true&height=21"
		return fbUrl.replace("href=http%3A%2F%2Fphotox1.com%2F","href="+encodeURIComponent(url));
	}
	,upadate:function(){
		if(VooProjectB.index>=VooProjectB.data.length)return;
		setTimeout(VooProjectB.upadate,100);
		VooProjectB.loadPage(VooProjectB.index);
		VooProjectB.index+=1;
	}
	,setup:function(){
		this.index=0;
		this.upadate();
	}
	,fbAction:function(){
		$("<iframe></iframe>")
			.attr("src","http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fphotox1.com%2Fuser%2Feric.cc.hsu%2F&width&layout=button_count&action=like&show_faces=false&share=true&height=21")
			.insertBefore(this)
		;
	}
	,objectPage:function(){
		InfinityScroll.onEnter=function(direction){};
		this.jImgLoadBuffer($(".photo img"),$(".photo img").attr("src"));
	}
	,mainPage:function(){
		$(function(){
			VooProjectB.jFrameSeed=$("#frame");
			VooProjectB.widthRectify();
			VooProjectB.loadNext();
		});
		InfinityScroll.onEnter=function(direction){
			switch(direction){
			case"next":
				VooProjectB.loadNext();
				break;
			}
		};
	}
	,loadNext:function(){
		var parameter={
				page:this.indexPage
			}
			,jqxhr
			;
		this.indexPage+=1;
		jqxhr = $.post(this.beApiRoot+"getObjects",parameter, function(data) {
			var i;
			VooProjectB.data=[];
			for(i in data.objests){
				VooProjectB.data.push(data.objests[i]);
			}
			VooProjectB.setup();
			// alert( "success" );
		},"json")
		.done(function() {
			// alert( "second success" );
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			// alert( "finished" );
		});
	}
	,checkLoginStatus:function(){
		// if()
	}
	,subscribeFbAuthResponseChange:function(){
		 FB.Event.subscribe('auth.authResponseChange', function(response) {
			if (response.status === 'connected') {
				// get self data
				FB.api(
					"/me",
					function (response) {
						var url="http://graph.facebook.com/"+VooProjectB.uid+"/picture?type=small";
						if(response && !response.error){
							VooProjectB.uid=response.username
							$("#userPicture").attr("src",url);
							$("#userSetting").text(VooProjectB.uid);
						}
						$(".afterLogin").show(0);
						$(".beforeLogin").hide(0);
						CookieJS.set({
							name:"uid"
							,value:VooProjectB.uid
							,path: '/'
							,expire :14
						});
						CookieJS.set({
							name:"userPicture"
							,value:url
							,path: '/'
							,expire :14
						});
					}
				);
			}else {
				$(".afterLogin").hide(0);
				$(".beforeLogin").show(0);
			}
		});
	}
	,uiAuthStatus:function(){
		if(!CookieJS.has("uid")){
			$(".afterLogin").hide(0);
			$(".beforeLogin").show(0);
			return;
		}
		var uid=CookieJS.get("uid")
			,url=CookieJS.get("userPicture")
			;
		this.uid=uid;
		$("#userPicture").attr("src",url);
		$("#userSetting").text(uid);
		$(".afterLogin").show(0);
		$(".beforeLogin").hide(0);
	}
	,gotoMyPage:function(){
		window.location=this.getUrl("user",this.uid);
		// window.location=this.root+"?page=user&uid="+this.uid;
	}
	,logout:function(){
		FB.logout();
		CookieJS.delete({
			name: 'uid',
			path: '/'
		});
		this.uiAuthStatus();
	}
	,login:function(){
		
		FB.login(function(response) {
			if (response.authResponse) {
				
				// The person logged into your app
			} else {
				// The person cancelled the login dialog
			}
		});
	}
}