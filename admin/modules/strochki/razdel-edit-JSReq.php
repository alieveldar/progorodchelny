<?
session_start();
if ($_SESSION['userrole']>1) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
	@mysql_data_seek($data["result"], 0); $pg=@mysql_fetch_array($data["result"]);
	$R=$_REQUEST; $table=$pg["link"]."_razdels";
	
	// операции =========================================================
	
	$q="UPDATE `".$table."` SET `name`='".$R["name"]."', `price`='".$R["price"]."' WHERE (`id`='".(int)$R["id"]."') LIMIT 1"; DB($q);

	// отправляемые данные ==============================================

	$result["test"]=$q; $GLOBALS['_RESULT']	= $result;
}
?>