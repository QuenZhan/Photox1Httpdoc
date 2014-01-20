<script src="script/cookie.min.js"></script>
<script src="script/jquery-1.10.1.min.js"></script>
<script src="script/Utility.js"></script>
<script src="script/InfinityScroll.js"></script>
<script src="script/VooProjectB.js"></script>
<script>
	VooProjectB.beApiRoot="dummyBackend.php?api=";
	VooProjectB.be("setUser",{user:{abc:"abc"}},function(data){
		console.log(data.abc);
	});
</script>