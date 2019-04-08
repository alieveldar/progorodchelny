<?
	$file="_index-exchang2e"; $VARS["cachepages"]=5;
	if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=ExchangeTable(); SetCache($file, $text, ""); }
	$Page["Content"].="<div class='ExchangeTable2'>".$text."</div>".$C10;
	$Page["Content"].='<p>Курс валют может меняться в течение дня, за детальной информацией обратитесь в банк.</p>';
	$Page["Content"].='<p style="font-size:12px; color:#666;">Данные курса валют предоставлены сайтом <a href="http://kovalut.ru/index.php?kod=1601" target="_blank">KOVALUT.RU</a></p>';	
	$Page["Content"].="<img src='/advert/showBanner.php?ids=1451' style='width:1px; height:1px;' />";
	
	function ExchangeTable() {
		$city=1601; $text="";
		$XMLfile=file_get_contents('http://informer.kovalut.ru/webmaster/xml-table.php?kod='.$city); $XMLall=new SimpleXMLElement($XMLfile); $XML=$XMLall->Actual_Rates;
		$url="https://open-broker.ru/lp/foreign-currency/?utm_source=progorodchelny.ru&utm_medium=display&utm_term=kazan&utm_content=brend-strochka-spisok&utm_campaign=kurs-valut";
		// --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  -- 
		### БАНК ОТКРЫТИЕ
			/* $open=json_decode(file_get_contents("http://api.open-broker.ru/v1/currency/rates"));
			$text.="<tr class='open' onclick='gosite();'><td>Открытие Брокер</td>";
		   $text.="<td class='rate'>".$open->currency->usd->rbc."</td><td class='rate'>".$open->currency->usd->rbc."</td>";
		   $text.="<td class='rate'>".$open->currency->eur->rbc."</td><td class='rate'>".$open->currency->eur->rbc."</td>";
			$text.="<td class='time'>".date("d.m.Y H:i")."</td></tr>"; */
		// --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  -- 
		for ($i=0; $i<sizeof($XML->Bank); $i++): $BankName = $XML->Bank[$i]->Name; if (strpos($BankName,"ткрыт")===false) {
			$text.="<tr><td>{$BankName}</td>";
		   $text.="<td class='rate'>{$XML->Bank[$i]->USD->Buy}</td><td class='rate'>{$XML->Bank[$i]->USD->Sell}</td>";
		   $text.="<td class='rate'>{$XML->Bank[$i]->EUR->Buy}</td><td class='rate'>{$XML->Bank[$i]->EUR->Sell}</td>";
		   $text.="<td class='time'>{$XML->Bank[$i]->ChangeTime}</td></tr>";
		} endfor; 
		// --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  --  -- 
		$tr='<tr align="center"><td rowspan="2" width="35%">Банк</td><td colspan="2">Доллар</td><td colspan="2">Евро</td><td rowspan="2" width="5%">Обновлено</td></tr>
		<tr align="center"><td>покупка</td><td>продажа</td><td>покупка</td><td>продажа</td></tr>';
		$text="<table cellpadding='0' cellspacing='0'>".$tr.$text."</table><script>function gosite(){ document.location='/advert/clickBanner.php?id=1451&away=".urlencode($url)."'; }</script>";		
		return array($text, "");
	}
?>