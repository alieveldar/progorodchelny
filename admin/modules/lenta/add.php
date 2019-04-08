<?php
### НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {

// РАЗДЕЛ
	$data=DB("SELECT `id`,`shortname`,`link`, `sets` FROM `_pages` WHERE (`link`='".$alias."') LIMIT 1");
	if ($data["total"]!=1) { $AdminText=ATextReplace('Item-Module-Error', $id, "_pages"); $GLOBAL["error"]=1; } else {
	@mysql_data_seek($data["result"], 0); $raz=@mysql_fetch_array($data["result"]);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["savebutton"])) {
		$dtags=","; foreach ($P["tags"] as $k=>$v) { $dtags.=$k.","; }
		$ar=explode(".", $P["ddata1"]); $sdata1=mktime($P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2]);
		$ar=explode(".", $P["ddata11"]); $sdata2=mktime($P["ddata21"], $P["ddata31"], $P["ddata41"], $ar[1], $ar[0], $ar[2]);

		$q="INSERT INTO `".$alias."_lenta` (`uid`, `bid`, `cat`, `name`, `kw`, `ds`, `cens`, `realinfo`, `comments`, `data`, `astat`, `adata`, `promo`, `spromo`, `onind`, `spec`, `yarss`, `mailrss`, `tavto`, `tags`, `redak`,`gis`,`mailtizer`,`showauthor`, `prokazanxml`)
		VALUES ('".(int)$P['authid']."', '".(int)$P['bid']."', '".(int)$P["site"]."', '".str_replace("'", '&#039;', $P["dname"])."', '".str_replace("'", '&#039;', $P["dkw"])."', '".str_replace("'", '&#039;', $P["dds"])."', '".$P["cens"]."', '".str_replace("'", '&#039;', $P["realinfo"])."', '".$P["comms"]."', '".$sdata1."',
		'".$P["autoon"]."', '".$sdata2."', '".$P["comrs"]."', '".$P["scomrs"]."','".$P["ontv"]."', '".$P["spec"]."', '".$P["yarss"]."', '".$P["mailrss"]."', '".$P["tavto"]."', '".$dtags."', '".$P["redak"]."', '".$P["gis"]."', '".$P["mailtizer"]."', '".$P['showauthor']."', '" . $P['prokazanxml'] . "')";

		$_SESSION["Msg"]="<div class='SuccessDiv'>Новая публикация успешно создана!</div>"; $data=DB($q); $last=DBL(); DB("UPDATE `".$alias."_lenta` SET `rate`='".$last."' WHERE  (id='".$last."')");
		DB("INSERT INTO `_lentalog` (`link`, `id`, `uid`, `data`, `ip`, `text`) VALUES ('".$alias."', '".$last."', '".$_SESSION['userid']."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', 'Создание #".$last.": ".str_replace("'", '&#039;', $P["dname"])."')");
		@header("location: ?cat=".$raz["link"]."_edit&id=".$last); exit();
	}
// ВЫВОД ПОЛЕЙ И ФОРМ

	$site=array(); $data=DB("SELECT `id`, `name` FROM `".$alias."_cats` ORDER BY `rate` DESC"); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $site[$ar["id"]]=$ar["name"]; endfor;
	$usr=array(); $data=DB("SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC"); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $usr[$ar["id"]]=$ar["nick"]; endfor;

	$AdminText='<h2>Добавление материала &laquo;'.$raz["shortname"].'&raquo;</h2>'.$_SESSION["Msg"];
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";

	### Основные данные
	$AdminText.="<div class='RoundText'><table>".'<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	
	<tr class="TRLine0"><td class="VarText">Заголовок материала<star>*</star></td><td class="LongInput">	<input name="dname" id="dname" type="text" class="JsVerify2" maxlength="80" style="width:500px; float:left;">
	<input id="dcount" type="text" title="Осталось символов" style="width:40px; float:right; text-align:center;" value="" readonly>	</td><tr>
	
	<tr class="TRLine1"><td class="VarText">Категория</td><td class="LongInput"><div class="sdiv"><select name="site">'.GetSelected($site, 0).'</select></div></td><tr>
	<tr class="TRLine0"><td class="VarName"></td><td><a href="javascript:void(0);" onclick="ShowSets();" id="ShowSets">Показать дополнительные настройки</a></td><tr>	
	<tr class="TRLine0 ShowSets"><td class="VarName">Автор материала</td><td class="LongInput"><div class="sdiv"><select name="authid">'.GetSelected($usr, $_SESSION['userid']).'</select></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Ключевые слова (keywords)</td><td class="LongInput"><input name="dkw" type="text"></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Описание (description)</td><td class="LongInput"><input name="dds" type="text"></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Номер ZID (<a href="?cat=banners_order" target="_blank">заявка БС</a>)</td><td class="LongInput"><input name="bid" type="text" value="0" placeholder="Введите номер BID из баннерной системы"></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Цензор материала</td><td class="LongInput"><input name="cens" type="text" value="16+"></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Источник материала</td><td class="LongInput"><input name="realinfo" type="text"></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Комментарии</td><td class="LongInput"><div class="sdiv"><select name="comms"><option value="0">Чтение и добавление</option><option value="1">Только чтение</option><option value="2">Запретить комментарии</option></select></div></td><tr>	
	<tr class="TRLine1 ShowSets"><td class="VarName">Дата создания</td><td class="DateInput">'.GetDataSet().'</td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Автопубликация</td><td class="DateInput">'.GetDataSet(0, 1).' включить таймер: <input type="checkbox" name="autoon" id="autoon" value="1"></td><tr>
	'."</table></div>";

	### Экспорт материала
	$AdminText.="<h2>Отображение и экспорт материала</h2><div class='RoundText TagsList'><table>
	<tr class='TRLine0'>
		<td width='1%'><input name='ontv' id='ontv' type='checkbox' value='1'></td><td width='20%'><b>«Телевизор»</b> ProGorodChelny</td>
		<td width='1%'><input name='prokazanxml' id='prokazanxml' type='checkbox' value='1'></td><td width='20%'>Отправить на ProKazan.ru</td>
		<td width='1%'></td><td width='1%'></td>
	</tr>
	<tr class='TRLine1'>
		<td width='1%'><input name='redak' id='redak' type='checkbox' value='1'></td><td width='20%'>Редакционная колонка</td>
		<td width='1%'><input name='tavto' id='tavto' type='checkbox' value='1'></td><td width='20%'>Выводить Фото в лентах</td>
		<td width='1%'><input name='showauthor' id='showauthor' type='checkbox' value='1'></td><td width='20%'>Показывать автора</td>
	</tr>
	<tr class='TRLine0'>
		<td width='1%'><input name='comrs' id='comrs' type='checkbox' value='1'></td><td width='20%'>Коммерческая новость</td>
		<td width='1%'><input name='scomrs' id='scomrs' type='checkbox' value='1'></td><td width='20%'>VIP коммерческая новость</td>
		<td width='1%'><input name='spec' id='spec' type='checkbox' value='1'></td><td width='20%'>Отправить PUSH уведомление</td>
	</tr>
	<tr class='TRLine1'>
		<td width='1%'><input name='yarss' id='yarss' type='checkbox' value='1' checked></td><td width='20%'>Отправить в <b>Яндекс RSS</b></td>
		<td width='1%'><input name='mailrss' id='mailrss' type='checkbox' value='1' checked></td><td width='20%'>Отправить в <b>Mail RSS</b></td>
		<td width='1%'><input name='mailtizer' id='mailtizer' type='checkbox' value='1'></td><td width='20%'>Отправить в <b>Тизер Mail</b></td>
	</tr>
	</table></div>";

	### Список тэгов публикцаций
	$tags=""; $data=DB("SELECT `id`, `name` FROM `_tags` ORDER BY `name` ASC"); $line=1; for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]);
	$tags.="<td width='1%'><input name='tags[".$ar["id"]."]' id='tags[".$ar["id"]."]' type='checkbox' class='tags' value='1'></td><td width='20%'>".$ar["name"]."</td>";
	if (($i+1)%3==0) { $tags.="</tr><tr class='TRLine".($line%2)."'>"; $line++; if ($line==3) { $line=1; }} endfor;
	$AdminText.="<h2>Тэги публикации</h2><div class='InfoH2'>Выберите 2-4 темы, самые подходящие по смыслу публикации:</div><div class='RoundText TagsList' style='max-height:500px;'><table><tr class='TRLine0'>".$tags."</tr></table></div>";
	$AdminText.="<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Создать запись'></div>";

// ПРАВАЯ КОЛОНКА
	$AdminRight="<br><br><div class='SecondMenu2'><a href='".$_SERVER["REQUEST_URI"]."'>Основные настройки</a></div><br>После сохранения основных настроек, вы сможете перейти к наполнению публикации контентом, загрузить фотографии и править остальные параметры записи.
	<div class='C20'></div><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Создать запись'></div></form>";
}




	}
$_SESSION["Msg"]="";
?>