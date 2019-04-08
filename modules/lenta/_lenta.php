<?
$table=$link."_lenta";
$table2="_widget_pics";
$table3="_widget_votes";
$table4="_widget_video";
$table5="_widget_voting";
$table6="_widget_contacts";
$table7="_widget_eventmap";
$table8="_widget_cards";


if ($start=="") { $start="list"; $dir[1]="list"; }
$file=$table."-".$start.".".$page.".".$id;

if ($link=="news" && (int)$page==106355) { @header("location: /itogi2015"); exit(); }
if ($link=="news" && (int)$page==107723) { @header("location: /undeground"); exit(); }
if ($link=="news" && (int)$page==108261) { @header("location: /admiral"); exit(); }
if ($link=="sport" && (int)$page==74308) { @header("location: /akbars"); exit(); }
if ($link=="news" && (int)$page==108963) { @header("location: /12april"); exit(); }
if ($link=="news" && (int)$page==109308) { @header("location: /tukay"); exit(); }
if ($link=="news" && (int)$page==109399) { @header("location: /9may_afisha"); exit(); }
if ($link=="news" && (int)$page==102) {header("Location: http://progorodsamara.ru"); exit; }


#############################################################################################################################################
### Вывод списка новостей общий
if ($start=="list") {
	#list($text, $cap)=GetLentaList();
	if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaList(); SetCache($file, $text, "");} 
	$Page["Content"]=$text; $Page["Caption"]=$node["name"];	
}

#############################################################################################################################################
### Вывод списка новостей в категории
if ($start=="cat") { if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaCat(); SetCache($file, $text, $cap); } $Page["Content"]=$text; $Page["Caption"]=$cap; }

#############################################################################################################################################
### Вывод новости
if ($start=="view") {
	$where=$GLOBAL["USER"]["role"]==0?"&& `stat`=1":"";
	$data=DB("SELECT `id`,`comments`, `promo`,`alttext`,`name` FROM `".$table."` WHERE (`id`='".(int)$dir[2]."' ".$where.") LIMIT 1");
	if ($data["total"]==1) {
		@mysql_data_seek($data["result"], 0); $new=@mysql_fetch_array($data["result"]); 
		
if ($new["alttext"]=="") { 
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- 
		if (RetCache($file)=="true") { list($text, $cap)=GetCache($file); } else { list($text, $cap)=GetLentaId(); SetCache($file, $text, $cap); }
		$text.="<img src='/modules/lenta/stat.php?ok=1&tab=".$dir[0]."&id=".$new["id"]."&uid=".$_SESSION["userid"]."' style='width:1px; height:1px;' />";
		if ($new["promo"]!=1) { $text.=$C20."<div class='banner' id='Banner-6-2'></div>"; }	
		$text.=UsersComments($link, $page, $new["comments"]);
		if ($new["promo"]!=1) { $text.=$C20."<div class='banner' id='Banner-6-3'></div>"; }
		$text.=razdelBrandsBattle().$C;
		if ($new["promo"]!=1) { $text.="<div class='banner' id='Banner-6-4'></div>"; }
		
		$text.='<!-- Яндекс.Директ --> <div id="yandex5ad"></div> <script type="text/javascript"> (function(w, d, n, s, t) { w[n] = w[n] || []; w[n].push(function() { Ya.Direct.insertInto(125901, "yandex5ad", { ad_format: "direct", font_size: 0.8, type: "horizontal", border_type: "block", limit: 1, title_font_size: 1, links_underline: true, site_bg_color: "FFFFFF", border_color: "CCCCFF", title_color: "0066CC", url_color: "333333", text_color: "000000", hover_color: "0066FF", no_sitelinks: true }); }); t = d.getElementsByTagName("script")[0]; s = d.createElement("script"); s.src = "//an.yandex.ru/system/context.js"; s.type = "text/javascript"; s.async = true; t.parentNode.insertBefore(s, t); })(window, document, "yandex_context_callbacks"); </script>'.$C20;

			if ($GLOBAL["USER"]["role"]>1) { $text=$C10."<div id='AdminEditItem'><a href='".$GLOBAL["mdomain"]."/admin/?cat=".$link."_edit&id=".(int)$dir[2]."'>Редактировать</a></div>".$C15.$text; }
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- 
} else { $text=$new["alttext"]; $cap=$new["name"]; $node["design"]="akbars"; }
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- 
		$Page["Content"]=$text; $Page["Title"]=$cap; $Page["Caption"]=$cap;
	} else { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); $Page["Content"]=$text; $Page["Caption"]=$cap; }
}

### ЛЕНТА НОВОСТЕЙ ОСТАЛЬНЫЕ ########################################################################################################################

function GetLentaList() {
	global $VARS, $GLOBAL, $dir, $link, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C25;
	$onpage=$node["onpage"]; $pg = $dir[2] ? $dir[2] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage; $onblock=4; /* Новостей в каждом блоке */
	$data=DB("SELECT `".$table."`.id, `".$table."`.cat, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`stat`=1)  GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	for ($i=0; $i<$data["total"]; $i++) {
		if (($i+1)%4==0 && $link!="ls"){ $text.="<div class='NewsLentaList' id='NewsLentaList-".$bubr[$bubrj]["id"]."'><a href='".$bubr[$bubrj]["link"]."' target='_blank'><img src='".$bubr[$bubrj]["pic"]."' /></a><h2><a href='".$bubr[$bubrj]["link"]."' target='_blank'>".$bubr[$bubrj]["name"]."</a></h2>".$C."</div>".$C25; $bubrj++; }			
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { /*Старый вид картинок*/ $pic="<img src='".$ar["pic"]."' title='".$ar["name"]."' />"; } else { /*Новый вид картинок*/ $pic="<img src='/userfiles/pictavto/".$ar["pic"]."' title='".$ar["name"]."' />"; }}
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<div class='CommentBox'><a href='/".$dir[0]."/view/".$ar["id"]."#comments'>".$ar["comcount"]."</a></div>"; } else { $coms=""; }
		$text.="<div class='NewsLentaList' id='NewsLentaList-".$ar["id"]."'><a href='/".$dir[0]."/view/".$ar["id"]."'>".$pic."</a><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2>".$C."
		<div class='Info'><div class='Other'>".Replace_Data_Days($d[4]).",  <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>,  Автор: ".$auth."</div>".$coms."</div></div>";
		if($data["total"]>($i+1)){ if (($i+1)%$onblock==0) { $text.=$C25."<div class='banner2' style='margin-left:10px;' id='Banner-6-".(floor($i/$onblock)+1)."'></div>".$C; } else { $text.=$C25; }}
	}
	$data=DB("SELECT count(id) as `cnt` FROM `".$table."`"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $dir[0]."/".$dir[1]."/[page]");
	return(array($text, ""));
}

##### КАТЕГОРИЯ НОВОСТЕЙ ########################################################################################################################################

function GetLentaCat() {
	global $VARS, $GLOBAL, $dir, $link, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C25;
	/* баброимпорт */ $xml = simplexml_load_file("http://bubr.ru/prokazan_news.xml"); $bubr=array(); $bubrj=0; if (!empty($xml)) { $count_str = count($xml->channel->item); $i = 0; while ($i <= ($count_str-1)): 
	$name=$xml->channel->item[$i]->title; $name2=$xml->channel->item[$i]->ttwo; $linkd=$xml->channel->item[$i]->link; $pic=$xml->channel->item[$i]->pic; $bubr[$i]["name"]=$name.". ".$name2; $bubr[$i]["link"]=$linkd.""; $bubr[$i]["pic"]=$pic.""; $i++; endwhile; }	
	$onpage=$node["onpage"]; $pg = $dir[3] ? $dir[3] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage; $onblock=4; /* Новостей в каждом блоке */
	$data=DB("SELECT `".$table."`.name, `".$table."`.uid, `".$table."`.cat, `".$table."`.pic, `".$table."`.data, `".$table."`.id, `".$table."`.comcount, `".$table."`.comments, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."`	LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='".(int)$dir[2]."' && `".$table."`.`stat`=1) GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	for ($i=0; $i<$data["total"]; $i++) {
		if (($i+1)%4==0 && $link!="ls"){ $text.="<div class='NewsLentaList' id='NewsLentaList-".$bubr[$bubrj]["id"]."'><a href='".$bubr[$bubrj]["link"]."' target='_blank'><img src='".$bubr[$bubrj]["pic"]."' /></a><h2><a href='".$bubr[$bubrj]["link"]."' target='_blank'>".$bubr[$bubrj]["name"]."</a></h2>".$C."</div>".$C25; $bubrj++; }
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic=""; $ncat=$ar["ncat"];
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { /*Старый вид картинок*/ $pic="<img src='".$ar["pic"]."' title='".$ar["name"]."' />"; } else { /*Новый вид картинок*/ $pic="<img src='/userfiles/pictavto/".$ar["pic"]."' title='".$ar["name"]."' />"; }}
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<div class='CommentBox'><a href='/".$dir[0]."/view/".$ar["id"]."#comments'>".$ar["comcount"]."</a></div>"; } else { $coms=""; }
		$text.="<div class='NewsLentaList' id='NewsLentaList-".$ar["id"]."'><a href='/".$dir[0]."/view/".$ar["id"]."'>".$pic."</a><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2>".$C."
		<div class='Info'><div class='Other'>".Replace_Data_Days($d[4]).",  <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>,  Автор: ".$auth."</div>".$coms."</div></div>";
		if($data["total"]>($i+1)){ if (($i+1)%$onblock==0) { $text.=$C25."<div class='banner2' style='margin-left:10px;' id='Banner-6-".(floor($i/$onblock)+1)."'></div>".$C; } else { $text.=$C25; }}
	}
	$ncat=$ar["ncat"]; $data=DB("SELECT count(id) as `cnt` FROM `".$table."` WHERE (`cat`='".(int)$dir[2]."')"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $dir[0]."/".$dir[1]."/".$dir[2]."/[page]");
	return(array($text, $ncat));
}

####### ВЫВОД СОДЕРЖАНИЯ НОВОСТИ ######################################################################################################################################
function GetLentaId() {
	$src="http://progorodchelny.ru";
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $link, $table, $table2, $table3, $table4, $table5, $table6, $table7, $table8,$link, $C, $C5, $C10, $C15, $C20, $ROOT, $forums; 
	
		$yandex='<!-- Яндекс.Директ -->
<div id="yandex3ad"></div>
<script type="text/javascript">
(function(w, d, n, s, t) {
    w[n] = w[n] || [];
    w[n].push(function() {
        Ya.Direct.insertInto(125901, "yandex3ad", {
            ad_format: "direct",
            font_size: 0.8,
            type: "horizontal",
            border_type: "block",
            limit: 1,
            title_font_size: 1,
            links_underline: true,
            site_bg_color: "FFFFFF",
            border_color: "CCCCFF",
            title_color: "0066CC",
            url_color: "333333",
            text_color: "000000",
            hover_color: "0066FF",
            no_sitelinks: true
        });
    });
    t = d.getElementsByTagName("script")[0];
    s = d.createElement("script");
    s.src = "//an.yandex.ru/system/context.js";
    s.type = "text/javascript";
    s.async = true;
    t.parentNode.insertBefore(s, t);
})(window, document, "yandex_context_callbacks");
</script>'.$C20; 


	### Основной запрос
	$data=DB("SELECT `".$table."`.*, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`, `_users`.`avatar`, `$table5`.`id` as `vvid` FROM `".$table."`
	LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `$table5` ON `$table5`.`pid`=`$table`.`id` AND `$table5`.`link`='".$dir[0]."' AND `$table5`.`vid`='0' AND `$table5`.`stat`=1	
	LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`id`='".(int)$dir[2]."') GROUP BY 1 LIMIT 1"); @mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);
	$Page["Description"]=$item["ds"]; $Page["KeyWords"]=$item["kw"]; $cap=$item["name"];
	
	if ((int)$item["promo"]!=1) { $ban="<div class='banner5' id='Banner-11-1'></div>"; }

### Карточки
	$p=DB("SELECT * FROM `".$table8."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."') order by `id` ASC"); $cards='';
	if ($p["total"]>0) { for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
		$cards.="<div class='card'>";
		if ($ar["num"]!="") { $cards.="<h2 class='cardnum'>".nl2br($ar["num"])."</h2>"; }
		if ($ar["name"]!="") { $cards.="<h2 class='cardname'>".nl2br($ar["name"])."</h2>"; }
		if ($ar["text"]!="") { $cards.="<div class='cardtext'>".nl2br($ar["text"])."</div>"; }
		$cards.="</div>";
	endfor; $cards.=$C20; }	
	
		
	### Фотография
	if ($item["pic"]!="") { $pic="<div class='PicItem' title='$cap'>"; if (strpos($item["pic"], "old")!=0) { $path='/'.$item["pic"]; } else { $path=$src.'/userfiles/picitem/'.$item["pic"]; }
	$pic.="<a rel='prettyPhoto[gallery]' href='".$src.'/userfiles/picoriginal/'.$item["pic"]."'><img src='".$path."' title='$cap' alt='$cap' /></a>";
	if ((int)$item["promo"]==1) {  $pic.="<div class='Cens'><img src='/template/standart/info.png' style='width:16px !important; height:16px !important; padding:3px 0 !important;'></div>"; } else { if ($item["cens"]!="") { $pic.="<div class='Cens'>".$item["cens"]."</div>"; }} 
	if ($item["picauth"]!="") { $pic.="<div class='PicAuth'>Фото: ".$item["picauth"]."</div>"; } $pic.="</div>".$C20; }
	
	### Претекст текст
	if ($item["lid"]!="") { $lid="<div class='ItemLid'>".$item["lid"]."</div>".$C15; }
	
	### Основной текст
	$maintext=$item["text"]; 
	$maintext=preg_replace('#<img.*(alt="([^"]*))([^>]*)>#','$0<div class="ImageAlt">$2</div>', $maintext);
	$maintext=preg_replace('#<img.*(src="([^"]*))([^>]*)>#','<a href="$2" rel="prettyPhoto[gallery]">$0</a>', $maintext);	
	$maintext=str_replace(array("\r","\n","<div>&nbsp;</div>",'<div class="ImageAlt"></div>',"<p>&nbsp;</p>"), array(' ', ' ', ' ', ' '), $maintext).$C;
	
	### Голосование
	if ((int)$item["vvid"]!=0 && $dir[0]=="adverting" && (int)$dir[2]==804) { $maintext.=$C5."<div id='ItemVotingDiv'></div><script>GetItemVoting(".(int)$item["vvid"].");</script>".$C5; }
		 
	### Фото-отчет
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='report' && `stat`=1) order by `rate` ASC"); $report='';
	if ($p["total"]>0) { for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); if ($ar["name"]!="") { $report.="<h2>".$ar["name"]."</h2>"; }
	$report.="<a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' class='ReportPicBig'></a>";
	if ($ar["author"]!="") { $report.=$C10."<div class='ImageAlt'>".$ar["author"]."</div>".$C10; } if ($ar["text"]!="") { $report.="<div class='nop'>".$ar["text"]."</div>"; } $report.=$C20; endfor; }
	
	### Фото-альбом
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='album' && `stat`=1) order by `rate` ASC");
	if ($p["total"]>0) { $album="<h3>Фотоальбом:</h3>$C10<div class='ItemAlbum'>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); $album.="<a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/pictavto/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a>"; endfor; $album.="</div>".$C; }
	
	### Голосование
	if ((int)$item["vvid"]!=0 && $dir[0]!="adverting" && (int)$dir[2]!=804) { $voting=$C5."<div id='ItemVotingDiv'></div><script>GetItemVoting(".(int)$item["vvid"].");</script>".$C5; }
	
	### Видео
	$p=DB("SELECT * FROM `".$table4."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."') LIMIT 1"); if ($p["total"]>0) { $video=""; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); if ($ar["text"]!="") { if ($ar["name"]!="") { $video.="<h4>".$ar["name"]."</h4>"; } $vid=GetNormalVideo($ar["text"]); $video.=$C15.$vid.$C15; } endfor; }
	
	
	### Читайте также
	$readmore=""; $doptext="";
	if ((int)$item["promo"]!=1 && (int)$item["spromo"]!=1 && $item["tags"]!=",") {
		$ta=explode(",", trim($item["tags"],",")); if (sizeof($ta)>0) { $tagid=""; $socblock=''; foreach($ta as $t){ $tagid.=" `tags` LIKE '%,".$t.",%' OR"; } $tagid=trim($tagid," OR"); $dopclass="";
		$p=DB("SELECT `id`,`name` FROM `".$link."_lenta` WHERE (`stat`=1 && (".$tagid.") && `id`!='".(int)$dir[2]."') ORDER BY `data` DESC LIMIT 17");
		if ($data["total"]>0) {
				
			if ($link=="afisha") { $readmore="<ul class='ReadMore' style='margin-top:-5px; width:100%;'>"; } else { $readmore="<ul class='ReadMore' style='margin-top:-5px;'>"; }
			
		for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); $readmore.="<li $dopclass><a href='/".$link."/view/".$ar["id"]."' title='$ar[name]'>$ar[name]</a></li>";
		if ($i==3) { $readmore.="<li id='morelibtn'><a href='javascript:void(0);' onclick='showmorelis();'><b>Показать больше...</b></a></li>"; $dopclass='class="hiddenlis"'; } endfor; $readmore.="</ul>"; }
		if (in_array(163, $ta)) { $socblock="<div id='zelgroup'></div><script type='text/javascript'>VK.Widgets.Group('zelgroup',{mode:0, width:'200', height:'250', color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 75255942);</script>";
		} elseif($link=="auto") { $socblock="<div id='kznagroup'></div><script type='text/javascript'>VK.Widgets.Group('kznagroup',{mode:0, width:'200', height:'250', color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 54815659);</script>";
		} else { $socblock="<div id='kzngroup'></div><script type='text/javascript'>VK.Widgets.Group('kzngroup',{mode:0, width:'200', height:'250', color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 27126289);</script>"; }
		
		
		
		/* $doptext="<div class='hiddenlis'>".$C10."<h4>Выбор читателей:</h4>".getMaxLikes()."</div>"; */
		if ($link=="afisha") {
			$readmore="<h3>Узнай больше! Вступай в группу нашего города:</h3><div style='float:left; width:480px;'>".$readmore."</div><div style='float:right; width:200px;'>".$socblock.$doptext."</div>";
		} else {
			$readmore="<h3>Узнай больше! Вступай в группу нашего города:</h3><div style='float:left; width:270px;'>".$readmore."</div><div style='float:right; width:200px;'>".$socblock.$doptext."</div>";
		}
		
	}}
		
	### Аватар автора, Автор и дата### Тэги
	$t=trim($item["tags"], ","); $tags=""; if ($t!="") { $ta=DB("SELECT * FROM `_tags` WHERE (`id` IN (".$t.")) LIMIT 3"); for ($i=0; $i<$ta["total"]; $i++): @mysql_data_seek($ta["result"],$i); $ar=@mysql_fetch_array($ta["result"]);
	$tags.="<a href='/tags/$ar[id]'>$ar[name]</a>, "; endfor; $tags2=trim($tags, ", "); $tags="Тэги: ".trim($tags, ", "); }
	if ($item["avatar"]=="" || !is_file($ROOT."/".$item["avatar"]) || filesize($ROOT."/".$item["avatar"])<100) { $avatar="<img src='/userfiles/avatar/no_photo.jpg'>"; } else { $avatar="<img src='/".$item["avatar"]."'>"; }
	$d=ToRusData($item["data"]); if ($item["uid"]!=0 && $item["nick"]!="") { $auth=$avatar."<a href='http://".$VARS["mdomain"]."/users/view/".$item["uid"]."/'>".$item["nick"]."</a>, ".$d[4]; } else { $auth="<img src='/userfiles/avatar/no_photo.jpg' />Автор: Народный корреспондент, ".$d[1]; }
	$mixblock="<div class='MixBlock'><div class='ILeft'><div class='ItemAuth'>".$auth."<br />$tags<br />Нашли ошибку? Выделите фразу и нажмите Ctrl+Enter</div></div>";
	if ($item["promo"]!=1) { $mixblock.="<div class='IRight'><div id='ItemLikesDiv'><img src='/template/standart/loader.gif' style='margin:15px 40px;'></div><script>GetItemLikes(".(int)$item["id"].", '".$dir[0]."');</script></div>"; }
	$mixblock.="<div class='C'></div>"; if ($item["pay"]!="") { $mixblock.="<div class='PayBlock'>".$item["pay"]."</div>".$C; } $mixblock.="</div>".$C15;
	
	if ($item["promo"]!=1) { 
		#$mixblock.="<div class='banner' id='Banner-6-1'></div>";
		$mixblock.='<div id="unit_86352"><a href="http://smi2.ru/">Новости smi2.ru</a></div>
		<script type="text/javascript" charset="utf-8">(function() { var sc = document.createElement("script"); sc.type = "text/javascript"; sc.async = true;
		sc.src = "//smi2.ru/data/js/86352.js"; sc.charset = "utf-8"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(sc, s); }()); </script>';
	}
		
	### Карта событий
	$edata=DB("SELECT `".$table7."`.*, `_pages`.`sets` FROM `".$table7."` LEFT JOIN `_pages` ON `_pages`.`module`='eventmap' WHERE (`".$table7."`.`pid`=".$item['id']." AND `".$table7."`.`link`='".$link."' AND `".$table7."`.`stat`=1)"); if($edata["total"]) {
		@mysql_data_seek($edata["result"],0); $ev=@mysql_fetch_array($edata["result"]);
		if($ev['maps']){ $event = '<script type="text/javascript" src="	http://maps.api.2gis.ru/1.0"></script><div id="Map" style="width:500px; height:300px;"></div>';
			$event .= '<script type="text/javascript">initMap(['.$ev['id'].', "'.htmlspecialchars($ev['name']).'", "'.$ev['maps'].'", "'.($ev['icon'] ? '/userfiles/mapicon/'.$ev['icon'] : '').'"]);</script>';
		} else if($ev['data']){
			$event_month_days = date('t', $ev['data']); $event_day = date('j', $ev['data']); $event_month = date('n', $ev['data']);
			$event_first_day = getdate(mktime(0, 0, 0, date('m', $ev['data']), 1, date('Y', $ev['data']))); 	$event_last_day = getdate(mktime(0, 0, 0, date('m', $ev['data']), $event_month_days, date('Y', $ev['data'])));		
			$calendar = '<div class="Calendar"><table>'; $calendar .= '<tr><th colspan="7">'.$GLOBAL["mothi"][date('n', $ev['data'])].' '.date('Y', $ev['data']).'</th></tr>';
			$calendar .= '<tr><th>ПН</th><th>ВТ</th><th>СР</th><th>ЧТ</th><th>ПТ</th><th>СБ</th><th>ВС</th></tr><tr>';				
			for($i = 2 - $event_first_day['wday'], $j = 1; $i <= $event_month_days + (7 - ($event_last_day['wday'] == 0 ? 7 : $event_last_day['wday'])); $i++, $j++){
				$calendar .= '<td><span'.($i == $event_day ? ' class="active" title="Начало"' : '').'>'.($i > 0 && $i <= $event_month_days ? $i : '').'</span></td>'; if($j % 7 == 0) { $calendar .= '</tr><tr>'; }
			} $calendar.='</tr></table></div>'; $event = $calendar;
		}
	}

	### Лого и контакты
	$cdata=DB("SELECT * FROM `".$table6."` WHERE (`pid`=".$item['id']." AND `link`='".$link."')");
	if($cdata["total"]) { @mysql_data_seek($cdata["result"],0); $con=@mysql_fetch_array($cdata["result"]); $web=$con["web"];
		if ($con['name']) { $contacts="<h2>".$con["name"]."</h2>"; }
		if ($con["pic"]) { $contacts.="<div style='float:left; margin-right:10px; width:120px;'><img src='/userfiles/picpreview/".$con["pic"]."' title='".$con["name"]."' width='120' /></div><div style='float:left; width:350px;'>"; } else { $contacts.="<div style='width:100%;'>"; }
		if ($con["address"]) { $contacts.="Адрес: ".$con["address"].$C5; }
		if ($con["phone"]) { $contacts.="Телефон: ".$con["phone"].$C5; }
		if ($web) { $ri=str_replace(array("www.","http://","https://"), "", $web); $ri=trim($ri,"/"); $ri=trim($ri); $ar1=explode("/", $ri); $ar2=explode("?",$ar1[0]); $web="<a href='http://".$ri."' target='_blank' rel='nofollow'>".$ar2[0]."</a>"; $contacts.="Сайт: ".$web.$C5; }
		if ($con["anonce"]) { $contacts.=nl2br($con["anonce"]); }
	$contacts.="</div>".$C; }
	
	### Лайки 
	$likes=$C10."<div class='Likes' style='text-align:center;'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($lid))).$C."</div>".$C;
	
	### Платные ссылки
	if ($item["adv"]!="") { $mixblock.="<div class='CBG'></div>".$C5."<div class='AdvBlock'>".$item["adv"]."</div>".$C; }
	
	### Заключительный текст
	if ($item["endtext"]!="") { $endtext="<div class='ItemLid'>".$item["endtext"]."</div>".$C15; }

	### Источник
	$realinfo=""; if ($item["realinfo"]!="") { if(strpos($item["realinfo"],"www.")!==false || strpos($item["realinfo"],"http")!==false || strpos($item["realinfo"],".ru")!==false || strpos($item["realinfo"],".com")!==false) {
		$ri=str_replace(array("www.","http://","https://"), "", $item["realinfo"]); $ri=trim($ri,"/"); $ri=trim($ri); $ar1=explode("/", $ri); $ar2=explode("?",$ar1[0]);
		$realinfo="<a href='http://".$ri."' target='_blank' rel='nofollow'>".$ar2[0]."</a>";
	} else { $realinfo=$item["realinfo"]; } $realinfo="<noindex><div class='RealInfo'>Источник: ".$realinfo."</div></noindex>".$C; }

    /**
     * Добавлен класс js-mediator-article для сервиса Медиатор
     */
	### компановка на вывод
	$text=$pic.$ban."<div class='ArticleContent js-mediator-article'>".$lid.$maintext."</div>"."<!--TEST-->".$report.$cards.$video.$endtext.$voting.$album.$event.$contacts.$realinfo.$likes.$mixblock.$yandex.$readmore;
	return(array($text, $cap));
}
?>