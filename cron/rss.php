<?
### Запрашиваемый файл должен определять переменную $rsstext 

$rsstext='<?xml version="1.0" encoding="UTF-8" ?><rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
<channel>
<atom:link href="http://'.$GLOBAL["host"].'/rss.xml" rel="self" type="application/rss+xml"/>
<title>'.$VARS["sitename"].'</title>
<link>http://'.$GLOBAL["host"].'</link>
<description>'.$VARS["sitename"].'</description>
<lastBuildDate>'.date("r").'</lastBuildDate>
<image>
  <url>http://'.$GLOBAL["host"].'/template/index/logo.png</url>
  <title>'.$VARS["sitename"].'</title>
  <link>http://'.$GLOBAL["host"].'</link>
</image>';


$q=""; foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`lid` as `text`, `$table`.`data`, `$table`.`pic`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'  WHERE (`$table`.`stat`='1' && `$table`.`promo`!='1') GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 20"); for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
if ($at["pic"]!="") {
	$rsstexti='
	<enclosure url="http://'.$GLOBAL["host"].'/userfiles/picitem/'.$at["pic"].'" type="image/jpeg" />';
	$rsstextim='';
}
$rsstext.='
<item>
	<title>'.$at["name"].'</title>
	<author>'.$GLOBAL["host"].'</author>
	<pubDate>'.date("r", $at["data"]).'</pubDate>
	<link>http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'</link>'.$rsstexti.'
	<guid isPermaLink="true">http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'</guid>
	<description><![CDATA['.$rsstextim.$at["text"].']]></description>';	
$rsstext.='
</item>';
}

$rsstext.='
</channel>
</rss>';
?>