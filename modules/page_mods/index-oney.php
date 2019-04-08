<?
$Page["Caption"]="";
$VARS["sitename"]="Журна &laquo;Кошка&raquo; - Интернет для женщин. Казань";

$used_id=array();

$table="oney_lenta";

$file="agregator-oney_blocktv"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=IndexPageTV(); SetCache($file, $text, ""); }
$Page["TopContent"]=$text.$C5;

### Центральная колонка
$tableall=array();
$tableall["redak"]	=	array(1,2,3); // Типы новостей в редакционные колонки 
$tableall["news"]	=	array(1,2,3,4,5,6,7,8,9,10); // Типы новостей (категории) для тизеров на главной

$file="agregator-oney_maincontent"; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=IndexPageCenter(); SetCache($file, $text, ""); }
$Page["Content"]=$text.MostDiscussed(array("oney_lenta")).Updates(array("oney_lenta"));

################################################################################################################################################################################################

function IndexPageTV() {
	global $table, $used_id, $VARS, $GLOBAL, $Domains, $C10, $C15, $C, $C5, $GLOBAL; $text="";
	
	$tmp=explode("_", $table); $link=$tmp[0];
	$text.=$C5."<!-- LEFT PART START --><div class='MainTVSpec'>";
	### TV ####################################################################################################################################################################################################################################		
	$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'
	WHERE (`$table`.`stat`='1' && `$table`.`onind`='1') GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 3");
	if ($data["total"]>0) { @mysql_data_seek($data["result"], 0); $tv[0]=@mysql_fetch_array($data["result"]); 
		$path="http://".trim($Domains[$tv[0]["domain"]].".".$VARS["mdomain"], ".")."/".$tv[0]["link"]."/view/"; $text.="<div class='MainTV'>"; $used_id[$tv[0]["link"]][]=$tv[0]["id"];
		$text.="<div id='MainTVPic'><a href='".$path.$tv[0]["id"]."'><img src='/userfiles/picintv/".$tv[0]["pic"]."'></a><div><h1><a href='".$path.$tv[0]["id"]."'>".$tv[0]["name"]."</a></h1></div></div>";
		$text.="<div class='MainTVList'>";	
		for($i=1; $i<3; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $tv[$i]=$ar; 
			$item=$tv[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/"; if ($i==0) { $class='2'; } else { $class='1'; }
			$text.="<div class='MainTVListItem' id='MainTV-".$item[id]."'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."'></a><div><a href='".$path.$item["id"]."'><b>".$item["name"]."</b></a></div></div>";
			$used_id[$item["link"]][]=$item["id"]; if ($i==2) { $text.=$C; } else { $text.="<div class='C10'></div>"; }
		}
		$text.="</div>".$C."</div>".$C15."<div class='CBG2'></div>".$C10;
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
		$text.="<div class='MainSpecBlock'><a href='".$path.$item["id"]."'><img src='/userfiles/picsquare/".$item["pic"]."' title='".$item["name"]."'></a><b><a href='".$path.$item["id"]."'>".$item["name"]."</a></b></div>";
		$used_id[$item["link"]][]=$item["id"]; if ($i==2) { $text.=$C; } else { $text.="<div class='CL'></div>"; }} $text.="</div>";
	}
	$text.="</div><!-- LEFT PART END -->";
	### Main Banner 240*400 
	$text.="<div class='banner' style='float:right' id='Banner-1-1'></div>".$C;
	
	### Гороскоп 
	$data=DB("SELECT id, name FROM `horoscope_lenta` WHERE (`stat`=1 AND (`astat`<>1 || (`astat`=1 AND `data`<=".$GLOBAL['now']."))) ORDER BY `data` DESC LIMIT 1"); if ($data["total"]>0) { @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
	$text.='<div class="WhiteBlock"><h3 align="center"><a href="/horoscope/view/'.$ar["id"].'">'.$ar["name"].'</a></h3>'; foreach ($GLOBAL["zodiac"] as $key => $value): 
	$text.='<div class="PreHoro"><a href="/horoscope/view/'.$ar["id"].'/#'.$key.'"><img src="/userfiles/images/zodiac/'.$key.'.jpg" /><br>'.$value["name"].'</a></div>'; endforeach; $text.=$C.'</div>'.$C15; }
	
	return(array($text, ""));
}

################################################################################################################################################################################################

function IndexPageCenter() {
	global $tableall, $table, $used_id, $VARS, $GLOBAL, $Domains, $C10, $C15, $C, $C20, $C5, $UserSetsSite; $text=""; $redak=array();
	
	############################################
	$text.="<div class='banner' id='Banner-6-1'></div>";
	############################################
	
	#### Редакционная колонка
	$tables=$tableall["redak"]; $tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ","); $q="";
	foreach($tables as $cat) { $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`uid`,  `$table`.`data`, `$table`.`cat`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`, `".str_replace("_lenta", "_cats", $table)."`.`name` as `cname`
	FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table`.`uid`  LEFT JOIN `".str_replace("_lenta", "_cats", $table)."` ON `".str_replace("_lenta", "_cats", $table)."`.`id`=`$table`.`cat`
	WHERE (`$table`.`cat`='".$cat."' &&  `$table`.`stat`='1' && `$table`.`redak`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 LIMIT 1) UNION "; }
	$data=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 3"); for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $redak[]=$ar; }
	$ident="MainRedaktor"; $text.="<div id='MainRedaktor'><div id='".$ident."-0' class='".$ident."'>"; for($i=0; $i<count($redak); $i++) { $item=$redak[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
	$text.="<div style='float:left;'><div class='MainRazdelName'><a href='/$link/cat/$item[cat]' title='".$item["cname"]."'>".$item["cname"]."</a></div><div class='MainRedaktorItem'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a>".$C."
	<div class='MainRedaktorAuth'><a href='/users/view/".$item["uid"]."'><img src='/".$item["avatar"]."' /></a><a href='".$path.$item["id"]."'>".$item["name"]."</a><b><a href='/users/view/".$item["uid"]."'>".$item["nick"]."</a></b></div></div></div>";
	$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C; }} $text.="</div></div>".$C10;

	############################################
	$text.="<div class='banner' id='Banner-6-2'></div>";
	############################################
	
	#### 3 коммерческие новости на главную
	$redak2=array(); $NOTIN=trim("0,".implode(",", $used_id[$link]), ","); 
	$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`
	FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table`.`uid`
	WHERE (`$table`.`stat`='1' && `$table`.`promo`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 3");
	for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $redak2[]=$ar; }

	if (count($redak2)>=3) { $count=3; } else { $count=count($redak2); } $knopki=""; $ident="MainCommerse";
	for($i=0; $i<$count; $i++) { $item=$redak2[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
		$text.="<div class='MainRedaktorItem2'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a><div class='CapItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a></div></div>";
		$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C; }
	} $text.=$C20;

	############################################
	$text.=TatBrand(0).$C20;
	############################################
	
/*
 		
	#### конкурсы на главную
	$tables=$tableall["concurs"]; $redak3=array();
	foreach($tables as $table) {
		$tmp=explode("_", $table); $link=$tmp[0]; $NOTIN=trim("0,".implode(",", $used_id[$link]), ","); 
		$data=DB("SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `_users`.`nick`, `_users`.`avatar`, `_users`.`id` as `uid2`
		FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' LEFT JOIN `_users` ON `_users`.`id`=`$table`.`uid`
		WHERE (`$table`.`stat`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 3");
		for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $redak3[]=$ar; }
	}
	$ars=array(); foreach($redak3 as $key=>$arr){ $ars[$key]=$arr['data']; } array_multisort($ars, SORT_DESC, $redak3);
	if (count($redak3)>=3) { $count=3; } else { $count=count($redak3); } $knopki=""; $ident="MainConcurs";
	for($i=0; $i<$count; $i++) { $item=$redak3[$i]; $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
		$text.="<div class='MainRedaktorItem3'><a href='".$path.$item["id"]."'><img src='/userfiles/picnews/".$item["pic"]."' title='".$item["name"]."'></a><div class='CapItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a></div></div>";
		$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C; }
	} $text.=$C20;
*/


	############################################
	$text.="<div class='banner' id='Banner-6-3'></div>";
	############################################
		
	#### Новостные ленты
	$cats=$tableall["news"];
	$NOTIN=trim("0,".implode(",", $used_id[$link]), ","); 
	foreach($cats as $cat) {
		$data=DB("SELECT `$table`.`id`, `$table`.`comments`, `$table`.`cat`, `$table`.`comcount`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link`, `".str_replace("_lenta", "_cats", $table)."`.`name` as `catname` FROM `$table`
		LEFT JOIN `".str_replace("_lenta", "_cats", $table)."` ON `".str_replace("_lenta", "_cats", $table)."`.`id`=`$table`.`cat` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`cat`='".$cat."' && `$table`.`promo`!='1' && `$table`.`stat`='1' && `$table`.`id` NOT IN (".$NOTIN.")) GROUP BY 1 ORDER BY `$table`.`data` DESC LIMIT 15");
		if ($data["total"]>0) {
			if ($data["total"]>=15) { $count=15; } else { $count=$data["total"]; } $knopki=""; $ident="Main".$link; @mysql_data_seek($data["result"], 0); $tmp=@mysql_fetch_array($data["result"]);
			for ($j=0; $j<ceil($count/3); $j++) { if ($j==0) { $class="bg"; } else { $class=""; } $knopki="<div id='".$ident."-knopka-".$j."' class='".$ident."-knopka ".$class."' onclick=\"ShowOtherBox('".$j."', '".$ident."');\" title='Страница: ".($j+1)."'></div>".$knopki; }
			$text.="<div class='MainRazdelName'><a href='http://".trim($Domains[$tmp["domain"]].".".$VARS["mdomain"], ".")."/".$tmp["link"]."/cat/".$tmp["cat"]."'>".$tmp["catname"]."</a>".$knopki."</div><div id='MainRedaktor'><div id='".$ident."-0' class='".$ident."'>";
			for($i=0; $i<$count; $i++) { @mysql_data_seek($data["result"], $i); $item=@mysql_fetch_array($data["result"]); $path="http://".trim($Domains[$item["domain"]].".".$VARS["mdomain"], ".")."/".$item["link"]."/view/";
			if ($item["comments"]!=2 && $UserSetsSite[3]==1) { $coms="<br><b><a href='".$path.$item["id"]."#comments'>Комментарии</a>: ".$item["comcount"]."</b>"; } else { $coms=""; }
			if ($item["pic"]!="") { if (strpos($item["pic"], "old")!=0) { $pic="<img src='".$item["pic"]."' />"; } else { $pic="<img src='/userfiles/picnews/".$item["pic"]."' />"; }}
			$text.="<div class='MainRedaktorItem'><a href='".$path.$item["id"]."'>".$pic."</a>".$C."<div class='MainRedaktorAuth NewItem'><a href='".$path.$item["id"]."'>".$item["name"]."</a>".$coms."</div></div>";
			$used_id[$item["link"]][]=$item["id"]; if (($i+1)%3!=0) { $text.="<div class='CL2'></div>"; } else { $text.=$C."</div><div class='".$ident."' id='".$ident."-".round(($i+1)/3)."' style='display:none;'>"; }
			} $text.="</div></div>".$C;
			############################################
			$jj++; $text.="<div class='banner' id='Banner-6-".(3+$jj)."'></div>";
			############################################
		}		
	}	
	

	
	
	
	
	
	
	
	
	
	
	return(array($text, ""));
}

################################################################################################################################################################################################
?>