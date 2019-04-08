$(document).ready(function() {
	
	


	maxlen(); setInterval(maxlen, 300);
});

function maxlen() {
	var ml=$("#dname").attr("maxlength");
	var ch=$("#dname").val();
	var chl=ch.length;
	var rem=ml-chl;
	$("#dcount").val(rem);
}

function sinchronize(obj) {
	alert("here!");
}