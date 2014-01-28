$("#categoryButton").click(function(){
	$("#panel")
		.toggle(0)
		.click(function(){
			$(this).hide(0)
		})
});
$("#setting , #userSetting").click(function(){
	$("#settingSlidedown")
		.toggle(0)
		.click(function(){
			$(this).hide(0)
		})
});
$("#alertEditSuccess, #alertEditFail").fadeOut(0);