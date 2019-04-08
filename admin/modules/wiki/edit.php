<?php

require_once('wiki.functions.php');
require_once('wiki.viewFunctions.php');

// НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {
    $raz = getPageName($alias);

    if (isset($_POST["savebutton"])) {
        $editRubricItem = array(
            'id' => (int)$id,
            'tags' => serializeTags($_POST["tags"]),
            'createTimestamp' => makeTimestamp($_POST["ddata1"], $_POST["ddata2"], $_POST["ddata3"], $_POST["ddata4"]),
            'publishTimestamp' => makeTimestamp($_POST["ddata11"], $_POST["ddata21"], $_POST["ddata31"], $_POST["ddata41"]),
            'authorId' => (int)$_POST['authid'],
            'name' => prepareText($_POST["dname"]),
            'keywords' => prepareText($_POST["dkw"]),
            'description' => prepareText($_POST["dds"]),
            'censor' => $_POST["cens"],
            'materialSource' => prepareText($_POST["realinfo"]),
            'commentsValue' => (int)$_POST["comms"],
            'autoTimerFlag' => (bool)$_POST["autoon"],
            'materialViewFlags' => array(
                'commercialNewsFlag' => (bool)$_POST["comrs"],
                'onTvFlag' => (bool)$_POST["ontv"],
                'specialViewFlag' => (bool)$_POST["spec"],
                'yandexRssFlag' => (bool)$_POST["yarss"],
                'mailRssFlag' => (bool)$_POST["mailrss"],
                'tavtoTeaserFlag' => (bool)$_POST["tavto"],
                'contributionColumnFlag' => (bool)$_POST["redak"],
                'mailTeaserFlag' => (bool)$_POST["mailtizer"],
                'gisFlag' => (bool)$_POST["gis"],
            )
        );

        $catId = getRubricCategoryById((int)$id);

        editRubricMainFields($editRubricItem);

        $_SESSION["Msg"] = "<div class='SuccessDiv'>Запись успешно сохранена!</div>";

        navigate("?cat={$raz["link"]}_edit&id=$id");
    }

    $rubricItem = getRubricById($id);

    $categoriesData = getWikiCategoryList();
    $currentCategoryText = $categoriesData[$rubricItem['cat']];

    $selectedMaterialAuthors = GetSelected(getUsersList(), $rubricItem['uid']);

    $AdminText = '<h2>Редактирование: &laquo;' . $raz["shortname"] . '&raquo;</h2>' . $_SESSION["Msg"];

    $checkedTags = explode(",", trim($rubricItem["tags"], ","));

    if ($rubricItem["stat"] == 1) {
        $chk = "checked";
    }
    if ($rubricItem["astat"] == 1) {
        $chk1 = "checked";
    }
    if ($rubricItem["promo"] == 1) {
        $chk2 = "checked";
    }
    if ($rubricItem["onind"] == 1) {
        $chk3 = "checked";
    }
    if ($rubricItem["spec"] == 1) {
        $chk4 = "checked";
    }
    if ($rubricItem["yarss"] == 1) {
        $chk5 = "checked";
    }
    if ($rubricItem["mailrss"] == 1) {
        $chk6 = "checked";
    }
    if ($rubricItem["tavto"] == 1) {
        $chk7 = "checked";
    }
    if ($rubricItem["mailtizer"] == 1) {
        $chk8 = "checked";
    }
    if ($rubricItem["redak"] == 1) {
        $chk9 = "checked";
    }
    if ($rubricItem["gis"] == 1) {
        $chk10 = "checked";
    }

    if ($rubricItem["comments"] == 0) {
        $c1 = "selected";
    } elseif ($rubricItem["comments"] == 1) {
        $c2 = "selected";
    } else {
        $c3 = "selected";
    }

    $AdminText .= "<form action=\"{$_SERVER['REQUEST_URI']}\" enctype=\"multipart/form-data\" method=\"post\">";

    //Форма создания вики раздела
    $AdminText .= "
        <div class=\"RoundText\">
            <table>
                <tr class=\"TRLine0\">
                    <td style=\"width:22%;\"></td>
                    <td style=\"width:78%;\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Категория</td>
                    <td class=\"LongInput\"><strong>$currentCategoryText</strong></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Заголовок материала<star>*</star></td>
                    <td class=\"LongInput\"><input name=\"dname\" id=\"dname\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\" value=\"{$rubricItem['name']}\"></td>
                </tr>";

    $AdminText .=
        "<tr class=\"TRLine0\">
                    <td class=\"VarName\"></td>
                    <td><a href=\"javascript:void(0);\" onclick=\"ShowSets();\" id=\"ShowSets\">Показать дополнительные настройки</a></td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Автор материала</td>
                    <td class=\"LongInput\">
                        <div class=\"sdiv\">
                            <select name=\"authid\">$selectedMaterialAuthors</select>
                        </div>
                    </td>
                </tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Ключевые слова (keywords)</td>
                    <td class=\"LongInput\"><input name=\"dkw\" type=\"text\" value=\"{$rubricItem['kw']}\"></td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Описание (description)</td>
                    <td class=\"LongInput\"><input name=\"dds\" type=\"text\" value=\"{$rubricItem['ds']}\"></td>
                </tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Цензор материала</td>
                    <td class=\"LongInput\"><input name=\"cens\" type=\"text\" value=\"16+\" value=\"{$rubricItem['cens']}\"></td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Источник материала</td>
                    <td class=\"LongInput\"><input name=\"realinfo\" type=\"text\" value=\"{$rubricItem['realinfo']}\"></td>
                </tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Комментарии</td>
                    <td class=\"LongInput\">
                        <div class=\"sdiv\">
                            <select name=\"comms\">
                                <option value=\"0\" $c1>Чтение и добавление</option>
                                <option value=\"1\" $c2>Только чтение</option>
                                <option value=\"2\" $c3>Запретить комментарии</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Дата создания</td>
                    <td class=\"DateInput\">" . GetDataSet($rubricItem['data']) . "</td><tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Автопубликация</td>
                    <td class=\"DateInput\">" . GetDataSet($rubricItem['adata'], 1) . " включить таймер: <input type=\"checkbox\" name=\"autoon\" id=\"autoon\" value=\"1\" $chk1></td>
                <tr>
	        </table>
	    </div>";

    //экспорт материала
    $AdminText .= "
        <h2>Отображение и экспорт материала</h2>
        <div class='RoundText TagsList'>
            <table>
                <tr class='TRLine0'>
                    <td width='1%'><input name='comrs' id='comrs' type='checkbox' value='1' $chk2></td><td width='20%'>Коммерческая новость</td>
                    <td width='1%'><input name='ontv' id='ontv' type='checkbox' value='1' $chk3></td><td width='20%'>Поместить в телевизор</td>
                    <td width='1%'><input name='spec' id='spec' type='checkbox' value='1' $chk4></td><td width='20%'>Спец. размещение</td>
                </tr>
                <tr class='TRLine1'>
                    <td width='1%'><input name='yarss' id='yarss' type='checkbox' value='1' $chk5></td><td width='20%'>Отправить в Яндекс RSS</td>
                    <td width='1%'><input name='mailrss' id='mailrss' type='checkbox' value='1' $chk6></td><td width='20%'>Отправить в Mail RSS</td>
                    <td width='1%'><input name='tavto' id='tavto' type='checkbox' value='1' $chk7></td><td width='20%'>Отправить в тизер TAVTO</td>
                </tr>
                <tr class='TRLine0'>
                    <td width='1%'><input name='mailtizer' id='mailtizer' type='checkbox' value='1' $chk8></td><td width='20%'>Отправить в тизер Mail</td>
                    <td width='1%'><input name='redak' id='redak' type='checkbox' value='1' $chk9></td><td width='20%'>Редакционная колонка</td>
                    <td width='1%'><input name='gis' id='gis' type='checkbox' value='1' $chk10></td><td width='20%'>Отправлять в ГисМетео</td>
                </tr>
            </table>
        </div>";

    $tagList = getTagsList();

    $tagCounter = 1;
    foreach ($tagList as $tagKey => $tagValue) {
        if (in_array($tagKey, $checkedTags)) {
            $checked = "checked";
        } else {
            $checked = "";
        }
        $tags .= "<td width=\"1%\">
            <input name=\"tags[{$tagKey}]\" id=\"tags[{$tagKey}]\" type=\"checkbox\" class=\"tags\" value=\"1\" $checked />
            <td width=\"20%\">$tagValue</td>";
        if (($tagCounter) % 3 == 0) {
            $tags .= "</tr><tr class=\"TRLine" . ($line % 2) . "\">";
            $line++;
            if ($line == 3) {
                $line = 1;
            }
        }
        ++$tagCounter;
    }

    $AdminText .= "
        <h2>Тэги публикации</h2>
        <div class=\"InfoH2\">Выберите 2-4 темы, самые подходящие по смыслу публикации:</div>
        <div class=\"RoundText TagsList\">
            <table>
                <tr class=\"TRLine0\">$tags</tr>
            </table>
        </div>";

    $AdminText .=
        "<div class=\"CenterText\">
            <input type=\"submit\" name=\"savebutton\" id=\"savebutton\" class=\"SaveButton\" value=\"Сохранить данные\">
        </div>";

    $catsToShowContactWidget = array(1, 2, 3);

    $catsToShowMapWidget = array(2, 3, 4);

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";

    $additionalFieldsLinkTextArray = getAdditionalFieldsTextArray($rubricItem['cat']);

    $AdminRight .= displayAdminRightAdditionFieldLinks($id, $additionalFieldsLinkTextArray);

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_photo&amp;id=$id\">Основная фотография</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_text&amp;id=$id\">Основное содержание</a></div>";

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