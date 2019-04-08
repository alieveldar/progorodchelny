<?php

require_once('wiki.functions.php');
require_once('wiki.viewFunctions.php');

// НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {
    $raz = getPageName($alias);

    if (isset($_POST["savebutton"])) {
        $editContactsItem = array(
            'id' => (int)$id,
            'phone' => prepareText($_POST["phone"]),
            'site' => prepareText($_POST["site"]),
            'email' => prepareText($_POST["email"]),
        );
        editWikiContacts($editContactsItem);

        $_SESSION["Msg"] = "<div class='SuccessDiv'>Запись успешно сохранена!</div>";

        navigate("?cat={$raz["link"]}_contacts&id=$id");
    }

    $rubricItem = getRubricById($id);

    if ($rubricItem["stat"] == 1) {
        $chk = "checked";
    }

    $rubricContactItem = getWikiContactsById($id);

    $categoriesData = getWikiCategoryList();
    $currentCategoryText = $categoriesData[$rubricItem['cat']];

    $AdminText .= "<form action=\"{$_SERVER['REQUEST_URI']}\" enctype=\"multipart/form-data\" method=\"post\">";
    //Форма контактов раздела
    $AdminText .= "
        <div class=\"RoundText\">
            <table>
                <tr class=\"TRLine0\">
                    <td style=\"width:22%;\"></td>
                    <td style=\"width:78%;\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Телефон</td>
                    <td class=\"LongInput\"><input name=\"phone\" id=\"phone\" type=\"text\" maxlength=\"80\" value=\"{$rubricContactItem['phone']}\"></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Сайт</td>
                    <td class=\"LongInput\"><input name=\"site\" id=\"site\" type=\"text\" maxlength=\"80\" value=\"{$rubricContactItem['site_url']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">E-mail</td>
                    <td class=\"LongInput\"><input name=\"email\" id=\"email\" type=\"text\" maxlength=\"80\" value=\"{$rubricContactItem['email']}\"></td>
                </tr>
            </table>
        </div>
        <div class=\"CenterText\">
            <input type=\"submit\" name=\"savebutton\" id=\"savebutton\" class=\"SaveButton\" value=\"Сохранить данные\">
        </div>
    </form>";

    $catsToShowContactWidget = array(1, 2, 3);

    $catsToShowMapWidget = array(2, 3, 4);

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";

    $additionalFieldsLinkTextArray = getAdditionalFieldsTextArray($rubricItem['cat']);

    $AdminRight .= displayAdminRightAdditionFieldLinks($id, $additionalFieldsLinkTextArray);

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_photo&amp;id=$id\">Основная фотография</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_text&amp;id=$id\">Основное содержание</a></div>";

    if (in_array($rubricItem['cat'], $catsToShowContactWidget)) {
        $AdminRight .= "<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_contacts&id=$id\">Контакты</a></div>";
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