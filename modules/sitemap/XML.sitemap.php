<?
if ($GLOBAL["sitekey"]!=1) {
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	$GLOBAL["sitekey"] = 1; $now=time();
	@require_once($ROOT."/modules/standart/DataBase.php");	
}

### Запрашиваемый файл должен определять переменную $text 
$total=0;
$text='<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>http://'.$GLOBAL["host"].'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
<url><loc>http://'.$GLOBAL["host"].'/best/</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
<url><loc>http://'.$GLOBAL["host"].'/blogs/</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
';

/* Субдомены сайта */
$d=DB("SELECT `id`,`prefix` FROM `_domains`"); for($i=0; $i<$d["total"]; $i++): @mysql_data_seek($d["result"],$i); $ar=@mysql_fetch_array($d["result"]); $doms[$ar["id"]]=$ar["prefix"]; endfor;

/* Все активные меню сайта */
$datat=DB("SELECT `link`, `data` FROM `_menuitem` WHERE (`stat`='1') order by `pid` asc, `rate` desc"); $total=$total+$datat["total"]; for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
if (mb_strpos($at["link"],":",0,"UTF-8")===false) { $text.='<url><loc>http://'.$GLOBAL["host"].trim($at["link"]).'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>
'; }}

/* Все активные тэги сайта */
$datat=DB("SELECT `link`, `data` FROM `_menuitem` WHERE (`stat`='1') order by `pid` asc, `rate` desc"); $total=$total+$datat["total"]; for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
$text.='<url><loc>http://'.$GLOBAL["host"].'/tags/'.$at["id"].'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>hourly</changefreq><priority>0.7</priority></url>
'; }

/* Все активные статичные страницы */
$datat=DB("SELECT `link`, `data`, `domain` FROM `_pages` WHERE (`stat`='1' && `inmap`='1' && `main`='0')"); $total=$total+$datat["total"]; for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]); $dom=$doms[$at["domain"]]."."; 
$text.='<url><loc>http://'.trim($dom.$GLOBAL["host"],"0.").'/'.trim($at["link"]).'</loc><lastmod>'.date("Y-m-d", $at["data"]).'</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
'; }

/* Все активные модульные страницы _lenta */
$q=""; $tablemaps=array(); $r=mysql_query("SHOW TABLES"); if (mysql_num_rows($r)>0) { while($row = mysql_fetch_array($r, MYSQL_NUM)) { $table = $row[0]; if (mb_strpos($table, "_lenta")!==false && $table!="_lentalog") { $tablemaps[]=$table; }}}

foreach($tablemaps as $table) {
	$tmp=explode("_", $table); $link=$tmp[0];
	$q.="(SELECT `$table`.`id`, `$table`.`data`, '$link' as `link` FROM `$table` WHERE (`$table`.`stat`='1') GROUP BY 1 ORDER BY `data` DESC LIMIT 300) UNION ";
}
$qs=trim($q, "UNION ");

$datat=DB($qs); $total=$total+$datat["total"]; for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]); $dom=$doms[$at["domain"]].".";
$text.='<url><loc>http://'.trim($dom.$GLOBAL["host"],"0.").'/'.trim($at["link"]).'/view/'.$at["id"].'</loc><lastmod>'.date("Y-m-d", $at["data"]).'</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
'; }

$text.='<url><loc>http://'.$GLOBAL["host"].'/users/</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>hourly</changefreq><priority>0.1</priority></url>
</urlset>';
$filek=@fopen($ROOT."/sitemap.xml", "w"); @fputs($filek, $text); @fclose($filek);
$cronlog.="В карту сайта помещено ссылок: <b>$total</b> (<b>".round((mb_strlen($text)/1000), 2)." Кб</b>)<br>";
?>