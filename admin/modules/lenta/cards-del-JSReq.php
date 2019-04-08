<?
session_start();
if ($_SESSION['userrole']>1) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$R=$_REQUEST; $items=$R["id"];
		
	// операции =========================================================
	
	DB("DELETE FROM `_widget_cards` WHERE (`id` IN (".$items."))"); $result["content"]="ok"; $GLOBALS['_RESULT']=$result;
}
?>