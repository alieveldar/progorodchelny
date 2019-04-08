<?
$table="ls_lenta"; $table2="_widget_pics"; $table3="_widget_votes"; $table4="_widget_video"; $table5="_widget_voting"; $table6="_widget_contacts"; $table7="_widget_eventmap";
if ($start=="") { header("location: /"); exit(); } $file=$table."2-".$start.".".$page.".".$id; $dir[0]="ls"; $node["onpage"]=33; $node["orderby"]="ORDER BY `data` DESC";
 
// BAN-30-2 -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
$Page["BottomContent"]="<div class='bannerbox2'><div class='banner' id='Banner-30-2'></div></div>".$C;

if ($link=="ls" && (int)$page==2216) { @header("location: /afisha2504"); exit(); }
if ($link=="ls" && (int)$page==2215) { @header("location: /detoks"); exit(); }
if ($link=="ls" && (int)$page==2231) { @header("location: /afisha_may_1_9"); exit(); }
//if ($link=="ls" && (int)$page==337) { @header("location: /afisha/view/337"); exit(); }
//if ($link=="ls" && (int)$page["cat"]==22) { @header("location: /afisha/view/$page"); exit(); }



#############################################################################################################################################
### Вывод списка новостей общий
// if ($start=="list") { if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaList(); SetCache($file, $text, ""); } $Page["Content"]=$text; $Page["Caption"]="Самое свежее и интересное в «Семье»"; }
list($text, $cap)=GetLentaList();

### Вывод списка новостей в категории
//if ($start=="cat") { if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=GetLentaCat(); SetCache($file, $text, $cap); }}
list($text, $cap)=GetLentaCat(); $Page["Content"]=$text; $Page["Caption"]=$cap; $ShowCap=1; 
if ($link=="ls" && (int)$dir[2]==33) { $node["specdesign"]="shite"; }




### Вывод новости
if ($start=="view") {
	$where=$GLOBAL["USER"]["role"]==0?"&& `stat`=1":"";
	$data=DB("SELECT `id`,`comments`, `promo`,`alttext`,`name`,`cat` FROM `".$table."` WHERE (`id`='".(int)$dir[2]."' ".$where.") LIMIT 1");
	if ($data["total"]==1) {
		@mysql_data_seek($data["result"], 0); $new=@mysql_fetch_array($data["result"]); UserTracker($link, $page);
		if ((int)$new["cat"]==33) { $node["specdesign"]="shite"; }
if ($new["alttext"]=="") { 
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- 		
		
		if (RetCache($file)=="true") { list($text, $cap)=GetCache($file); } else { list($text, $cap)=GetLentaId(); SetCache($file, $text, $cap); }
		$text.=$C20."<div class='banner' id='Banner-31-2'></div>";
		$text.=$C20.UsersComments($link, $page, $new["comments"]); 
		$text.=$C20."<div class='banner' id='Banner-31-3'></div>";
		### Читайте также 
		$text.=$C15.'<h2 class="Cat7">Самое новое</h2>'; $p=DB("SELECT `id`,`name`,`pic` FROM `".$table."` WHERE (`stat`=1 && `id`!='".(int)$dir[2]."') ORDER BY `data` DESC LIMIT 6");
		$text.="<div>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
		$text.="<div class='sem3td rm'><a href='/".$link."/view/".$ar["id"]."' title='$ar[name]'><img src='/userfiles/pictavto/$ar[pic]'>$ar[name]</a></div>";
		if (($i+1)%3==0) { $text.="<clear></clear>"; } endfor; $text.="</div>"; $text.=$C10;
		if ($GLOBAL["USER"]["role"]>1) { $text=$C10."<div id='AdminEditItem'><a href='".$GLOBAL["mdomain"]."/admin/?cat=".$link."_edit&id=".(int)$dir[2]."'>Редактировать</a></div>".$C15.$text; }

//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- 
} else { $text=$new["alttext"]; $cap=$new["name"]; $node["design"]="akbars"; }
//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- 	
	
	$Page["Content"]=$text; $Page["Title"]=$cap; $Page["Caption"]=$cap; } else { $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); $Page["Content"]=$text; $Page["Caption"]=$cap; }
}




###########################################################################################################################################
### ЛЕНТА НОВОСТЕЙ ВСЕХ ########################################################################################################################

function GetLentaList() {
	global $VARS, $GLOBAL, $dir, $link, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C15, $C25;
	$onpage=$node["onpage"]; $pg=$dir[3]?$dir[3]:1; $orderby=$node["orderby"]; $from=($pg-1)*$onpage; $onblock=5; $text="<div>"; $ban31=1;
	$q="SELECT `".$table."`.name, `".$table."`.cat, `".$table."`.pic, `".$table."`.data, `".$table."`.id, `".$table."`.comcount, `".$table."`.comments, `".$dir[0]."_cats`.`name` as `ncat` FROM `".$table."`
	LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='".(int)$dir[2]."' && `".$table."`.`stat`=1) GROUP BY 1 ".$orderby." LIMIT $from, $onpage"; $data=DB($q);
	for ($i=0; $i<$data["total"]; $i++) {
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $ncat=$ar["ncat"];
		if ($ar["pic"]!="") { $path="picintv"; if (($i+5)%5==0) { $path="semya"; } if (!is_file('userfiles/'.$path.'/'.$ar["pic"])) { $path="picitem"; }
		$pic="<img src='/userfiles/".$path."/".$ar["pic"]."' title='".$ar["name"]."' />"; } else { $pic=""; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<div class='CommentBox'><a href='/".$dir[0]."/view/".$ar["id"]."#comments'>".$ar["comcount"]."</a></div>"; } else { $coms=""; }
		$text.="<div class='Lenta7ya'><a href='/".$dir[0]."/view/".$ar["id"]."'>".$pic."<h2><a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2>".$C."</div>";
		if(($i+1)%5==0) { $text.="<clear></clear></div><div class='banner' id='Banner-31-".$ban31."'></div>".$C25.$C20."<div>"; $ban31++; } 
	}  $text.="<clear></clear></div>";
	$data=DB("SELECT count(id) as `cnt` FROM `".$table."` WHERE (`cat`='".(int)$dir[2]."')"); mysql_data_seek($data["result"],0); $ap=@mysql_fetch_array($data["result"]);
	$text.=Pager2($pg, $onpage, ceil($ap["cnt"]/$onpage), $dir[0]."/".$dir[1]."/".$dir[2]."/[page]"); return(array($text, ""));
}

##### КАТЕГОРИЯ НОВОСТЕЙ ########################################################################################################################################
function GetLentaCat() {
	global $VARS, $GLOBAL, $dir, $link, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C15, $C25;
	$onpage=$node["onpage"]; $pg=$dir[3]?$dir[3]:1; $orderby=$node["orderby"]; $from=($pg-1)*$onpage; $onblock=5; $text="<div>"; $ban31=1;
	$q="SELECT `".$table."`.name, `".$table."`.cat, `".$table."`.pic, `".$table."`.data, `".$table."`.id, `".$table."`.comcount, `".$table."`.comments, `".$dir[0]."_cats`.`name` as `ncat` FROM `".$table."`
	LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`cat`='".(int)$dir[2]."' && `".$table."`.`stat`=1) GROUP BY 1 ".$orderby." LIMIT $from, $onpage"; $data=DB($q);
	for ($i=0; $i<$data["total"]; $i++) {
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $ncat=$ar["ncat"]; $coms="";
		if ((int)$ar["comcount"]!=0) { $coms="<span class='Coms7ya'>".(int)$ar["comcount"]."</span>"; } 		
		if ($ar["pic"]!="") { $path="picintv"; if (($i+5)%5==0) { $path="semya"; } if (!is_file('userfiles/'.$path.'/'.$ar["pic"])) { $path="picitem"; }
		$pic="<img src='/userfiles/".$path."/".$ar["pic"]."' title='".$ar["name"]."' />"; } else { $pic=""; }
		#if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<div class='CommentBox'><a href='/".$dir[0]."/view/".$ar["id"]."#comments'>".$ar["comcount"]."</a></div>"; } else { $coms=""; }
		$text.="<div class='Lenta7ya'><a href='/".$dir[0]."/view/".$ar["id"]."'>".$pic."<h2>".$coms."<a href='/".$dir[0]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2>".$C."</div>";
		if(($i+1)%5==0) { $text.="<clear></clear></div><div class='banner' id='Banner-31-".$ban31."'></div>".$C25.$C20."<div>"; $ban31++; } 
	}  $text.="<clear></clear></div>";
	$data=DB("SELECT count(id) as `cnt` FROM `".$table."` WHERE (`cat`='".(int)$dir[2]."')"); mysql_data_seek($data["result"],0); $ap=@mysql_fetch_array($data["result"]);
	$text.=Pager2($pg, $onpage, ceil($ap["cnt"]/$onpage), $dir[0]."/".$dir[1]."/".$dir[2]."/[page]"); return(array($text, $ar["ncat"]));
} 

#############################################################################################################################################
function GetRelevantNews($art, $limit, $tags2) {
	return;
	global $dir, $table; $tab=$table; $dtags='<div class="Dtags">'; $r=rand(0, 4);
	/* новость из телека */	$tables=array("auto_lenta", "business_lenta", "news_lenta" , "sport_lenta", "concurs_lenta", "demotivators_lenta");
	foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q1.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`onind`='1') GROUP BY 1) UNION "; }	
	$data=DB(trim($q1, "UNION ")." ORDER BY `data` DESC LIMIT 6"); for($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $tv[]=$ar; } $new=$tv[$r];
	if ($new["name"]!="" && $new["name"]) { $d=ToRusData($new["data"]); $dtags.="<a href='/$new[link]/view/$new[id]/' title='".$new["name"]."'><img src='/userfiles/picintv/".$new["pic"]."' style='width:200px; height:110px; border:none; border-radius:5px; margin-bottom:7px;' title='".$new["name"]."' alt='".$new["name"]."' /></a><a href='/$new[link]/view/$new[id]/' title='".$new["name"]."'>".$new["name"]."</a><br><b>".$d[4]."</b><div class='C'></div><div class='CB'></div>"; }
	/* новости по тэгам */ $q=""; foreach ($art as $k=>$v) { if ($v!='') { $q.="`tags` LIKE '%,".$v.",%' OR "; }} $qr="SELECT `pic`,`data`,`name`,`id` FROM `".$tab."` WHERE ((".trim($q, "OR ").") AND (`id`!='".(int)$dir[2]."') AND (`stat`='1')) ORDER BY `data` DESC LIMIT ".$limit; $data2=DB($qr);
	if ($data2["total"]>0) { for ($i=0; $i<$data2["total"]; $i++): @mysql_data_seek($data2["result"],$i); $ar=@mysql_fetch_array($data2["result"]); $d=ToRusData($ar["data"]); $dtags.="<a href='/$dir[0]/view/$ar[id]/' title='".$ar["name"]."'>".$ar["name"]."</a><br><b>".$d[4]."</b><div class='C'></div><div class='CB'></div>"; endfor; $dtags.="<div class='C10'></div>Темы: ".$tags2; }
	$dtags.='</div>'; return $dtags;
}


####### ВЫВОД СОДЕРЖАНИЯ НОВОСТИ ######################################################################################################################################
function GetLentaId() {
	global $VARS, $GLOBAL, $dir, $RealHost, $Page, $node, $table, $table2, $table3, $table4, $table5, $table6, $table7, $link, $C, $C5, $C10, $C15, $C20, $ROOT, $forums; 
	
	
	### Основной запрос
	$data=DB("SELECT `".$table."`.*, `".$dir[0]."_cats`.`name` as `ncat`, `_users`.`nick`, `_users`.`avatar`, `$table5`.`id` as `vvid` FROM `".$table."`
	LEFT JOIN `_users` ON `".$table."`.`uid`=`_users`.`id` LEFT JOIN `$table5` ON `$table5`.`pid`=`$table`.`id` AND `$table5`.`link`='".$dir[0]."' AND `$table5`.`vid`='0' AND `$table5`.`stat`=1	
	LEFT JOIN `".$dir[0]."_cats` ON `".$dir[0]."_cats`.`id`=`".$table."`.`cat` WHERE (`".$table."`.`id`='".(int)$dir[2]."') GROUP BY 1 LIMIT 1"); @mysql_data_seek($data["result"], 0); $item=@mysql_fetch_array($data["result"]);

	$Page["Description"]=$item["ds"]; $Page["KeyWords"]=$item["kw"]; $cap=$item["name"];
	
	$text.=$C20;
	
	### Фотография
	if ($item["pic"]!="") {
		$text.="<div class='PicItem' title='$cap'>"; 
			$text.="<img src='/userfiles/picitem/".$item["pic"]."' title='$cap' alt='$cap' />";
			if ($item["cens"]!="") { $text.="<div class='Cens'>".$item["cens"]."</div>"; }
			if ($item["picauth"]!="") { $text.="<div class='PicAuth'>Фото: ".$item["picauth"]."</div>"; }
		$text.="</div>";
	}
	
	if ($item["lid"]!="") { $text.=$C20."<div class='ItemLid'>".$item["lid"]."</div>"; }
	if ($item["promo"]!=1 && (int)$item["id"]!=1579 && (int)$item["id"]!=1633) { $text.=$C20."<div class='banner' id='Banner-31-1'></div>"; }
	if ($item["text"]!="") { $text.=$C20.$item["text"]; }
	 
	### Фото-отчет
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='report' && `stat`=1) order by `rate` ASC"); $report='';
	if ($p["total"]>0) { for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]); $report.=$C20; 
	$report.="<h3>".$ar["name"]."</h3>".$C10."<a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' class='ReportPicBig'></a>".$C10;
	if ($ar["text"]!="") { $report.=$ar["text"]; } $report.=$C; endfor; $report.=$C; } $text.=$report;
	
	
	### Фото-альбом
	$p=DB("SELECT * FROM `".$table2."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."' && `point`='album' && `stat`=1) order by `rate` ASC");
	if ($p["total"]>0) { $text.=$C20."<h3>Фотоальбом:</h3>$C10<div class='ItemAlbum'>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
	$text.="<a href='/userfiles/picoriginal/".$ar["pic"]."' title='".$ar["name"]."' rel='prettyPhoto[gallery]'><img src='/userfiles/pictavto/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."'></a>"; endfor; $text.="</div>".$C; }
	
	### Голосование
	if ((int)$item["vvid"]!=0) { $text.=$C20."<div id='ItemVotingDiv'></div><script>GetItemVoting(".(int)$item["vvid"].");</script>".$C5; }
	
	### Видео
	$p=DB("SELECT * FROM `".$table4."` WHERE (`pid`='".(int)$dir[2]."' && `link`='".$dir[0]."') LIMIT 1");
	if ($p["total"]>0) { for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
	if ($ar["text"]!="") { if ($ar["name"]!="") { $text.="<h3>".$ar["name"]."</h3>"; } $vid=GetNormalVideo($ar["text"]); $text.=$C15.$vid.$C15; } endfor; }

	### Заключительный текст
	if ($item["endtext"]!="") { $text.=$C20."<div class='TextQuot'>".$item["endtext"]."</div>".$C10; }		

	### Лого и контакты
	$cdata=DB("SELECT * FROM `".$table6."` WHERE (`pid`=".$item['id']." AND `link`='".$link."')"); if($cdata["total"]) { @mysql_data_seek($cdata["result"],0); $con=@mysql_fetch_array($cdata["result"]);
	if($con['name']){ $contacts = $C10.'<div class="WhiteBlock">'; if ($con["pic"]!="") { $contacts .= "<div style='float:left; margin-right:10px;'><img src='/userfiles/picpreview/".$con["pic"]."' title='".$con["name"]."' width='80' /></div>"; }
	$contacts .= "<h4>".$con["name"]."</h4><p class='contacts'><img src='/template/standart/address.png' style='vertical-align:middle;' />"; if($con["address"]) { $contacts.="<strong class='address'>".$con["address"]."</strong>"; }
	if($con["address"] && $con["phone"]) { $contacts.="<strong class='address'>. </strong>"; } if($con["phone"]) { $contacts.="<strong class='phone'>тел: <span>".$con["phone"]."</span></strong>"; } $contacts.="</p>".nl2br($con["anonce"]).$C.'</div>'; }}
	$text.=$contacts;
	
	### Лайки 
	$text.=$C10."<div class='Likes' style='text-align:center;'>".Likes(Hsc($cap), "", "http://".$RealHost.$path, Hsc(strip_tags($lid))).$C."</div>".$C10;
	
	### Читайте также
	$text.='<h2 class="Cat7">Читайте также:</h2>'; $p=DB("SELECT `id`,`name`,`pic` FROM `".$table."` WHERE (`stat`=1 && `cat`='".$item["cat"]."' && `id`!='".(int)$dir[2]."') ORDER BY `data` DESC LIMIT 3");
	$text.="<div>"; for ($i=0; $i<$p["total"]; $i++): mysql_data_seek($p["result"],$i); $ar=@mysql_fetch_array($p["result"]);
	$text.="<div class='sem3td rm'><a href='/".$link."/view/".$ar["id"]."' title='$ar[name]'><img src='/userfiles/pictavto/$ar[pic]'>$ar[name]</a></div>"; endfor; $text.="</div>"; $text.=$C10;
	
	### Тэги
	$t=trim($item["tags"], ","); $tags=""; if ($t!="") { $ta=DB("SELECT * FROM `_tags` WHERE (`id` IN (".$t.")) LIMIT 3"); for ($i=0; $i<$ta["total"]; $i++): @mysql_data_seek($ta["result"],$i); $ar=@mysql_fetch_array($ta["result"]);
	$tags.="<a href='/tags/$ar[id]'>$ar[name]</a>, "; endfor; $tags2=trim($tags, ", "); $tags="Тэги: ".trim($tags, ", "); } $mixblock.="<div class='ItemTags'>".$tags."</div>".$C10;
	
	### Аватар автора, Автор и дата
	if ($item["avatar"]=="" || !is_file($ROOT."/".$item["avatar"]) || filesize($ROOT."/".$item["avatar"])<100) { $avatar="<img src='/userfiles/avatar/no_photo.jpg'>"; } else { $avatar="<img src='/".$item["avatar"]."'>"; }
	$d=ToRusData($item["data"]); if ($item["uid"]!=0 && $item["nick"]!="") { $auth=$avatar."Автор: <a href='http://".$VARS["mdomain"]."/users/view/".$item["uid"]."/'>".$item["nick"]."</a>, ".$d[1]; } else { $auth="<img src='/userfiles/avatar/no_photo.jpg' />Автор: Народный корреспондент, ".$d[1]; }
	$mixblock.="<div class='ItemAuth'>".$auth."<br />Если Вы нашли ошибку, <u>выделите фразу с ошибкой</u> и нажмите Ctrl+Enter</div>";
	$text.=$mixblock;
	

	
	### Платные ссылки
	if ($item["adv"]!="") { $text.=$C10."<div class='CBG'></div>".$C5."<div class='AdvBlock'>".$item["adv"]."</div>".$C; }


	return(array($text, $cap));
}


?>