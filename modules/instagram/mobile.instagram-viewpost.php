<?
####### ВЫВОД СОДЕРЖАНИЯ НОВОСТИ ######################################################################################################################################

function GetInstaContent() {
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $link, $start, $page, $C, $C5, $C10, $C15, $C20, $C25, $C30, $BANS; $adminstat=" && `stat`=1";
	
	### Основной запрос
	$data=DB("SELECT * FROM `_widget_insta` WHERE (`id`='".$start."'".$adminstat.") LIMIT 1"); if ($data["total"]!=1) { return(array('','','','',0)); }
	@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]); $cap=NoHashTag($item["picname"]); if ($cap=="" ) { $cap=$node["name"]; }
	$ds="Фотографии из Инстаграм: ".$cap; $kw=$cap." , инстаграм, фото, два мира, #dvamira"; $noerror=1; $d=ToRusData($item["data"]);
	
	### Определение Лайки
	$Likes='<div class="Likes">'.MLikes($cap,'',$item["picpreview"], $ds).$C.'</div>';
	
	### Автор и дата
	$AuthData="Опубликовано: <a href='http://instagram.com/".$item["userlink"]."' rel='nofollow' target='_blank'>".$item["username"]."</a>".$C5.$d[1];

	### Пред и след фотки
	$data=DB("SELECT `id` FROM `_widget_insta` WHERE (`data`>'".$item["data"]."'".$adminstat.") ORDER BY `data` ASC LIMIT 1"); $next="";
	if ($data["total"]==1){ @mysql_data_seek($data["result"],0); $ar=@mysql_fetch_array($data["result"]); $next="<a href='/".$link."/".$ar["id"]."' class='OutBg'>Следующая</a>"; }
	
	$data=DB("SELECT `id` FROM `_widget_insta` WHERE (`data`<'".$item["data"]."'".$adminstat.") ORDER BY `data` DESC LIMIT 1"); $prev="";
	if ($data["total"]==1){ @mysql_data_seek($data["result"],0); $ar=@mysql_fetch_array($data["result"]); $prev="<a href='/".$link."/".$ar["id"]."' class='InBg'>Предыдущая</a>"; }

	### Фотография
	if ($item["picoriginal"]!="") {
		$imglikes="<noindex><div class='CatTypelight'><a href='".$item["piclink"]."' rel='nofollow' target='_blank'>♥ ".$item["likes"]."</a></div></noindex>";
		$text.="<div class='CaptionL'>".$prev."</div><div class='CaptionL' style='float:right;'>".$next."</div>".$C15;
		$text.="<div class='PicAuthBox' title='$cap'><img src='".$item["picoriginal"]."' />".$imglikes."</div>";
		$text.="<noindex><div style='text-align:center;'>".$AuthData.$C15."</div></noindex>";
		$text.="<div class='CaptionL'>".$prev."</div><div class='CaptionL' style='float:right;'>".$next."</div>".$C15;
		$text.=$Likes;
		$text.=$BANS["L730x90x1"];
	} else { return(array('','','','',0)); }

	return(array($text, $cap, $kw, $ds, $noerror));
}
?>