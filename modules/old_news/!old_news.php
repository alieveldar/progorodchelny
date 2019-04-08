<?
$table2="_widget_pics";
$table3="_widget_votes";
$table4="_widget_video";
$table5="_widget_voting";

$Page["SocialGroups"]="";

$tables=array("auto_lenta", "business_lenta", "news_lenta" , "sport_lenta");
foreach ($tables as $tabl) {
	$data=DB("SELECT * FROM `$tabl` WHERE (`id`='$nid_old') limit 1");
	if ($data["total"]==1) {
		@mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); 
		$table=$tabl;
		$page="view";
		$id=$nid_old;
		$start="view";
		$dir[2]=$id;
		$tmp=explode("_", $table);
		$dir[0]=$tmp[0];
		$link=$dir[0];
		$pid=$dir[2];
	}
}

$MENU["maintags"]="";
$MENU["dopmenu"]="";
$MENU["ourcities"]="";



 
$file=$table."-".$start.".old.".$page.".".$id;

#############################################################################################################################################
### Вывод новости
if ($start=="view") {
	$where=$GLOBAL["USER"]["role"]==0?"&& `stat`=1":"";
	$data=DB("SELECT `comments` FROM `".$table."` WHERE (`id`='".(int)$dir[2]."' ".$where.") LIMIT 1");
	if ($data["total"]==1) {
		@mysql_data_seek($data["result"], 0); $new=@mysql_fetch_array($data["result"]); 
		if (RetCache($file)=="true") { list($text, $cap)=GetCache($file); } else { list($text, $cap)=GetLentaId(); SetCache($file, $text, $cap); }
		$text.=$C15."<div style='float:right;'><a href='/$link/view/$pid'>Перейти в раздел новости</a></div>";
		//if ($Page404==0) { UserTracker($link, $page); $text.=UsersComments($link, $page, $new["comments"]); } 
		$edit="<div id='AdminEditItem'><a href='".$GLOBAL["mdomain"]."/admin/?cat=".$link."_edit&id=".(int)$dir[2]."'>Редактировать</a></div>";
		if ($GLOBAL["USER"]["role"]>2) { $text=$C10.$edit.$C.$text; } $Page["Content"]=$text; $Page["Title"]=$cap; $Page["Caption"]="";
	} else {
		$cap="Материал не найден";
		$text=@file_get_contents($ROOT."/template/404.html");
		$Page["Content"]=$text; $Page["Caption"]=$cap;
	}
}

#############################################################################################################################################

function GetLentaId() {
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $table, $table2, $table3, $table4, $table5, $C, $C5, $C10, $C15; 
	
	### Основной запрос
	$data=DB("SELECT `".$table."`.*, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`, `_users`.`avatar`, `$table5`.`id` as `vvid` FROM `".$table."`
	LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id`	
	LEFT JOIN `$table5` ON `$table5`.`pid`=`$table`.`id` AND `$table5`.`link`='".$dir[0]."' AND `$table5`.`vid`='0' AND `$table5`.`stat`=1	
	LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat`	
	WHERE (`".$table."`.`id`='".(int)$dir[2]."') GROUP BY 1 LIMIT 1");
	@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);
	
	### Формирование данных
	$cap=$item["name"]; 
	//$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."/cat/".$item["cat"]."'>".$item["ncat"]."</a> &raquo; ".$cap."</div>";
	$Page["Crumbs"]="";
	
	### Фотография
	if ($item["pic"]!="") { if (strpos($item["pic"], "old")!=0) { /*Старый*/ $old=1; $path='/'.$item["pic"]; $pic="<div class='LentaPictureOld' title='".$cap."'><img src='".$item["pic"]."' title='".$cap."' /></div>"; } else {
	/*Новый*/ $old=0; $path='/userfiles/picitem/'.$item["pic"]; $pic.="<div class='LentaPicture-picitem' title='$cap'><img src='/userfiles/picitem/".$item["pic"]."' title='$cap' /><div class='LentaPicture-Title'><h1>$cap</h1></div><div class='LentaPicture-Cens'><h1>".$item["cens"]."</h1></div></div>"; }}
	### Основной текст
	$maintext=CutEmptyTags($item["text"]);
	### Заключительный текст
	if ($item["endtext"]!="") { $maintext.=$C5."<div class='WhiteBlock EndText'>".$item["endtext"]."</div>".$C; }
	
	### Претекст текст
	$lid=$item["lid"]; if ($lid!="") { if ($old==1) { $lid="<div class='ItemLidOld'>".$lid."</div>".$C10; } else { $lid="<div class='ItemLid'>".$lid."</div>".$C10; }}
	### Фото-отчет
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='report') order by `rate` ASC"); $report=""; if ($p["total"]>0) { $report.="<table class='ItemOrder'>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); $report.="<tr><td width=1% valign='top'><a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picpreview/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a><td><td width=99% valign='top'><h4>".$ar["name"]."</h4>".$ar["text"]."<td></tr>"; endfor; $report.="</table>".$C10; }
	### Фото-альбом
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='album') order by `rate` ASC"); if ($p["total"]>0) { $album="<h2>Фотоальбом:</h2><div class='ItemAlbum'>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); $album.="<a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a>"; endfor; $album.="</div>".$C; }
	### Голосование
	if ((int)$item["vvid"]!=0) { $voting=$C5."<div id='ItemVotingDiv'></div><script>GetItemVoting(".(int)$item["vvid"].");</script>".$C5; }
	### Видео
	$p=DB("SELECT * FROM `".$table4."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."') LIMIT 1"); if ($p["total"]>0) { $video=""; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
	if ($ar["text"]!="") { if ($ar["name"]!="") { $video.="<h2>".$ar["name"]."</h2>"; } $vid=GetNormalVideo($ar["text"]); $video.=$vid.$C10; } endfor; }
	### Автор и дата
	$d=ToRusData($item["data"]);
	if ($item["uid"]!=0 && $item["nick"]!="") { $auth="<img src='/".$item["avatar"]."' />Автор: <a href='http://".$VARS["mdomain"]."/users/view/".$item["uid"]."/'>".$item["nick"]."</a><br><b>".$d[1]."</b>";
	} else { $auth="<img src='/userfiles/avatar/no_photo.jpg' />Автор: <a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a><br><b>".$d[1]."</b>"; }
	### Тэги и ошибки
	$t=trim($item["tags"], ","); $tags=""; if ($t!="") { $ta=DB("SELECT * FROM `_tags` WHERE (`id` IN (".$t.")) LIMIT 3"); for ($i=0; $i<$ta["total"]; $i++): @mysql_data_seek($ta["result"],$i); $ar=@mysql_fetch_array($ta["result"]);
	$tags.="<a href='/tags/view/$ar[id]'>$ar[name]</a>, "; endfor; $tags="Тэги:".trim($tags, ", "); } $tags.="<br><i>Если Вы нашли ошибку в тексте - выделите ее и нажмите Ctrl+Enter</i>";
	### Лайки 
	//$likes="<div class='Likes'>".Likes($cap, "", "http://".$RealHost.$path, strip_tags($lid)).$C."</div>".$C10;
	### Текст вывода
	if ($item["adv"]!="") { $adv=$C20.$C5."<div class='AdvBlock'>".$item["adv"]."</div>".$C; } else { $adv=""; }
	
	if ($item["pay"]!="") { $mixblock.=$C10."<div class='WhiteBlock PayBlock'>".$item["pay"]."</div>"; }
	if ($old==1) { $text="<h1>".$cap."</h1><div class='WhiteBlock LentaItem'>".$lid.$pic."<div class='ItemText'>".$maintext."</div>".$report.$video.$album.$adv.$voting.$C10.$likes.$mixblock.$C10."Опубликовано: ".$d[1]."</div>";
	} else { $text="<div class='WhiteBlock LentaItem' style='padding:9px;'>".$pic.$C15.$lid."<div class='ItemText'>".$maintext."</div>".$report.$video.$album.$adv.$voting.$C10.$likes.$mixblock.$C10."Опубликовано: ".$d[1]."</div>"; }
	
	return(array($text, $cap));
}
?>