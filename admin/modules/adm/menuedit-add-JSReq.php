<?
session_start();
if ($_SESSION['userrole']>2) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	//@require "../../../modules/standart/Settings.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$R=$_REQUEST;
	
	// операции =========================================================
	$q="INSERT INTO `_menuitem` (`nid`,`pid`,`stat`,`name`,`link`,`class`) VALUES ('".(int)$R["nid"]."','".(int)$R["pid"]."','".(int)$R["chk"]."','".$R["name"]."','".$R["link"]."','".$R["css"]."')";
	DB($q); $rate=DBL(); DB("UPDATE `_menuitem` SET `rate`='".$rate."' WHERE (id='".$rate."')");

	// отправляемые данные ==============================================

	$result["test"]=$q;
	$GLOBALS['_RESULT']	= $result;
}
?>