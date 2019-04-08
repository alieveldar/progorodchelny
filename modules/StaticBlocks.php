<?
# ПРАВЫЙ БЛОК # Переменная $Page["RightContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/right_block/[поддомен].php берем его, иначе берем дефолтный правый блок /modules/page_mods/right_block/default.php
//echo "aaa";exit;
if ($link=="") {

} else {
	if (is_file("modules/page_mods/right_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/right_block/new-".$Domains[$SubDomain].".php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: правый блок &laquo;modules/page_mods/right_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/right_block/default.php")) {
		@require("modules/page_mods/right_block/default.php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: правый блок &laquo;modules/page_mods/right_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: правый блок не подключен<hr>"; }
}

# ЛЕВЫЙ БЛОК - СТАРТ # $Page["LeftContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/left_block/[поддомен].php берем его, иначе берем дефолтный левый блок /modules/page_mods/left_block/default.php
if ($link=="") {

} else {
	if (is_file("modules/page_mods/left_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/left_block/new-".$Domains[$SubDomain].".php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: левый блок &laquo;modules/page_mods/left_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/left_block/default.php")) {
		@require("modules/page_mods/left_block/default.php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: левый блок &laquo;modules/page_mods/left_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: левый блок не подключен<hr>"; }
}

# ВЕРХНИЙ БЛОК # Переменная $Page["TopContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/top_block/[поддомен].php берем его, иначе берем дефолтный правый блок /modules/page_mods/top_block/default.php
if ($link=="ls" || $Domains[$SubDomain]=="7kazan") {

} else {
	if (is_file("modules/page_mods/top_block/new-".$Domains[$SubDomain].".php")) {

		@require("modules/page_mods/top_block/new-".$Domains[$SubDomain].".php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: верхний блок &laquo;modules/page_mods/top_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/top_block/default.php")) {
//	  echo "aaa";exit;
		@require("modules/page_mods/top_block/default.php");
//			    echo "aaa";exit;
		$GLOBAL["log"].="<i>Подключение PHP</i>: верхний блок &laquo;modules/page_mods/top_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: верхний блок не подключен<hr>"; }
}


# НИЖНИЙ БЛОК # Переменная $Page["BottomContent"] может быть определена в запрашиваемых файлах
# Если определен файл в папке /modules/page_mods/bottom_block/[поддомен].php берем его, иначе берем дефолтный правый блок /modules/page_mods/bottom_block/default.php
	if (is_file("modules/page_mods/bottom_block/new-".$Domains[$SubDomain].".php")) {
		@require("modules/page_mods/bottom_block/new-".$Domains[$SubDomain].".php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: нижний блок &laquo;modules/page_mods/bottom_block/new-".$Domains[$SubDomain].".php&raquo; подключен<hr>";
	} elseif (is_file("modules/page_mods/bottom_block/default.php")) {
		@require("modules/page_mods/bottom_block/default.php");
		$GLOBAL["log"].="<i>Подключение PHP</i>: нижний блок &laquo;modules/page_mods/bottom_block/default.php&raquo; подключен<hr>";
	} else { $GLOBAL["log"].="<u>Подключение PHP</u>: нижний блок не подключен<hr>"; }

function Dater($ar, $float="left", $view="comcount") {
	if ($view=="comcount") { $coms=""; if($ar["comcount"]!=0){ $coms="<i title='Посмотреть комментарии'>".$ar["comcount"]."</i>"; }}
	if ($view=="seens") { $coms=""; if($ar["seens"]!=0){ $coms="<s title='Количество просмотров статьи'>".$ar["seens"]."</s>"; }}
	if ($view=="likes") { $coms=""; if($ar["likes"]!=0){ $coms="<u title='Количество положительных отзывов'>".$ar["likes"]."</u>"; }}
	$data=""; if ($ar["data"]!="") { $d=ToRusData($ar["data"]); $data="<b>".$d[10]."</b>"; }
	if($coms!="" || $data!="") { $text="<div class='dater' style='float:".$float."'><a href='/".$ar["link"]."/view/".$ar["id"]."#comments'>".$data.$coms."</a></div>"; }
return str_replace("//", "/", $text); }

function FullDater($ar) {
	$coms1=""; if($ar["comcount"]!=0){ $coms1="<i title='Посмотреть комментарии'>".$ar["comcount"]."</i>"; }
	$coms2=""; if($ar["seens"]!=0){ $coms2="<s title='Количество просмотров статьи'>".$ar["seens"]."</s>"; }
	$coms3=""; if($ar["likes"]!=0){ $coms3="<u title='Количество положительных отзывов'>".$ar["likes"]."</u>"; }
	$data=""; if ($ar["data"]!="") { $d=ToRusData($ar["data"]); $data="<b>".$d[10]."      </b>"; }
	$text="<div class='dater'><a href='/".$ar["link"]."/view/".$ar["id"]."#comments'>".$data.$coms2.$coms3.$coms1."</a></div>";
return str_replace("//", "/", $text); }

function ToLocalDay($data) { return(str_replace(array(date("d.m.Y"), date("d.m.Y", time()-60*60*24)), array("Сегодня", "Вчера"), $data)); }
function ArraySort($a, $b){ if ($a["data"] == $b["data"]){ return 0; } return ($a["data"] > $b["data"]) ? -1 : 1; }

# ПОИСК ВСЕХ ТАБЛИЦ С НОВОСТЯМИ
function getLentasOnModules() { global $lentas; if (!is_array($lentas) || sizeof($lentas)==0) { $modules=array("lenta","concurs"); $notin=array("vtorzhilio","world","uncensored","realestatenews","gadgets");
$q="SELECT `link` FROM `_pages` WHERE (`module` IN ('".implode("','", $modules)."') && `link` NOT IN ('".implode("','", $notin)."')) LIMIT 50"; $data=DB($q);
for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $lentas[$ar["link"]]=$ar["link"]."_lenta"; }}return $lentas; }

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
        if( ! is_array( $used[ $l ] ) ) {
            $used[ $l ] = [];
        }
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

function LSgetNewsFromLentas($q='',$endq='') { global $used; $LSlentas=LSgetLentasOnModules(); foreach ($LSlentas as $l=>$t) { $usedtext=""; if (sizeof($used[$l])>0) { $usedtext=" && `".$t."`.`id` NOT IN (0, ".implode(",", $used[$l]).")"; } // не включаем в выборку ранее взятые новости
		 $qitem="(".str_replace(array("[table]","[link]"),array($t, $l),$q).") UNION "; $qitem=str_replace("[used]", $usedtext, $qitem); $query.=$qitem; } $query=trim($query, "UNION ").' '.$endq; $data=DB($query); return $data; } // заменяем таблицу и ссылку на нужное и формируем запрос

function LSgetLentasOnModules() { global $LSlentas; if (sizeof($LSlentas)==0) { $modules=array("lenta", "concurs", "tatbrand"); $isin=array("ls"); $notin=array("vtorzhilio");
	$q="SELECT `link` FROM `_pages` WHERE (`module` IN ('".implode("','", $modules)."') && `link` NOT IN ('".implode("','", $notin)."')  && `link` IN ('".implode("','", $isin)."')) LIMIT 50"; $data=DB($q);
		 for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $LSlentas[$ar["link"]]=$ar["link"]."_lenta"; }} return $LSlentas;
}

function getMaxComments($limit=7) {
	global $C, $C20; $lentas=getLentasOnModules(); $dataold=time()-7*24*60*60; $q="SELECT `[table]`.`id`, `[table]`.`tags`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".$dataold."')";
	$endq="ORDER BY `comcount` DESC LIMIT ".$limit; $tv=getNewsFromLentas($q, $endq); if ((int)$tv["total"]>0) { for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]);
	if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
	$text.="<div class='ONew'><a href='/".$ar["link"]."/view/".$ar["id"]."' $rel>".$ar["name"].specIconOnTags($ar)."</a>".$C.Dater($ar)."</div>".$C20; endfor; } return $text;
}

function getMaxSeens($limit=7) {
	global $C, $C20; $lentas=getLentasOnModules(); $dataold=time()-7*24*60*60; $q="SELECT `[table]`.`id`,`[table]`.`tags`,`[table]`.`seens`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".$dataold."')";
	$endq="ORDER BY `seens` DESC LIMIT ".$limit; $tv=getNewsFromLentas($q, $endq); if ((int)$tv["total"]>0) { for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]);
	if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
	$text.="<div class='ONew'><a href='/".$ar["link"]."/view/".$ar["id"]."' $rel>".$ar["name"].specIconOnTags($ar)."</a>".$C.Dater($ar,"","seens")."</div>".$C20; endfor; } return $text;
}

function getMaxLikes($limit=7) {
	global $C, $C20; $lentas=getLentasOnModules(); $dataold=time()-7*24*60*60; $q="SELECT `[table]`.`id`,`[table]`.`tags`, `[table]`.`likes`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".$dataold."')";
	$endq="ORDER BY `likes` DESC LIMIT ".$limit; $tv=getNewsFromLentas($q, $endq); if ((int)$tv["total"]>0) { for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]);
	if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
	$text.="<div class='ONew'><a href='/".$ar["link"]."/view/".$ar["id"]."' $rel>".$ar["name"].specIconOnTags($ar)."</a>".$C.Dater($ar,"","likes")."</div>".$C20; endfor; } return $text;
}

function specIconOnTags($ar) {
	$tags=explode(",", trim($ar["tags"],",")); $text="";
	if (in_array(93, $tags)) { $text.="<i class='fa fa-picture-o' title='фото'></i>"; }
	if (in_array(143, $tags)) { $text.="<i class='fa fa-youtube-play' title='видео'></i>"; }
	if (in_array(168, $tags)) { $text.="<i class='fa fa-volume-up' title='мультимедиа'></i>"; }
	if (in_array(9999, $tags)) { $text.="<i class='fa fa-registered'></i>"; }
	return $text;
}



function razdelAfishaKazan() {
	$data=DB("SELECT `id`,`name`,`pic`,`lid`,`data`,`comcount`,'afisha' as `link` FROM `afisha_lenta` WHERE (`stat`='1') ORDER BY `data` DESC LIMIT 4");
	if ($data["total"]>0) { $text="<h2><a href='/afisha'>Афиша Казани</a></h2><div class='RedBlock'>";
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]);
	if ($i==0) { $text.="<div class='FirstNews'><a href='/$ar[link]/view/$ar[id]'><img src='/userfiles/picnews/$ar[pic]' /><div class='Cap'>$ar[name]</h3></div></a><div class='Lid'>".CutText($ar["lid"], 100)."</div><div class='Data'>".ToLocalDay($d[4])."</div></div>";
	} else { $text.="<div class='OtherNewsItem'><a href='/$ar[link]/view/$ar[id]'><img src='/userfiles/pictavto/$ar[pic]' /><div class='Cap'>$ar[name]</h3></div></a><div class='Data'>".ToLocalDay($d[4])."</div></div>"; }
	endfor; $text.="<a href='/".$ar["link"]."' class='ReadMoreLink'>Читать больше »</a>"; $text.="</div>"; return $text; }
}

function DrawNewsItem($ar, $datas='', $class='') {
	$pic=""; $text=""; if ($datas=='') { $data=ToRusData($ar["data"]); } else { $data=ToRusData($datas); } $pic="<img src='/userfiles/picsquare/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' />";
	$text.="<div class='itemlist ".$class."'><data>".ToLocalDay($data[4])."</data><a href='/".$ar["link"]."/view/".$ar["id"]."'>"; if ($ar["pic"]!="") { $text.=$pic; }
	$text.=$ar["name"]."</a>"; if ((int)$ar["comcount"]!=0) { $text.=" <span class='ComCnt' title='Комментарии'>".$ar["comcount"]."</span>"; } $text.="</div>"; return $text;
}

function DrawBubrItem($ar) {
	if ($ar["name"]!="") { $pic=""; $text=""; $pic="<img src='".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' />";
	$text.="<noindex><div class='itemlist ".$class."'><a href='".$ar["link"]."' rel='nofollow'>"; if ($ar["pic"]!="") { $text.=$pic; } $text.=$ar["name"]."</a>"; $text.="</div></noindex>"; return $text; }
}

function DrawNewsComrs($ar, $datas='', $class='') {
	$pic=""; $text=""; $pic="<img src='/userfiles/picsquare/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' />"; $text.="<div class='itemlist ".$class."'><a href='/".$ar["link"]."/view/".$ar["id"]."'>"; if ($ar["pic"]!="") { $text.=$pic; } $text.=$ar["name"]."</a></div>"; return $text;
}



function TheNewestInKazan($limit=5) {
	$lentas=getLentasOnModules(); global $GLOBAL;
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1)";
	$endq="ORDER BY `data` DESC LIMIT 4"; $tv=getNewsFromLentas($q, $endq); $text.="<newsblock>"; $onblock=4;

	for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]);

	$text.=DrawNewsItem($ar, 0);
	endfor; $text.="</newsblock>"; return $text;
}

function LSTheNewestInKazan($limit=5) {
	$lentas=LSgetLentasOnModules(); $q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1)";
	$endq="ORDER BY `data` DESC LIMIT ".$limit; $tv=LSgetNewsFromLentas($q, $endq); $text.="<newsblock>"; $onblock=4;
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


function ShowUspehBlock() {
	$data=DB("SELECT `id`,`name`,`pic`,`lid`,`data`,`comcount`,'business' as `link` FROM `business_lenta` WHERE (`stat`='1' && `cat`=5) ORDER BY `data` DESC LIMIT 4");
	if ($data["total"]>0) { $text="<h2><a href='/business/cat/5'>Школа успеха</a></h2><div class='RedBlock'>";
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]);
	if ($i==0) { $text.="<div class='FirstNews'><a href='/$ar[link]/view/$ar[id]'><img src='/userfiles/picnews/$ar[pic]' /><div class='Cap'>$ar[name]</h3></div></a><div class='Lid'>".CutText($ar["lid"], 100)."</div><div class='Data'>".ToLocalDay($d[4])."</div></div>";
	} else { $text.="<div class='OtherNewsItem'><a href='/$ar[link]/view/$ar[id]'><img src='/userfiles/pictavto/$ar[pic]' /><div class='Cap'>$ar[name]</h3></div></a><div class='Data'>".ToLocalDay($d[4])."</div></div>"; }
	endfor; $text.="<a href='/".$ar["link"]."/cat/5' class='ReadMoreLink'>Читать больше »</a>"; $text.="</div>"; return $text; }
}

function razdelZelen() {
	global $C5;	$table = array(); #tag 163
	$lentas=getLentasOnModules(); $q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`tags` LIKE '%,163,%')";
	$endq="ORDER BY `data` DESC LIMIT 4"; $tv=getNewsFromLentas($q, $endq); for ($i=0; $i<$tv["total"]; $i++): @mysql_data_seek($tv["result"], $i); $ar=@mysql_fetch_array($tv["result"]); $table[]=$ar; endfor;
	$text.="<h2><a href='/tags/163'>Зеленодольск</a></h2><div class='RedBlock'>"; $incount=1; foreach ($table as $ar): $d=ToRusData($ar["data"]);
		if ($incount==1) { $text.="<div class='FirstNews'><a href='/$ar[link]/view/$ar[id]'><img src='/userfiles/picnews/$ar[pic]' /><div class='Cap'>$ar[name]</h3></div></a><div class='Lid'>".CutText($ar["lid"], 100)."</div><div class='Data'>".ToLocalDay($d[4])."</div></div>";
		} else { $text.="<div class='OtherNewsItem'><a href='/$ar[link]/view/$ar[id]'><img src='/userfiles/pictavto/$ar[pic]' /><div class='Cap'>$ar[name]</h3></div></a><div class='Data'>".ToLocalDay($d[4])."</div></div>"; }
	$incount++; endforeach; $text.="<a href='/tags/163' class='ReadMoreLink'>Читать больше »</a>"; $text.="</div>"; return $text;
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
	$path='http://'.trim($Domains[$item["domain"]].'.'.$VARS["mdomain"], '.').'/'.$link.'/view/'.$item['id']; $block=0; $i=1; foreach ($items as $ar) { $horblock[$block]["names"].='<td>'.$ar["name"].'</td>';
	$horblock[$block]["pics"].='<td><div class="votingImg"><a href="/userfiles/picoriginal/'.$ar["pic"].'" title=\''.$ar["name"].'\' rel="prettyPhoto[gallery]"><img title=\''.$ar["name"].'\'  src="/userfiles/picsquare/'.$ar['pic'].'" border="0" /></a></div>';
	$horblock[$block]["pics"].='<div class="votingButton" style="color:#0082c5;">Голосов: <span class="votes">'.$ar["cnt"].'</span><br />';
	$horblock[$block]["pics"].='<a href="javascript:void(0);" rel="nofollow" onclick=\'voteForm('.$ar["id"].', '.$item["id"].', "'.$link.'", "'.Hsc($item["name"]).'", "Я голосую за: '.Hsc($ar["name"]).'", "'.$path.'", "http://'.$VARS["mdomain"].'/userfiles/picpreview/'.$ar['pic'].'")\'>Голосовать</a>';
	$horblock[$block]["pics"].='</div></td>'; if ($i%3==0) { $block++; } $i++; } foreach ($horblock as $block) { $content.="<tr>".$block["names"]."</tr><tr>".$block["pics"]."</tr><tr><td colspan='3'>".$C20.$C10."</td></tr>"; }
	$votingEnd='<div style="text-align:center !important;">До окончания голосования осталось:<span class="digits"></span></div><script>setTimeout(function(){votingCountdown('.$item['votingend'].', '.$item['winnerscount'].', '.$item['id'].')}, 1000);</script>';
	$text.='<div class="votingCon" id="node'.$item['id'].'"><span class="votingEnd" style="text-align:center !important;">'.$votingEnd.'</span>'.$C10.'<div class="voting"><table>'.$content.'</table></div><div class="Info"></div></div>';
	return "<div class='RedBlock2'>".$text.$C10."</div><script src='/modules/tatbrand/tatbrand.js' type='text/javascript'></script><link rel='stylesheet' type='text/css' href='/modules/tatbrand/tatbrand.css' media='all' />";
}

// SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA === SEMYA

function Semya3td($ar, $linked, $flag=0) { global $C;
	$data=ToRusData($ar["data"]); $coms=""; if ((int)$ar["comcount"]!=0) { $coms="<span class='Coms7ya'>".(int)$ar["comcount"]."</span>"; }
	if ($ar['cat'] == 7) $linked = 'afisha';
	$text.="<div class='sem3td'><a href='/".$linked."/view/".$ar["id"]."'>"; $text.="<img src='/userfiles/picintv/".$ar["pic"]."' />".$ar["name"].$coms;
	if ($flag==1) { $text.="<div class='flag'><a href='/".$linked."/cat/".$ar["cat"]."'>".$ar["cname"]."</a></div>"; } $text.="</a></div>";	return($text);
}

function Semya3look($ar, $linked) { global $C; $data=ToRusData($ar["data"]); $text.="<div class='sem3td'><a href='/".$linked."/view/".$ar["id"]."'>"; $text.="<img src='/userfiles/lookbook/".$ar["pic"]."' />".$ar["name"]."</a></div>"; return($text); }
?>