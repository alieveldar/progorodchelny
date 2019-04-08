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

$(document).ready(function(){
    var $datepickstart = $("#datepick-lstart");
    var ht1 = $datepickstart.val();
    $datepickstart.datepicker();
    $datepickstart.datepicker("option", "dateFormat", "dd.mm.yy");
    $datepickstart.val(ht1);

    var $datepickend = $("#datepick-lend");
    var ht2 = $datepickend.val();
    $datepickend.datepicker();
    $datepickend.datepicker("option", "dateFormat", "dd.mm.yy");
    $datepickend.val(ht2);
});