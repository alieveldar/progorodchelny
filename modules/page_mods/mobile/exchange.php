<?
	
	$file="_index-exchange"; $VARS["cachepages"] = 0;
	if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=ExchangeTable(); SetCache($file, $text, ""); }

	$Page["Content"].="<div class='ExchangeTable' style='padding:0 !important;'>".$text."</div>".$C10;
	$Page["Content"].='<p>*В таблице <span style="color: green; font-weight: bold;">выделены</span> наиболее выгодные предложения купли/продажи валюты.</p>';
	$Page["Content"].='<p>**Курс валют может меняться в течение дня, за детальной информацией обратитесь в банк.</p>';
	
	function ExchangeTable() { $C=""; 
		$txt=@file_get_contents("http://dengi.116.ru/exchange/exchange.html"); $txt=iconv("windows-1251", "utf-8", $txt); $tm=explode('Версия	для печати', $txt); $text=$tm[1];
		$text=str_replace(array('<br />','<br>','на карте'), "", $text); $text=strip_tags($text, "<table><tr><th><td><font><b><span>"); return (array($text, $C));
	}
?>