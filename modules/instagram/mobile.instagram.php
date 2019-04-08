<?
if ($start=="0") { header("location: /".$link."/new"); exit(); }

$static=array("new", "best");

if (in_array($start, $static))	{ $modlink=$start;
} elseif((int)$start!=0) 		{ $modlink="viewpost"; 
} else 							{ $modlink=''; }

#####################################################################################################################################################################################################

$modfile="modules/".$node["module"]."/mobile.".$node["module"]."-".$modlink.".php";

if (is_file($modfile) && $modlink!="") {

	$file=$link."-".$start.".".$page.".".$id;
	if (RetCache($file)=="true") {
		list($text,$cap,$kw,$ds)=GetCache($file); $cachestat="Взято из кэша"; $ner=1;
	} else {
		@require($modfile); list($text,$cap,$kw,$ds,$ner)=GetInstaContent(); $cachestat="Вывод без кэша";
		if ($ner==1){ SetCache($file,$text,$cap,$kw,$ds); }else{ $cap="Материал не найден"; $text=@file_get_contents($ROOT."/template/404.html"); }
	}
	$Page["Caption"]=$cap; $Page["Content"]=$edit.$text; $Page["KeyWords"]=$kw; $Page["Description"]=$ds; $GLOBAL["log"].="<i>Подключение PHP</i>: модуль «".$modfile."» раздела «".$link."» подключен<hr>";

} else {

	$Page["Caption"]="Материал не найден"; $Page["Content"]=@file_get_contents($ROOT."/template/404.html");
	$GLOBAL["log"].="<u>Подключение PHP</u>: модуль «".$modfile."» раздела «".$link."» не найден<hr>";

}

#####################################################################################################################################################################################################
?>