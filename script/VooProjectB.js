var VooProjectB={first:false
	,psid:""
	,streamLayout:""
	,cid:""
	,isAliasDone:false
	,root:""
	,beApiRoot:""
	,index:0
	,indexPage:0
	,isEnd:false
	,page:""
	,uid:"uid"
	,targetUid:"uid"
	,streams:[]
	,jFrameSeed:false
	,data:[]
	,be:function(apiName,parameter,reply){
		$.post(this.beApiRoot+apiName,parameter,reply,"json");
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
		if(this.streamLayout=="curation")num=2;
		$(".dynamicWidth").css("width",num*($(".stream").outerWidth()+7)-7);
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
		switch(this.streamLayout){
		case"category":
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
		switch(this.streamLayout){
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
					switch(VooProjectB.streamLayout){
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
			.find(".userPhoto").attr("src",frame.user.photoUrl).end()
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
		if(VooProjectB.isEnd)return;
		var parameter={first:false
				// ,psid:this.psid
				,page:this.indexPage
				,categories:[this.cid]
				,uid:this.uid
				,targetUid:this.targetUid
			}
			,jqxhr
			;
		this.indexPage+=1;
		$.post(this.beApiRoot+"getObjects"
			,parameter
			,function(data){
				var i;
				VooProjectB.data=[];
				for(i in data.objects){
					VooProjectB.data.push(data.objects[i]);
				}
				VooProjectB.setup();
				if(data.isEnd)VooProjectB.isEnd=true;
			},"json")
		.fail(function() {
			$(".framesContainer").append("<p>Oops ! 無法讀取照片</p>");
		});
		// this.be("getObjects",parameter,);
	}
	,subscribeFbAuthResponseChange:function(){
		return;
		 FB.Event.subscribe('auth.authResponseChange', function(response){
			if (response.status === 'connected') {
				// alert("bbbb");
				FB.api(
					"/me",
					function (response) {
						var url="http://graph.facebook.com/"+VooProjectB.uid+"/picture?type=small"
							,user;
						if(response && !response.error){
							VooProjectB.uid=response.username
							url="http://graph.facebook.com/"+VooProjectB.uid+"/picture?type=small"
						}
						user={
							uid:VooProjectB.uid
							,firstName:response.name
							,lastName:response.last_name
							,signUpFrom:"fb"
							,gender:response.gender
							,email:response.email
							,emailSync:response.email
				
						};
						// VooProjectB.be("setUser",{"psid":"","user":user,"photoFile":null},function(data){});
					}
				);
			}else {
			}
		});
	}
	,gotoMyPage:function(){
		window.location=this.getUrl("user",this.uid);
	}
	,logout:function(){
		$.post("beProxy.php",{option:"logout"},function(){
			location.reload();
		});
	}
	,login:function(){
		FB.login(function(response) {
			FB.api(
				"/me",
				function (response){
					var url="http://graph.facebook.com/"+VooProjectB.uid+"/picture?type=small"
						,user;
					if(response&&!response.error){
						VooProjectB.uid=response.username
						url="http://graph.facebook.com/"+VooProjectB.uid+"/picture?type=small"
					}
					user={
						uid:VooProjectB.uid
						,firstName:response.name
						,lastName:response.last_name
						,signUpFrom:"fb"
						,gender:0//response.gender
						,email:response.email
						,emailSync:response.email
					};
					$.post("beProxy.php",{option:"login",user:user},function(){
						// location.reload();
					});
					$("#form").ajaxSubmit({
						url:VooProjectB.beApiRoot+"setUser"
						// ,data:{user:JSON.stringify(user)}
						,data:({"user":user})
						,dataType:'json'
						,success:function(data){
							VooProjectB.psid=data.psid;
						}
					});
				}
			);
		},{scope:'email'});
	}
	,editUser:function(){
		var jForm=$(".editForm"),data;
		data={psid:this.psid
			,user:{
				uid:this.uid
				,website:jForm.find("#website").val()
			}
		}
		$.post(this.beApiRoot+"setUser",data)
		.done(function(){
			$("#alertEditSuccess").fadeIn();
			// alert( "second success" );
		})
		.fail(function() {
			$("#alertEditFail").fadeIn();
		});
	}
	,uploadFile:function(){
		$('#upload').ajaxSubmit({
			url:"beProxy.php?option=upload"
			,data:{option:"upload"}
			,success:function(data){
				// alert(data);
				var d = new Date();
				VooProjectB.jImgLoadBuffer($("#upload .photo img"),data+"?"+d.getTime());
			}
			,dataType:'text'
		});
		// $("#upload .photo img").attr(src,"");
	}
	,newObject:function(){
		var jForm=$('#upload');
		jForm.ajaxSubmit({
			url:this.beApiRoot+"setObject"
			,success:function(){
				alert("success");
				VooProjectB.gotoMyPage();
			}
			,error:function(){
				alert("error");
			}
			,dataType:'json'
			,data:{
				psid:this.psid
				,uid:this.uid
				,object:{
					categoryID:jForm.find(".category").val()
					,title:jForm.find(".title").val()
					,description:jForm.find(".description").val()
					,hyperlink:jForm.find(".hyperlink").val()
				}
			}
		});
	}
}