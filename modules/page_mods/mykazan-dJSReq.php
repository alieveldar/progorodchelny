<?
session_start(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2];
if ($HTTPREFERER==$_SERVER['HTTP_HOST']) {
	
	$GLOBAL["sitekey"]=1; $error=0;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	
	$data=DB("SELECT `id`,`nick`,`avatar`,`stat`,`role` FROM `_users` WHERE (`id`='".(int)$_SESSION['userid']."') LIMIT 1");
	if ($data["total"]==1) { @mysql_data_seek($data["result"],0); $GLOBAL["USER"]=@mysql_fetch_array($data["result"]); }
	
	// полученные данные ================================================
	$R = $_REQUEST; $id=preg_replace('/[^a-z0-9_]+/i', '', $R["id"]);
	
	// отправка данных ================================================
	$result["text"]="";	if ((int)$GLOBAL["USER"]["role"]>1) { DB("UPDATE `_widget_insta` set `stat`=0 WHERE (`id`='".$id."')"); }
	
	
} else { $result=array("Code"=>0, "Text"=>"--- Security alert ---", "Class"=>"ErrorDiv", "Comment"=>''); }

// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;
?>