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
$file=$table."-".$start."_mobile.".$page.".".$id;

if ($link=="news" && (int)$dir[2]==183035) { @header("location: /cosmos"); exit(); }

#############################################################################################################################################
### Вывод новости
if ($start=="view") {
	$where=$GLOBAL["USER"]["role"]==0?"&& `stat`=1":"";
	$data=DB("SELECT `comments`,`alttext`, `promo` FROM `".$table."` WHERE (`id`='".(int)$dir[2]."' ".$where.") LIMIT 1");
	if ($data["total"]==1) {
		@mysql_data_seek($data["result"], 0); $new=@mysql_fetch_array($data["result"]);

if ($new["alttext"]=="") {
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		if (RetCache($file)=="true") { list($text, $cap)=GetCache($file); } else { list($text, $cap)=GetLentaId(); SetCache($file, $text, $cap); }
		if ($Page404==0) { UserTracker($link, $page);
			$text .= '<!-- orion choko -->';
			$text .= '<script type="text/javascript">
(function loadExtData(w) {
  w.adv = new Adv();
})(window);
</script>
  <div id="adfox_149699505329239857"></div>
  <script type="text/javascript">
adv.banner(function(webmd) {
    console.log("Showing banner 1", webmd);
           var wmclusters = webmd["clusters"].toString();
           var audiences =  webmd["audiences"].toString();
           wmclusters = wmclusters.replace(/,/g,":");
           audiences = audiences.replace(/,/g,":");
    window.Ya.adfoxCode.create({
        ownerId: 251657,
        containerId: "adfox_149699505329239857",
        params: {
            pp: "g",
            ps: "cjof",
            p2: "frcw",
            puid1: webmd["socio_demographics"]["age"],
            puid2: webmd["socio_demographics"]["gender"],
            puid3: webmd["socio_demographics"]["social_class"],
            puid4: wmclusters,
            puid5: audiences
        },
           onRender: function() { console.log("otag_rendered"); },
           onError: function(error) { console.log("otag_error"); },
           onStub: function() { console.log("otag_stub"); }
});
});
</script>';
			$text.=UsersComments($link, $page, $new["comments"]);
		}
		$text .= '<div id="adfox_149699503432432432"></div>';
    $text .= '<script type="text/javascript">
    (function loadExtData(w) {
     w.adv = new Adv();
     })(window);
    </script>
    <div id="adfox_149699505329239829"></div>
    <script type="text/javascript">
    adv.banner(function(webmd) {
               console.log("Showing banner 1", webmd);
               var wmclusters = webmd["clusters"].toString();
               var audiences =  webmd["audiences"].toString();
               wmclusters = wmclusters.replace(/,/g,\':\');
                                               audiences = audiences.replace(/,/g,\':\');
                                                                             window.Ya.adfoxCode.create({
                                                                                                        ownerId: 251657,
                                                                                                        containerId: \'adfox_149699505329239829\',
                                                                                                        params: {
                                                                                                        pp: \'g\',
                                                                                                        ps: \'cjof\',
                                                                                                        p2: \'frcw\',
                                                                                                        puid1: webmd[\'socio_demographics\'][\'age\'],
                                                                                                        puid2: webmd[\'socio_demographics\'][\'gender\'],
                                                                                                        puid3: webmd[\'socio_demographics\'][\'social_class\'],
                                                                                                        puid4: wmclusters,
                                                                                                        puid5: audiences
                                                                                                        },
                                                                                                        onRender: function() { console.log(\'otag_rendered\'); },
                                                                                                                                           onError: function(error) { console.log(\'otag_error\'); },
                                                                                                                                                                                  onStub: function() { console.log(\'otag_stub\'); }
                                                                                                                                                                                                                   });
                                                                                                                                                                                  });
                                                                                                                                           </script>';
		$edit="<div id='AdminEditItem'><a href='".$GLOBAL["mdomain"]."/admin/?cat=".$link."_edit&id=".(int)$dir[2]."'>Редактировать</a></div>";
		if ($GLOBAL["USER"]["role"]>1) { $text=$C10.$edit.$C.$text; } $Page["Content"]=$text; $Page["Title"]=$cap; $Page["Caption"]="";
		$text.="<img src='/modules/lenta/stat.php?ok=1&tab=".$dir[0]."&id=".$new["id"]."&uid=".$_SESSION["userid"]."' style='width:1px; height:1px;' />";
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
} else { $text=$new["alttext"]; $cap=$new["name"]; $node["design"]="akbars"; $Page["Content"]=$text; $Page["Caption"]=$cap; }
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

	} else {
		$cap="Материал не найден";
		$text=@file_get_contents($ROOT."/template/404.html");
		$Page["Content"]=$text; $Page["Caption"]=$cap;
	}
}

### Вывод списка новостей общий
if ($start=="list") {
	if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaList(); SetCache($file, $text, ""); }
	$Page["Content"].=$text; $Page["Caption"]=$node["name"];
}

### Вывод списка новостей в категории
if ($start=="cat") {
	if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaCat(); SetCache($file, $text, $cap); }
	$Page["Content"]=$text; $Page["Caption"]=$cap;
}

#############################################################################################################################################

function GetLentaId() {
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $table, $table2, $table3, $table4, $table8, $table5, $table6, $table7, $link, $C, $C5, $C10, $C15, $C20, $ROOT;

	$ban1.='<div class="C10"></div><div class="Banner" id="Banner-27-1" style="width:100%; height:auto; overflow:hidden;"></div><div class="C10"></div>';
	$ban2.='<div class="C10"></div><div class="Banner" id="Banner-28-1" style="width:100%; height:auto; overflow:hidden;"></div><div class="C10"></div>';
	$ban3.='<div class="C10"></div><div class="Banner" id="Banner-28-2" style="width:100%; height:auto; overflow:hidden;"></div><div class="C10"></div>';

	### Основной запрос
	$data=DB("SELECT `".$table."`.*, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`, `_users`.`avatar`, `$table5`.`id` as `vvid` FROM `".$table."`
	LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id`	
	LEFT JOIN `$table5` ON `$table5`.`pid`=`$table`.`id` AND `$table5`.`link`='".$dir[0]."' AND `$table5`.`vid`='0' AND `$table5`.`stat`=1	
	LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat`	
	WHERE (`".$table."`.`id`='".(int)$dir[2]."') GROUP BY 1 LIMIT 1");
	@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);

	$Page["Description"]=$item["ds"];
	$Page["KeyWords"]=$item["kw"];

	### Формирование данных
	$cap=$item["name"];

	### Фотография
	if ($item["pic"]!="") {
		if (strpos($item["pic"], "old")!=0) { /*Старый*/ $old=1; $path='/'.$item["pic"]; $pic="<div class='LentaPictureOld' title='".$cap."'><img src='".$item["pic"]."' title='".$cap."' /></div>"; } else {
		/*Новый*/ $old=0; $path='/userfiles/picitem/'.$item["pic"]; $pic.="<div class='LentaPicture-picitem' title='$cap'><img src='/userfiles/picitem/".$item["pic"]."' title='$cap' /><div class='LentaPicture-Cens'><h1>".$item["cens"]."</h1></div></div>"; }
		if ($item["picauth"]!="") { $pic.="<div class='PicAuth'>Автор фотографии: ".$item["picauth"]."</div>"; }
	}
	### Основной текст
	$maintext=CutEmptyTags($item["text"]);

	### Карточки
	$p=DB("SELECT * FROM `".$table8."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."') order by `id` ASC"); $cards='';
	if ($p["total"]>0) { for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
		$cards.="<div class='card'>";
		if ($ar["num"]!="") { $cards.="<h2 class='cardnum'>".nl2br($ar["num"])."</h2>"; }
		if ($ar["name"]!="") { $cards.="<h2 class='cardname'>".nl2br($ar["name"])."</h2>"; }
		if ($ar["text"]!="") { $cards.="<div class='cardtext'>".nl2br($ar["text"])."</div>"; }
		$cards.="</div>";
	endfor; $cards.=$C20; }

	### Претекст текст
	$lid=$item["lid"]; if ($lid!="") { if ($old==1) { $lid="<div class='ItemLidOld'>".$lid."</div>".$C10; } else { $lid="<div class='ItemLid'>".$lid."</div>".$C10; }}
	### Фото-отчет
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='report' && `stat`=1) order by `rate` ASC"); $report=$C.""; if ($p["total"]>0) { $report.="<table class='ItemOrder'>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
		if ($ar["sets"]!=0 && !empty($ar["name"])) { $report.="<tr><td width='100%' valign='top' class='type".$ar["sets"]."'><a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picpreview/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a></td><td width='70%' valign='top' class='type".$ar["sets"]."'><h4>".$ar["name"]."</h4>".$ar["text"]."</td></tr>";
		} else { $report.="<tr><td width=100% valign='top' colspan='2' class='type".$ar["sets"]."'><h4>".$ar["name"]."</h4><a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a>".$C5.$ar["text"]."</td></tr>"; }
	endfor; $report.="</table>".$C10; }
	### Фото-альбом
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='album' && `stat`=1) order by `rate` ASC"); if ($p["total"]>0) { $album="<h2>Фотоальбом:</h2><div class='ItemAlbum'>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); $album.="<a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a>"; endfor; $album.="</div>".$C; }
	### Голосование
	if ((int)$item["vvid"]!=0) { $voting=$C5."<div id='ItemVotingDiv'></div><script>GetItemVoting(".(int)$item["vvid"].");</script>".$C5; }
	### Видео
	$p=DB("SELECT * FROM `".$table4."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."') LIMIT 1"); if ($p["total"]>0) { $video=""; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
	if ($ar["text"]!="") { if ($ar["name"]!="") { $video.="<h2>".$ar["name"]."</h2>"; } $vid=GetNormalVideo($ar["text"]); $video.=$vid.$C10; } endfor; }
	### Аватар автора
	if ($item["avatar"]=="" || !is_file($ROOT."/".$item["avatar"]) || filesize($ROOT."/".$item["avatar"])<100) { $avatar="<img src='/userfiles/avatar/no_photo.jpg'>"; } else { $avatar="<img src='/".$item["avatar"]."'>"; }
	### Автор и дата
	$d=ToRusData($item["data"]);
	if ($item["uid"]!=0 && $item["nick"]!="") { $auth=$avatar."Автор: <a href='http://".$VARS["mdomain"]."/users/view/".$item["uid"]."/'>".$item["nick"]."</a><br><b>".$d[1]."</b>";
	} else { $auth="<img src='/userfiles/avatar/no_photo.jpg' />Автор: <a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a><br><b>".$d[1]."</b>"; }
	### Тэги и ошибки
	$t=trim($item["tags"], ","); $tags=""; if ($t!="") { $ta=DB("SELECT * FROM `_tags` WHERE (`id` IN (".$t.")) LIMIT 3"); for ($i=0; $i<$ta["total"]; $i++): @mysql_data_seek($ta["result"],$i); $ar=@mysql_fetch_array($ta["result"]);
	$tags.="<a href='/tags/$ar[id]'>$ar[name]</a>, "; endfor; $tags2=trim($tags, ", "); $tags="Тэги:".trim($tags, ", "); } $tags.="<br><a href='http://".$VARS["mdomain"]."/newsv2/".$item["id"].".html'>Старый формат новостей</a>";
	### Лого и контакты
	$cdata=DB("SELECT * FROM `".$table6."` WHERE (`pid`=".$item['id']." AND `link`='".$link."')"); if($cdata["total"]) { @mysql_data_seek($cdata["result"],0); $con=@mysql_fetch_array($cdata["result"]);
	if($con['name']){ $contacts = $C10.'<div class="WhiteBlock">'; if ($con["pic"]!="") { $contacts .= "<div style='float:left; margin-right:10px;'><img src='/userfiles/picpreview/".$con["pic"]."' title='".$con["name"]."' width='80' /></div>"; }
	$contacts .= "<h3>".$con["name"]."</h3><p class='contacts'><img src='/template/standart/address.png' style='vertical-align:middle;' />"; if($con["address"]) { $contacts.="<strong class='address'>".$con["address"]."</strong>"; }
	if($con["address"] && $con["phone"]) { $contacts.="<strong class='address'>. </strong>"; } if($con["phone"]) { $contacts.="<strong class='phone'>тел: <span>".$con["phone"]."</span></strong>"; } $contacts.="</p>".$con["anonce"].$C.'</div>'; }}
	### Лайки
	$likes="<div class='Likes'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($lid))).$C."</div>".$C10;
	### Вывод автора и тэгов
	$mixblock="<div><div class='ItemAuth'>".$auth."</div><div class='ItemTags'>".$tags."</div>".$C."</div>".$C5;
	if ($item["pay"]!="") { $mixblock.=$C10."<div class='WhiteBlock PayBlock'>".$item["pay"]."</div>"; }
	### Платные ссылки
	if ($item["adv"]!="") { $mixblock.=$C20."<div class='CBG'></div>".$C5."<div class='AdvBlock'>".$item["adv"]."</div>".$C; }
    $mixblock.='
<div id="smi_teaser_11726">
    <center>
        <a href="https://24smi.info/?utm_source=informer_11726">
            Агрегатор новостей 24СМИ
        </a>
    </center>
</div>

<script type="text/JavaScript" encoding="utf8">
 (function() {
 var sm = document.createElement("script");
 sm.type = "text/javascript";
 sm.async = true;
 sm.src = "//jsn.24smi.net/3/a/11726.js";
 var s = document.getElementsByTagName("script")[0];
 s.parentNode.insertBefore(sm, s);})();
</script>';
	### Новости по таким же тэгам
	$dtags.="<h3>Будь в теме:</h3>"; $dtags.=TheNewestInKazan(3, $item["id"]);

	/*
	$data2=DB("SELECT `pic`,`data`,`name`,`id` FROM `".$table."` WHERE (`id`!='".(int)$dir[2]."' AND `stat`='1') ORDER BY `data` DESC LIMIT 3");
	if ($data2["total"]>0) { $dtags='<div class="Dtags"><h3>Будь в теме:</h3>';
	for ($i=0; $i<$data2["total"]; $i++): @mysql_data_seek($data2["result"],$i); $ar=@mysql_fetch_array($data2["result"]); $d=ToRusData($ar["data"]);
	$dtags.="<a href='/$dir[0]/view/$ar[id]/' title='".$ar["name"]."'><img src='/userfiles/picsquare/$ar[pic]' style='float:left; width:100px; height:100px; border:none; margin-right:10px;'>".$ar["name"]."</a><div class='C'></div><div style='margin:10px 0; border-bottom:1px dashed #CCC;'></div>";
	endfor; $dtags.="<div class='C10'></div>Темы: ".$tags2.'</div>'; }
	*/

	### Заключительный текст
	if ($item["endtext"]!="") { $endtext=$C5."<div class='WhiteBlock EndText'>".$item["endtext"]."</div>".$C; }
	### Текст вывода

    /**
     * Добавлен класс js-mediator-article для сервиса Медиатор
     */
	if ($old==1) { $text="<h1>".$cap."</h1>"."<div class='WhiteBlock LentaItem js-mediator-article'>".$lid.$pic.$ban1."<div class='ItemText'>".$maintext."</div>".$cards.$ban2.$report.$video.$album.$endtext.$voting.$event.$contacts.$C10.$likes.$mixblock.$ban3."</div>";
	} else { $text="<h1>".$cap."</h1><div class='WhiteBlock LentaItem js-mediator-article' style='padding:9px;'>".$pic.$C15.$lid.$ban1."<div class='ItemText'>".$maintext.$cards.$ban."</div>".$ban2.$report.$video.$album.$endtext.$voting.$event.$contacts.$C10.$likes.$mixblock.$dtags.$ban3."</div>";}

	if ($link=="ls") {
		$text.='<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = _tmr || [];
_tmr.push({id: "2549072", type: "pageView", start: (new Date()).getTime()});
(function (d, w) {
   var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
   ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
   var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
   if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window);
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2549072;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->';
	}

	// Растягиваем youtube на всю ширину
	$text .= "
	<style>
	iframe[src*=\"https://www.youtube.com/\"] {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
	</style>
	<script>
	var yt_nodes = document.querySelectorAll('iframe[src*=\"https://www.youtube.com/\"]');
	var styles = {
		position: 'relative',
		paddingBottom: '56.25%',
		paddingTop: '25px',
		height: '0'
	};
	for(var i = 0; i < yt_nodes.length; i++) {
		for(var key in styles) { 
			if (styles.hasOwnProperty(key)) {
				yt_nodes[i].parentElement.style[key] = styles[key];
			}
		}
	}
	</script>";

	return(array($text, $cap));
}

#############################################################################################################################################

function GetLentaList() {
	global $VARS, $GLOBAL, $dir, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C25;
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; ".$node["name"]."</div>";
	$onpage=$node["onpage"]; $pg = $dir[2] ? $dir[2] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage; $onblock=4; /* Новостей в каждом блоке */
	$data=DB("SELECT `".$table."`.id, `".$table."`.cat, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`stat`=1)  GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	$text.="<div class='RDNEWS'>";
	for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $item=@mysql_fetch_array($data["result"]); $d=ToRusData($item["data"]);
		$d=ToRusData($item["data"]);
		$a="<a href='/".$dir[0]."/view/".$item["id"]."'>";
		$p=$a."<img src='/userfiles/picsquare/".$item["pic"]."'>"."</a>";
		$n="<span>".$a.$item["name"]."</a><br><i>".$d[1]."<br>Комментарии: ".$item["comcount"]."</i></span>";
		$text.="<ins>".$p.$n."</a></ins>";
	} $text.="</div>";
	$data=DB("SELECT count(id) as `cnt` FROM `".$table."`"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
	$text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $dir[0]."/".$dir[1]."/[page]");
	return(array($text, ""));
}

#############################################################################################################################################

function GetLentaCat() {
	global $VARS, $GLOBAL, $dir, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C25;
	$onpage=$node["onpage"]; $pg = $dir[3] ? $dir[3] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage; $onblock=4; /* Новостей в каждом блоке */
	$data=DB("SELECT `".$table."`.name, `".$table."`.uid, `".$table."`.cat, `".$table."`.pic, `".$table."`.data, `".$table."`.id, `".$table."`.comcount, `".$table."`.comments, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."`	LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='".(int)$dir[2]."' && `".$table."`.`stat`=1) GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; }

	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>";
		} else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div>
		<div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";

		if (($i+1)%$onblock==0) { $text.="</div>".$C10."<div class='banner' id='Banner-28-".(floor($i/$onblock)+1)."'></div>".$C10."<div class='WhiteBlock'>"; } else { $text.=$C25; }
	}
	if ($data["total"]>0) { $text.="</div>"; } $ncat=$ar["ncat"];
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; <a href='http://".$RealHost."/".$dir[0]."'>".$node["name"]."</a> &raquo; ".$ar["ncat"]."</div>";

	$data=DB("SELECT count(id) as `cnt` FROM `".$table."` WHERE (`cat`='".(int)$dir[2]."')"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
	$text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $dir[0]."/".$dir[1]."/".$dir[2]."/[page]");

	return(array($text, $ncat));
}
?>
