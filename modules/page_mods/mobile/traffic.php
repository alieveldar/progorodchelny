<?
$file="_index-traffic"; $VARS["cachepages"] = 0; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=Traffic(); SetCache($file, $text, ""); }
$Page["Content"]='<div class="WhiteBlock" style="padding:0 !important;">'.$text.'</div>'.$C20.$Page["Content"];

function Traffic() { $C=""; 
	$text='<script src="//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script><script type="text/javascript">ymaps.ready(init); function init () {
	var myMap = new ymaps.Map("map", { center: [55.786764, 49.122853], zoom: 13 }); 
	var actualProvider = new ymaps.traffic.provider.Actual({}, { infoLayerShown: true }); actualProvider.setMap(myMap);
	myMap.controls.add("zoomControl", { left: 5, top: 5 }).add("typeSelector").add("mapTools", { left: 35, top: 5 });
	var trafficControl = new ymaps.control.TrafficControl({ providerKey: "traffic#actual", shown: true });
	myMap.controls.add(trafficControl); trafficControl.getProvider("traffic#actual").state.set("infoLayerShown", true);
	}</script><div id="map" style="width:100%; height:600px"></div>';
	return (array($text, $C));
}
 
$Page["RightContent"]='<p style="margin-top:40px;">Компания &quot;Яндекс&quot; предоставила порталу ProAuto статистику по самым длинным пробкам в Казани:</p>
<b>7 января 15:00</b><br>от Лебяжьего озера до улицы Болотникова общей протяженностью 5.3 километра. Пробка продержалась 3 часа.'.$C15.'
<b>22 января 08:30</b><br>от улицы Комиссара Габишева до улицы Рихарда Зорге общей протяженностью 4.4 километра. Пробка продержалась 2 часа 20 минут.'.$C15.'
<b>7 февраля 18:00</b><br>от улицы Халитова до Советской улицы общей протяженностью 6.3 километра. Пробка продержалась 4 часа 30 минут.'.$C15.'
<b>12 февраля 08:30</b><br>от улицы Кул Гали до улицы Рихарда Зорге общей протяженностью 5.9 километра. Пробка продержалась 2 часа 10 минут.'.$C15.'
<b>2 марта 15:00</b><br>от улицы Кулахметова до Лебяжьего озера общей протяженностью в 7 километров. Пробка продержалась 5 часов 10 минут.'.$C15.'
<b>2 марта 16:30</b><br>от улицы Болотникова до Лебяжьего озера общей протяженностью 5.3 километра. Пробка продержалась 6 часов.';

list($t, $c)=CreateMostProNewest(3); $Page["RightContent"].=$C25."<div class='FromOtherNews'><h2>Новое на ProAuto</h2>".$t."</div>".$C25;
//$Page["Right2Content"]="";
?>