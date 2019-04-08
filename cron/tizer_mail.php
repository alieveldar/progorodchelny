<?
### Запрашиваемый файл должен определять переменную $rsstext 
$rsstext='{"logo" : "http://'.$GLOBAL["host"].'/template/prokazansmalllogo.png", "news": [';
$q=""; foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`mailtizer`='1' && `$table`.`pic`<>'') GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 3"); for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]); list($d, $m)=explode(".", date("d.m", $at["data"])); $m=$m+0;
$rsstext.='
{ "img": "http://'.$GLOBAL["host"].'/userfiles/pictavto/'.$at["pic"].'", "title": "'.htmlspecialchars($at["name"], ENT_QUOTES).'", "datetime": "'.($d+0).' '.$ma[$m].'", "url": "http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'"},'; }
$rsstext=trim($rsstext, ",").'
]}';
?>