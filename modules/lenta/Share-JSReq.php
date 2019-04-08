<?php
session_start(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2];
if ($HTTPREFERER==$_SERVER['SERVER_NAME']) {

	$GLOBAL["sitekey"]=1;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");

	// полученные данные ================================================
	$R = $_REQUEST;
	$pid = (int) $R["pid"];
	$network = $R["sn"];
	$ip = $_SERVER['REMOTE_ADDR'];
	$uid = (int) $_SESSION['userid'];

	// операции =========================================================
	if ($pid !== 0) {
	    if( $uid > 0 ) {
            $userfield = 'uid';
            $userid    = $uid;
        } else {
            $userfield = 'ip';
            $userid    = $ip;
        }
        $data=DB("SELECT `id` FROM `_shared` WHERE (`pid`='{$pid}' AND `network` = '{$network}' AND `{$userfield}`='{$userid}' )");
        $is_shared = 0 < (int) $data["total"];
        if (! $is_shared) {
            DB("INSERT INTO `_shared` (`pid`, `network`, `{$userfield}`) VALUES ('{$pid}', '{$network}','{$userid}')");
        }
	}
	$result["result"]= ! ( $pid === 0 || $is_shared);
}
// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;
?>