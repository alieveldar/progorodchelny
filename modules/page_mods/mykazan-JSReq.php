<?
session_start(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2];
if ($HTTPREFERER==$_SERVER['HTTP_HOST']) {
	
	$GLOBAL["sitekey"]=1; $error=0;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	
	$data=DB("SELECT `id`,`stat`,`role` FROM `_users` WHERE (`id`='".(int)$_SESSION['userid']."') LIMIT 1");
	if ($data["total"]==1) { @mysql_data_seek($data["result"],0); $GLOBAL["USER"]=@mysql_fetch_array($data["result"]); }
	
	// полученные данные ================================================
	$R = $_REQUEST; $id=preg_replace('/[^a-z0-9_]+/i', '', $R["id"]);
	
	// отправка данных ================================================
	$result["code"]=0; $result["text"]=""; $result["lastid"]=$id; $part=time(); $result["part"]=$part;
	
	$news=DB("SELECT `id`,`picpreview`,`picoriginal`,`username` FROM `_widget_insta` WHERE (`stat`=1 && `id`<'".$id."') ORDER BY `data` DESC LIMIT 30");
	if ($news["total"]>1) { for ($i=0; $i<$news["total"]; $i++): @mysql_data_seek($news["result"], $i); $ar=@mysql_fetch_array($news["result"]); $result["lastid"]=$ar["id"];
		$result["text"].="<div class='image' id='div".$ar["id"]."'>";
		$result["text"].="<a href='".$ar["picoriginal"]."' title=\"Автор: <a href='http://instagram.com/".$ar["userlink"]."' target='_blank'>".$ar["username"]."</a>\" rel='prettyPhoto".$part."[gallery]'><img src='".$ar["picpreview"]."' title='Автор: ".$ar["username"]."' alt='Автор: ".$ar["username"]."' /></a>";
		if ((int)$GLOBAL["USER"]["role"]>1) { $result["text"].="<a href='javascript:void(0);' class='close' onclick=\"HidePic('".$ar["id"]."')\">X</a>"; }
		$result["text"].="</div>";
	endfor; } if ($news["total"]==30) { $result["code"]=1; }
	
} else { $result=array("Code"=>0, "Text"=>"--- Security alert ---", "Class"=>"ErrorDiv", "Comment"=>''); }

// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;
?>