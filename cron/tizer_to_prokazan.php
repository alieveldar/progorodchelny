<?
if ($GLOBAL["sitekey"]!=1) {
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	$GLOBAL["sitekey"] = 1; $now=time();
	@require_once($ROOT."/modules/standart/DataBase.php");	
}

$tables=array("auto_lenta", "business_lenta", "news_lenta" , "sport_lenta");

$q =''; $text = '<noindex><div class="TizersBlock">';
foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`=1 AND `$table`.`onind`=1) GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." order by `data` DESC LIMIT 3"); 
for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
$text .= '<div class="TizerItem">';
$text .= '<div class="TizerPic"><a href="http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'" rel="nofollow"><img src="http://'.$GLOBAL["host"].'/userfiles/picsquare/'.$at["pic"].'"></a></div>';
$text .= '<div class="TizerName"><a href="http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'" rel="nofollow">'.$at["name"].'</a></div>';
$text .= '</div>';
}
$text .= $C.'</div></noindex>';
$filek=@fopen($ROOT.'/tizer_to_prokazan.html', "w"); @fputs($filek, $text); @fclose($filek);
?>