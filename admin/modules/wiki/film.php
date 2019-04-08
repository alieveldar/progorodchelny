<?
require_once('wiki.functions.php');
require_once('wiki.viewFunctions.php');

if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	$table2="_widget_video";

    $raz = getPageName($alias);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["savebutton"])) {	
		
		if ($P["vtext"]!="") {
			$data=DB("SELECT id FROM `$table2` WHERE (`pid`=".(int)$id." AND `link`='".$alias."') LIMIT 1");
			if ($data["total"]==0) { DB("INSERT INTO `$table2` (`pid`, `link`, `name`, `text`) VALUES (".(int)$id.", '".$alias."', '".$P["vname"]."', '".$P["vtext"]."')");
			} else { DB("UPDATE `$table2` SET `name`='".$P["vname"]."', `text`='".$P["vtext"]."' WHERE (`pid`=".(int)$id." AND `link`='".$alias."') LIMIT 1 "); }
			$msg="<div class='SuccessDiv'>Запись успешно сохранена!</div>";
		} else {
			DB("DELETE FROM `$table2` WHERE (`pid`=".(int)$id." AND `link`='".$alias."') LIMIT 1");
			$msg="<div class='ErrorDiv'>Запись успешно удалена!</div>";
		}
		
		$_SESSION["Msg"]=$msg; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}


    $rubricItem = getRubricById($id);

    if ($rubricItem["stat"] == 1) {
        $chk = "checked";
    }
	
	$v=DB("SELECT `id`, `name`, `text` FROM `$table2` WHERE (`pid`=".(int)$id." AND `link`='".$alias."') LIMIT 1");
	@mysql_data_seek($v["result"], 0); $vid=@mysql_fetch_array($v["result"]);
		
	$AdminText='<h2>Видео: &laquo'.$rubricItem["name"].'&raquo;</h2>'.$_SESSION["Msg"];
	$AdminText.='<form action="'.$_SERVER["REQUEST_URI"].'" enctype="multipart/form-data" method="post">';

	### Основные данные
	$AdminText.='<div class="RoundText"><table><tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>';
	$AdminText.='<tr class="TRLine0"><td class="VarText">Название</td><td class="LongInput"><input name="vname" type="text" value="'.$vid["name"].'"></td><tr>';
	$AdminText.='<tr class="TRLine1"><td class="VarText">Код видео</td><td class="LongInput"><textarea style="height:300px;" name="vtext">'.$vid["text"].'</textarea></td><tr>';
	$AdminText.='</table></div>';
	

	### Сохранение
	$AdminText.="<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div>";

    $catsToShowContactWidget = array(1, 2, 3);

    $catsToShowMapWidget = array(2, 3, 4);

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";

    $additionalFieldsLinkTextArray = getAdditionalFieldsTextArray($rubricItem['cat']);

    $AdminRight .= displayAdminRightAdditionFieldLinks($id, $additionalFieldsLinkTextArray);

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_photo&amp;id=$id\">Основная фотография</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_text&amp;id=$id\">Основное содержание</a></div>";

    if (in_array($rubricItem['cat'], $catsToShowContactWidget)) {
        $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_contacts&id=$id\">Контакты</a></div>";
    }

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_report&amp;id=$id\">Виджет: Фото-отчет</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_album&amp;id=$id\">Виджет: Фото-альбом</a></div>
	<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_film&amp;id=$id\">Виджет: Видео-вставка</a></div>";

    if (in_array($rubricItem['cat'], $catsToShowMapWidget)) {
        $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_eventmap&id=$id\">Виджет: Карта событий</a></div>";
    }

    $AdminRight .=
        "$C5
	<div class=\"SecondMenu\"><a href=\"/{$alias}/view/{$id}/\" target=\"_blank\">Просмотр</a></div>
	<br>
	<div class=\"RoundText\">
        <table>
            <tr class=\"TRLine\">
                <td class=\"CheckInput\"><input type=\"checkbox\" id=\"RS-{$id}-{$alias}_lenta\" $chk></td>
                <td><b>Материал опубликован</b></td>
            </tr>
        </table>
	</div>";
    $AdminRight .= "<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные' /></div></form>";

}
$_SESSION["Msg"]="";