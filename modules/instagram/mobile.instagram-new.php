<?
####### ВЫВОД СОДЕРЖАНИЯ НОВОСТИ ######################################################################################################################################

function GetInstaContent() {
	/*
	global $VARS, $GLOBAL, $Page, $BANS, $node, $link, $start, $page, $C, $C10, $C20; $noerror=1;
	$adminstat=" && `_widget_insta`.`stat`=1"; $cap=$node["name"]; $ds=$node["ds"]; $kw=$node["kw"]; $onpage=$node["onpage"]; $orderby=$ORDERS[$node["orderby"]];
	if((int)$page==0){ $page=1; $text=$node["text"].$C10; }$from=((int)$page-1)*$onpage; $PAGEBAN=array("every"=>9, "limit"=>2, "now"=>0);
	
	// СПИСОК ---- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----
	
	### Основной запрос
	$data=DB("SELECT `id`,`likes`,`picname`,`picpreview` FROM `_widget_insta` WHERE (`id`!=0".$adminstat.") ORDER BY `data` DESC LIMIT ".$from.", ".$onpage);
	if ($data["total"]>0) {
		$text.="<div class='getInstaPics'>";
		for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]);	$alt=$ar["picname"];
			$text.="<a href='/".$link."/".$ar["id"]."'><img src='".$ar["picpreview"]."' title='".$alt."' alt='".$alt."' /></a>"; if (($i+1)%3==0) { $text.=$C; }
		endfor;
		$text.="</div>";
	} else {
		$noerror=0;
	}
	
	// ПАГЕР ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----	
	
	$data=DB("SELECT count(id) as `cnt` FROM `_widget_insta` WHERE (`id`!=0".$adminstat.")"); @mysql_data_seek($data["result"], 0);
	$ar=@mysql_fetch_array($data["result"]); $text.=Pager2($page, $onpage, ceil($ar["cnt"]/$onpage), $link."/".$start."/[page]");
	
	// ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----	

	return(array($text, $cap, $kw, $ds)); */
}
?>