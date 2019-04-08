<?
session_start();
if ($_SESSION['userrole']>1) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	@require "../../../modules/standart/JsRequest.php";
	@require "../../../modules/standart/Settings.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");

	// полученные данные ================================================
	$R=$_REQUEST;
	$id=(int)$R["id"];
	$link=$R["link"];
	
	// операции =========================================================
	DB("UPDATE `".$link."_lenta` SET `data`='".time()."', `adata`='".time()."' WHERE (`id`=".$id.") LIMIT 1");
	$d=ToRusData(time()); $result["newtime"]="Обновлено: ".$d[4];
	
	$result["content"]="ok"; $GLOBALS['_RESULT']	= $result;
}
?>