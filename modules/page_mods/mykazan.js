$(function(){ var tr = new Date(2015, 6, 15); $('#countdown').countdown({ timestamp: tr }); });
function ShowMore() { $("#More").html("<img src='/template/instakazan/load.gif'>"); JsHttpRequest.query('/modules/page_mods/mykazan-JSReq.php',{'id':lastid},function(result,errors){ if(result){ lastid=result["lastid"]; if (result["code"]==1) { $("#More").html("<a href='javascript:void(0)' onclick='ShowMore()'>Показать больше фотографий</a>"); } else { $("#More").html(""); } $("#works").append(result["text"]); $("a[rel^='prettyPhoto"+result["part"]+"']").prettyPhoto({showTitle:true}); }},true); }
function HidePic(id) { JsHttpRequest.query('/modules/page_mods/mykazan-dJSReq.php',{'id':id}, function(result,errors){ if(result){ $("#div"+id).fadeOut(); }},true); }

function ShowRules() { $("#RulesA").slideUp(300); $("#RulesT").slideDown(300); }
