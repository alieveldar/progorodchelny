<?
$linked="ls"; $tab="ls"; $tab2="afisha"; $onpage=120; $pg=$dir[1]?$dir[1]:1; $from=($pg-1)*$onpage; $dir[0]=$link;
$file="_index-kazan72ya.".$pg; if (RetCache($file,'cacheblock')=="true") { list($text,$cap)=GetCache($file,0); }else{ list($text,$cap)=Kazan7IndexPage(); SetCache($file,$text,'','cacheblock'); }
#list($text,$cap)=Kazan7IndexPage(); 


$Page["TopContent"]=$text; $Page["Caption"]=""; $Page["Content"]=""; $Page["LeftContent"]=""; $Page["RightContent"]="";
$Page["Title"]=$node["name"];

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ###

function Kazan7IndexPage(){
	global $VARS, $GLOBAL, $C10, $C15, $C20, $C25, $C, $linked, $tab, $tab2, $dir, $qs, $qe, $used, $pg, $from, $onpage; $used=array(0); $ban31=1; $text=$C10;
	$qs="SELECT `".$tab."_lenta`.`id`,`".$tab."_lenta`.`cat`,`".$tab."_lenta`.`pic`,`".$tab."_lenta`.`name`,`".$tab."_lenta`.`comcount`,`".$tab."_cats`.`name` as `cname` FROM `".$tab."_lenta` LEFT JOIN `".$tab."_cats` ON `".$tab."_cats`.`id`=`".$tab."_lenta`.`cat`";
	$qe="GROUP BY 1 ORDER BY `".$tab."_lenta`.`data` DESC";
	
	// TV -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	
	$data=DB("(SELECT `".$tab."_lenta`.`id`,`".$tab."_lenta`.`data`,`".$tab."_lenta`.`cat`,`".$tab."_lenta`.`pic`,`".$tab."_lenta`.`name`,`".$tab."_lenta`.`comcount`,`".$tab."_cats`.`name` as `cname`, '".$tab."' as `link` FROM `".$tab."_lenta` LEFT JOIN `".$tab."_cats` ON `".$tab."_cats`.`id`=`".$tab."_lenta`.`cat` WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`gis`=1) GROUP BY 1) UNION (SELECT `".$tab2."_lenta`.`id`,`".$tab2."_lenta`.`data`,`".$tab2."_lenta`.`cat`,`".$tab2."_lenta`.`pic`,`".$tab2."_lenta`.`name`,`".$tab2."_lenta`.`comcount`,`".$tab2."_cats`.`name` as `cname`, '".$tab2."' as `link` FROM `".$tab2."_lenta` LEFT JOIN `".$tab2."_cats` ON `".$tab2."_cats`.`id`=`".$tab2."_lenta`.`cat` WHERE (`".$tab2."_lenta`.`stat`=1 && `".$tab2."_lenta`.`gis`=1) GROUP BY 1) ORDER BY `data` DESC LIMIT 1");	
	if ($data["total"]==1 && $pg==1) { @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $used[]=$ar["id"];
		$text.="<div class='main7p'>";
			$text.="<div class='mainp'>";
				$text.="<a href='/".$ar["link"]."/view/".$ar["id"]."'><img src='/userfiles/semya/".$ar["pic"]."' />";
				$text.="<div class='black'></div><div class='mainname'>".$ar["name"]."</div></a>";
				$text.="<div class='maincat'><a href='".$ar["link"]."/cat/".$ar["cat"]."'>".$ar["cname"]."</a></div>";
			$text.="</div>";
			$text.="<div class='ubanner'><div class='banner' id='Banner-33-1'></div></div>";
		$text.="</div>".$C;
	}
	
	// СЕТКА: НОВОЕ + РЕКЛ + СПЕЦ -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	$resnews=array(); $is=0; $spec=array(); $in=0; $new=array();
	if ($pg==1) { 
	/* РЕК */
	$data=DB($qs." WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`promo`=1 && `".$tab."_lenta`.`data`>'".(time()-3*24*60*60)."' && `".$tab."_lenta`.`id` NOT IN (".implode(",",$used).")) ".$qe." LIMIT 5");
	$total=$data["total"]; for ($i=0; $i<$total; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $used[]=$ar["id"]; $spec[]=$ar; endfor;

	/* СПЕЦ */ $data=DB($qs." WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`promo`!=1 && `".$tab."_lenta`.`spec`=1 && `".$tab."_lenta`.`id` NOT IN (".implode(",",$used).")) ".$qe." LIMIT ".(5-$total));
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $used[]=$ar["id"]; $spec[]=$ar; endfor;
	}
	
	/* НОВОЕ */ $new=array(); 
	
	$data=DB("(SELECT `".$tab."_lenta`.`id`,`".$tab."_lenta`.`data`,`".$tab."_lenta`.`cat`,`".$tab."_lenta`.`pic`,`".$tab."_lenta`.`name`,`".$tab."_lenta`.`comcount`,`".$tab."_cats`.`name` as `cname`, '".$tab."' as `link` FROM `".$tab."_lenta` LEFT JOIN `".$tab."_cats` ON `".$tab."_cats`.`id`=`".$tab."_lenta`.`cat` WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`promo`!=1 && `".$tab."_lenta`.`id` NOT IN (".implode(",",$used).")) GROUP BY `".$tab."_lenta`.`id`) UNION (SELECT `".$tab2."_lenta`.`id`,`".$tab2."_lenta`.`data`,`".$tab2."_lenta`.`cat`,`".$tab2."_lenta`.`pic`,`".$tab2."_lenta`.`name`,`".$tab2."_lenta`.`comcount`,`".$tab2."_cats`.`name` as `cname`, '".$tab2."' as `link` FROM `".$tab2."_lenta` LEFT JOIN `".$tab2."_cats` ON `".$tab2."_cats`.`id`=`".$tab2."_lenta`.`cat` WHERE (`".$tab2."_lenta`.`stat`=1 && `".$tab2."_lenta`.`promo`!=1 && `".$tab2."_lenta`.`id` NOT IN (".implode(",",$used).")) GROUP BY `".$tab2."_lenta`.`id`) ORDER BY `data` DESC LIMIT ".$from.", ".$onpage);
	
	//$data=DB($qs." WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`promo`!=1 && `".$tab."_lenta`.`id` NOT IN (".implode(",",$used).")) ".$qe." LIMIT ".$from.", ".$onpage);
	
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $new[]=$ar; endfor;
	

	/* СЕТКА */ for($i=0; $i<$data["total"]; $i++) { if ((($i+1)%3==0 || ($i+1)==5) && (int)$spec[$is]["id"]!=0) { $resnews[]=$spec[$is]; $is++; } else { $resnews[]=$new[$in]; $in++; }}
	
	// ВЫВОД: НОВОЕ + РЕКЛ + СПЕЦ  -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	$i=1; $text.="<div>"; foreach($resnews as $ar) { $used[]=$ar["id"];
	$text.='<!-- ' . var_export($ar['cat'], 1) . '-->'; 
	if ($ar['cat'] == 7) $linked = 'afisha';
	else $linked = 'ls';
	$text.=Semya3td($ar, $linked, 1); if ($i%3==0) { $text.="<clear></clear>"; }
	if ($i%6==0 && $i<115) {
		$text.="</div><div class='bannerbox'><div class='banner' id='Banner-31-".$ban31."'></div></div><div><clear></clear>"; $ban31++;	
	} $i++; } 
	$text.="</div>";
	
	$data=DB("SELECT `".$tab."_lenta`.`id` FROM `".$tab."_lenta` WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`promo`!=1)"); $cnt=$data["total"]; 
	$text.="<clear></clear><div><clear></clear>".Pager2($pg, $onpage, ceil($cnt/$onpage), $dir[0]."/[page]")."<clear></clear></div>";
	
	return array($text,$cap);
}

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ###

function CatViewSemya($cat) { global $used, $linked, $qs, $qe, $tab;
	$raz=array(); $q=$qs." WHERE (`".$tab."_lenta`.`stat`=1 && `".$tab."_lenta`.`cat`=".$cat." && `".$tab."_lenta`.`id` NOT IN (".implode(",",$used).")) ".$qe." LIMIT 9";  $data=DB($q);
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $raz[]=$ar; $used[]=$ar["id"]; endfor;
	$text.="<h1 class='Cat7'><a href='/".$linked."/cat/".$ar["cat"]."'>".$ar["cname"]."</a></h1><div><div>"; $i=1; foreach($raz as $ar) { $text.=Semya3td($ar, $linked); if ($i%3==0) { $text.="<clear></clear>"; } 
	if($i==3) { $text.="</div><div class='showmore'><a href='javascript:void(0);' onclick=\"$('#hidden".$ar["cat"]."').slideDown(500); $(this).parent().hide();\">Показать больше</a></div><div class='hidden' id='hidden".$ar["cat"]."'>"; }
	$i++; } $text.="</div></div>"; return $text;	
}

?>