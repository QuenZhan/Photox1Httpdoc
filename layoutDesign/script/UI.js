$("#categoryButton").click(function(){
	$("#panel")
		.slideToggle()
		.click(function(){
			$(this).slideUp()
		})
});
$("#setting").click(function(){
	$("#settingSlidedown")
		.slideToggle()
		.click(function(){
			$(this).slideUp()
		})
});