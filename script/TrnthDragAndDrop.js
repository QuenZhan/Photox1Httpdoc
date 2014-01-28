var TrnthDragAndDrop={first:false
	,onDropFile:function(event){
		// var dom=event.dataTransfer.getData('image/jpg');
		alert(event.dataTransfer.files.length);
		// console.log(files);
	}
	,onDragOver:function(event){
		event.preventDefault();
	}
};