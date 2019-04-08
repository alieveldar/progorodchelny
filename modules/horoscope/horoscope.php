<?
$table=$link."_lenta"; $table2=$link."_items";
if ($start=="") { $start="list"; $dir[1]="list"; }
$file=$table."-".$start.".".$page.".".$id;

#############################################################################################################################################

if ($start=="view") {
	$where=$GLOBAL["USER"]["role"]==0?"AND `$table`.`stat`=1":"";
	$data=DB("SELECT $table.`name`, $table.`text`, $table.`pic`, $table2.* FROM `$table` LEFT JOIN $table2 ON $table2.`pid`=".(int)$dir[2]." WHERE (`id`=".(int)$dir[2]." $where) LIMIT 1");
	
	if ($data["total"]) {
		@mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);
		if (RetCache($file)=="true") { list($text, $cap)=GetCache($file); } else { list($text, $cap)=GetHoroscopeId($item); SetCache($file, $text, $cap); } UserTracker($link, $page); $text.=UsersComments($link, $page, $item['comments']);
		$edit="<div id='AdminEditItem'><a href='".$GLOBAL["mdomain"]."/admin/?cat=".$link."_edit&id=".(int)$dir[2]."'>Редактировать</a></div>";
	}
	else { $text=@file_get_contents($ROOT."/template/404.html"); $cap="Материал не найден"; $Page404=1; }
}
if($start == 'list'){
	if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaList(); SetCache($file, $text, ""); }	
	$cap=$node["name"];
}

if ($GLOBAL["USER"]["role"]>2) { $text=$C.$edit.$C.$text; } $Page["Content"] = $text; $Page["Caption"] = $cap;

#############################################################################################################################################

function GetHoroscopeId($item) {
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $table, $table2, $table3, $link, $C, $C20, $C10, $C25;
	
	$cap=$item["name"]; $text = '';
	//if($item['pic']) $text.='<div class="nodePic"><a href="/userfiles/picoriginal/'.$item['pic'].'" rel="prettyPhoto[gallery]"><img src="/userfiles/picnews/'.$item['pic'].'" border="0" /></a></div>';
	$text .= $item['text'];
	$path='/userfiles/picnews/'.$item["pic"];	
	$text.='<ul class="horoscope">';
	foreach ($GLOBAL["zodiac"] as $key => $value):
		$text .= '<li id="'.$key.'" name="'.$key.'"><div class="WhiteBlock">';
		$text .= '<img src="/userfiles/images/zodiac/'.$key.'.jpg" />';
		$text .= '<h2>'.$value["name"].' ('.$value["date"].')</h2>';
		$text .= '<div>'.$item[$key].'</div>';
		$text .= $C.'</div></li>'.$C20;
	endforeach;
	$text.='</ul>';	
	$text.="<div class='Likes'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($item['lid']))).$C."</div>".$C10;
	return(array($text, $cap));
}


#############################################################################################################################################

function GetLentaList() {
	global $VARS, $GLOBAL, $dir, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C25;
	$Page["Crumbs"]="<div class='Crumbs'><a href='http://".$RealHost."'>Главная</a> &raquo; ".$node["name"]."</div>";
	
	//$where=$GLOBAL["USER"]["role"]==0?"WHERE (`".$table."`.`stat`=1)":"";
	$onpage=$node["onpage"]; $pg = $dir[2] ? $dir[2] : 1; $orderby=$ORDERS[$node["orderby"]]; $from=($pg - 1)*$onpage; $onblock=4; /* Новостей в каждом блоке */
	$data=DB("SELECT `".$table."`.id,  `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` WHERE (`".$table."`.`stat`=1)  GROUP BY 1 ".$orderby." LIMIT $from, $onpage");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; }
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>";
		} else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div>
		<div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
		
		if($data["total"] > ($i + 1)){ if (($i+1)%$onblock==0) { $text.="</div>".$C10."".$C10."<div class='WhiteBlock'>"; } else { $text.=$C25; } }
	}
	if ($data["total"]>0) { $text.="</div>"; }
	$data=DB("SELECT count(id) as `cnt` FROM `".$table."`"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
	$text.=Pager2($pg, $onpage, ceil($ar["cnt"]/$onpage), $dir[0]."/".$dir[1]."/[page]");
	return(array($text, ""));
}
?>