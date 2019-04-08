<?php
require_once('wiki.functions.php');
require_once('wiki.viewFunctions.php');

// НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {
    $raz = getPageName($alias);

    $subGroupId = (int)$_GET['sub'];

    if (isset($_POST["savebutton"])) {
        $catId = getRubricCategoryById((int)$id);

        $editRubricItem = array(
            'id' => (int)$id
        );

        switch ($catId) {
            case 1:
                switch($subGroupId) {
                    case 0:
                        $editRubricItem += array(
                            'education' => prepareText($_POST["education"]),
                            'carrier' => prepareText($_POST["carrier"])
                        );
                        break;
                    case 1:

                        $editRubricItem += array(
                            'birthLocation' => prepareText($_POST["birthLocation"]),
                            'deathLocation' => prepareText($_POST["deathLocation"]),
                            'birthDate' => makeDate($_POST["ddata11"]),
                            'deathDate' => makeDate($_POST["ddata12"])
                        );
                        break;
                }
                break;
            case 2:
                $editRubricItem += array(
                    'startDate' => makeDate($_POST["ddata11"]),
                    'endDate' => makeDate($_POST["ddata12"]),
                    'location' => prepareText($_POST["location"])
                );
                break;
            case 3:
                $editRubricItem += array(
                    'owner' => prepareText($_POST["owner"]),
                    'author' => prepareText($_POST["author"]),
                    'constructStartYear' => (int)$_POST["constructStartYear"],
                    'constructEndYear' => (int)$_POST["constructEndYear"],
                    'releaseDate' => makeDate($_POST["ddata11"]),
                );
                break;
            case 4:
                $editRubricItem += array(
                    'howToReach' => prepareText($_POST["howToReach"])
                );
                break;
            case 5:
                $editRubricItem += array(
                    'author' => prepareText($_POST["author"]),
                    'acceptDate' => makeDate($_POST["ddata11"]),
                );
                break;
            case 6:
                $editRubricItem += array(
                    'managers' => prepareText($_POST["managers"]),
                    'foundationDate' => makeDate($_POST["ddata11"]),
                    'decayDate' => makeDate($_POST["ddata12"]),
                );
                break;
            case 7:
                $editRubricItem += array(
                    'inventionAuthor' => prepareText($_POST["inventionAuthor"]),
                    'inventionDate' => makeDate($_POST["ddata11"])
                );
                break;
        }

        editRubricAdditionalFields($editRubricItem);

        $_SESSION["Msg"] = "<div class='SuccessDiv'>Запись успешно сохранена!</div>";

        navigate("?cat={$raz["link"]}_additional&id=$id&sub=$subGroupId");
    }

    $rubricItem = getRubricById($id);

    if ($rubricItem["stat"] == 1) {
        $chk = "checked";
    }

    $categoriesData = getWikiCategoryList();
    $currentCategoryText = $categoriesData[$rubricItem['cat']];

    $AdminText = '<h2>Редактирование: &laquo;' . $raz["shortname"] . '&raquo;</h2>' . $_SESSION["Msg"];

    $AdminText .= "<form action=\"{$_SERVER['REQUEST_URI']}\" enctype=\"multipart/form-data\" method=\"post\">";

    $AdminText .= "
        <div class=\"RoundText\">
            <table>
                <tr class=\"TRLine0\">
                    <td style=\"width:22%;\"></td>
                    <td style=\"width:78%;\"></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Заголовок материала<star>*</star></td>
                    <td class=\"LongInput\"><strong>{$rubricItem['name']}</strong></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Категория</td>
                    <td class=\"LongInput\"><strong>$currentCategoryText</strong></td>
                </tr>";

    switch ($rubricItem['cat']) {
        case 1:
            switch ($subGroupId) {
                case 0:
                    $AdminText .=
                        "<tr class=\"TRLine0\">
                                <td class=\"VarText\">Образование</td>
                                <td class=\"LongInput\"><textarea name=\"education\" id=\"education\" >{$rubricItem['education']}</textarea></td>
                            </tr>
                            <tr class=\"TRLine1\">
                                <td class=\"VarText\">Карьера</td>
                                <td class=\"LongInput\"><textarea name=\"carrier\" id=\"carrier\" >{$rubricItem['carrier']}</textarea></td>
                            </tr>";
                    break;
                case 1:
                    $birthDateShortFormat = $rubricItem['birth_date'] == null ? '' : getShortDateString($rubricItem['birth_date']);
                    $deathDateShortFormat = $rubricItem['death_date'] == null ? '' : getShortDateString($rubricItem['death_date']);
                    $AdminText .=
                        "<tr class=\"TRLine0\">
                            <td class=\"VarText\">Дата рождения</td>
                            <td class=\"DateInput\">" . getCalendarWidget($birthDateShortFormat, 1) . "</td>
                            </tr>
                            <tr class=\"TRLine1\">
                                <td class=\"VarText\">Дата смерти</td>
                                <td class=\"DateInput\">" . getCalendarWidget($deathDateShortFormat, 2) . "</td>
                            </tr>
                            <tr class=\"TRLine0\">
                                <td class=\"VarText\">Место рождения</td>
                                <td class=\"LongInput\"><textarea name=\"birthLocation\" id=\"birthLocation\" class=\"JsVerify2\">{$rubricItem['birth_location']}</textarea></td>
                            </tr>
                            <tr class=\"TRLine1\">
                                <td class=\"VarText\">Место смерти</td>
                                <td class=\"LongInput\"><textarea name=\"deathLocation\" id=\"deathLocation\" class=\"JsVerify2\">{$rubricItem['death_location']}</textarea></td>
                            </tr>";
                    break;
            }
            break;
        case 2:
            $startDateShortFormat = $rubricItem['start_date'] == null ? '' : getShortDateString($rubricItem['start_date']);
            $endDateShortFormat = $rubricItem['end_date'] == null ? '' : getShortDateString($rubricItem['end_date']);
            $AdminText .=
                "<tr class=\"TRLine0\">
                    <td class=\"VarText\">Дата начала</td>
                    <td class=\"DateInput\">" . getCalendarWidget($startDateShortFormat, 1) . "</td>
                            </tr>
                            <tr class=\"TRLine1\">
                                <td class=\"VarText\">Дата конца</td>
                                <td class=\"DateInput\">" . getCalendarWidget($endDateShortFormat, 2) . "</td>
                            </tr>
                            <tr class=\"TRLine0\">
                                <td class=\"VarText\">Где происходит</td>
                                <td class=\"LongInput\"><textarea name=\"location\" id=\"location\" class=\"JsVerify2\">{$rubricItem['location']}</textarea></td>
                            </tr>";
            break;
        case 3:
            $AdminText .=
                "<tr class=\"TRLine0\">
                    <td class=\"VarText\">Владелец</td>
                    <td class=\"LongInput\"><input name=\"owner\" id=\"owner\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\" value=\"{$rubricItem['owner']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Автор</td>
                    <td class=\"LongInput\"><input name=\"author\" id=\"author\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\" value=\"{$rubricItem['author']}\"></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Годы строительства</td>
                    <td class=\"SmallInput\">
                        <input name=\"constructStartYear\" id=\"constructStartYear\" type=\"text\" class=\"JsVerify2\" maxlength=\"4\" value=\"{$rubricItem['construct_start_year']}\"><strong> - </strong>
                        <input name=\"constructEndYear\" id=\"constructEndYear\" type=\"text\" class=\"JsVerify2\" maxlength=\"4\" value=\"{$rubricItem['construct_end_year']}\">
                    </td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Дата официального открытия</td>
                    <td class=\"DateInput\">" . GetDataSet($rubricItem['releaseDate'], 2) . "</td>
                            </tr>";
            break;
        case 4:
            $AdminText .=
                "<tr class=\"TRLine0\">
                    <td class=\"VarText\">Как проехать</td>
                    <td class=\"LongInput\"><input name=\"howToReach\" id=\"howToReach\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\" value=\"{$rubricItem['how_to_reach']}\"></td>
                </tr>";
            break;
        case 5:
            $acceptDateShortFormat = $rubricItem['accept_date'] == null ? '' : getShortDateString($rubricItem['accept_date']);
            $AdminText .=
                "<tr class=\"TRLine0\">
                    <td class=\"VarText\">Автор</td>
                    <td class=\"LongInput\"><input name=\"author\" id=\"author\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\" value=\"{$rubricItem['author']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Дата официального принятия</td>
                    <td class=\"DateInput\">" . getCalendarWidget($acceptDateShortFormat, 1) . "</td>
                            </tr>";
            break;
        case 6:
            $foundationDateShortFormat = $rubricItem['foundation_date'] == null ? '' : getShortDateString($rubricItem['foundation_date']);
            $decayDateShortFormat = $rubricItem['decay_date'] == null ? '' : getShortDateString($rubricItem['decay_date']);
            $AdminText .=
                "<tr class=\"TRLine0\">
                    <td class=\"VarText\">Дата создания</td>
                    <td class=\"DateInput\">" . getCalendarWidget($foundationDateShortFormat, 1) . "</td>
                            </tr>
                            <tr class=\"TRLine1\">
                                <td class=\"VarText\">Дата распада</td>
                                <td class=\"DateInput\">" . getCalendarWidget($decayDateShortFormat, 2) . "</td>
                            </tr>
                            <tr class=\"TRLine0\">
                                <td class=\"VarText\">Руководители</td>
                                <td class=\"LongInput\"><textarea name=\"managers\" id=\"managers\" class=\"JsVerify2\">{$rubricItem['managers']}</textarea></td>
                            </tr>";
            break;
        case 7:
            $inventionDateShortFormat = $rubricItem['invention_date'] == null ? '' : getShortDateString($rubricItem['invention_date']);
            $AdminText .=
                "<tr class=\"TRLine0\">
                    <td class=\"VarText\">Автор изобретения</td>
                    <td class=\"LongInput\"><input name=\"inventionAuthor\" id=\"inventionAuthor\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\" value=\"{$rubricItem['author']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Дата изобретения</td>
                    <td class=\"DateInput\">" . getCalendarWidget($inventionDateShortFormat, 1) . "</td>
                        </tr>";
            break;
    }

    $AdminText .= "</table></div>";

    $AdminText .=
        "<div class=\"CenterText\">
            <input type=\"submit\" name=\"savebutton\" id=\"savebutton\" class=\"SaveButton\" value=\"Сохранить данные\">
        </div>";

    $catsToShowContactWidget = array(1, 2, 3);

    $catsToShowMapWidget = array(2, 3, 4);

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";

    $additionalFieldsLinkTextArray = getAdditionalFieldsTextArray($rubricItem['cat']);

    $AdminRight .= displayAdminRightAdditionFieldLinks($id, $additionalFieldsLinkTextArray, $subGroupId);

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