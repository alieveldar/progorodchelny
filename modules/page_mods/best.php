<?
$tables=array("auto_lenta", "business_lenta", "news_lenta" , "sport_lenta");

### Вывод списка новостей общий
$file="best-list2"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaList(); SetCache($file, $text, ""); }	
$Page["Content"]=$text; $Page["Caption"]="Полезное на портале";

#############################################################################################################################################

function GetLentaList() {
	global $VARS, $GLOBAL, $dir, $RealHost, $UserSetsSite, $tables, $C, $C20, $C15, $C25;
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; Полезное на портале</div>";
	foreach($tables as $table) { $q.="(SELECT `$table`.`id`, `$table`.`name`,  `$table`.`data`, `$table`.`pic` FROM `$table` WHERE (`$table`.`stat`='1' && `$table`.`adv`<>'')) UNION "; }
	$data=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 200");
	 
	$text.="<div class='WhiteBlock'>";
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) {
			 $pic="<a href='/newsv2/".$ar["id"].".html'><img src='".$ar["pic"]."' title='".$ar["name"]."' style='width:70px; height:56px;' /></a>";
		} else {
			$pic="<a href='/newsv2/".$ar["id"].".html'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' style='width:70px; height:56px;'  /></a>";
		}}	
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time' style='height:56px;'><b>".$d[5]."</b></div><div class='Pic' style='width:70px; height:56px;'>".$pic."</div><div class='Text'><div class='Caption'><h2 style='font-size:15px; line-height:18px;'><a href='/newsv2/".$ar["id"].".html'>".$ar["name"]."</a></h2></div></div>".$C."</div><div class='C15'></div>";
	}
	$text.="</div>";

	return(array($text, ""));
}
?>