<?
####### ВЫВОД СОДЕРЖАНИЯ НОВОСТИ ######################################################################################################################################

function GetInstaContent() {
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $link, $start, $page, $C, $C5, $C10, $C15, $C20, $C25, $C30, $BANS; $adminstat=" && `stat`=1";
	
	### Основной запрос
	$data=DB("SELECT * FROM `_widget_insta` WHERE (`id`='".$start."'".$adminstat.") LIMIT 1"); if ($data["total"]!=1) { return(array('','','','',0)); }
	@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]); $cap=$item["picname"]; if ($cap=="" ) { $cap=$node["name"]; }
	$ds="Фотографии из Инстаграм: ".$cap; $kw=$cap." , инстаграм, фото, два мира, #dvamira"; $noerror=1; $d=ToRusData($item["data"]);
	
	### Определение Лайки
	$Likes='<div class="Likes">'.Likes($cap,'',$item["picpreview"], $ds).$C.'</div>';
	
	### Автор и дата
	$AuthData="Опубликовано: <a href='http://instagram.com/".$item["userlink"]."' rel='nofollow' target='_blank'>".$item["username"]."</a>, ".$d[1];

	### Пред и след фотки
	$data=DB("SELECT `id` FROM `_widget_insta` WHERE (`data`>'".$item["data"]."'".$adminstat.") ORDER BY `data` ASC LIMIT 1"); $next="";
	if ($data["total"]==1){ @mysql_data_seek($data["result"],0); $ar=@mysql_fetch_array($data["result"]); $next="<a href='/".$link."/".$ar["id"]."'><img src='/modules/instagram/next.png' class='NavPics' title='Следующая'></a>"; }
	
	$data=DB("SELECT `id` FROM `_widget_insta` WHERE (`data`<'".$item["data"]."'".$adminstat.") ORDER BY `data` DESC LIMIT 1"); $prev="";
	if ($data["total"]==1){ @mysql_data_seek($data["result"],0); $ar=@mysql_fetch_array($data["result"]); $prev="<a href='/".$link."/".$ar["id"]."'><img src='/modules/instagram/prev.png' class='NavPics' title='Предыдущая'></a>"; }

	### Фотография
	if ($item["picoriginal"]!="") {

		$imglikes="<noindex><div class='CatTypelight'><a href='".$item["piclink"]."' rel='nofollow' target='_blank'>♥ ".$item["likes"]."</a></div></noindex>";
		$text="<table cellpadding='0' cellspacing='0' class='InstaPic'><tr><td width='40px'>".$prev."</td><td width='640px'><div class='PicAuthBox' title='$cap'><img src='".$item["picoriginal"]."' />
		".$imglikes."</div></td><td width='40px'>".$next."</td></tr><tr><td colspan='3'><noindex>".$AuthData.$C20.$Likes."</noindex></td></tr></table>";
		
		$text.=$BANS["L730x90x1"];
		$text.=$C.$node["text"];

	} else { return(array('','','','',0)); }

	return(array($text, $cap, $kw, $ds, $noerror));
}
?>