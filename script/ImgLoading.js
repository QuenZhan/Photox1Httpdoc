$("photo img").each(function(){
		this.onload=function(index){
			$(this).prop("frame").find(".loading").fadeOut();
		};
		var d=new Date();
		this.src=this.src+"?"+d.getTime();
	})
	.prop("frame",$(this).parent())
}