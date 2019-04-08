<?
session_start();
if ($_SESSION['userrole']>2) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	@require "../../../modules/standart/Settings.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$R=$_REQUEST;
	$item=(int)$R["id"];
	$items=$R["id"];
	$pg=$R["pg"];
	$table="_comments";
	$table2="_users";
	$table3="_commentf";
	$limit=50;
	$from=($pg - 1) * $limit;
	
		
	// операции =========================================================
	if ($R["act"]=="DEL") {
		$data = DB("SELECT `link`,`pid` FROM `".$table."` WHERE (`id` IN (".$items."))"); for ($i=0; $i<$data["total"]; $i++){ @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); DB("UPDATE `".$ar["link"]."_lenta` set `comcount`=`comcount`-1 WHERE (`id`='".$ar["pid"]."') LIMIT 1"); }
		$data = DB("SELECT `pic` FROM `".$table3."` WHERE (`pid` IN (".$items."))"); for ($i=0; $i<$data["total"]; $i++){ @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) { @unlink($ROOT."/userfiles/".$path."/".$ar['pic']); }}
		DB("DELETE FROM `".$table."` WHERE (`id` IN (".$items."))"); DB("DELETE FROM `".$table3."` WHERE (`pid` IN (".$items."))");
	}

	$result["content"]="ok"; $GLOBALS['_RESULT']	= $result;
}
?>