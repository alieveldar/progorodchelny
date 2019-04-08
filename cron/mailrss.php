<?
### Запрашиваемый файл должен определять переменную $rsstext 

$rsstext='<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" xmlns:mailru="http://news.mail.ru/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
<link>http://'.$GLOBAL["host"].'/newsrss.xml</link>
<title>'.$VARS["sitename"].'</title>
<description>'.$VARS["sitename"].'</description>
<language>ru</language>
<pubDate>'.date("r").'</pubDate>
<lastBuildDate>'.date("r").'</lastBuildDate>
<generator>rss generator</generator>
<webMaster>disweb@mail.ru</webMaster>
';

$q=""; $ats=array(); foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`text`, `$table`.`lid`, `$table`.`data`, `$table`.`pic`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`promo`!='1' && `$table`.`mailrss`='1') GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 20"); for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $att=@mysql_fetch_array($datat["result"]); $ats[$att["id"]]=$att; }


foreach($ats as $key=>$at) {

/* Аттач картинок к новости */ $pics=""; $datap=DB("SELECT `pic` FROM `_widget_pics` WHERE (`pid`='".(int)$at["id"]."' && `link`='".$at["link"]."')"); for($it=0; $it<$datap["total"]; $it++) { @mysql_data_seek($datap["result"], $it); $ar=@mysql_fetch_array($datap["result"]); $rsstext.='	<enclosure url="http://'.$GLOBAL["host"].'/userfiles/picoriginal/'.$ar["pic"].'" type="image/jpeg" />
'; }

$rsstext.='
<item>
	<title><![CDATA[ '.$at["name"].']]></title>
	<link>http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'</link>
	<guid isPermaLink="true">http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'</guid>
	<dc:creator>http://'.$GLOBAL["host"].'</dc:creator>
	<description><![CDATA[ '.$at["lid"].' ]]></description>
	<mailru:full-text><![CDATA[ '.$at["text"].' ]]></mailru:full-text>
	<pubDate>'.date("r", $at["data"]).'</pubDate>';
if ($at["pic"]!="") { $rsstext.='
	<enclosure url="http://'.$GLOBAL["host"].'/userfiles/picoriginal/'.$at["pic"].'" type="image/jpeg" />'; }
if ($pics!="") { $rsstext.='
	'.$pics; }
$rsstext.='
</item>';
}

$rsstext.='
</channel>
</rss>';
?> 

