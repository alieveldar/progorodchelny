<?
//session_start();
$StartTime2=GetMicroTime2(); 
$result["Code"]=0; $cache=600; $now=time(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2]; $uip=$_SERVER["REMOTE_ADDR"]; $tabs=array(); $log="";
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/advert/users")){ @mkdir($_SERVER['DOCUMENT_ROOT']."/advert/users", 0777); @chmod($_SERVER['DOCUMENT_ROOT']."/advert/users", 0777); }
### сдвиги для каждого баннерного места по текущему пользователю
if (file_exists($_SERVER['DOCUMENT_ROOT']."/advert/users/".$uip)) { $t=explode(";", @file_get_contents($_SERVER['DOCUMENT_ROOT']."/advert/users/".$uip)); 
foreach ($t as $str) { if ($str!="") { $ar=explode(":", $str); $pid=$ar[0]; $tabs[$pid]=$ar[1]; }}}
	
$GLOBAL["sitekey"]=1; $text=""; $file=""; $all=array(); $ban=array(); $rate=array(); $setka=array();
@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php"; $JsHttpRequest=new JsHttpRequest("utf-8");
# id; pid; did; prior; flash; pic; mobile; link; link2; link3; w; h;outer;  text
// полученные данные ==================================================================
$R = $_REQUEST; $d=(int)$R["domain"];
	
	/* КЭШ */
	$path=$_SERVER['DOCUMENT_ROOT']."/advert/cache/domain-".$d.".dat"; #$log.=$path."\r\n".file_get_contents($path);
	if (is_file($path) && (time()-filemtime($path))<$cache) { $tmp=explode("<|>", @file_get_contents($path));
		foreach ($tmp as $item) { if ($item!="") { list($pid, $data)=explode("<===>", $item); $text[$pid]=explode("<&>", $data); }}
		
		/* проверяем на сколько сдвинуть баннеры */
		foreach ($text as $key=>$array) {
			$tab=(int)$tabs[$key]+1; #$log.="BEFORE TAB=".(int)$tab." [$key] => ".getids($array)."\r\n";
			$text[$key]=array_merge(array_slice($array, $tab), array_slice($array, 0, $tab));		
			if ($tab>=count($text[$key])) { $tab=0; } $tabs[$key]=$tab; # $log.="AFTER TAB=".$tab." [$key] => ".getids($result)."\r\n"."\r\n";
		}
		/* сохраняем сдвиг баннеров для этого пользователя */
		$txt=""; foreach($tabs as $k=>$v) { $txt.=$k.":".$v.";"; } $txt=trim($txt, ";"); @file_put_contents($_SERVER['DOCUMENT_ROOT']."/advert/users/".$uip, $txt);
	} else {
		// загружаем список баннеров ==========================================================
		if (is_file($_SERVER['DOCUMENT_ROOT']."/advert/domains/domain-9999.dat")) { $file.=@file_get_contents($_SERVER['DOCUMENT_ROOT']."/advert/domains/domain-9999.dat"); }
		if (is_file($_SERVER['DOCUMENT_ROOT']."/advert/domains/domain-".$d.".dat")) { $file.=@file_get_contents($_SERVER['DOCUMENT_ROOT']."/advert/domains/domain-".$d.".dat"); }
		// mobile and desktop now work the same
        if ($d==0 && is_file($_SERVER['DOCUMENT_ROOT']."/advert/domains/domain-8.dat")) { $file.=@file_get_contents($_SERVER['DOCUMENT_ROOT']."/advert/domains/domain-8.dat"); }
		$all=explode("<|>", $file); foreach ($all as $item) { if ($item!="") { $tmp=explode(";", $item); $ban[$tmp[1]][$tmp[0]]=$item; $rate[$tmp[0]]=$tmp[3]; }} 	
		// создаем список баннеров по каждому региону, согласно приоритету =====================
		foreach ($ban as $pid=>$items) { $text[$pid]=getSetka($items); $cachetext.=$pid."<===>".implode("<&>", $text[$pid])."<|>"; }
		@file_put_contents($path, $cachetext); @unlink($_SERVER['DOCUMENT_ROOT']."/advert/users/".$uip);
	}

$StopTime2=GetMicroTime2(); $RunTime2=$StopTime2-$StartTime2; $log.="BannerSystem action time: ".round($RunTime2, 3);
// отдаем список баннеров ==============================================================
$result["Code"]=1; $result["Banners"]=$text; $result["log"]=$log; $GLOBALS['_RESULT'] = $result;	


function getids($array) { $ids=""; foreach($array as $ar) { $i=explode(";", $ar); $ids.=$i[0]." . "; } return(trim($ids, ". ")); } ### получаем список id баннеров при сдвиге - только для логов
function getSetka($items) { global $rate; $tmp=array(); $red=array(); $have=array(); $x=0; foreach ($items as $key=>$data) { for ($i=0; $i<$rate[$key]; $i++) { $tmp[]=$data; }} ### Создали сетку со всеми баннерами * приоритет
while ($x<count($items)) { $rnd=rand(0, count($tmp)-1); $p=explode(";", $tmp[$rnd]); $id=$p[0]; if (!in_array($id, $have)) { $x++; $have[]=$id; $red[]=$items[$id]; }} return $red; }
function GetMicroTime2($pre=4) { list($usec, $sec)=explode(" ", microtime()); $time=(float)$usec+(float)$sec; return($time); }
?>