<?
$table = $link.'_albums';
$table2 = $link.'_photos';
if ($start=="" || (int)$start > 0) { $start="albums"; } $page=(int)$page; $file=$table."-".$start.".".$page.".".$id;

	
if ($start=="albums") {
	$data=DB("SELECT `name`, `sets`, `text` FROM `_pages` WHERE (`link`='".$link."' && `stat`='1') limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
		$sets = explode('|', $ar['sets']);
		list($text, $cap)=GetAlbums();
		if($sets[2]){
			if($_SESSION['userid']) $text .= '<script type="text/javascript">$(".Add").css({"float":"right", "margin-top":-35}).html(\'<a href="/'.$dir[0].'/add" class="SaveButton">Добавить свой альбом</a>\').after(\'<div class="C10"></div>\');</script>';
			else {
				$redirect_uri=rawurlencode("http://".$RealHost."/modules/standart/LoginSocial.php?back=http://".$RealHost."/".$dir[0]."/addalbum");
				$text .= '<script type="text/javascript">$(".Add").css({"float":"right", "margin-top":-35}).html("<a href=\'javascript:void(0)\' onClick=\'UserAuthEnter(\"Авторизация\", \"'.$redirect_uri.'\");\' class=\'SaveButton\'>Добавить свой альбом</a>").after(\'<div class="C10"></div>\');</script>';
			}
		}
		if($GLOBAL['USER']['role'] > 2) {
			$text .= '<script type="text/javascript">jQuery.each($(".Item"), function(i, val) { var fid=$(this).attr("id"); id=fid.split("-"); $(this).append("<div class=\'AdminPanel\'><div id=\'Act"+id[1]+"\' class=\'Act\'><a href=\'javascript:void(0);\' onclick=\'ItemDelete("+id[1]+", \"'.$dir[0].'\", \"DELALBUM\")\' title=\'Удалить альбом\'><img src=\'/template/standart/exit.png\'></a></div><div class=\'Act\'><a href=\'/'.$dir[0].'/edit/"+id[1]+"\' title=\'Настройки альбома\'><img src=\'/template/standart/edit.png\'></a></div></div>"); });</script>';
		} 
	}
}

if ($start=="tags") {
	$data=DB("SELECT `name`, `sets`, `text` FROM `_pages` WHERE (`link`='".$link."' && `stat`='1') limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
		list($text, $cap)=GetTagAlbums(); 
	}
}

if ($start=="view") {
	$data=DB("SELECT `".$table."`.*, `_users`.`nick`, `_users`.`avatar`, (SELECT `".$table2."`.`pic` FROM `".$table2."` WHERE (`".$table2."`.`pid`=`".$table."`.`id`) ORDER BY `".$table2."`.`rate` ASC LIMIT 1) AS `photo1` FROM `".$table."` LEFT JOIN `_users` ON `_users`.`id`=`".$table."`.`uid` WHERE (`".$table."`.`id`=".$dir[2]." && `".$table."`.`stat`='1') limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);		
		list($text, $cap)=GetAlbum($item);
		if($item['photofromusers'] && $_SESSION['userid'] != $item['uid'] && $GLOBAL['USER']['role'] < 3) {
			if($_SESSION['userid']) $text .= '<script type="text/javascript">if(!$(".winner").size()) { $(".Add").html(\'<a href="/'.$dir[0].'/addphoto/'.$item['id'].'" class="SaveButton">Добавить своё фото</a>\').after(\'<div class="C15"></div>\'); } jQuery.each($(".Item"), function(i, val) { var fid=$(this).attr("id"); id=fid.split("-"); if(id[2] == '.$_SESSION['userid'].') $(this).append("<div class=\'AdminPanel\'><div id=\'Act"+id[1]+"\' class=\'Act\'><a href=\'javascript:void(0);\' onclick=\'ItemDelete("+id[1]+", \"'.$dir[0].'\", \"DELPHOTO\")\' title=\'Удалить фотографию\'><img src=\'/template/standart/exit.png\'></a></div><div class=\'Act\'><a href=\'/'.$dir[0].'/editphoto/"+id[1]+"\' title=\'Настройки фотографии\'><img src=\'/template/standart/edit.png\'></a></div></div>"); });</script>';
			else {
				$redirect_uri=rawurlencode("http://".$RealHost."/modules/standart/LoginSocial.php?back=http://".$RealHost."/".$dir[0]."/addphoto/".$item['id']);
				$text .= '<script type="text/javascript">if(!$(".winner").size()) { $(".Add").html("<a href=\'javascript:void(0)\' onClick=\'UserAuthEnter(\"Авторизация\", \"'.$redirect_uri.'\");\' class=\'SaveButton\'>Добавить своё фото</a>").after(\'<div class="C15"></div>\'); }</script>';
			}
		}
		else if($_SESSION['userid'] == $item['uid'] || $GLOBAL['USER']['role'] > 2) {
			$edit='<div id="AdminEditItem"><a href="/admin/'.$dir[0].'/edit/'.$item['id'].'">Редактировать</a></div>';
			$text .= '<script type="text/javascript">if(!$(".winner").size()) { $(".Add").html(\'<a href="/'.$dir[0].'/addphoto/'.$item['id'].'" class="SaveButton">Добавить фото</a>\').after(\'<div class="C15"></div>\'); } jQuery.each($(".Item"), function(i, val) { var fid=$(this).attr("id"); id=fid.split("-"); $(this).append("<div class=\'AdminPanel\'><div id=\'Act"+id[1]+"\' class=\'Act\'><a href=\'javascript:void(0);\' onclick=\'ItemDelete("+id[1]+", \"'.$dir[0].'\", \"DELPHOTO\")\' title=\'Удалить фотографию\'><img src=\'/template/standart/exit.png\'></a></div><div class=\'Act\'><a href=\'/'.$dir[0].'/editphoto/"+id[1]+"\' title=\'Настройки фотографии\'><img src=\'/template/standart/edit.png\'></a></div></div>"); });</script>';
		}
	} 
}

if ($start=="edit") {
	$data=DB("SELECT `".$table."`.*, `_pages`.`sets`, `_users`.`nick`, `_users`.`avatar`, (SELECT `".$table2."`.`pic` FROM `".$table2."` WHERE (`".$table2."`.`pid`=`".$table."`.`id`) ORDER BY `".$table2."`.`rate` ASC LIMIT 1) AS `photo1` FROM `".$table."` LEFT JOIN `_users` ON `_users`.`id`=`".$table."`.`uid` LEFT JOIN `_pages` ON `_pages`.`link`='".$link."' WHERE (`".$table."`.`id`=".$dir[2]." && `".$table."`.`uid`=".$_SESSION['userid'].") limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);		
		if($_SESSION['userid'] == $item['uid']) list($text, $cap)=EditAlbum($item);
	} 
}

if ($start=="add") {
	$data=DB("SELECT `name`, `sets`, `text` FROM `_pages` WHERE (`link`='".$link."' && `stat`='1') limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
		$sets = explode('|', $ar['sets']);		
		if($sets[2] && $_SESSION['userid']) list($text, $cap)=AddAlbum($sets); 
	}
}


if ($start=="photo") {
	$data=DB("SELECT `".$table2."`.*, `".$table."`.`concurs`, `".$table."`.`tags`, `".$table."`.`comments`, `".$table."`.`uid` AS `puid`, `".$table."`.`name` AS `pname`, `users1`.`nick` AS `pnick`, `users1`.`avatar` AS `pavatar`, `users2`.`nick`, `users2`.`avatar`, `_pages`.`sets` FROM `".$table2."` LEFT JOIN `".$table."` ON `".$table."`.`id`=`".$table2."`.`pid` LEFT JOIN `_users` AS `users1` ON `users1`.`id`=`".$table."`.`uid` LEFT JOIN `_users` AS `users2` ON `users2`.`id`=`".$table2."`.`uid` LEFT JOIN `_pages` ON `_pages`.`link`='".$dir[0]."' WHERE (`".$table2."`.`id`=".$dir[3]." && (`".$table2."`.`stat`='1' OR `".$table."`.`uid`=".$_SESSION['userid'].")) limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);
		list($text, $cap)=GetPhoto($item);
		$text.=UsersComments($link, $page, $item["comments"]);
	}
}

if ($start=="addphoto") {
	$data=DB("SELECT `".$table."`.* FROM `".$table."` WHERE (`".$table."`.`id`=".$dir[2]." && (`".$table."`.`stat`='1' OR `".$table."`.`uid`=".$_SESSION['userid'].")) limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);		
		if(($item['photofromusers'] && $_SESSION['userid']) || $_SESSION['userid'] == $item['uid']) list($text, $cap)=AddPhoto($item);
	}
}

if ($start=="editphoto") {
	$data=DB("SELECT `".$table2."`.*, `".$table."`.`email`, `".$table."`.`name` AS `pname`, `".$table."`.`uid` AS `puid`, `".$table."`.`photoapproval` FROM `".$table2."` LEFT JOIN `".$table."` ON `".$table."`.`id`=`".$table2."`.`pid` WHERE (`".$table2."`.`id`=".$dir[2]." && `".$table2."`.`uid`=".$_SESSION['userid'].") limit 1"); 
	if (!$data["total"]) { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); } else {
		@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);		
		if(($item['photofromusers'] && $_SESSION['userid']) || $_SESSION['userid'] == $item['uid']) list($text, $cap)=EditPhoto($item);
	}
}

$Page["Content"]=$edit.$text; $Page["Caption"]=$cap;	
	

function GetAlbums() {
	global $VARS, $GLOBAL, $dir, $ORDERS, $RealHost, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C25;
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; ".$node["name"]."</div>";
		
	$cap=$node["name"]; $text="";
	$onpage=$node["onpage"]; $pg = $dir[1] ? $dir[1] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage;
	$data=DB("SELECT `".$table."`.`id`, `".$table."`.`name`, `".$table."`.`uid`, `".$table."`.`data`, `".$table."`.`concurs`, (SELECT `".$table2."`.`pic` FROM `".$table2."` WHERE (`".$table2."`.`pid`=`".$table."`.`id`) ORDER BY `".$table2."`.`rate` ASC LIMIT 1) AS `photo1`, `".$table2."`.`pic`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `_users`.`id`=`".$table."`.`uid` LEFT JOIN `".$table2."` ON `".$table2."`.`main`=1 AND `".$table2."`.`pid`=`".$table."`.`id` WHERE (`".$table."`.`stat`=1)  GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	
	if($data["total"]) {
		$text .= '<div class="Add"></div>';
		$text .= '<div class="WhiteBlock">';
		$text .= '<div class="Albums">';
		for ($i=0; $i<$data["total"]; $i++) {
			@mysql_data_seek($data["result"], $i); $ar2=@mysql_fetch_array($data["result"]); $d=ToRusData($ar2["data"]);
			$text .= '<ins class="WhiteBlock Item" id="Item-'.$ar2['id'].'">';
			$text .= '<div class="Cover"><a href="/'.$link.'/view/'.$ar2['id'].'"><img src="/userfiles/picnews/'.($ar2['pic'] ? $ar2['pic'] : $ar2['photo1']).'"></a></div>';
			$text .= '<div class="Aname"><a href="/'.$link.'/view/'.$ar2['id'].'">'.$ar2['name'].'</a></div>';
			$text .= '<div class="Adate">'.$d[5].'</div><div class="Aauthor">Автор: <a href="/users/view/'.$ar2['uid'].'">'.$ar2['nick'].'</a></div>';
			if($ar2['concurs']) $text .= '<img src="/template/standart/concurs-tag.png" class="tag">';
			$text .= '</ins>';
		}
		$text .= '</div>';
		$text .= $C.'</div><script type="text/javascript">var i_height = 0; jQuery.each($(".Item"), function(i, val) { if($(this).height() > i_height) i_height = $(this).height(); }); $(".Item").height(i_height);</script>';
		
		$data=DB("SELECT count(id) as `cnt` FROM `".$table."` WHERE (`stat`=1)"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
		$text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $link."/[page]");
		
	} else {
		$text.="<h2>Альбомов не найдено =(</h2>";
	}
	
	return(array($text, $cap));
}


function GetTagAlbums() {
	global $VARS, $GLOBAL, $dir, $ORDERS, $RealHost, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C25;
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; ".$node["name"]."</div>";
		
	$cap=$node["name"]; $text="";
	$onpage=$node["onpage"]; $pg = $dir[3] ? $dir[3] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage;
	$data=DB("SELECT `".$table."`.`id`, `".$table."`.`name`, `".$table."`.`uid`, `".$table."`.`data`, `".$table."`.`concurs`, (SELECT `".$table2."`.`pic` FROM `".$table2."` WHERE (`".$table2."`.`pid`=`".$table."`.`id`) ORDER BY `".$table2."`.`rate` ASC LIMIT 1) AS `photo1`, `".$table2."`.`pic`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `_users`.`id`=`".$table."`.`uid` LEFT JOIN `".$table2."` ON `".$table2."`.`main`=1 AND `".$table2."`.`pid`=`".$table."`.`id` WHERE (`".$table."`.`tags` LIKE '%,".$dir[2].",%' AND `".$table."`.`stat`=1)  GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	
	if($data["total"]) {
		$text .= '<div class="Add" style="float:right:"></div>';
		$text .= '<div class="WhiteBlock">';
		$text .= '<div class="Albums">';
		for ($i=0; $i<$data["total"]; $i++) {
			@mysql_data_seek($data["result"], $i); $ar2=@mysql_fetch_array($data["result"]); $d=ToRusData($ar2["data"]);
			$text .= '<ins class="WhiteBlock Item">';
			$text .= '<div class="Cover"><a href="/'.$link.'/view/'.$ar2['id'].'"><img src="/userfiles/picnews/'.($ar2['pic'] ? $ar2['pic'] : $ar2['photo1']).'"></a></div>';
			$text .= '<div class="Aname"><a href="/'.$link.'/view/'.$ar2['id'].'">'.$ar2['name'].'</a></div>';
			$text .= '<div class="Adate">'.$d[5].'</div><div class="Aauthor">Автор: <a href="/users/view/'.$ar2['uid'].'">'.$ar2['nick'].'</a></div>';
			if($ar2['concurs']) $text .= '<img src="/template/standart/concurs-tag.png" class="tag">';
			$text .= '</ins>';
		}
		$text .= '</div>';
		$text .= $C.'</div><script type="text/javascript">var i_height = 0; jQuery.each($(".Item"), function(i, val) { if($(this).height() > i_height) i_height = $(this).height(); }); $(".Item").height(i_height);</script>';
		
		$data=DB("SELECT count(id) as `cnt` FROM `".$table."` WHERE (`stat`=1)"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
		$text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $link."/[page]");
		
	} else {
		$text.="<h2>Альбомов не найдено =(</h2>";
	}
	
	return(array($text, $cap));
}


function GetAlbum($item) {
	global $VARS, $GLOBAL, $ROOT, $dir, $ORDERS, $RealHost, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C15, $C25;
		
	$cap=$item["name"]; $d=ToRusData($item["data"]); $text="";
	$path='/userfiles/picnews/'.($item['pic'] ? $item['pic'] : $item['photo1']); $lid = CutText($item["text"], 100);
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a>  &raquo; ".$cap."</div>";
	$onpage=$node["onpage"]; $pg = $dir[3] ? $dir[3] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage;
	$data=DB("SELECT * FROM `".$table2."` WHERE (`pid`=".$dir[2]." AND`stat`=1)  GROUP BY 1 ORDER BY `rate` ASC LIMIT $from, $onpage");
		
	if($data["total"]) {
		$text .= '<div class="WhiteBlock">';
		if($item["text"]) $text .= $item["text"].$C15;
		$text .= "<div class='Likes'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($lid))).$C."</div>".$C15;
		$text .= '<div class="Add"></div>';
		$text .= '<div class="Albums">';
		for ($i=0; $i<$data["total"]; $i++) {
			@mysql_data_seek($data["result"], $i); $ar2=@mysql_fetch_array($data["result"]); $d=ToRusData($ar2["data"]);
			$text .= '<ins class="WhiteBlock Item" id="Item-'.$ar2['id'].'-'.$ar2['uid'].'">';
			$text .= '<div class="Cover"><a href="/'.$link.'/photo/view/'.$ar2['id'].'"><img src="/userfiles/picnews/'.$ar2['pic'].'"></a></div>';
			$text .= '<div class="Aname"><a href="/'.$link.'/photo/view/'.$ar2['id'].'">'.$ar2['name'].'</a></div>';
			if($ar2['winner']) $text .= '<div class="winner">Победитель</div>';
			$text .= '</ins>';
		}
		$text .= '</div>';		
		$text .= $C."<div class='Likes'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($lid))).$C."</div>".$C15;
		if ($item["avatar"]=="" || !is_file($ROOT."/".$item["avatar"]) || filesize($ROOT."/".$item["avatar"])<100) { $avatar ="<img src='/userfiles/avatar/no_photo.jpg'>"; } else { $avatar ="<img src='/".$item["avatar"]."'>"; }
		$text .= '<div class="ItemAuth">'.$avatar.'Автор: <a href="/users/view/'.$item['uid'].'">'.$item['nick'].'</a><br><b>'.$d[1].'</b></div>';
		$t=trim($item["tags"], ","); $tags=""; if ($t!="") { $ta=DB("SELECT * FROM `".$dir[0]."_tags` WHERE (`id` IN (".$t.")) LIMIT 3"); for ($i=0; $i<$ta["total"]; $i++): @mysql_data_seek($ta["result"],$i); $ar=@mysql_fetch_array($ta["result"]);
		$tags.="<a href='/$dir[0]/tags/$ar[id]'>$ar[name]</a>, "; endfor; $tags2=trim($tags, ", "); $tags="Тэги:".trim($tags, ", "); }
		$text .= "<div class='ItemTags'>".$tags."</div>";		
		$text .= $C.'</div><script type="text/javascript">var i_height = 0; jQuery.each($(".Item"), function(i, val) { if($(this).height() > i_height) i_height = $(this).height(); }); $(".Item").height(i_height);</script>';
		
		$data=DB("SELECT count(id) as `cnt` FROM `".$table2."` WHERE (`pid`=".$dir[2]." AND `stat`=1)"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
		$text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $dir[0]."/".$dir[1]."/".$dir[2]."/[page]");
		
	} else {
		$text.="<h2>Фотографий не найдено =(</h2>";
	}

	return(array($text, $cap));
}


function GetPhoto($item) {
	global $VARS, $GLOBAL, $ROOT, $dir, $ORDERS, $RealHost, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C15, $C25;		
	
	$orderby=$ORDERS[$node["orderby"]];
	$text=""; $cap=$item["name"] ? $item["name"] : 'Фотография без названия'; $d=ToRusData($item["data"]); $sets = explode('|', $item["sets"]);
	$path='/userfiles/picnews/'.$item['pic']; $lid = CutText($item["text"], 100);
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."/view/".$item["pid"]."'>".$item["pname"]."</a>  &raquo; ".$cap."</div>";
	
	$text .= '<div class="WhiteBlock">';
	$text .= '<div class="ItemPic"><a href="/userfiles/picoriginal/'.$item["pic"].'" rel="prettyPhoto[gallery]"><img src="/userfiles/picoriginal/'.$item["pic"].'"/></a></div>';
	if($item['author']){
		if(!$item['concurs']) $text .= '<div class="PicAuth">Автор фотографии: '.$item['author'].'</div>';
		else $text .= '<div class="PicAuth"><h3 class="CenterText">Автор фотографии: '.$item['author'].'</h3></div>';
	}
	if($item["text"]) $text .= $C10.$item["text"];
	
	$text .= $C15."<div class='Likes'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($lid))).$C."</div>";
	
	if($item["maps"]){
		$text .= $C15.'<div style="display:none;"><span class="maps_'.$item["id"].'">'.$item["maps"].'</span><span class="maps_default">'.$VARS["maps"].'</span><span class="pic_'.$item["id"].'">'.$item["pic"].'</span></div>';
		$text .= '<script type="text/javascript" src="http://maps.api.2gis.ru/1.0"></script><div id="Map" style="width:'.$sets[0].'px; height:'.$sets[1].'px;"></div><script type="text/javascript">initMap('.$item["id"].');</script>';
	}
	
	$text .= $C15.'<h3><a href="http://'.$RealHost.'/'.$dir[0].'/view/'.$item["pid"].'">'.$item["pname"].'</a></h3>'.$C10;
	if ($item["pavatar"]=="" || !is_file($ROOT."/".$item["pavatar"]) || filesize($ROOT."/".$item["pavatar"])<100) { $avatar ="<img src='/userfiles/avatar/no_photo.jpg'>"; } else { $avatar ="<img src='/".$item["pavatar"]."'>"; }
	if(!$item['concurs']) $text .= '<div class="ItemAuth">'.$avatar.'Автор альбома: <a href="/users/view/'.$item['puid'].'">'.$item['pnick'].'</a></div>';
	$t=trim($item["tags"], ","); $tags=""; if ($t!="") { $ta=DB("SELECT * FROM `".$dir[0]."_tags` WHERE (`id` IN (".$t.")) LIMIT 3"); for ($i=0; $i<$ta["total"]; $i++): @mysql_data_seek($ta["result"],$i); $ar=@mysql_fetch_array($ta["result"]);
	$tags .="<a href='/$dir[0]/tags/$ar[id]'>$ar[name]</a>, "; endfor; $tags2=trim($tags, ", "); $tags="Тэги:".trim($tags, ", "); }
	$text .= "<div class='ItemTags'>".$tags."</div>";
				
	
	$data=DB("SELECT `id`, `name`, `pic`, `maps` FROM `".$table2."` WHERE (`pid`=".$item["pid"]." AND`stat`=1)  GROUP BY 1 ORDER BY `rate` ASC");
	if($data["total"] > 1){
		$text .= $C15.'<div class="CenterText"><h4>Все фотографии альбома<h4></div><div class="WhiteBlock Carusel"><div class="Container"><div class="Slider">';
		for ($i=0; $i<$data["total"]; $i++) {
			@mysql_data_seek($data["result"], $i); $ar2=@mysql_fetch_array($data["result"]);
			$text .= '<a href="/'.$dir[0].'/photo/view/'.$ar2['id'].'" title="'.$ar2['name'].'"'.($ar2['id'] == $item['id'] ? ' class="current"' : '').'><img src="/userfiles/picnews/'.$ar2['pic'].'"></a>';
		}
		$text .= '</div></div>';
		if($data["total"] > 9) $text .= '<a href="javascript:void(0);" class="btn l-btn"><img src="/template/standart/tleft1.png" onclick="$(\'.Carusel .Slider a:last\').prependTo($(\'.Carusel .Slider\'));"></a><a href="javascript:void(0);" class="btn r-btn"><img src="/template/standart/tright1.png" onclick="$(\'.Carusel .Slider a:first\').appendTo($(\'.Carusel .Slider\'));"></a>';
	}
	$text .= '</div>'.$C10.'<div class="Add"><a href="/'.$dir[0].'/view/'.$item['pid'].'" class="SaveButton">Вернуться в альбом</a></div></div>';
	return(array($text, $cap));
}

function AddAlbum($sets) {
	global $VARS, $GLOBAL, $ROOT, $dir, $ORDERS, $RealHost, $RealPage, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C25;
		
	if (isset($_SESSION['Data']["SaveButton"])) {
		$P = $_SESSION['Data'];
		if(trim($P['name']) == '') $msg = '<div class="ErrorDiv">Ошибка! Поля не заполнены или заполнены неверно</div>';
		else{			
			$name = str_replace("'", "\'", $P['name']);
			$text = str_replace("'", "\'", $P['text']);
			$stat = $sets[3] ? 0 : 1;
			$q="INSERT INTO `$table` (`data`, `name`, `text`, `uid`, `stat`, `comments`) VALUES ('".time()."', '".$name."', '".$text."', ".$_SESSION['userid'].", ".$stat.", ".$P['comments'].")";
			DB($q); $last=DBL(); DB("UPDATE `$table` SET `rate`='".$last."' WHERE  (id='".$last."')"); unset($P);
			if($stat) $_SESSION['msg'] = '<div class="SuccessDiv">Альбом добавлен</div>';
			else $_SESSION['msg'] = '<div class="SuccessDiv">Альбом добавлен, но будет отображен после одобрения администратором</div>';
			header('Location: /'.$dir[0].'/addphoto/'.$last);
		}
		SD();		
	}
	$cap = 'Добавление альбома';
	if ($P["comments"]==0) { $c1="selected"; } elseif ($P["comments"]==1) { $c2="selected"; } else { $c3="selected"; }

	$text = '<div class="RoundText">';
	$text.=$msg.'<form action="/modules/SubmitForm.php?bp='.$RealPage.'" enctype="multipart/form-data" method="post" onsubmit="return JsVerify();"><table>';			
	$text.="<tr><td class='VarText'>Название альбома</td><td class='LongInput'><input name='name' type='text' value='".$P['name']."'></td></tr>";
	$text.="<tr><td class='VarText' style='vertical-align:top; padding-top:10px;'>Описание</td><td class='LongInput'><textarea name='text' style='outline:none;'>".$P['text']."</textarea></td></tr>";
	$text.='<tr><td class="VarName">Комментарии</td><td class="LongInput"><select name="comments"><option value="0">Разрешить</option><option value="2">Запретить</option></select></td><tr>';
	$text.='</table>'.$C10.'<div class="CenterText"><input type="submit" name="SaveButton" class="SaveButton" value="Отправить"></div>';
	$text.='</form></div>';
	
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; ".$cap."</div>";

	return(array($text, $cap));
}

function EditAlbum($item) {
	global $VARS, $GLOBAL, $ROOT, $dir, $ORDERS, $RealHost, $RealPage, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C25;
		
	if (isset($_SESSION['Data']["SaveButton"])) {
		$P = $_SESSION['Data'];
		if(trim($P['name']) == '') $msg = '<div class="ErrorDiv">Ошибка! Поля не заполнены или заполнены неверно</div>';
		else{			
			$name = str_replace("'", "\'", $P['name']);
			$text = str_replace("'", "\'", $P['text']);
			$sets = explode('|', $item['sets']);
			$stat = $sets[3] ? 0 : 1;
			$q="UPDATE `$table` SET `name`='".$name."', `text`='".$text."', `stat`=".$stat." WHERE (`id`=".$item['id'].")";
			DB($q);
			if($stat) $msg = '<div class="SuccessDiv">Альбом отредактирован</div>';
			else $msg = '<div class="SuccessDiv">Альбом отредактирован, но будет отображен после одобрения администратором</div>';
		}
		SD();		
	}
	if(!$P) $P = $item;
	$cap = 'Редактирование альбома';	
	if ($P["comments"]==0) { $c1="selected"; } elseif ($P["comments"]==1) { $c2="selected"; } else { $c3="selected"; }

	$text = '<div class="RoundText">';
	$text.=$msg.'<form action="/modules/SubmitForm.php?bp='.$RealPage.'" enctype="multipart/form-data" method="post" onsubmit="return JsVerify();"><table>';			
	$text.="<tr><td class='VarText'>Название альбома</td><td class='LongInput'><input name='name' type='text' value='".$P['name']."'></td></tr>";
	$text.="<tr><td class='VarText' style='vertical-align:top; padding-top:10px;'>Описание</td><td class='LongInput'><textarea name='text' style='outline:none;'>".$P['text']."</textarea></td></tr>";
	$text.='<tr><td class="VarName">Комментарии</td><td class="LongInput"><select name="comments"><option value="0" '.$c1.'>Разрешить</option><option value="2" '.$c3.'>Запретить</option></select></td><tr>';
	$text.='</table>'.$C10.'<div class="CenterText"><input type="submit" name="SaveButton" class="SaveButton" value="Отправить"></div>';
	$text.='</form></div>';
	
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; ".$cap."</div>";

	return(array($text, $cap));
}


function AddPhoto($item) {
	global $VARS, $GLOBAL, $ROOT, $dir, $ORDERS, $RealHost, $RealPage, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C25;
		
	if (isset($_SESSION['Data']["SaveButton"])) {
		$P = $_SESSION['Data'];
		if(trim($P['name']) == '' || !$P['pic']) $msg = '<div class="ErrorDiv">Ошибка! Поля не заполнены или заполнены неверно</div>';
		else{			
			$pic = $P['pic'];
			if($pic) {
				@require($ROOT."/modules/standart/ImageResizeCrop.php");
				foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) {			
					if (!is_dir($ROOT."/userfiles/".$path)) { mkdir($ROOT."/userfiles/".$path, 0777); }
					list($w,$h)=getimagesize($ROOT."/userfiles/temp/".$pic);
					list($sw, $sh)=explode("-", $size); if ($sw!=0 && $sh!=0) { $sk=$sw/$sh; }
					
					if($path=="picpreview") resize($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic, $sw, $sh);
					else if($path=="picoriginal"){
						if($w > $sw) resize($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic, $sw, $sh);
						else copy($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic);
					}
					else{					
						$k = min($w / $sw, $h / $sh);
						$x = round(($w - $sw * $k) / 2); $y = round(($h - $sh * $k) / 2);
						crop($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic, array($x, $y, round($sw * $k), round($sh * $k)));
						resize($ROOT."/userfiles/".$path."/".$pic, $ROOT."/userfiles/".$path."/".$pic, $sw, $sh);		
					}
				}				
			}
			$name = str_replace("'", "\'", $P['name']);
			$text = str_replace("'", "\'", $P['text']);
			$author = str_replace("'", "\'", $P['author']);
			$stat = $item['photoapproval'] ? 0 : 1;
			$q="INSERT INTO `$table2` (`data`, `name`, `text`, `author`, `pic`, `pid`, `uid`, `maps`, `stat`) VALUES ('".time()."', '".$name."', '".$text."', '".$author."', '".$pic."', ".$item['id'].", ".$_SESSION['userid'].", '".$P['maps']."', ".$stat.")";
			DB($q); $last=DBL(); DB("UPDATE `$table2` SET `rate`='".$last."' WHERE  (id='".$last."')"); unset($P);
			if($stat) $msg = '<div class="SuccessDiv">Фотография добавлена</div>';
			else $msg = '<div class="SuccessDiv">Фотография добавлена, но будет отображена после одобрения администратором</div>';
			if($item['email']){
				$subject = 'Новая фотография в вашем фотоальбоме';
				$body = "Здравствуйте. В вашем фотоальбоме <a href='http://".$RealHost."/".$dir[0]."/view/".$item["id"]."'>".$item["name"]."</a> добавлена новая фотография. Посмотреть её можно по <a href='http://".$RealHost."/".$dir[0]."/photo/view/".$last."'>ссылке</a>.";
				if(!$stat) $body .= "В данное время фотография не опубликована и ожидает вашего одобрения";
				MailSend($item['email'], $subject, $body, $VARS["sitemail"]);
			}		
		}
		SD();		
	}
	if ($P['main']) { $chk="checked"; }
	$cap = 'Добавление фотографии';
	if(isset($_SESSION['msg'])) { $msg = $_SESSION['msg']; unset($_SESSION['msg']); }

	$text = '<div class="RoundText">';
	$text.='<link media="all" href="/modules/standart/multiupload/client/uploader2.css" type="text/css" rel="stylesheet"><script type="text/javascript" src="/modules/standart/multiupload/client/uploader.js"></script><script type="text/javascript" src="http://maps.api.2gis.ru/1.0"></script>';
	$text.=$msg.'<form action="/modules/SubmitForm.php?bp='.$RealPage.'" enctype="multipart/form-data" method="post" onsubmit="return JsVerify();"><table>';			
	$text.="<tr><td class='VarText'>Название</td><td class='LongInput'><input name='name' type='text' value='".$P['name']."'></td></tr>";
	$text.="<tr><td class='VarText' style='vertical-align:top; padding-top:10px;'>Описание</td><td class='LongInput'><textarea name='text' style='outline:none;'>".$P['text']."</textarea></td></tr>";
	$text.='<tr><td class="VarText" style="vertical-align:top; padding-top:10px;">Фотография</td><td class="LongInput"><div class="uploaderCon" style="'.($P['pic'] ? 'display:none;' : '').'"><div class="uploader"></div><div class="Info">Вы можете загрузить фотографию в формате jpg, gif и png</div></div><div class="uploaderFiles">';
	$text.="<tr><td class='VarText'>Автор фотографии</td><td class='LongInput'><input name='author' type='text' value='".$P['author']."'></td></tr>";
	if($_SESSION['userid'] == $item['uid']) $text .="<tr><td></td><td><input name='main' type='checkbox' ".$chk."> Обложка альбома</td></tr>";
	if($P['pic']) $text.='<span class="imgCon"><img src="/userfiles/temp/'.$P['pic'].'" class="img" /><img src="/template/standart/exit.png" class="remove" onclick="imgRemove($(this))" /><input type="hidden" name="pic" value="'.$P['pic'].'" /></span>';
	$text.='</div></td></tr>';
	$text.="<tr><td class='VarText'>Координаты на карте</td><td><div id='Map' style='height:400px;'></div><div style='display:none;'><span class='maps_default'>".$VARS["maps"]."</span></div><script type='text/javascript'>initMap(0);</script></td><tr>";	
	$text.='</table>'.$C10.'<div class="CenterText"><input name="maps" type="hidden" class="maps_0"><input type="submit" name="SaveButton" class="SaveButton" value="Отправить"></div>';
	$text.='</form></div>';
	
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."/view/".$item["id"]."'>".$item["name"]."</a>  &raquo; ".$cap."</div>";

	return(array($text, $cap));
}


function EditPhoto($item) {
	global $VARS, $GLOBAL, $ROOT, $dir, $ORDERS, $RealHost, $RealPage, $Page, $node, $link, $UserSetsSite, $table, $table2, $C, $C20, $C10, $C25;
		
	if (isset($_SESSION['Data']["SaveButton"])) {
		$P = $_SESSION['Data'];
		if(trim($P['name']) == '' || !$P['pic']) $msg = '<div class="ErrorDiv">Ошибка! Поля не заполнены или заполнены неверно</div>';
		else{			
			$pic = $item['pic'];
			if($pic != $P["pic"] || $P["pic"] == ''){				
				if($pic) { foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) { @unlink($ROOT."/userfiles/".$path."/".$pic); }}
				$pic = $P["pic"];	
				if($pic) {
					@require($ROOT."/modules/standart/ImageResizeCrop.php");
					foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) {			
						if (!is_dir($ROOT."/userfiles/".$path)) { mkdir($ROOT."/userfiles/".$path, 0777); }
						list($w,$h)=getimagesize($ROOT."/userfiles/temp/".$pic);
						list($sw, $sh)=explode("-", $size); if ($sw!=0 && $sh!=0) { $sk=$sw/$sh; }
						
						if($path=="picpreview") resize($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic, $sw, $sh);
						else if($path=="picoriginal"){
							if($w > $sw) resize($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic, $sw, $sh);
							else copy($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic);
						}
						else{					
							$k = min($w / $sw, $h / $sh);
							$x = round(($w - $sw * $k) / 2); $y = round(($h - $sh * $k) / 2);
							crop($ROOT."/userfiles/temp/".$pic, $ROOT."/userfiles/".$path."/".$pic, array($x, $y, round($sw * $k), round($sh * $k)));
							resize($ROOT."/userfiles/".$path."/".$pic, $ROOT."/userfiles/".$path."/".$pic, $sw, $sh);		
						}
					}
				}
			}
			$name = str_replace("'", "\'", $P['name']);
			$text = str_replace("'", "\'", $P['text']);
			$author = str_replace("'", "\'", $P['author']);
			$stat = $item['photoapproval'] ? 0 : 1;
			$q="UPDATE `$table2` SET `name`='".$name."', `text`='".$text."', `author`='".$author."', `pic`='".$pic."', `maps`='".$P['maps']."', `stat`='".$stat."' WHERE (`id`=".$item['id'].")";
			DB($q);
			if($stat) $msg = '<div class="SuccessDiv">Фотография отредактирована</div>';
			else $msg = '<div class="SuccessDiv">Фотография отредактирована, но будет отображена после одобрения администратором</div>';
			if($item['email']){
				$subject = 'Редактирование фотографии в вашем фотоальбоме';
				$body = "Здравствуйте. В вашем фотоальбоме <a href='http://".$RealHost."/".$dir[0]."/view/".$item["pid"]."'>".$item["pname"]."</a> редактировалась фотография. Посмотреть её можно по <a href='http://".$RealHost."/".$dir[0]."/photo/view/".$item['id']."'>ссылке</a>.";
				if(!$stat) $body .= "В данное время фотография не опубликована и ожидает вашего одобрения";
				MailSend($item['email'], $subject, $body, $VARS["sitemail"]);
			}		
		}
		SD();		
	}
	if(!$P) $P = $item;
	if ($P['main']) { $chk="checked"; }
	$cap = 'Редактирование фотографии';

	$text = '<div class="RoundText">';
	$text.='<link media="all" href="/modules/standart/multiupload/client/uploader2.css" type="text/css" rel="stylesheet"><script type="text/javascript" src="/modules/standart/multiupload/client/uploader.js"></script><script type="text/javascript" src="http://maps.api.2gis.ru/1.0"></script>';
	$text.=$msg.'<form action="/modules/SubmitForm.php?bp='.$RealPage.'" enctype="multipart/form-data" method="post" onsubmit="return JsVerify();"><table>';			
	$text.="<tr><td class='VarText'>Название</td><td class='LongInput'><input name='name' type='text' value='".$P['name']."'></td></tr>";
	$text.="<tr><td class='VarText' style='vertical-align:top; padding-top:10px;'>Описание</td><td class='LongInput'><textarea name='text' style='outline:none;'>".$P['text']."</textarea></td></tr>";
	$text.="<tr><td class='VarText'>Автор фотографии</td><td class='LongInput'><input name='author' type='text' value='".$P['author']."'></td></tr>";
	$text.='<tr><td class="VarText" style="vertical-align:top; padding-top:10px;">Фотография</td><td class="LongInput"><div class="uploaderCon" style="'.($P['pic'] ? 'display:none;' : '').'"><div class="uploader"></div><div class="Info">Вы можете загрузить фотографию в формате jpg, gif и png</div></div><div class="uploaderFiles">';
	if($P['pic']) $text.='<span class="imgCon"><img src="/userfiles/picpreview/'.$P['pic'].'" class="img" /><img src="/template/standart/exit.png" class="remove" onclick="imgRemove($(this))" /><input type="hidden" name="pic" value="'.$P['pic'].'" /></span>';
	$text.='</div></td></tr>';
	if($_SESSION['userid'] == $item['puid']) $text .="<tr><td></td><td><input name='main' type='checkbox' ".$chk."> Обложка альбома</td></tr>";
	$text.="<tr><td class='VarText'>Координаты на карте</td><td><div id='Map' style='height:400px;'></div><div style='display:none;'><span class='maps_".$item['id']."'>".$P['maps']."</span><span class='maps_default'>".$VARS["maps"]."</span></div><script type='text/javascript'>initMap(".$item['id'].");</script></td><tr>";	
	$text.='</table>'.$C10.'<div class="CenterText"><input name="maps" type="hidden" class="maps_'.$item['id'].'" value="'.$P['maps'].'"><input type="submit" name="SaveButton" class="SaveButton" value="Отправить"></div>';
	$text.='</form></div>';
	
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."/view/".$item["pid"]."'>".$item["pname"]."</a>  &raquo; ".$cap."</div>";

	return(array($text, $cap));
}
?>