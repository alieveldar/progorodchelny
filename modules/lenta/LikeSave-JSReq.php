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
	$qid = (int)$R["qid"];
	$ip = $_SERVER['REMOTE_ADDR'];
	$link = $R["link"]; $link=preg_replace('/[^a-zA-Z0-9_\.\-]+/i', '', $link); 
	
	// операции =========================================================
	if ($R["link"]==$link && $pid!=0) {
		$data=DB("SELECT `id` FROM `_likes` WHERE (`link`='".$link."' && `pid`='".$pid."' && `ip`='".$ip."' && `data`>'".(time()-1*24*60*60)."')"); $user=(int)$data["total"];
		
		$result["log"]="SELECT `id` FROM `_likes` WHERE (`link`='".$link."' && `pid`='".$pid."' && `ip`='".$ip."' && `data`>'".(time()-1*24*60*60)."') [".$user."]";
		
		if ($user==0) {
			# update likes and dislikes
			if ($ip==trim("94.180.249.218")) { $ip="My.".rand(100000,999999999); }
			DB("INSERT INTO `_likes` (`data`,`ip`,`link`,`pid`,`like`) VALUES ('".time()."','".$ip."','".$link."','".$pid."','".$qid."')");
			if ($qid==0) { DB("UPDATE `".$link."_lenta` set `dislikes`=`dislikes`+1 WHERE (`id`='".$pid."') LIMIT 1"); }
			if ($qid==1) { DB("UPDATE `".$link."_lenta` set `likes`=`likes`+1 WHERE (`id`='".$pid."') LIMIT 1"); }
		}
		# get likes and dislikes
		$data=DB("SELECT `likes`,`dislikes` FROM `".$link."_lenta` WHERE (`id`='".$pid."') LIMIT 1"); @mysql_data_seek($data["result"], 0);
		$ar=@mysql_fetch_array($data["result"]); $likes=(int)$ar["likes"]; $dlikes=(int)$ar["dislikes"];
	}
	$result["text"]="<div class='LikesInf'>Спасибо за голос!</div>";
	$result["text"].="<div class='DlikesNs' title='Не нравится'><img src='/template/standart/dislike.png'>".$dlikes."</div>";
	$result["text"].="<div class='LikesNs' title='Нравится'><img src='/template/standart/like.png'>".$likes."</div>";
}
// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;	
?>