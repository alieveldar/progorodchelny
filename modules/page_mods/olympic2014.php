<?
$file="_index-olympic2014"; $VARS["cachepages"]=10;
if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=FireTable(); SetCache($file, $text, ""); }
if (RetCache($file."2")=="true") { list($text2, $cap)=GetCache($file."2", 0); } else { list($text2, $cap)=Fire2Table(); SetCache($file."2", $text2, ""); }


$Page["Content"]=$text2."<hr /><a href='/tags/133'><h2 style='font-size:22px;' align='center'><u>Все новости Зимних игр 2014</u></h2></a><div class='C20'></div>".'<div class="banner" id="Banner-6-1"></div>'.$Page["Content"].$C20.'<div class="banner" id="Banner-6-2"></div>';

$Page["RightContent"]="<h2 style='font:16px/22px Georgia;'>Медальный зачет</h2>".$C10;
$Page["RightContent"].='<iframe width="240" height="400" src="http://www.sochi2014.com/widgets/standings?rows=10" frameborder="0"></iframe>'.$C20;
$Page["RightContent"].="<div class='banner' id='Banner-1-1'></div>";
$Page["RightContent"].=$text.$C20;
$Page["RightContent"].="<div class='banner' id='Banner-10-1'></div>";




function FireTable() { global $C, $C20, $C10, $C25, $C15; $tables = array(); $onpage=10; $orderby=" order by data DESC";
	$data=DB("SHOW TABLES"); for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $tables[] = $ar[0]; }
	foreach ($tables as $tab) {
		if(!preg_match('/(_lenta)$/', $tab)) continue;
		else {
			list($alias) = explode('_', $tab);
			if(!in_array($alias.'_cats', $tables)) continue;
		}		
	if($query) $query.=" UNION "; $query.="(SELECT `".$tab."`.name, `".$tab."`.pic, `".$tab."`.data, `".$tab."`.id, '".$alias."' as `alias` FROM `".$tab."` WHERE (`".$tab."`.`tags` LIKE '%,133,%' && `$tab`.`stat`=1))"; }
	$data=DB($query." ".$orderby." LIMIT 12,".$onpage); if ($data["total"]>0) { $text.="<h2 style='font:16px/22px Georgia;'>Новости Зимних игр 2014</h2>".$C10;
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($i%4==0) { $pic="<a href='/".$ar["alias"]."/view/".$ar["id"]."'><img src='/userfiles/picitem/".$ar["pic"]."' title='".$ar["name"]."' style='margin-bottom:5px; width:240px; border-radius:5px;' border='0' /></a>"; } else { $pic=""; }
		$text.=$pic."<a href='/".$ar["alias"]."/view/".$ar["id"]."' style='font:11px/15px Tahoma;'><u>".$ar["name"]."</u></a>
		<div style='font:11px/14px Cuprum; color:#777; margin-top:5px; margin-bottom:10px; padding-bottom:11px; border-bottom:1px dashed #777;'>".$d[0].$C5."</div>".$C5;
}} return (array($text, $C)); }


function Fire2Table() { global $C, $C20, $C10, $C25, $C15; $tables = array(); $onpage=12; $orderby=" order by data DESC";
	$data=DB("SHOW TABLES"); for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $tables[] = $ar[0]; }
	foreach ($tables as $tab) {
		if(!preg_match('/(_lenta)$/', $tab)) continue;
		else {
			list($alias) = explode('_', $tab);
			if(!in_array($alias.'_cats', $tables)) continue;
		}		
	if($query) $query.=" UNION "; $query.="(SELECT `".$tab."`.name, `".$tab."`.pic, `".$tab."`.data, `".$tab."`.id, '".$alias."' as `alias` FROM `".$tab."` WHERE (`".$tab."`.`tags` LIKE '%,133,%' && `$tab`.`stat`=1))"; }
	$data=DB($query." ".$orderby." LIMIT ".$onpage); if ($data["total"]>0) { for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		
		if (($i+1)%3==0) { $m=""; } else { $m="margin-right:2%;"; } $text.="<div style='float:left; width:32%; overflow:hidden; ".$m."'>";
		$pic="<a href='/".$ar["alias"]."/view/".$ar["id"]."'><img src='/userfiles/picintv/".$ar["pic"]."' title='".$ar["name"]."' style='width:100%; margin-bottom:7px;' /></a>";
		$text.=$pic."<a href='/".$ar["alias"]."/view/".$ar["id"]."' style='font:16px/18px Cuprum;'><u>".$ar["name"]."</u></a></div>"; if (($i+1)%3==0) { $text.="<div class='C25'></div>"; }
			
}} return (array($text, $C)); }
?>