<?
# ПРАВЫЙ БЛОК # Переменная $Page["RightContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/right_block/[поддомен].php берем его, иначе берем дефолтный правый блок /modules/page_mods/right_block/default.php
if ($SubDomain==100 || $link=="index" || $link=="new-index") {
	// Правая колонка АВТО портала задается в модуле автопортала
	// Правая колонка главной страницы задается отдельно в index.php
} else {
	if (is_file("modules/page_mods/right_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/right_block/new-".$Domains[$SubDomain].".php"); $GLOBAL["log"].="<i>Подключение PHP</i>: правый блок &laquo;modules/page_mods/right_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/right_block/default.php")) {
		@require("modules/page_mods/right_block/default.php"); $GLOBAL["log"].="<i>Подключение PHP</i>: правый блок &laquo;modules/page_mods/right_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: правый блок не подключен<hr>"; }
}
//echo "aaa";
# ЛЕВЫЙ БЛОК - СТАРТ # $Page["LeftContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/left_block/[поддомен].php берем его, иначе берем дефолтный левый блок /modules/page_mods/left_block/default.php
if ($SubDomain==100 || $link=="index" || $link=="new-index") {
	// Левая колонка АВТО портала задается в модуле автопортала
	// Левая колонка главной страницы задается отдельно в index.php
} else {
	if (is_file("modules/page_mods/left_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/left_block/new-".$Domains[$SubDomain].".php"); $GLOBAL["log"].="<i>Подключение PHP</i>: левый блок &laquo;modules/page_mods/left_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/left_block/default.php")) {
		@require("modules/page_mods/left_block/default.php"); $GLOBAL["log"].="<i>Подключение PHP</i>: левый блок &laquo;modules/page_mods/left_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: левый блок не подключен<hr>"; }
}
# ВЕРХНИЙ БЛОК # Переменная $Page["TopContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/top_block/[поддомен].php берем его, иначе берем дефолтный правый блок /modules/page_mods/top_block/default.php
if ($SubDomain==100 || $link=="index" || $link=="new-index") {
	// Верхняя колонка АВТО портала задается в модуле автопортала
	// Верхняя колонка главной страницы задается отдельно в index.php
} else {
	if (is_file("modules/page_mods/top_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/top_block/new-".$Domains[$SubDomain].".php"); $GLOBAL["log"].="<i>Подключение PHP</i>: верхний блок &laquo;modules/page_mods/top_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/top_block/default.php")) {
		@require("modules/page_mods/top_block/default.php"); $GLOBAL["log"].="<i>Подключение PHP</i>: верхний блок &laquo;modules/page_mods/top_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: верхний блок не подключен<hr>"; }
}
//echo "aaa";
# НИЖНИЙ БЛОК # Переменная $Page["BottomContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/bottom_block/[поддомен].php берем его, иначе берем дефолтный правый блок /modules/page_mods/bottom_block/default.php
if ($SubDomain==100) {
	// Верхняя колонка АВТО портала задается в модуле автопортала
	// Верхняя колонка главной страницы задается отдельно в index.php
} else {
	if (is_file("modules/page_mods/bottom_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/bottom_block/new-".$Domains[$SubDomain].".php"); $GLOBAL["log"].="<i>Подключение PHP</i>: нижний блок &laquo;modules/page_mods/bottom_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/bottom_block/default.php")) {
		@require("modules/page_mods/bottom_block/default.php"); $GLOBAL["log"].="<i>Подключение PHP</i>: нижний блок &laquo;modules/page_mods/bottom_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: нижний блок не подключен<hr>"; }
}
//echo "aaa";
# ПОИСК ЯНДЕКС
$Page["SiteSearch"]="<div class='ya-site-form ya-site-form_inited_no' onclick=\"return {'bg': 'transparent', 'target': '_self', 'language': 'ru', 'suggest': false, 'tld': 'ru', 'site_suggest': true, 'action': 'http://".$VARS["mdomain"]."/search/', 'webopt': false, 'fontsize': 11, 'arrow': false, 'fg': '#000000', 'searchid': '2043787', 'logo': 'rb', 'websearch': false, 'type': 3}\"><form action=\"http://yandex.ru/sitesearch\" method=\"get\" target=\"_self\"><input type=\"hidden\" name=\"searchid\" value=\"2043787\" /><input type=\"hidden\" name=\"l10n\" value=\"ru\" /><input type=\"hidden\" name=\"reqenc\" value=\"utf-8\" /><input type=\"text\" name=\"text\" value=\"\" /><input type=\"submit\" value=\"Найти\" /></form></div><style type=\"text/css\">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type=\"text/javascript\">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;(' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1&&(e.className+=' ya-page_js_yes');s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>";

# ИНФОРМЕРЫ ПОД МЕНЮ
$file="_index-widgets"; if (RetCache($file,'cacheblock')=='true'){ list($Page["MainInformers"],$cap)=GetCache($file,0); }else{ list($Page["MainInformers"],$cap)=GetMainInformers(); SetCache($file,$Page["MainInformers"],'','cacheblock'); }
function GetMainInformers() { global $C5;
//	$xml=simplexml_load_file("http://export.yandex.ru/bar/reginfo.xml?region=43"); $lvl=$xml->traffic->level; $icnt=$xml->traffic->icon; $txtt=$xml->traffic->hint; $temp=$xml->weather->day->day_part->temperature; $icnpa=$xml->weather->day->day_part->xpath('//image-v3'); $txtp=$xml->weather->day->day_part->weather_type; $icnp=$icnpa[0]; if ($lvl==1) { $balls=$lvl." балл"; } elseif ($lvl==2 || $lvl==3 || $lvl==4) { $balls=$lvl." балла"; } else { $balls=$lvl." баллов"; }
	$xml=simplexml_load_file("http://kovalut.ru/webmaster/xml-table.php?kod=1601"); $eprod=9999;  $epok=0; $uprod=9999;  $upok=0; $data=$xml->Actual_Rates->xpath('//Bank'); foreach($data as $bank) { if ($bank->USD->Buy<$uprod) { $uprod=$bank->USD->Buy; } if ($bank->USD->Sell>$upok) { $upok=$bank->USD->Sell; } if ($bank->EUR->Buy<$eprod) { $eprod=$bank->EUR->Buy; } if ($bank->EUR->Sell>$epok) { $epok=$bank->EUR->Sell; }} $kurs="<img src='/template/standart/icons/dollar.png' />покупка: ".$uprod."  продажа: ".$upok.$C5."<img src='/template/standart/icons/euro.png' />покупка: ".$eprod."  продажа: ".$epok;
	//$text.="<div class='Item'><a href='/weather' target='_blank'>Погода в Казани</a>: ".($temp-1)." … ".($temp+1)."°C".$C5."<img src='".$icnp."' />".$txtp.$C5."<a href='/weather' style='padding-top:5px;'>Прогноз погоды на 2 недели</a></div>";
	//$text.="<div class='Item'><a href='/traffic' target='_blank'>Пробки в Казани</a>: ".$balls.$C5."<img src='/template/standart/icons/".$icnt.".png' />".$txtt.$C5."<a href='/traffic'>Посмотреть карту пробок онлайн</a></div>";
	$text.="<div class='Item'><a href='/exchange' target='_blank'>".$kurs."</a>".$C5."<a href='/exchange'>Узнать курсы в банках Казани</a></div>";
	$text.="<div class='Item'><a href='/fines-gibdd' target='_blank'>Проверка штрафов ГИБДД</a>:".$C5."<a href='/fines-gibdd' target='_blank'><img src='/template/standart/icons/fines.gif' style='margin:0;' /></a>".$C5."<a href='/fines-gibdd'>Проверить и оплатить штрафы</a></div>";
	$text.="<div class='Item'><a href='/advertise' target='_blank'><img src='/template/standart/icons/progorod.png' style='margin:0; width:170px; height:60px; border-radius:4px;' /></a></div>"; return array("<div id='MainInformers'>".$text."</div>", "");
}
//echo "aaa";
# ПОИСК ВСЕХ ТАБЛИЦ С НОВОСТЯМИ
function getLentasOnModules() { global $lentas; if (sizeof($lentas)==0) { $modules=array("lenta", "concurs", "tatbrand"); $q="SELECT `link` FROM `_pages` WHERE (`module` IN ('".implode("','", $modules)."')) LIMIT 50"; $data=DB($q); for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $lentas[$ar["link"]]=$ar["link"]."_lenta"; }} return $lentas;}
function ToLocalDay($data) { return(str_replace(array(date("d.m.Y"), date("d.m.Y", time()-60*60*24)), array("Сегодня", "Вчера"), $data)); }

function getNewsFromLentas( $q = '', $endq = '' ) {
    global $used;
    $lentas = getLentasOnModules();
    $query = '';
    if ( false !== ( $limwpos = strpos( strtoupper( $endq ), 'LIMIT' ) ) &&
         ( $colonpos = strpos( $endq, ',', $limwpos ) ) ) {
        $oldlimit = explode( 'LIMIT', strtoupper( $endq ) );
        if ( 1 > (int) substr( $endq, $limwpos + 5, $colonpos - strlen( $endq ) ) ) {
            $newlimit = substr( $endq, $colonpos + 1 );
            $single_endq = str_replace( $oldlimit, ' ' . trim( $newlimit ), $endq );
        } else {
            $single_endq = str_replace('LIMIT' . $oldlimit[count($oldlimit) - 1], '', $endq);
        }
    } else {
        $single_endq = $endq;
    }
    foreach ( $lentas as $l => $t ) {
        $usedtext = "";
        if ( sizeof( $used[ $l ] ) > 0 ) {
            $usedtext = " && `" . $t . "`.`id` NOT IN (0, " . implode( ",", $used[ $l ] ) . ")";
        } // не включаем в выборку ранее взятые новости
        $qitem = "(" . str_replace( array( "[table]", "[link]" ), array( $t, $l ), $q ) . $single_endq . ") UNION ALL ";
        $query .= str_replace( "[used]", $usedtext, $qitem );
    }
    $query = trim( $query, "UNION ALL" ) . ' ' . $endq;
    $data = DB( $query );

    return $data;
} // заменяем таблицу и ссылку на нужное и формируем запрос

function DrawNewsItem($ar, $datas='', $class='') {
	$pic=""; $text=""; $pic="<img src='/userfiles/picsquare/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' />";
	$text.="<div class='itemlist ".$class."'><a href='/".$ar["link"]."/view/".$ar["id"]."'>"; if ($ar["pic"]!="") { $text.=$pic; }
	$text.=$ar["name"]."</a>"; $text.="</div>"; return $text;
}

function DrawNewsComrs($ar, $datas='', $class='') {
	$pic=""; $text=""; $pic="<img src='/userfiles/picsquare/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' />"; $text.="<div class='itemlist ".$class."'><a href='/".$ar["link"]."/view/".$ar["id"]."'>"; if ($ar["pic"]!="") { $text.=$pic; } $text.=$ar["name"]."</a></div>"; return $text;
}

function TheNewestInKazan($limit=5, $not=0) {
	$lentas=getLentasOnModules(); $q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]`
	WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1 && `[table]`.`id`!=".$not." && `[table]`.`data`>'".(time()-24*60*60)."')";
	$endq="ORDER BY RAND() LIMIT ".$limit; $tv=getNewsFromLentas($q, $endq); $text.="<newsblock>"; $onblock=4;
	for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]); $text.=DrawNewsItem($ar, 0); endfor; $text.="</newsblock>"; return $text;
}

function TheCommerceInKazan($limit=5, $from=0) {
	$lentas=getLentasOnModules(); $dataold=time()-7*24*60*60;
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".$dataold."' && `[table]`.`promo`=1)";
	$endq="ORDER BY `data` DESC LIMIT ".$from.",".$limit; $tv=getNewsFromLentas($q, $endq);
	if ((int)$tv["total"]>0) { $text.="<newsblock>"; $onblock=4; for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]); $text.=DrawNewsItem($ar, 0); endfor; $text.="</newsblock>"; }
	return $text;
}

function ShowProAutoBlock($limit=3, $from=0) {
	global $C5; $tv=DB("SELECT `id`,`name`,`pic`,`comcount`,`data`, 'auto' as `link` FROM `auto_lenta` WHERE (`cat`=2 && `stat`=1) ORDER BY `data` DESC LIMIT ".$from.",".$limit); $text.="<newsblock>"; for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]);
	if ($i==0) { $text.="<div class='itemlist'><a href='/$ar[link]/view/$ar[id]' style='font-size:13px; font-weight:bold;'><img src='/userfiles/pictavto/$ar[pic]' style='border:none; width:200px; height:120px;'>".$C5."$ar[name]</a></div>"; } else { $text.=DrawNewsItem($ar); } endfor; $text.="</newsblock>"; return $text;
}

function ShowStreetFashioBlock($limit=3, $from=0) {
	global $C5; $tv=DB("SELECT `id`,`name`,`pic`,`comcount`,`data`, 'news' as `link` FROM `news_lenta` WHERE (`cat`=3 && `stat`=1) ORDER BY `data` DESC LIMIT ".$from.",".$limit); $text.="<newsblock>"; for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]);
	if ($i==0) { $text.="<div class='itemlist'><a href='/$ar[link]/view/$ar[id]' style='font-size:13px; font-weight:bold;'><img src='/userfiles/pictavto/$ar[pic]' style='border:none; width:200px; height:120px;'>".$C5."$ar[name]</a></div>"; } else { $text.=DrawNewsItem($ar); } endfor; $text.="</newsblock>"; return $text;

}

function ShowTopOnForumBlock($limit=3, $from=0) {
	global $C5; $tv=DB("SELECT `id`,`name`,`comcount`,`data`, 'live' as `link` FROM `live_lenta` WHERE (`stat`=1) ORDER BY `data` DESC LIMIT ".$from.",".$limit); $text.="<newsblock>"; for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]); $text.=DrawNewsItem($ar); endfor; $text.="</newsblock>"; return $text;
}

function razdelBrandsBattle() {
	global $C, $C20, $C10, $C25, $VARS; $link="brandsbattle"; $table=$link."_lenta"; $table2="_widget_pics"; $table3="_widget_votes";
	$data=DB("SELECT * FROM `$table` WHERE (`stat`=1) ORDER BY `data` DESC LIMIT 1"); @mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]); $text.="<h3 align='center'>".$item["name"]."</h3>".$C10;
	$data=DB("SELECT `".$table2."`.*, COUNT(`".$table3."`.`id`) as `cnt` FROM `".$table2."` LEFT JOIN `".$table3."` ON `".$table3."`.`vid`=`".$table2."`.`id` WHERE (`".$table2."`.`link`='".$link."' AND `".$table2."`.`pid`=".$item["id"]." AND `".$table2."`.`stat`=1) GROUP BY 1 ORDER BY `".$table2."`.`rate` ASC");
	$items=array(); $horblock=array(); $total=$data["total"]; for ($i=0; $i<$data["total"]; $i++){ @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $items[]=$ar; }
	$path='http://'.trim($Domains[$item["domain"]].'.'.$VARS["mdomain"], '.').'/'.$link.'/view/'.$item['id']; $block=0; $i=1; foreach ($items as $ar) { $horblock[$block]["names"].='<td><strong>'.$ar["name"].'</strong></td>';
	$horblock[$block]["pics"].='<td><div class="votingImg"><a href="/userfiles/picoriginal/'.$ar["pic"].'" title=\''.$ar["name"].'\' rel="prettyPhoto[gallery]"><img title=\''.$ar["name"].'\'  src="/userfiles/picsquare/'.$ar['pic'].'" border="0" /></a></div>';
	$horblock[$block]["pics"].='<div class="votingButton"><strong>Голосов: <span class="votes">'.$ar["cnt"].'</span></strong><br />';
	$horblock[$block]["pics"].='<a href="javascript:void(0);" onclick=\'voteForm('.$ar["id"].', '.$item["id"].', "'.$link.'", "'.Hsc($item["name"]).'", "Я голосую за: '.Hsc($ar["name"]).'", "'.$path.'", "http://'.$VARS["mdomain"].'/userfiles/picpreview/'.$ar['pic'].'")\'>Голосовать</a>';
	$horblock[$block]["pics"].='</div></td>'; if ($i%3==0) { $block++; } $i++; } foreach ($horblock as $block) { $content.="<tr>".$block["names"]."</tr><tr>".$block["pics"]."</tr><tr><td colspan='3'>".$C20.$C10."</td></tr>"; }
	$votingEnd='<div style="text-align:center !important;">До окончания голосования осталось:<span class="digits"></span></div><script>setTimeout(function(){votingCountdown('.$item['votingend'].', '.$item['winnerscount'].', '.$item['id'].')}, 1000);</script>';
	$text.='<div class="votingCon" id="node'.$item['id'].'"><span class="votingEnd" style="text-align:center !important;">'.$votingEnd.'</span>'.$C10.'<div class="voting"><table>'.$content.'</table></div><div class="Info"></div></div>';
	return "<div class='RedBlock2'>".$text.$C10."</div><script src='/modules/tatbrand/tatbrand.js' type='text/javascript'></script><link rel='stylesheet' type='text/css' href='/modules/tatbrand/tatbrand.css' media='all' />";
}

function RightGadgetsInKazan($limit=4) {
	global $C5,$C20; $data=DB("SELECT `id`,`name`,`pic`,`lid`,`data`,`comcount`,'gadgets' as `link` FROM `gadgets_lenta` WHERE (`stat`='1') ORDER BY `data` DESC LIMIT ".$limit); if ($data["total"]>0) {
	$text.="<newsblock>";
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);
		if ($i==0) { $text.="<div class='itemlist'><a href='/$ar[link]/view/$ar[id]' style='font-size:13px; font-weight:bold;'><img src='/userfiles/pictavto/$ar[pic]' style='border:none; width:200px; height:120px;'>".$C5."$ar[name]</a></div>"; } else { $text.=DrawNewsItem($ar); }
	endfor;
	$text.="</newsblock>";
	$text1="<table width='100%' cellpadding='0' cellspacing='0' border='0'>
		<tr><td class='CitiLinkGoods2'><a href='http://www.citilink.ru/catalog/audio_and_digits/tv/769778/' target='_blank' rel='nofollow'><img src='http://www.citilink.ru/_catalog_images/769778_v01_s.jpg' border='0' />LED-телевизор 42'' LG, Full HD</a><b>16 990 p.</b></td></tr>
		<tr><td class='CitiLinkGoods2'><a href='http://www.citilink.ru/catalog/computers_and_notebooks/tablet_pc/871177/' target='_blank' rel='nofollow'><img src='http://www.citilink.ru/_catalog_images/871177_d01_s.jpg' border='0' />SAMSUNG Galaxy Tab 3 Lite SM-T110 8Gb Wi-Fi</a><b>5 990 p.</b></td></tr>
		<tr><td class='CitiLinkGoods2'><a href='http://www.citilink.ru/catalog/computers_and_notebooks/notebooks/835933/' target='_blank' rel='nofollow'><img src='http://www.citilink.ru/_catalog_images/835933_v01_s.jpg' border='0' />Ноутбук ACER TravelMate 15.6''</a><b>16 990 p.</b></td></tr>
		<tr><td class='CitiLinkGoods2'><a href='http://www.citilink.ru/catalog/mobile/cell_phones/757557/' target='_blank' rel='nofollow'><img src='http://www.citilink.ru/_catalog_images/757557_f01_s.jpg' border='0' />Смартфон ASUS Padfone 2 64Gb</a><b>14 990 p.</b></td></tr>
	</table>".$C15;
	/* Импорт XML от Ситилинка */
		if ($_SESSION["userid"]!=100) {
		$text.="<table width='100%' cellpadding='0' cellspacing='0' border='0' id='CitiLinkTable2'><tr>";
		$xml=simplexml_load_file("http://www.citilink.ru/ya/data/prokazan.xml");
		if (!empty($xml)) { $count=count($xml->shop->offers->offer); if ($count>6) { $count=6; }
		$i=0; while ($i<=($count-1)):
			$url=$xml->shop->offers->offer[$i]->url;
			$price=$xml->shop->offers->offer[$i]->price;
			$pic=$xml->shop->offers->offer[$i]->picture;
			$name=$xml->shop->offers->offer[$i]->name;
			$text.="<td class='CitiLinkGoods' style='border:1px dotted #CCC; padding:5px;'><b>  ".$price." p.</b><a href='$url' target='_blank' rel='nofollow' style='font-size:11px;'>
			<div class='imgbox'><img src='$pic' border='0' /></div>
			$name</a></td>";

		$text.="</tr><tr><td style='height:7px;'></td></tr><tr>";
		//if (($i+1)%2==1) { $text.="<td>   </td>"; } else { $text.="</tr><tr>"; }
		$i++; endwhile; }
		$text.="</tr></table>";
	} else {
		$text.=$text1;
	}
	/* Импорт XML от Ситилинка */
	} return $text."<div style='border-bottom:2px solid #5B7FA6;'></div>".$C20;
}

function razdelForumIndex() {
	$data=DB("SELECT `id`,`name`,`comcount` FROM `live_lenta` ORDER BY `data` DESC LIMIT 7"); if ($data["total"]>0) { $text="<h2><a href='/live'>Казанский форум: самое свежее</a></h2><div class='ForumOnMain'><ul>";
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $text.="<li><a href='/live/view/$ar[id]'>$ar[name]</a></li>"; endfor; $text.="</ul></div>"; return $text; }
}

// PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO ===
// PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO ===
// PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO ===
/***** НАСТРОЙКИ ПОРТАЛА ПРОАВТО
if ($SubDomain==1) {

	$Page["AutoSpecial"]="<a href='/be-auto-hero'><img src='/template/proauto/beautohero.png' /></a>";
	$CSSmodules["основной шрифт авто раздела"]="http://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=cyrillic-ext,latin";
	$ProGoogle='<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:300px;height:235px;margin:0 5px;overflow:hidden;" data-ad-client="ca-pub-2073806235209608" data-ad-slot="6939556615"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
	$ProGoogleLong='<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:970px;height:90px" data-ad-client="ca-pub-2073806235209608" data-ad-slot="8617779415"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
	$Page["AutoSearch"]="<div class=\"ya-site-form ya-site-form_inited_no\" onclick=\"return {'bg': 'transparent', 'publicname': 'Yandex Site Search #2091241', 'target': '_self', 'language': 'ru', 'suggest': false, 'tld': 'ru', 'site_suggest': false, 'action': 'http://auto.prokazan.ru/search/', 'webopt': false, 'fontsize': 12, 'arrow': false, 'fg': '#000000', 'searchid': '2091241', 'logo': 'rb', 'websearch': false, 'type': 2}\"><form action=\"http://yandex.ru/sitesearch\" method=\"get\" target=\"_self\"><input type=\"hidden\" name=\"searchid\" value=\"2091241\" /><input type=\"hidden\" name=\"l10n\" value=\"ru\" /><input type=\"hidden\" name=\"reqenc\" value=\"\" /><input type=\"text\" name=\"text\" value=\"\" /><input type=\"submit\" value=\"Найти\" /></form></div><style type=\"text/css\">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type=\"text/javascript\">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;(' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1&&(e.className+=' ya-page_js_yes');s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>";
	$Page["AutoGroups"]="<div><div class='MainColumn' style='border:1px solid #e8e8e8; border-radius:5px; width:308px;'><script type='text/javascript' src='//vk.com/js/api/openapi.js?101'></script><div id='vk_pro_groups'></div><script type='text/javascript'>VK.Widgets.Group('vk_pro_groups', {mode: 0, width: '308', height: '320', color1: 'FFFFFF', color2: 'e70000', color3: '999999'}, 54815659);</script></div><div class='MainColumn' style='border:1px solid #e8e8e8; border-radius:5px; width:308px;'><iframe src='//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fautokazan&amp;width=310&amp;height=208&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:310px; height:320px;' allowTransparency='false'></iframe></div><div class='MainColumn' style='width:308px;'><a class='twitter-timeline' href='https://twitter.com/autokazan1' data-widget-id='398008256158642176'>Твиты @AutoKazan</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script></div>
	<div class='MainColumn OnlyBig' style='border:1px solid #e8e8e8; border-radius:5px; width:308px;'>


	</div></div>";
	$ProLikes="<div class='ProLikes'><script>(function(d,c){ var up=d.createElement('script'), s=d.getElementsByTagName('script')[0], r=Math.floor(Math.random() * 1000000); var cmp = c + Math.floor(Math.random() * 10000); d.write(\"<div id='\"+cmp+\"' class='__uptlk' data-uptlkwdgtId='\"+r+\"'></div>\"); up.type = 'text/javascript'; up.async = true; up.src=\"//w.uptolike.com/widgets/v1/widgets.js?b=fb.vk.tw.mr.ok&id=37843&o=1&m=1&sf=2&ss=2&sst=9&c1=ededed&c1a=0.0&c3=ff9300&mc=1&c2=000000&c4=ffffff&c4a=1.0&mcs=1&sel=1&fol=1&fl.fb=autokazan&fl.vk=proautokzn&fl.tw=autokazan1&c=\"+cmp; s.parentNode.insertBefore(up, s);})(document,\"__uptlk\");</script>".$C."</div>";
}
*****/
$tab="auto";
function Get_Pro_News_Block($array, $limit=4, $from=0) {
	global $tab, $bannersq, $C, $C5, $C10, $C15, $C20, $C25; $text=''; $cats=implode(',', $array); $image_folder="picintv";
	// ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----
		$data=DB("SELECT `".$tab."_lenta`.`id`,`".$tab."_lenta`.`gis`, `".$tab."_lenta`.`name`, `".$tab."_lenta`.`pic`, `".$tab."_lenta`.`cat`, `".$tab."_lenta`.`data`, `".$tab."_cats`.`name` as `ncat` FROM `".$tab."_lenta`
		LEFT JOIN `".$tab."_cats` ON `".$tab."_cats`.`id`=`".$tab."_lenta`.`cat` WHERE (`".$tab."_lenta`.`stat`='1' AND `".$tab."_lenta`.`cat` IN (".$cats.")) GROUP BY 1 ORDER BY `".$tab."_lenta`.`data` DESC LIMIT ".$from.",".($limit*4));
		if ($data["total"]>0) { $text.="<div class='MainColumn'>"; } $column=1;
		for($i=0; $i<$data["total"]; $i++):
			@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]);
			if ($column==2 && ($i+1-($column-1)*$limit)==2) { if ($bannersq==0) { $text.="<div id='Banner-20-1' class='Auto-Sq-Banner'></div>"; } else { $text.="<div id='Banner-21-".$bannersq."' class='Auto-Sq-Banner'></div>"; } $bannersq++; }
			if ((int)$ar["gis"]==1) { $glavnayapic="<div class='GlavnayaTema'></div>"; $name="<a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'><b>".$ar["name"]."</b></a>"; } else { 	$glavnayapic=""; $name="<a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'>".$ar["name"]."</a>"; }

			$text.="<div class='ProAuto-Lenta'>"; $pic="<div class='NewsProBigPic'><a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'><img src='/userfiles/".$image_folder."/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' />".$glavnayapic."</a></div>".$C5;
				if (($i%$limit)==0) { $text.="<div class='CategoryBig'><a href='/".$tab."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a> ".$d[4]."</div>".$C5.$pic.$name;
				} else { $text.="<div class='CategorySmall'><a href='/".$tab."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a> ".$d[4]."</div>".$C5;
					if ($column==1 && ($i+1-($column-1)*$limit)==4) { $text.=$pic.$C; }
					if ($column==3 && ($i+1-($column-1)*$limit)==3) { $text.=$pic.$C; }
					if ($column==4 && ($i+1-($column-1)*$limit)==4) { $text.=$pic.$C; }
				$text.=$name; }
			$text.="</div>"."<div class='Block-Line'></div>"; if (($i+1)%$limit==0 && $column!=4) { if ($column!=3) { $text.="</div><div class='MainColumn'>"; } else { $text.="</div><div class='MainColumn OnlyBig'>"; } $column++; }
		endfor; if ($data["total"]>0) { $text.=$C."</div>".$C; }
	// ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----
	return Replace_Data_Days($text);
}

// ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- -----------

function Get_Pro_Test_Drive($array, $limit=3) { global $tab, $bannersq, $C, $C5, $C10, $C15, $C20, $C25; $text=''; $cats=implode(',', $array); $image_folder="picintv"; $image_folder2="picsquare";
	// ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----
	$data=DB("SELECT `".$tab."_lenta`.`id`, `".$tab."_lenta`.`name`, `".$tab."_lenta`.`lid`, `".$tab."_lenta`.`pic`, `".$tab."_lenta`.`cat`, `".$tab."_lenta`.`data`, `".$tab."_cats`.`name` as `ncat` FROM `".$tab."_lenta`
	LEFT JOIN `".$tab."_cats` ON `".$tab."_cats`.`id`=`".$tab."_lenta`.`cat` WHERE (`".$tab."_lenta`.`stat`='1' AND `".$tab."_lenta`.`cat` IN (".$cats.")) GROUP BY 1 ORDER BY `".$tab."_lenta`.`data` DESC LIMIT ".$limit);
	for($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic=''; $pics='';
		if ($i==0) { $data2=DB("SELECT `pic` FROM `_widget_pics` WHERE (`pid`='".$ar["id"]."' && `link`='".$tab."' && `stat`=1) LIMIT 5"); for($j=0; $j<$data2["total"]; $j++): @mysql_data_seek($data2["result"], $j);
			$ap=@mysql_fetch_array($data2["result"]); $pics.="<a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'><img src='/userfiles/".$image_folder2."/".$ap["pic"]."' class='smallpic' /></a>"; endfor;
			$pic="<a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'><img src='/userfiles/".$image_folder."/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' class='bigpic' /></a>";
			$cat="<div class='name'><a href='/".$tab."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a></div>"; $text.="<div class='TestDrive'><div class='images'>".$cat.$pic.$C10.$pics."</div>";
			$text.="<div class='text'><h2><a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'>".$ar["name"]."</a></h2>".$C5.$ar["lid"].$C10."<div class='CategorySmall'>".$d[4]."</div></div>"; $text.="</div>";
		} else {
			if ($i==1) { $text.="<div class='TestDriveAnons'>"; } $pic="<a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'><img src='/userfiles/".$image_folder."/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' /></a>";
			$text.="<div class='item'>".$pic."<a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'>".$ar["name"]."</a><div class='CategorySmall'>".$d[4]."</div>".$C."</div>"; if ($i%2==0 && $i!=($data["total"]-1) && $i!=0) { $text.=$C."<div class='Block-Line'></div>"; } if ($i==($data["total"]-1)) { $text.=$C."</div>"; }
		}
	endfor;
	// ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----
	return Replace_Data_Days($text);
}

// ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- -----------

function Get_Pro_Blog_Block($users, $cat=7) { global $tab, $bannersq, $C, $C5, $C10, $C15, $C20, $C25; $text=''; $users=implode(',', $users); $image_folder="avatar";
	// ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----
	$data=DB("SELECT `".$tab."_lenta`.`id`, `".$tab."_lenta`.`uid` as `uid`, `".$tab."_lenta`.`name`, `".$tab."_lenta`.`cat`, `".$tab."_lenta`.`data`, `_users`.`nick`, `_users`.`avatar` FROM `".$tab."_lenta`
	LEFT JOIN `_users` ON `".$tab."_lenta`.`uid`=`_users`.`id` WHERE (`".$tab."_lenta`.`stat`='1' AND `".$tab."_lenta`.`cat`=".$cat.") GROUP BY `id`  ORDER BY `".$tab."_lenta`.`data` DESC LIMIT 3");
	if ($data["total"]>0) { $text.="<div><div class='MainColumn'><div class='BlogItem'><img src='/userfiles/avatar/img-20130320100404-521.jpg' />"; $ar["name"]="Отвечает Михаил Савин, заместитель командира полка ДПС ГИБДД Казани";
	$text.="<div class='name'><a href='/companies/consult/69' title='".$ar["name"]."'>Консультация ГИБДД онлайн</a></div><div class='text'><a href='/companies/consult/69' title='".$ar["name"]."'>".$ar["name"]."</a></div>".$C."</div>";
	$text.="</div><div class='MainColumn'>"; } $column=2;
	for($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic='';
			$pic="<a href='/users/blogger/".$ar["uid"]."' title='".$ar["name"]."'><img src='/".$ar["avatar"]."' /></a>"; $text.="<div class='BlogItem'>".$pic;
			$text.="<div class='name'><a href='/users/blogger/".$ar["uid"]."' title='".$ar["name"]."'>".$ar["nick"]."</a></div>";
			$text.="<div class='text'><a href='/".$tab."/view/".$ar["id"]."' title='".$ar["name"]."'>".$ar["name"]."</a></div>"; $text.=$C."</div>";
		if (($i+1)%$limit==0 && $column!=4) { if ($column!=3) { $text.="</div><div class='MainColumn'>"; } else { $text.="</div><div class='MainColumn OnlyBig'>"; } $column++; }
	endfor; if ($data["total"]>0) { $text.=$C."</div></div>".$C5; }
	// ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----
	return Replace_Data_Days($text);
}

// ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- -----------

function MostProDiscussed($limit=30) { global $JSmodules, $CSSmodules, $SubDomain, $GLOBAL; $file="agregator-".$SubDomain."_promostdiscussed"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=CreateMostProDiscussed($limit); SetCache($file, $text, ""); } return $text; }
function CreateMostProDiscussed($limit){ global $VARS, $GLOBAL, $Domains, $C, $C15, $C30; $query=''; $table="auto"; $text=''; $twoWeeksAgo=strtotime(date('Y-m-d H:i'))-(60*60*24*21);
	$data=DB("SELECT `".$table."_lenta`.`id`,`".$table."_lenta`.`name`, `".$table."_lenta`.`cat`, `".$table."_lenta`.`comcount`, `".$table."_cats`.`name` as `catname`
	FROM `".$table."_lenta` LEFT JOIN `".$table."_cats` ON `".$table."_lenta`.`cat`=`".$table."_cats`.`id` WHERE (`".$table."_lenta`.`stat`='1' && `".$table."_lenta`.`data`>$twoWeeksAgo) GROUP BY 1 ORDER BY `".$table."_lenta`.`comcount` DESC LIMIT $limit");
	if ($data["total"]>0) { $text.='<div class="ProNewsBlock"><div class="scrollbarY"><div class="scrollbar"><div class="track"><div class="thumb"></div></div></div><div class="NewsBlockItems viewport"><div class="overview">';
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $path='/'.$table.'/view/'.$ar["id"]; $text.='<a href="'.$path.'">'.$ar["name"].'</a>
	<div class="CategorySmall"><a href="/'.$table.'/cat/'.$ar["cat"].'">'.$ar["catname"].'</a> Комментарии: '.$ar["comcount"].'</div><div class="Block-Line"></div>';
	} $text.='<div class="C25"></div></div></div></div></div>'; } return (array($text, ""));
}


// ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- -----------

function MostProNewest($limit=5) { global $JSmodules, $CSSmodules, $SubDomain, $GLOBAL; $file="agregator-".$SubDomain."_promostNewest"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=CreateMostProNewest($limit); SetCache($file, $text, ""); } return $text; }
function CreateMostProNewest($limit){ global $VARS, $GLOBAL, $Domains, $C, $C15, $C30; $query=''; $table="auto"; $text='';
	$data=DB("SELECT `".$table."_lenta`.`id`,`".$table."_lenta`.`pic`,`".$table."_lenta`.`name`,`".$table."_lenta`.`data`, `".$table."_lenta`.`cat`, `".$table."_lenta`.`comcount`, `".$table."_cats`.`name` as `catname`
	FROM `".$table."_lenta` LEFT JOIN `".$table."_cats` ON `".$table."_lenta`.`cat`=`".$table."_cats`.`id` WHERE (`".$table."_lenta`.`stat`='1') GROUP BY 1 ORDER BY `".$table."_lenta`.`data` DESC LIMIT $limit");
	if ($data["total"]>0) { $text.='<div>';	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $path='/'.$table.'/view/'.$ar["id"]; $d=ToRusData($ar["data"]); $cap=$ar["catname"];
	if ($i==0) { $pic="<a href='".$path."'><img src='/userfiles/picintv/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' /></a>"; $text.='<item class="KazanFirst">'.$pic.'<a href="'.$path.'" class="FirstLink">'.$ar["name"].'</a><div class="CategorySmall FirstData">'.$ar["catname"].' : '.$d[4].'</div></item><div class="Block-Line"></div>';
	} else { $text.='<item><a href="'.$path.'">'.$ar["name"].'</a><div class="CategorySmall"><a href="/'.$table.'/cat/'.$ar["cat"].'">'.$ar["catname"].'</a> '.$d[4].'</div></item><div class="Block-Line"></div>'; }
	} $text.='</div>'; } return (array(Replace_Data_Days($text), $cap));
}

// ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- -----------

function ProNewestCat($CAT=0, $limit=5) { global $JSmodules, $CSSmodules, $SubDomain, $GLOBAL; $file="agregator-".$SubDomain."_promostNewest".$CAT; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=CreateProNewestCat($limit); SetCache($file, $text, ""); } return $text; }
function CreateProNewestCat($CAT="0", $limit=5){ global $VARS, $GLOBAL, $Domains, $C, $C15, $C30; $query=''; $table="auto"; $text=''; $catin=""; if ($CAT!="" && $CAT!=0) { $catin=" && `".$table."_lenta`.`cat` IN (0,".$CAT.")"; }
	$data=DB("SELECT `".$table."_lenta`.`id`,`".$table."_lenta`.`pic`,`".$table."_lenta`.`name`,`".$table."_lenta`.`data`, `".$table."_lenta`.`cat`, `".$table."_lenta`.`comcount`, `".$table."_cats`.`name` as `catname`
	FROM `".$table."_lenta` LEFT JOIN `".$table."_cats` ON `".$table."_lenta`.`cat`=`".$table."_cats`.`id` WHERE (`".$table."_lenta`.`stat`='1' ".$catin.") GROUP BY 1 ORDER BY `".$table."_lenta`.`data` DESC LIMIT $limit");
	if ($data["total"]>0) { $text.='<div>'; for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $path='/'.$table.'/view/'.$ar["id"]; $d=ToRusData($ar["data"]); $cap=$ar["catname"];
	if ($i==0) { $pic="<a href='".$path."'><img src='/userfiles/picintv/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' /></a>"; $text.='<item class="KazanFirst">'.$pic.'<a href="'.$path.'" class="FirstLink">'.$ar["name"].'</a><div class="CategorySmall FirstData">'.$ar["catname"].' : '.$d[4].'</div></item><div class="Block-Line"></div>';
	} else { $text.='<item><a href="'.$path.'">'.$ar["name"].'</a><div class="CategorySmall"><a href="/'.$table.'/cat/'.$ar["cat"].'">'.$ar["catname"].'</a> '.$d[4].'</div></item><div class="Block-Line"></div>';	}
	} $text.='</div>'; } return (array(Replace_Data_Days($text), $cap));
}

// ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- ----------- -----------

function ForumProDiscussed($limit=30) { global $JSmodules, $CSSmodules, $SubDomain, $GLOBAL; $file="agregator-".$SubDomain."_proforumdiscussed"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=CreateForumProDiscussed($limit); SetCache($file, $text, ""); } return $text; }
function CreateForumProDiscussed($limit){ global $VARS, $GLOBAL, $Domains, $C, $C15, $C30; $query=''; $table="live"; $text=''; $twoWeeksAgo=strtotime(date('Y-m-d H:i'))-(60*60*24*60);
	$q="SELECT `".$table."_lenta`.`id`,`".$table."_lenta`.`name`, `".$table."_lenta`.`cid`, `".$table."_lenta`.`comcount`, `".$table."_cat`.`name` as `catname`
	FROM `".$table."_lenta` LEFT JOIN `".$table."_cat` ON `".$table."_lenta`.`cid`=`".$table."_cat`.`id`
	WHERE (`".$table."_lenta`.`stat`='1' && `".$table."_lenta`.`fid`=7 && `".$table."_lenta`.`data`>$twoWeeksAgo) GROUP BY 1 ORDER BY `".$table."_lenta`.`update` DESC LIMIT $limit"; $data=DB($q);
	if ($data["total"]>0) { $text.='<div class="ProNewsBlock"><div class="scrollbarY"><div class="scrollbar"><div class="track"><div class="thumb"></div></div></div><div class="NewsBlockItems viewport"><div class="overview">';
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $path='/'.$table.'/view/'.$ar["id"]; $text.='<a href="'.$path.'">'.$ar["name"].'</a>
	<div class="CategorySmall"><a href="/'.$table.'/cat/'.$ar["cat"].'">'.$ar["catname"].'</a> Комментарии: '.$ar["comcount"].'</div><div class="Block-Line"></div>';
	} $text.='<div class="C25"></div></div></div></div></div>'; } return (array($text, ""));
}
// PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO ===
// PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO ===
// PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO === PROAUTO ===
