<?
require_once('wiki.functions.php');
require_once('wiki.viewFunctions.php');

// МЕНЮ САЙТА
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {
    // РАЗДЕЛ
    $raz = getPageName($alias);

    $subGroupId = (int)$_GET['sub'];

    $rubricItem = getRubricById($id);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
    $P = $_POST;
    if (isset($P["savebutton"])) {

        $q = "UPDATE `" . $alias . "_lenta` SET `lid`='" . str_replace("'", '&#039;', $P["lid"]) . "', `text`='" . $P["PostText"] . "', `alttext`='" . $P["Post2Text"] . "' WHERE (id='" . (int)$id . "')";
        DB($q);
        $_SESSION["Msg"] = "<div class='SuccessDiv'>Настройки успешно сохранены</div>";
        @header("location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

        if ($rubricItem["stat"] == 1) {
            $chk = "checked";
        }
	
		$AdminText = '<h2>Редактирование: &laquo' . $rubricItem["name"] . '&raquo;</h2>' . $_SESSION["Msg1"];
		$AdminText .= "<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/filemanager/ajex.js'></script>";
		$AdminText .= "<form action='" . $_SERVER["REQUEST_URI"] . "' enctype='multipart/form-data' method='post'><div class='RoundText'>";
		$AdminText .= "<h2>Короткое описание публикации</h2><div class='LongInput'><textarea name='lid'>" . $rubricItem["lid"] . "</textarea></div>" . $C15;
		$AdminText .= "<h2>Основное содержание публикации</h2><textarea name='PostText' id='textedit' style='outline:none;'>" . $rubricItem["text"] . "</textarea>
		<script type='text/javascript'>var editor=CKEDITOR.replace('textedit'); AjexFileManager.init({ returnTo: 'ckeditor', editor: editor});</script>";
		$AdminText .= $C5 . '<div><a href="javascript:void(0);" onclick="ShowSets();" id="ShowSets">Показать дополнительные настройки</a></div>';
		$AdminText .= "<div class='ShowSets'>" . $C . "<h2>Альтернативное содержание публикации</h2><textarea name='Post2Text' id='text2edit' style='outline:none;'>" . $rubricItem["alttext"] . "</textarea>
		<script type='text/javascript'>var editor=CKEDITOR.replace('text2edit'); AjexFileManager.init({ returnTo: 'ckeditor', editor: editor});</script></div>";
		$AdminText .= $C10 . "<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div></div>";

    $catsToShowContactWidget = array(1, 2, 3);

    $catsToShowMapWidget = array(2, 3, 4);

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";


    $additionalFieldsLinkTextArray = getAdditionalFieldsTextArray($rubricItem['cat']);
    $AdminRight .= displayAdminRightAdditionFieldLinks($id, $additionalFieldsLinkTextArray);

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_photo&amp;id=$id\">Основная фотография</a></div>
	<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_text&amp;id=$id\">Основное содержание</a></div>";

    if (in_array($rubricItem['cat'], $catsToShowContactWidget)) {
        $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_contacts&id=$id\">Контакты</a></div>";
    }

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_report&amp;id=$id\">Виджет: Фото-отчет</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_album&amp;id=$id\">Виджет: Фото-альбом</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_film&amp;id=$id\">Виджет: Видео-вставка</a></div>";

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
$_SESSION["Msg"] = "";