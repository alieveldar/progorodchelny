<?
$Page["Caption"]="";
$used_id=array();

$table="auto_lenta";

$file="agregator-auto_blocktv"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=IndexPageTV(); SetCache($file, $text, ""); }
$Page["TopContent"]=$text.$C;

### Центральная колонка
$tableall=array();
$tableall["redak"]	=	array(2,3); // Типы новостей в редакционные колонки 
$tableall["test-drive"]	=	array(2); // Типы новостей (категории) для тизеров на главной

$file="agregator-auto_maincontent"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=IndexPageCenter(); SetCache($file, $text, ""); }
$Page["Content"]=$text.MostDiscussed(array("auto_lenta")).Updates(array("auto_lenta"));

################################################################################################################################################################################################

function IndexPageTV() {
	global $table, $used_id, $VARS, $GLOBAL, $Domains, $C10, $C15, $C, $C5; $text="";
	
	$tmp=explode("_", $table); $link=$tmp[0];
	$text.=$C5."<!-- LEFT PART START --><div class='MainTVSpec'>";
	### TV ####################################################################################################################################################################################################################################		
	$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'
	WHERE (`$table`.`stat`='1' && `$table`.`onind`='1') GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 5");
	if ($data["total"]>0) { @mysql_data_seek($data["result"], 0); $tv[0]=@mysql_fetch_array($data["result"]); 
		$path="http://".trim($Domains[$tv[0]["domain"]].".".$VARS["mdomain"], ".")."/".$tv[0]["link"]."/view/"; $text.="<div class='MainTV'>"; $used_id[$tv[0]["link"]][]=$tv[0]["id"];
		$text.="<div id='MainTVPic'><a href='".$path.$tv[0]["id"]."'><img src='/userfiles/picintv/".$tv[0]["pic"]."'></a><div><h1><a href='".$path.$tv[0]["id"]."'>".$tv[0]["name"]."</a></h1></div></div>";
		$text.="<div class='MainTVList'>";	
		for($i=0; $i<5; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $tv[$i]=$ar; 
			$item=$tv[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/"; if ($i==0) { $class='2'; } else { $class='1'; }
			$hover="MainTvHover(".$item["id"].", '".$item["pic"]."', '".str_replace(array("'", '"'), array("&#039;","&quot;"), $item["name"])."', '".$path."')";
			$text.="<div class='MainTVListItem MainTVListItem".$class."' id='MainTV-".$item[id]."' onmouseover=\"".$hover."\"><a href='".$path.$item["id"]."'><img src='/userfiles/picsquare/".$item["pic"]."'></a><a href='".$path.$item["id"]."'>".$item["name"]."</a></div>";
			$used_id[$item["link"]][]=$item["id"]; if ($i==5) { $text.=$C; } else { $text.="<div class='CB'></div>"; }
		}
		$text.="</div>".$C."</div>".$C5.$C10;
	}
	### SPEC #################################################################################################################################################################################################################################
	$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'
	WHERE (`$table`.`stat`='1' && `$table`.`spec`='1' && `$table`.`promo`!='1') GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 3");
	for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $spec[]=$ar; }
	/* Если есть ком. новость в спец. размещение - заменяем ею третью новсть из спец блока*/
	$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table`
	LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`spec`='1' && `$table`.`promo`='1') GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 1");
	if ($data["total"]==1) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $spec[2]=$ar; }
	if (count($spec)>0) {
		$text.="<div class='MainSpec'>"; for($i=0; $i<3; $i++) { $item=$spec[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
		$pic = strpos($item["pic"], "old") ? $item["pic"] : "/userfiles/picsquare/".$item["pic"];
		$text.="<div class='MainSpecBlock'><a href='".$path.$item["id"]."'><img src='".$pic."' title='".$item["name"]."'></a><b><a href='".$path.$item["id"]."'>".$item["name"]."</a></b></div>";
		$used_id[$item["link"]][]=$item["id"]; if ($i==2) { $text.=$C; } else { $text.="<div class='CL'></div>"; }} $text.="</div>";
	}
	$text.="</div><!-- LEFT PART END -->";
	### Main Banner 240*400 
	$text.="<div class='banner' style='float:right' id='Banner-1-1'></div>";
	return(array($text, ""));
}

################################################################################################################################################################################################

function IndexPageCenter() {
	global $tableall, $table, $used_id, $VARS, $GLOBAL, $Domains, $SubDomain, $C10, $C15, $C, $C20, $C5, $C25, $UserSetsSite; $text=""; $redak=array();
	
	############################################
	$text.="<div class='banner' id='Banner-6-1'></div>";
	############################################
	
	#### Редакционная колонка 2 новости 
	$tables=$tableall["redak"]; $tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ","); $q="";
	foreach($tables as $cat) { $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`uid`,  `$table`.`data`, `$table`.`cat`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`, `".str_replace("_lenta", "_cats", $table)."`.`name` as `cname`
	FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table`.`uid`  LEFT JOIN `".str_replace("_lenta", "_cats", $table)."` ON `".str_replace("_lenta", "_cats", $table)."`.`id`=`$table`.`cat`
	WHERE (`$table`.`cat`='".$cat."' &&  `$table`.`stat`='1' && `$table`.`redak`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 order by `$table`.`data` DESC LIMIT 1) UNION "; }
	
	$data=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 2"); for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $redak[]=$ar; }
	$ident="MainRedaktor"; $text.="<div id='MainRedaktor'><div id='".$ident."-0' class='".$ident."'>"; for($i=0; $i<count($redak); $i++) { $item=$redak[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
	$text.="<div style='float:left;'><div class='MainRazdelName'><a href='/$link/cat/$item[cat]' title='".$item["cname"]."'>".$item["cname"]."</a></div><div class='MainRedaktorItem'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a>".$C."
	<div class='MainRedaktorAuth'><a href='/users/view/".$item["uid"]."'><img src='/".$item["avatar"]."' /></a><a href='".$path.$item["id"]."'>".$item["name"]."</a><b><a href='/users/view/".$item["uid"]."'>".$item["nick"]."</a></b></div></div></div>";
	$used_id[$link][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C; }} 
	#### Редакционная колонка 1 конкурс
	/* $table2="concurs_lenta"; $link="concurs"; $q="SELECT `$table2`.`id`, `$table2`.`name`, `$table2`.`uid`,  `$table2`.`data`, `$table2`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`
	FROM `$table2` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table2`.`uid` WHERE (`$table2`.`stat`='1' && `$table2`.`domain`='".$SubDomain."' AND (`".$table2."`.`astat`<>1 || (`".$table2."`.`astat`=1 AND `".$table2."`.`data`<=".$GLOBAL['now']."))) ORDER BY `$table2`.`data` DESC LIMIT 1"; $data=DB($q);
	if ($data["total"]==1) { @mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]); $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";		
		$text.="<div style='float:left;'><div class='MainRazdelName'><a href='/concurs/' title='".$item["cname"]."'>Конкурс</a></div><div class='MainRedaktorItem'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a>".$C."
		<div class='MainRedaktorAuth'><a href='/users/view/".$item["uid"]."'><img src='/".$item["avatar"]."' /></a><a href='".$path.$item["id"]."'>".$item["name"]."</a><b><a href='/users/view/".$item["uid"]."'>".$item["nick"]."</a></b></div></div></div>".$C; 	
	} */
	### ДТП недели
	$table2="auto_lenta"; $link="auto"; $q="SELECT `$table2`.`id`, `$table2`.`name`, `$table2`.`uid`,  `$table2`.`data`, `$table2`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`
	FROM `$table2` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table2`.`uid` WHERE (`".$table2."`.`stat`='1' && `".$table2."`.`cat`='1' && `$table2`.`redak`='1' && `$table2`.`tags` LIKE '%,1,%' && `$table2`.`id` NOT IN (".$NOTIN.")) ORDER BY `$table2`.`data` DESC LIMIT 1"; $data=DB($q);
	if ($data["total"]==1) { @mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]); $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";	$used_id[$link][]=$item["id"];
		$text.="<div style='float:left;'><div class='MainRazdelName'><a href='/tags/1/' title='".$item["cname"]."'>ДТП недели</a></div><div class='MainRedaktorItem'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a>".$C."
		<div class='MainRedaktorAuth'><a href='/users/view/".$item["uid"]."'><img src='/".$item["avatar"]."' /></a><a href='".$path.$item["id"]."'>".$item["name"]."</a><b><a href='/users/view/".$item["uid"]."'>".$item["nick"]."</a></b></div></div></div>".$C; 	
	}
	$text.="</div></div>".$C10;

	############################################
	$text.="<div class='banner' id='Banner-6-2'></div>";
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ","); $dir[0]=$link;
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.`name`, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
	
	#### 3 коммерческие новости на главную
	/*$redak2=array(); $NOTIN=trim("0,".implode(",", $used_id[$link]), ","); 
	$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`
	FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table`.`uid`
	WHERE (`$table`.`stat`='1' && `$table`.`promo`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 3");
	for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $redak2[]=$ar; }

	if (count($redak2)>=3) { $count=3; } else { $count=count($redak2); } $knopki=""; $ident="MainCommerse";
	for($i=0; $i<$count; $i++) { $item=$redak2[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
		$text.="<div class='MainRedaktorItem2'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a><div class='CapItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a></div></div>";
		$used_id[$link][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C; }
	} $text.=$C20;*/
	
	############################################
	$text.="<div class='banner' id='Banner-6-3'></div>";
	############################################
	
	############################################
	//$text.=TatBrand(0).$C20;
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
	
	#### Новостные ленты
	/*$cats=$tableall["autokazan"]; $j=0;
	$NOTIN=trim("0,".implode(",", $used_id[$link]), ","); 
	foreach($cats as $cat) {
		$data=DB("SELECT `$table`.`id`, `$table`.`comments`, `$table`.`cat`, `$table`.`comcount`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `".str_replace("_lenta", "_cats", $table)."`.`name` as `catname` FROM `$table`
		LEFT JOIN `".str_replace("_lenta", "_cats", $table)."` ON `".str_replace("_lenta", "_cats", $table)."`.`id`=`$table`.`cat` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`cat`='".$cat."' && `$table`.`promo`!='1' && `$table`.`stat`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 15");
		if ($data["total"]>=15) { $count=15; } else { $count=$data["total"]; } $knopki=""; $ident="Main".$cat.$link; @mysql_data_seek($data["result"], 0); $tmp=@mysql_fetch_array($data["result"]);
		for ($j=0; $j<ceil($count/3); $j++) { if ($j==0) { $class="bg"; } else { $class=""; } $knopki="<div id='".$ident."-knopka-".$j."' class='".$ident."-knopka ".$class."' onclick=\"ShowOtherBox('".$j."', '".$ident."');\" title='Страница: ".($j+1)."'></div>".$knopki; }
		$text.="<div class='MainRazdelName'><a href='http://".trim($Domains[$tmp["domain"]].".".$VARS["mdomain"], ".")."/".$tmp["link"]."/cat/".$tmp["cat"]."'>".$tmp["catname"]."</a>".$knopki."</div><div id='MainRedaktor'><div id='".$ident."-0' class='".$ident."'>";
		for($i=0; $i<$count; $i++) { @mysql_data_seek($data["result"], $i); $item=@mysql_fetch_array($data["result"]); $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
		if ($item["comments"]!=2 && $UserSetsSite[3]==1) { $coms="<br><b><a href='".$path.$item["id"]."#comments'>Комментарии</a>: ".$item["comcount"]."</b>"; } else { $coms=""; }
		if ($item["pic"]!="") { if (strpos($item["pic"], "old")!=0) { $pic="<img src='".$item["pic"]."' />"; } else { $pic="<img src='/userfiles/picnews/".$item["pic"]."' />"; }}
		$text.="<div class='MainRedaktorItem'><a href='".$path.$item["id"]."'>".$pic."</a>".$C."<div class='MainRedaktorAuth NewItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a>".$coms."</div></div>";
		$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C."</div><div class='".$ident."' id='".$ident."-".round(($i+1)/3)."' style='display:none;'>"; }
		} $text.="</div></div>".$C5; $jj++;
	}*/
		
	############################################
	$text.="<div class='banner' id='Banner-6-4'></div>";
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
	
	
	#### Новостные ленты
	$cats=$tableall["test-drive"]; $j=0; 
	$NOTIN=trim("0,".implode(",", $used_id[$link]), ","); 
	foreach($cats as $cat) {
		$data=DB("SELECT `$table`.`id`, `$table`.`comments`, `$table`.`cat`, `$table`.`comcount`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `".str_replace("_lenta", "_cats", $table)."`.`name` as `catname` FROM `$table`
		LEFT JOIN `".str_replace("_lenta", "_cats", $table)."` ON `".str_replace("_lenta", "_cats", $table)."`.`id`=`$table`.`cat` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`cat`='".$cat."' && `$table`.`promo`!='1' && `$table`.`stat`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 15");
		if ($data["total"]>=15) { $count=15; } else { $count=$data["total"]; }
		if($count){ 
			$knopki=""; $ident="Main".$cat.$link; @mysql_data_seek($data["result"], 0); $tmp=@mysql_fetch_array($data["result"]);
			for ($j=0; $j<ceil($count/3); $j++) { if ($j==0) { $class="bg"; } else { $class=""; } $knopki="<div id='".$ident."-knopka-".$j."' class='".$ident."-knopka ".$class."' onclick=\"ShowOtherBox('".$j."', '".$ident."');\" title='Страница: ".($j+1)."'></div>".$knopki; }
			$text.="<div class='MainRazdelName'><a href='http://".trim($Domains[$tmp["domain"]].".".$VARS["mdomain"], ".")."/".$tmp["link"]."/cat/".$tmp["cat"]."'>".$tmp["catname"]."</a>".$knopki."</div><div id='MainRedaktor'><div id='".$ident."-0' class='".$ident."'>";
			for($i=0; $i<$count; $i++) { @mysql_data_seek($data["result"], $i); $item=@mysql_fetch_array($data["result"]); $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
			if ($item["comments"]!=2 && $UserSetsSite[3]==1) { $coms="<br><b><a href='".$path.$item["id"]."#comments'>Комментарии</a>: ".$item["comcount"]."</b>"; } else { $coms=""; }
			if ($item["pic"]!="") { if (strpos($item["pic"], "old")!=0) { $pic="<img src='".$item["pic"]."' />"; } else { $pic="<img src='/userfiles/picnews/".$item["pic"]."' />"; }}
			$text.="<div class='MainRedaktorItem'><a href='".$path.$item["id"]."'>".$pic."</a>".$C."<div class='MainRedaktorAuth NewItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a>".$coms."</div></div>";
			$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C."</div><div class='".$ident."' id='".$ident."-".round(($i+1)/3)."' style='display:none;'>"; }
			} $text.="</div></div>".$C5; $jj++;
		}
	}	
		
	############################################
	$text.="<div class='banner' id='Banner-6-5'></div>";
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
	
	### Выбор по тэгу ДТП
	$NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `$table`.`id`, `$table`.`comments`, `$table`.`cat`, `$table`.`comcount`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `".str_replace("_lenta", "_cats", $table)."`.`name` as `catname` FROM `$table`
	LEFT JOIN `".str_replace("_lenta", "_cats", $table)."` ON `".str_replace("_lenta", "_cats", $table)."`.`id`=`$table`.`cat` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`cat`='1' && `$table`.`tags` LIKE '%,1,%' && `$table`.`promo`!='1' && `$table`.`stat`='1') GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 15");
	if ($data["total"]>=15) { $count=15; } else { $count=$data["total"]; } $knopki=""; $ident="Main".$link; @mysql_data_seek($data["result"], 0); $tmp=@mysql_fetch_array($data["result"]);
	for ($j=0; $j<ceil($count/3); $j++) { if ($j==0) { $class="bg"; } else { $class=""; } $knopki="<div id='".$ident."-knopka-".$j."' class='".$ident."-knopka ".$class."' onclick=\"ShowOtherBox('".$j."', '".$ident."');\" title='Страница: ".($j+1)."'></div>".$knopki; }
	$text.="<div class='MainRazdelName'><a href='/tags/1'>ДТП в Набережных Челнах</a>".$knopki."</div><div id='MainRedaktor'><div id='".$ident."-0' class='".$ident."'>";

	for($i=0; $i<$count; $i++) { @mysql_data_seek($data["result"], $i); $item=@mysql_fetch_array($data["result"]); $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
	if ($item["comments"]!=2 && $UserSetsSite[3]==1) { $coms="<br><b><a href='".$path.$item["id"]."#comments'>Комментарии</a>: ".$item["comcount"]."</b>"; } else { $coms=""; }
	if ($item["pic"]!="") { if (strpos($item["pic"], "old")!=0) { $pic="<img src='".$item["pic"]."' />"; } else { $pic="<img src='/userfiles/picnews/".$item["pic"]."' />"; }}
	$text.="<div class='MainRedaktorItem'><a href='".$path.$item["id"]."'>".$pic."</a>".$C."<div class='MainRedaktorAuth NewItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a>".$coms."</div></div>";
	$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C."</div><div class='".$ident."' id='".$ident."-".round(($i+1)/3)."' style='display:none;'>"; }
	} $text.="</div></div>".$C15;
	
		
	############################################
	$text.="<div class='banner' id='Banner-6-6'></div>";
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
	
	#### Консультация
	############################################
	//$text.=Consults(69);
	############################################
		
	############################################
	$text.="<div class='banner' id='Banner-6-7'></div>";
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
		
	############################################
	$text.="<div class='banner' id='Banner-6-8'></div>";
	############################################
	
	#### 3 Линейные новости
	$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ",");
	$data=DB("SELECT `".$table."`.id, `".$table."`.`cat`, `".$table."`.name, `".$table."`.uid, `".$table."`.pic, `".$table."`.data,`".$table."`.comcount, `".$table."`.comments, `".$link."_cats`.`name` as `ncat`, `_users`.`nick`
	FROM `".$table."` LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `".$link."_cats` ON `".$link."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='1' && `".$table."`.`stat`='1' && `".$table."`.`id` NOT IN ($NOTIN))  GROUP BY 1 ORDER BY `data` DESC LIMIT 0, 3");
	if ($data["total"]>0) { $text.="<div class='WhiteBlock'>"; } for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='".$ar["pic"]."' title='".$ar["name"]."' /></a>"; } else {
		/*Новый*/ $pic="<a href='/".$dir[0]."/view/".$ar["id"]."'><img src='/userfiles/picnews/".$ar["pic"]."' title='".$ar["name"]."' /></a>"; }}	
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='http://".$VARS["mdomain"]."/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='http://".$VARS["mdomain"]."/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<a href='/".$dir[0]."/view/".$ar["id"]."#comments'>Комментарии</a>: <b>".$ar["comcount"]."</b>"; } else { $coms=""; }
		$text.="<div class='NewsLentaBlock' id='NewsLentaBlock-".$ar["id"]."'><div class='Time'><b>".$d[8]."</b></div><div class='Pic'>".$pic."</div><div class='Text'><div class='Caption'><h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2></div><div class='CatAndAuth'>
		<div class='CatAuth'>Категория: <a href='/".$dir[0]."/cat/".$ar["cat"]."'>".$ar["ncat"]."</a>. Автор: ".$auth."</div><div class='Coms'>".$coms."</div></div></div>".$C."</div>";
	if ($i!=2) { $text.=$C25; } else { $text.=$C;} $used_id[$link][]=$ar["id"]; } if ($data["total"]>0) { $text.="</div>"; } $text.=$C20;
		
	############################################
	$text.="<div class='banner' id='Banner-6-9'></div>";
	############################################			
	
	return(array($text, ""));
}

################################################################################################################################################################################################
?>