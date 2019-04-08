$(document).ready(function() { maxlen(); setInterval(maxlen, 300); });

function maxlen() {
	var ml=$("#dname").attr("maxlength");
	console.log(ml);
	var ch=$("#dname").val();
	var chl=ch.length;
	var rem=ml-chl;
	$("#dcount").val(rem);
}
