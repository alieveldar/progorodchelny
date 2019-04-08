<?
### НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
$table=$alias."_lenta"; $table2="_widget_cards";

// РАЗДЕЛ
$data=DB("SELECT `id`,`shortname`,`link`, `sets` FROM `_pages` WHERE (`link`='".$alias."') LIMIT 1");
if ($data["total"]!=1) { $AdminText=ATextReplace('Item-Module-Error', $id, "_pages"); $GLOBAL["error"]=1; } else {
@mysql_data_seek($data["result"], 0); $raz=@mysql_fetch_array($data["result"]);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["savebutton"])) {
		foreach($P["name"] as $k=>$v) {
			$P["name"][$k]=str_replace("'","\'",$P["name"][$k]);
			$P["num"][$k]=str_replace("'","\'",$P["num"][$k]);
			$P["text"][$k]=str_replace("'","\'",$P["text"][$k]);
			DB("UPDATE `".$table2."` SET `name`='".$P["name"][$k]."', `num`='".$P["num"][$k]."', `text`='".$P["text"][$k]."' WHERE (`id`=$k)");
		}
		$_SESSION["Msg"]="<div class='SuccessDiv'>Запись успешно сохранена!</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}


	// ВЫВОД ПОЛЕЙ И ФОРМ
	$data=DB("SELECT `id`,`name`,`stat` FROM `".$table."` WHERE (`id`='".(int)$id."')");
	if ($data["total"]!=1) { $AdminText=ATextReplace('ItemError', $raz["shortname"]." (".$alias.")", $id); $GLOBAL["error"]=1; } else {

	### Заполнение данных
	@mysql_data_seek($data["result"], 0); $node=@mysql_fetch_array($data["result"]); if ($node["stat"]==1) { $chk="checked"; }
	$data=DB("SELECT * FROM `".$table2."` WHERE (`pid`=".(int)$id." AND `link`='".$alias."')");
	$AdminText='<h2>Карточки: &laquo'.$node["name"].'&raquo;</h2>'.$_SESSION["Msg"];
	$AdminText.='<form action="'.$_SERVER["REQUEST_URI"].'" enctype="multipart/form-data" method="post">';

	### Основные данные
	$AdminText.=$C10.'<div class="LinkG"><a href="javascript:void(0);" onclick="AddField(\''.$alias.'\', \''.$id.'\')">Добавить карточку</a></div>'.$C10.$C10;
	$AdminText.='<div class="RoundText" id="Cards">'; for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ap=@mysql_fetch_array($data["result"]); $l=$ap["id"];
	$AdminText.="<div class='card' id='card".$l."'><input name='num[".$l."]' value='".$ap["num"]."' placeholder='Номер карточки'><a href='javascript:void(0);' onclick='RemoveField(".$l.")'>Удалить</a>".$C10."
	<textarea name='name[".$l."]' placeholder='Заголовок карточки'>".$ap["name"]."</textarea>".$C10."<textarea name='text[".$l."]' placeholder='Текст карточки'>".$ap["text"]."</textarea></div>";
	endfor; $AdminText.='</div>'; $AdminText.="<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div>";

	// ПРАВАЯ КОЛОНКА
	$AdminRight="<br><br>
	<div class='RoundText'><table><tr class='TRLine'><td class='CheckInput'><input type='checkbox' id='RS-".$id."-".$alias."_lenta' ".$chk."></td><td><b>Опубликовано</b></td></tr>
	<tr><td colspan='2'><hr><div id='dataNow' align='center'><a href='javascript:void(0);' onclick='stanUpData();'>Поставить текущие дату и время</a></div></td></tr></table></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_edit&id=".$id."'>Основные настройки</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_photo&id=".$id."'>Основная фотография</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_text&id=".$id."'>Основное содержание</a></div>
	<div class='SecondMenu2'><a href='?cat=".$alias."_cards&id=".$id."'>Виджет: Карточки</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_pretext&id=".$id."'>Виджет: Текстовые поля</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_voting&id=".$id."'>Виджет: Голосование</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_report&id=".$id."'>Виджет: Фото-отчет</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_album&id=".$id."'>Виджет: Фото-альбом</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_film&id=".$id."'>Виджет: Видео-вставка</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_contacts&id=".$id."'>Виджет: Лого и контакты</a></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_eventmap&id=".$id."'>Виджет: Карта событий</a></div>
	<div class='SecondMenu'><a href='?cat=" . $alias . "_review&id=" . $id . "'>Виджет: Отзывы</a></div>
	<div class='SecondMenu'><a href='?cat=" . $alias . "_questions&id=" . $id . "'>Виджет: Ответы на вопросы</a></div>
	<br><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div><br><br>
	<div class='SecondMenu2'><a href='/$alias/view/$id/' target='_blank'>Просмотр на сайте</a></div></form>";
	if ($_SESSION['userrole']>2) { $AdminRight.="<div class='SecondMenu'><a href='?cat=".$alias."_log&id=".$id."'>Лог редактирования записи</a></div>"; }

	}}
}
$_SESSION["Msg"]="";
?>