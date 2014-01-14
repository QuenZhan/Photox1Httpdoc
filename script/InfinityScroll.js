var InfinityScroll={first:false
	,onEnter:function(direction){
		alert("onEnter "+direction);
	}
	,padding:100
	,sur:""
	,onScroll:function(){
		var scrollPosition
			,pageHeight = $(window).height()
			,contentHeight= $(document).height()
			;
		if(navigator.appName == "Microsoft Internet Explorer")scrollPosition = document.documentElement.scrollTop;  
		else scrollPosition = window.pageYOffset;
		switch(this.sur){
		case"enter":
			// alert("bah");
			// this.sur="";
			// if(scrollPosition>this.padding
				// &&contentHeight-scrollPosition-pageHeight>this.padding)this.sur="";
			break;
		default:
			if(scrollPosition<this.padding){
				this.sur="enter";
				setTimeout(function(){InfinityScroll.sur="";},500);
				this.onEnter("prev");
			}
			if(contentHeight-scrollPosition-pageHeight<this.padding){
				this.sur="enter";
				setTimeout( function(){InfinityScroll.sur="";},500);
				this.onEnter("next");
			}
		}
	}
}
$(window).scroll(function() {
	InfinityScroll.onScroll();
});