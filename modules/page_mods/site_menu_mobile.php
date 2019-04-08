<?
	$file="site_all_menus-menu".$am["id"]; $type="cachemenu";
	if (RetCache($file, $type)=="true") { list($MENU[$am["link"]], $cap)=GetCache($file, 0); } else {		
	// ---- получение списка пунктов  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----

	$itext=""; $data=DB("SELECT * FROM `_menuitem` WHERE (`nid`='".$am["id"]."' && `stat`='1') ORDER BY `rate` DESC");
	for ($i=0; $i<=$data["total"]; $i++):
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); 
		if ($ar['name']!="") { $itext.='<a href="'.$ar["link"].'">'.$ar['name'].'</a>'; }
	endfor; 
	
	$MENU[$am["link"]]="<div class='MenuDiv MenuDiv-".$am["link"]."' id='MenuDiv-".$am["link"]."'>".$itext."</div>";

	// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ----  ---- 
	$MENU[$am["link"]]=str_replace("[mdomain]", $VARS["mdomain"], $MENU[$am["link"]]);
	SetCache($file, $MENU[$am["link"]], "", $type); }
?>