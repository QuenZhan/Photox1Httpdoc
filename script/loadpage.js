var streams=[]
	,jFrameSeed=$("#frame")
	,jActionSeed=$("#action")
	,widthFrame=$("#frame").innerWidth()
	,database=[
//-------------------------------------------------------------
//以下為json格式，可以任意增減
//Action目前沒功能
		{
			"imgUrl":"content/001.jpg"
			,"caption":"海鮮大蝦"
			,"actions":["購買","分享"]
		}
		,{
			"imgUrl":"content/002.jpg"
			,"caption":"烤雞"
			,"actions":["購買","分享","訂閱"]
		}
		,{
			"imgUrl":"content/003.jpg"
			,"caption":"牛排"
			,"actions":["購買","分享","訂閱","這真讚"]
		}
		,{
			"imgUrl":"content/004.jpg"
			,"caption":"義大利"
			,"actions":["購買","分享","訂閱","這真讚","收藏"]
		}
		,{
			"imgUrl":"content/005.jpg"
			,"caption":"大螃蟹"
			,"actions":["購買","分享","訂閱","這真讚","收藏","轉寄"]
		}
		,{
			"imgUrl":"content/006.jpg"
			,"caption":"酒"
			,"actions":["購買","分享","訂閱","這真讚","收藏","轉寄"]
		}
		,{
			"imgUrl":"content/007.jpg"
			,"caption":"酒"
			,"actions":["購買","分享","訂閱","這真讚","收藏","轉寄"]
		}
		,{
			"imgUrl":"content/008.jpg"
			,"caption":"酒"
			,"actions":["購買","分享","訂閱","這真讚","收藏","轉寄"]
		}
                ,{
			"imgUrl":"content/009.jpg"
			,"caption":"酒"
			,"actions":["購買","分享","訂閱","這真讚","收藏","轉寄"]
		}
                ,{
			"imgUrl":"content/010.jpg"
			,"caption":"酒"
			,"actions":["購買","分享","訂閱","這真讚","收藏","轉寄"]
		}
//以上
//--------------------------------------------------------------
	];
function choose(arr){
	return arr[Math.floor(arr.length*Math.random())];
}
function bindActions(jFrame){
	var jActionWrapper=jFrame.find(".actions");
	jActionWrapper.empty();
	for(var key in frame.actions){
		var jAction=jActionSeed.clone();
		jAction
			.text(frame.actions[key])
			.appendTo(jActionWrapper)
	}
	var number=widthFrame/frame.actions.length;
	// alert(jActionWrapper.width())
	// jActionWrapper.find(".action").outerWidth(number-4);
}
function loadNext(){
	// jFrameSeed.clone().appendTo(streams[0]);
	for(var i=0;i<database.length;i++){
		// var frame=choose(database);
		var frame=database[i];
		// var frame=database[0];
		var stream=streams[0];
		for(var key in streams){
			if(stream.height()>streams[key].height())stream=streams[key];
		}
		var jFrame=jFrameSeed.clone();
		// bindActions(jFrame)
		var img = new Image();
		img.src = frame.imgUrl;
		img.onload = function() {
			var isV=this.width<this.height;
			if(isV)$(this).addClass("isV");
			else $(this).addClass("isH");
		}
		jFrame
			.appendTo(stream).end()
			// .find("img")
				// .each(function(){
					// this.onload=function(){
						// $(this).fadeIn()
					// }
				// })
				// .fadeOut(0)
				// .end()
			.find(".photo img")
				.each(function(){
					this.onload=function(index){
						$(this).prop("frame").find(".loading").fadeOut()
					}
					this.src=frame.imgUrl
				})
				// .attr("src",frame.imgUrl)
				// .addClass("hidden")
				.prop("frame",jFrame)
				// .fadeOut(0)
				// .css("visible","hidden")
				.end()
			// .find(".photo").fadeOut(0).end()
			.find(".title").text(frame.caption).end()
			.find(".score").text(choose(["★★★★☆"
				,"★★★☆☆"
				,"★★☆☆☆"
				,"★☆☆☆☆"
				,"☆☆☆☆☆"
				])).end()
			// .fadeOut(0)
			
			;
	}
	// setTimeout(imgRectify,500);
}
$(function(){
	start();
	// console.log($(".frame").length);
});
// $("#topBar").load("loadheaderBar.html",widthRectify);
$("#logout").click(logout);
$("#loginWithFb").click(loginWithFb);
$("#getLoginStatus").click(getLoginStatus);
$("#likes").click(likes);
$("#getPhotos").click(getPhotos);
$("#fbPostEvent").click(fbPostEvent);
$("#categoryButton").click(categoryButton);
function randomScore(){
	$(".score").text(choose(["★★★★☆"
		,"★★★☆☆"
		,"★★☆☆☆"
		,"★☆☆☆☆"
		,"☆☆☆☆☆"
		]))
}
function actionJustify(){
	$(".actions").each(function(){
		var number=$(this).width()/$(this).find(".action").size();
		$(this).find(".action").outerWidth(number-4);
	})
}