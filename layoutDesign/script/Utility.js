var U={
	pad:function pad(num, size) {
		var s = "000000000" + num;
		return s.substr(s.length-size);
	}
	,choose:function choose(arr){
	return arr[Math.floor(arr.length*Math.random())];
}
}