function HidePic(vkid) { JsHttpRequest.query('/modules/page_mods/citilink-dJSReq.php',{'vkid':vkid}, function(result,errors){ if(result){ $("#div"+vkid).fadeOut(); }},true); }
function ShowMoreCiti(data) { 
	$("#ShowMoreVK").html("Идет загрузка данных..."); 
	JsHttpRequest.query('/modules/page_mods/citilink-JSReq.php',{'data':data},function(result,errors){ if(result){
		lastdata=result["lastdata"];
		if (result["code"]==1) { $("#ShowMoreVK").html("<a href='javascript:void(0);' onclick='ShowMoreCiti("+lastdata+")'>Показать ещё записи</a>");
		} else { $("#ShowMoreVK").html(''); } $("#boxes").append(result["text"]); 
	}},true);
}