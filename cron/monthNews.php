<?
if ($GLOBAL["sitekey"]!=1) {
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	$GLOBAL["sitekey"] = 1; $now=time();
	@require_once($ROOT."/modules/standart/DataBase.php");	
}

$tables=array("auto_lenta", "business_lenta", "news_lenta" , "oney_lenta", "sport_lenta", "concurs_lenta");

$q =''; $date = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`=1 AND `$table`.`data`>=".$date.") GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." order by `data` ASC"); 
$handle = fopen($ROOT.'/userfiles/docs/'.date('Y-m', $date).'.txt', 'w');
for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
$string = date('d.m.Y', $at["data"]).' // '.trim($at["name"]).' // http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"];
fwrite($handle, $string."\n"); }
fclose($handle);
?>