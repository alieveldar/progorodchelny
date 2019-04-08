<?
	$G=$_GET; $GLOBAL["sitekey"]=1; require("../../modules/standart/DataBase.php"); $id=(int)$G["id"]; $uid=(int)$G['uid']; $tab=$G["tab"]; $table=$tab."_lenta";
	if ($tab!="") {
		if ($id!=0) { DB("UPDATE `".$table."` SET `seens`=`seens`+1 WHERE (`id`=".$id.") LIMIT 1"); }
		if ($uid!=0 && $id!=0) { DB("UPDATE `_tracker` SET `stat`='0' WHERE (`uid`='".$uid."' && `link`='".$tab."' && `pid`='".$id."')"); }
	} 
	@header("location: /template/standart/space.gif"); exit();
?>