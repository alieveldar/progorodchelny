<?
session_start(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2];
if ($HTTPREFERER==$_SERVER['HTTP_HOST']) {
	
	$GLOBAL["sitekey"]=1; $error=0;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	
	// полученные данные ================================================
	$R = $_REQUEST; $id=preg_replace('/[^a-z0-9_]+/i', '', $R["id"]);
	
	// отправка данных ================================================
	$result["code"]=0; $result["text"]=""; $result["lastid"]=$id; $part=time(); $result["part"]=$part;
	
	$news=DB("SELECT `id`,`picpreview`,`picoriginal`,`username` FROM `_widget_insta` WHERE (`stat`=1 && `id`<'".$id."') ORDER BY `data` DESC LIMIT 9");
	if ($news["total"]>1) { for ($i=0; $i<$news["total"]; $i++): @mysql_data_seek($news["result"], $i); $ar=@mysql_fetch_array($news["result"]); $result["lastid"]=$ar["id"];
		$result["text"].="<a href='".$ar["picoriginal"]."' title='Автор: ".$ar["username"]."' rel='prettyPhoto".$part."[gallery]'><img src='".$ar["picpreview"]."' alt='Автор: ".$ar["username"]."' title='Автор: ".$ar["username"]."' /></a>";
	endfor; } if ($news["total"]==9) { $result["code"]=1; }
	
} else { $result=array("Code"=>0, "Text"=>"--- Security alert ---", "Class"=>"ErrorDiv", "Comment"=>''); }

// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;
?>