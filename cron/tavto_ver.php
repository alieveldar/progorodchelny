<?
### Запрашиваемый файл должен определять переменную $rsstext 

$rsstext='<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /><style>body {background:#e1e1e1;} * {margin: 0; padding: 0;} .prokazan_title { color:#3366CC; text-decoration: underline; font-size:12px; font-weight:bold; font-family: Arial,sans-serif; }
a.prokazan_title:hover{ text-decoration: none; } .prokazan_img {float: left; margin: 0 5px 0 0; border: 0;} .prokazan_img img {border: 1px #000000 solid; margin: 0; padding: 0} .prokazan_table {width: 100%; height: 60px; font-family: Arial cyr,Sans-serif;}
.prokazan_table td {vertical-align:top;} .prokazan_table .vertical td {padding-bottom: 10px;}</style></head><body><table class="prokazan_table">';

$q=""; foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`tavto`='1' && `$table`.`pic`<>'') GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 3"); for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
$rsstext.='<tr class="vertical"><td width="100%"><a target="_blank" class="prokazan_img" href="http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'"><img style="width: 75px; height: 56px;" src="http://'.$GLOBAL["host"].'/userfiles/pictavto/'.$at["pic"].'" alt="'.htmlspecialchars($at["name"]).'" title="'.htmlspecialchars($at["name"]).'" /></a><a target="_blank" href="http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'" class="prokazan_title">'.htmlspecialchars($at["name"]).'</a></td></tr>'; }

$rsstext.='</table></body></html>';
?>