<?
$table="_tags"; if ($start=="") { $start=0; $dir[1]=0; } $file=$table."-".$start.".".$page.".".$id;

#############################################################################################################################################
### Вывод списка новостей в категории
if ($start===0) {
	$file="_tags-mcloud"; if (RetCache($file)=="true") { list($tags, $cap)=GetCache($file, 0); } else { list($tags, $cap)=TagsCloud(); SetCache($file, $tags, ""); }	
	$cap="Теги публикаций"; $Page["Content"]=$tags; $Page["Caption"]=$cap;
}

### Вывод списка новостей общий
else {
	$data=DB("SELECT `name` FROM `".$table."` WHERE (`id`='".(int)$dir[1]."') LIMIT 1");
	if ($data["total"]==1){
		@mysql_data_seek($data["result"], 0); $tag=@mysql_fetch_array($data["result"]); list($text, $cap)=GetLentaList();
		//if (RetCache($file)=="true") { list($text, $cap)=GetCache($file); } else { list($text, $cap)=GetLentaList(); SetCache($file, $text, $cap); }
		$Page["Content"]=$text; $Page["Caption"]=$cap;		
	}
	else {
		$cap="Тег не найден";
		$text=@file_get_contents($ROOT."/template/404.html");
		$Page["Content"]=$text; $Page["Caption"]=$cap;
	}	
}

#############################################################################################################################################

function GetLentaList() {
	global $ORDERS, $VARS, $ROOT, $GLOBAL, $dir, $RealHost, $Page, $node, $UserSetsSite, $table, $tag, $C, $C20, $C10, $C25, $C15;$query = ''; $orderby=$ORDERS[$node["orderby"]];$tables = array();
	$onpage=30; $pg = $dir[2] ? $dir[2] : 1;  $from=($pg - 1)*$onpage; $onblock=4;
	
	$q="SELECT `[table]`.`id`, `[table]`.`uid`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`onind`, `_users`.`nick`, '[link]' as `link`
	FROM `[table]` LEFT JOIN `_users` ON `_users`.`id`=`[table]`.`uid` WHERE (`[table]`.`stat`='1' && `[table]`.`tags` LIKE '%,".(int)$dir[1].",%')";
	$endq="ORDER BY `data` DESC LIMIT ".$from.", ".$onpage; $data=getNewsFromLentas($q, $endq);
		
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
		if ($ar["pic"]!="") { if (strpos($ar["pic"], "old")!=0) { /*Старый вид картинок*/ $pic="<img src='".$ar["pic"]."' title='".$ar["name"]."' />"; } else { /*Новый вид картинок*/ $pic="<img src='/userfiles/pictavto/".$ar["pic"]."' title='".$ar["name"]."' />"; }}
		if ($ar["uid"]!=0 && $ar["nick"]!="") { $auth="<a href='/users/view/".$ar["uid"]."/'>".$ar["nick"]."</a>"; } else { $auth="<a href='/add/2/'>Народный корреспондент</a>"; }
		if ($UserSetsSite[3]==1 && $ar["comments"]!=2) { $coms="<div class='CommentBox'><a href='/".$ar["link"]."/view/".$ar["id"]."#comments'>".$ar["comcount"]."</a></div>"; } else { $coms=""; }
		$text.="<div class='NewsLentaList' id='NewsLentaList-".$ar["id"]."'><a href='/".$ar["link"]."/view/".$ar["id"]."'>".$pic."</a><h2><a href='/".$ar["link"]."/view/".$ar["id"]."'>".$ar["name"]."</a></h2>".$C."
		<div class='Info'><div class='Other'>".Replace_Data_Days($d[4]).",  Автор: ".$auth."</div>".$coms."</div></div>";
		if($data["total"]>($i+1)){ if (($i+1)%$onblock==0) { $text.=$C25."<div class='banner2' id='Banner-6-".(floor($i/$onblock)+1)."'></div>".$C; } else { $text.=$C25; }}
	}

	
	$q="SELECT `[table]`.`id` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`tags` LIKE '%,".(int)$dir[1].",%')"; $endq="";
	$data=getNewsFromLentas($q, $endq); $total=$data["total"]; $text.=Pager2($pg, $onpage, ceil($total/$onpage), $dir[0]."/".$dir[1]."/[page]"); return(array($text, $tag['name']));
}

#############################################################################################################################################


?>