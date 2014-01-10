function imageOnload(){
	$("img")
		.each(function(){
			this.onload=function(index){
				$(this).fadeIn()
				// alert("bah");
			}
			// this.src=this.src;
		})
		.fadeOut(0)
	
}
imageOnload();