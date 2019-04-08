<?
$table=$link."_lenta"; $start=(int)$start; $orderby=$ORDERS[$node["orderby"]];
if ($start==0){ list($text, $cap)=GetLentaIndex(); }else{ list($text, $cap)=GetLentaList(); }
$Page["Content"]=$text; $Page["Caption"]=$Page["Title"]=strip_tags($cap);
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


function GetLentaIndex() {
	global $C20, $node, $dir; 
	$cap=$node["name"]; $text="<h1 class='cap'>".$cap."</h1>"; $data=DB("SELECT * FROM `".$dir[0]."_cats` WHERE (`stat`=1) ORDER BY `rate` DESC");
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);
	$text.="<div class='category'><a href='/".$dir[0]."/".$ar["id"]."'><img src='/userfiles/mapicon/".$ar["icon"]."'><span>".$ar["name"]."</span></a></div>";
	endfor; return(array($text, $cap));
}



// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

function GetLentaList() {
	global $VARS, $GLOBAL, $Page, $node, $link, $start, $dir, $table, $C, $C5, $C30, $C10, $C20; 
	$d=DB("SELECT * FROM `".$dir[0]."_cats` WHERE (`stat`=1) ORDER BY `rate` DESC"); for($i=0; $i<$d["total"]; $i++): @mysql_data_seek($d["result"],$i); $ar=@mysql_fetch_array($d["result"]);
	$text.="<a href='/$dir[0]/$ar[id]' class='mcat'><img src='/userfiles/mapicon/$ar[icon]'>$ar[name]</a>"; if ($ar["id"]==$dir[1]) { $cat=$ar; } endfor; $text.=$C;
	$text.="<div class='pgbg' style='background-image:url(/userfiles/picoriginal/$cat[pic]);'><h1>$cat[name]<hr></h1><h2>".nl2br($cat["lid"])."</h2></div>".$C30;
	$data=DB("SELECT * FROM `".$table."` WHERE (`stat`=1 && `cat`='".$cat["id"]."')  GROUP BY 1 ORDER BY RAND()");
	$text.="<div>";
	for ($i=0; $i<$data["total"]; $i++) {
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $text.="<div class='box'>";
		if ($ar["pic"]!="") { $text.="<img src='/userfiles/picintv/".$ar["pic"]."' title='".$ar["name"]."' class='mpic' />"; }
		if ($ar["name"]!="") { $text.="<div class='pil2'>".$ar["name"]."</div>".$C; }
		if ($ar["alttext"]!="") { $text.="<div class='pil'><img src='/modules/digest/phone.png'>".$ar["alttext"]."</div>".$C; }
		if ($ar["soctext"]!="") { $text.="<div class='pil'><img src='/modules/digest/adres.png'>".$ar["soctext"]."</div>".$C; }
		if ($ar["realinfo"]!="") { $text.="<noindex><div class='pil'><img src='/modules/digest/site.png'><a href='http://".wthhp($ar["realinfo"])."' target='_blank' rel='nofollow'><u>".wthhp($ar["realinfo"])."</u></a></div></noindex>".$C; }
		$text.="<div class='textbox'>";
			if ($ar["lid"]!="") { $text.="<div class='texts'>".$ar["lid"]."</div>".$C; }
			if ($ar["endtext"]!="") { $text.="<div class='textu'>".$ar["endtext"]."</div>".$C; }
		$text.="</div>";
		$text.="</div>"; if (($i+1)%3==0) { $text.="<clear></clear>"; }
	}
	$text.="</div><clear></clear>"; if ($cat["text"]!="") { $text.=$cat["text"]; } return(array($text, $cat["name"]));
}

function wthhp($link) { $link=str_replace(array("http://","https://"),"",$link); return $link; }

?>