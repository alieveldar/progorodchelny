<?
session_start();
if ($_SESSION['userrole']>1) {
	$GLOBAL["sitekey"]=1; @require "../../../modules/standart/DataBase.php"; @require "../../../modules/standart/JsRequest.php"; $JsHttpRequest=new JsHttpRequest("utf-8");

	// полученные данные ================================================
	$R=$_REQUEST;
	$lid=$R["lid"];
	$text=$R["text"];
	$id=(int)$R["id"];
	$link=$R["link"];
	
	// операции =========================================================
	DB("UPDATE `".$link."_lenta` SET `lid`='".str_replace("'", "\'", $lid)."', `text`='".str_replace("'", "\'", $text)."' WHERE (`id`=".$id.") LIMIT 1");
	
	$result["content"]="ok"; $GLOBALS['_RESULT']	= $result;
}
?>