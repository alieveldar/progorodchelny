<?
$Text=""; $Script="";
### ДАННЫЕ ТЕСТА ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  --- 
$ok="Правильно!";
$no="Это неправильный ответ!";

### Текст итога теста, отметка "От и больше * баллов"
$ends=array(
	0=>"<div style=\"height:200px;\"><p><b>А вот и нет! Это может показаться невероятным, но маникюр в этом салоне стоит всего 250 рублей!</b></p><p>Покрытие гель-лаком обойдется вам в 700 рублей.</p><p>Записывайтесь по номеру 239-35-33.</p><p>Подробности в группе ВК <a href=\"https://vk.com/malinanail_kzn\" target=\"_blank\">vk.com/malinanail_kzn</a> и в Instagram <a href=\"https://www.instagram.com/malinanail.kzn\" target=\"_blank\">instagram.com/malinanail.kzn</a></p></div>",
	1=>"<div style=\"height:200px;\"><p><b>Угадали! Маникюр в этом салоне обойдется вам в 250 рублей!</b></p><p>Покрытие гель-лаком обойдется вам в 700 рублей.</p><p>Записывайтесь по номеру 239-35-33.</p><p>Подробности в группе ВК <a href=\"https://vk.com/malinanail_kzn\" target=\"_blank\">vk.com/malinanail_kzn</a> и в Instagram <a href=\"https://www.instagram.com/malinanail.kzn\" target=\"_blank\">instagram.com/malinanail.kzn</a></p></div>",
);

### Вопросы и ответы по порядку
$quets=array(
0=>array(
	"qst"=>"Итак, сколько стоит маникюр в этом салоне?",
	"img"=>"",
	"ans"=>array(
		"0"=>array(1, "100-300 рублей"),
		"1"=>array(0, "301-500 рублей"),
		"2"=>array(0, "501-700 рублей"),
		"3"=>array(0, "701-900 рублей"),
		"4"=>array(0, "больше 1000 рублей"),
)),


);

### НИЧЕГО НЕ ТРОГАТЬ!!! ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  --- 
$i=0; foreach ($quets as $ar) { $Text.="<div>"; if ($ar["img"]!="") { $Text.="<div class='PicItem'><img src='".$ar["img"]."' /></div>"; } $Text.=$C5."<h2>".$ar["qst"]."</h2>".$C."<div id='ans-".$i."' class='answertext'></div>";
foreach($ar["ans"] as $ans) { $Text.="<div class='testanswer testanswering answer-".$i." anstype".$ans[0]."' id='div-".$i."-".$ans[0]."' onclick='clickanswer(this);'>".$ans[1]."</div>"; } $Text.="</div>".$C30; $i++; }
$end=""; foreach($ends as $point=>$text) { $end.=" end[".$point."]='".$text."'; "; } $Script="<script>var total=".sizeof($quets)."; var textok='".$ok."'; var textno='".$no."'; var end=Array(); ".$end."</script>";

### ДОБАВЛЕНИЕ В ПОСТ ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  ---  --- 
$Page["Content"]=str_replace("<!--TEST-->", $Text.$Script, $Page["Content"]);
$Page["Content"].="<script src='/modules/test/test-type1.js' type='text/javascript'></script>";
?>
