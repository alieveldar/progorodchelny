<?
### Виджет: Новости Казани (Yandex)
$filename="yandex-widget.cache";
$ftime=@filemtime($filename); $now=time(); $val=GetWidgetYandex($filename);
/*
if (($now-$ftime)>1800 || !is_file($file)) {
	$val=GetWidgetYandex($file);
} else {
	$val=file_get_contents($file);
}
*/ 
function GetWidgetYandex($filename) {
	$GLOBAL["sitekey"]=1;
	require($_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php"); 
	$tables=array("news_lenta", "auto_lenta", "sport_lenta", "business_lenta", "ls_lenta");
	foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; 
		$q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'
		WHERE (`$table`.`stat`=1 && `$table`.`promo`<>1) GROUP BY 1) UNION ";
	} 
	$data=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 4");
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $path="http://progorodchelny.ru/".$ar["link"]."/view/".$ar["id"];
		if ($ar["pic"]!='') { $pic="<img src='http://progorodchelny.ru/userfiles/picsquare/$ar[pic]' title='Новости Казани: ".$ar["name"]."' alt='Новости Казани: ".$ar["name"]."' />"; } else { $pic=''; }
		$val.="<a href='$path' target='_blank'  title='Новости Казани: ".$ar["name"]."'>".$pic.$ar["name"]."<br /></a>";
	endfor;
	$val.="</div><div class='C5'></div>";
	$val.="<a href='http://prokazan.ru' target='_blank'>Новости</a> | <a href='http://auto.prokazan.ru' target='_blank'>Авто</a> | <a href='http://sport.prokazan.ru' target='_blank'>Спорт</a> | <a href='http://business.prokazan.ru/' target='_blank'>Бизнес</a>";
	$val.="<div class='C5'></div>";
	@file_put_contents($filename, $val);
	return $val;
}
?>


<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:widget="http://wdgt.yandex.ru/ns/"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="title" content="Новости Казани" /><meta name="description" content="Новости города Казань, спортивные и авто новости Казани" /><link rel="stylesheet" type="text/css" href="yandex.css" media="all"><script type="text/javascript" src="http://img.yandex.net/webwidgets/1/WidgetApi.js"></script></head><body><div id="NewsLenta"><? echo $val; ?></div><script type="text/javascript">widget.onload=function(){ widget.adjustIFrameHeight(); }</script></body></html>