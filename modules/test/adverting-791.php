<?
$Text=""; $Script="";
### ДАННЫЕ ТЕСТА ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  --- 
$ok="Правильно!"; $no="Неправильно =(";

### Текст итога теста, отметка "От и больше * баллов"
$ends=array(
	0=>"<div style=\"height:200px; width:400px; font-size:17px; line-height:25px;\"><p>Очень жаль, но вы плохо знакомы с историей и достижениями легендарного Казанского вертолетного завода. Рекомендуем вам почитать наши материалы о заводе. Поверьте, это очень увлекательно.</p></div>",
	
	4=>"<div style=\"height:200px; width:400px; font-size:17px; line-height:25px;\"><p>Вы кое-что знаете об истории и достижениях легендарного Казанского вертолетного завода. Но все же рекомендуем вам перечитать наши материалы о заводе. Уверены, вам будет очень интересно.</p></div>",
	
	7=>"<div style=\"height:200px; width:400px; font-size:17px; line-height:25px;\"><p>Поздравляем! Вы очень внимательно читали наши материалы о легендарном Казанском вертолетном заводе. Мы гордимся, что у нас есть такие внимательные и любознательные читатели.</p></div>",
);

### Вопросы и ответы по порядку
$quets=array(

0=>array(
	"qst"=>"В каком году в Казани было запущено серийное производство вертолетов МИ-8? <br><a href=\"http://prokazan.ru/adverting/view/705\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d1d/Dm-oxvkFhVA.jpg",
	"ans"=>array(
		"0"=>array(0, "1961"),
		"1"=>array(1, "1965"),
		"2"=>array(0, "1969"),
)),

1=>array(
	"qst"=>"Когда был совершен первый полет на вертолете «Ансат»?<br><a href=\"http://prokazan.ru/adverting/view/431\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d26/gnI9sZTBOts.jpg",
	"ans"=>array(
		"0"=>array(1, "17 августа 1999 года"),
		"1"=>array(0, "20 апреля 2002 года"),
		"2"=>array(0, "13 января 2009 года"),
)),

2=>array(
	"qst"=>"Какую сумму удалось собрать участникам благотворительного «Крылатого забега»? <br><a href=\"http://prokazan.ru/adverting/view/569\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d2f/gaJq6SczSdM.jpg",
	"ans"=>array(
		"0"=>array(0, "176 500 рублей."),
		"1"=>array(0, "345 200 рублей."),
		"2"=>array(1, "375 200 рублей."),
)),

3=>array(
	"qst"=>"Сколько всего вертолетов Ми-8/17 было продано с начала производства?<br><a href=\"http://prokazan.ru/adverting/view/705\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d4a/ZGQJA1OoKn8.jpg",
	"ans"=>array(
		"0"=>array(0, "Более 5 000 вертолетов."),
		"1"=>array(0, "Более 10 000 вертолетов."),
		"2"=>array(1, "Более 12 000 вертолетов."),
)),

4=>array(
	"qst"=>"Что делают люди, изображенные на этой фотографии?<br><a href=\"http://prokazan.ru/adverting/view/527\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d38/7UJZ4nUImOE.jpg",
	"ans"=>array(
		"0"=>array(0, "Сотрудники Казанского вертолетного завода заготавливают дрова для корпоративного выезда на природу."),
		"1"=>array(1, "Сотрудники КВЗ в рамках движения «75 добрых дел» ремонтируют песочницу в детском саду."),
		"2"=>array(0, "Сотрудники КВЗ обустраивают вертолетную площадку в одном из казанских дворов."),
)),

5=>array(
	"qst"=>"На какую высоту смог подняться и установить мировой рекорд вертолет МИ-38, на котором установлены лопасти из стеклоткани, во время 14-го Чемпионата мира по вертолетному спорту в 2012 году?<br><a href=\"http://prokazan.ru/adverting/view/416\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d53/tLWPnF0NVHs.jpg",
	"ans"=>array(
		"0"=>array(1, "8 600 метров."),
		"1"=>array(0, "8 100 метров."),
		"2"=>array(0, "7 800 метров."),
)),

6=>array(
	"qst"=>"Сколько дней заняли съемки видео, приуроченного к 75-летию КВЗ?<br><a href=\"http://prokazan.ru/adverting/view/630\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d5c/W-OxRnxHe3c.jpg",
	"ans"=>array(
		"0"=>array(0, "14 дней."),
		"1"=>array(1, "5 дней."),
		"2"=>array(0, "28 дней."),
)),

7=>array(
	"qst"=>"В каком парке проводился благотворительный «Крылатый забег»?<br><a href=\"http://prokazan.ru/adverting/view/569\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d41/igq7K65WacU.jpg",
	"ans"=>array(
		"0"=>array(1, "В парке «Крылья Советов»."),
		"1"=>array(0, "В парке «Континент»."),
		"2"=>array(0, "В парке им. М.Горького."),
)),

8=>array(
	"qst"=>"Официальным днем рождения «Казанского вертолетного завода» считается:<br><a href=\"http://www.russianhelicopters.aero/ru/kvz/history/\" target=\"_blank\">Подсказка</a>",
	"img"=>"https://pp.vk.me/c630928/v630928790/9d65/F7ZdGG379SM.jpg",
	"ans"=>array(
		"0"=>array(0, "8 августа 1941 года."),
		"1"=>array(0, "22 июля 1940 года."),
		"2"=>array(1, "4 сентября 1940 года."),
)),

);

### НИЧЕГО НЕ ТРОГАТЬ!!! ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  --- 
$i=0; foreach ($quets as $ar) { $Text.="<div><div class='PicItem'><img src='".$ar["img"]."' /></div>".$C5."<h2>".$ar["qst"]."</h2>".$C."<div id='ans-".$i."' class='answertext'></div>";
foreach($ar["ans"] as $ans) { $Text.="<div class='testanswer testanswering answer-".$i." anstype".$ans[0]."' id='div-".$i."-".$ans[0]."' onclick='clickanswer(this);'>".$ans[1]."</div>"; } $Text.="</div>".$C30; $i++; }
$end=""; foreach($ends as $point=>$text) { $end.=" end[".$point."]='".$text."'; "; } $Script="<script>var total=".sizeof($quets)."; var textok='".$ok."'; var textno='".$no."'; var end=Array(); ".$end."</script>";

### ДОБАВЛЕНИЕ В ПОСТ ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  --- 
$Page["Content"]=str_replace("<!--TEST-->", $Text.$Script, $Page["Content"]);
$Page["Content"].="<script src='/modules/test/test-type1.js' type='text/javascript'></script>";
?>
