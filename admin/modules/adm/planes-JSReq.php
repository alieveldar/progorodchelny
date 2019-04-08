<?
session_start();
if ($_SESSION['userrole']>1) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$R=$_REQUEST; $table="_planes"; $item=(int)$R["id"];
		
	// операции =========================================================
	
	DB("DELETE FROM `".$table."` WHERE (`id`=".$item.")");
	$result["content"]="ok"; $GLOBALS['_RESULT']	= $result;
}
?>