<?
session_start(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2];
if ($HTTPREFERER==$_SERVER['SERVER_NAME']) {
	
	$GLOBAL["sitekey"]=1;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";	
	$JsHttpRequest=new JsHttpRequest("utf-8");
	
	// полученные данные ================================================
	
	$R = $_REQUEST;
	$pid = (int)$R["pid"];
	$ip = $_SERVER['REMOTE_ADDR'];
	$link = $R["link"]; $link=preg_replace('/[^a-zA-Z0-9_\.\-]+/i', '', $link); 
		
	// операции =========================================================
	
	if ($R["link"]==$link && $pid!=0) {
		$data=DB("SELECT `likes`,`dislikes` FROM `".$link."_lenta` WHERE (`id`='".$pid."') LIMIT 1"); @mysql_data_seek($data["result"], 0);
		$ar=@mysql_fetch_array($data["result"]); $likes=(int)$ar["likes"]; $dlikes=(int)$ar["dislikes"];
		$data=DB("SELECT `id` FROM `_likes` WHERE (`link`='".$link."' && `pid`='".$pid."' && `ip`='".$ip."' && `data`>'".(time()-1*24*60*60)."')"); $user=(int)$data["total"];
	}
	
	if ($user==0) {
		$result["text"]="<div class='LikesInf'>Нравится статья?</div>";
		$result["text"].="<div class='DlikesNs' title='Не нравится'><a href='javascript:void(0);' onclick=\"likeSavelenta(0, $pid, '".$link."')\"><img src='/template/standart/dislike.png'>".$dlikes."</a></div>";
		$result["text"].="<div class='LikesNs' title='Нравится'><a href='javascript:void(0);' onclick=\"likeSavelenta(1, $pid, '".$link."')\"><img src='/template/standart/like.png'>".$likes."</a></div>";
	} else {
		$result["text"]="<div class='LikesInf'>Спасибо за голос!</div>";
		$result["text"].="<div class='DlikesNs' title='Не нравится'><img src='/template/standart/dislike.png'>".$dlikes."</div>";
		$result["text"].="<div class='LikesNs' title='Нравится'><img src='/template/standart/like.png'>".$likes."</div>";		
	}
}


// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;	
?>