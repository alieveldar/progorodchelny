<?php

require_once('wiki.functions.php');

// НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {

    $raz = getPageName($alias);

    if (isset($_POST["savebutton"])) {
        $createRubricItem = array(
            'tags'              => serializeTags($_POST["tags"]),
            'createTimestamp'   => makeTimestamp($_POST["ddata1"], $_POST["ddata2"], $_POST["ddata3"], $_POST["ddata4"]),
            'publishTimestamp'  => makeTimestamp($_POST["ddata11"], $_POST["ddata21"], $_POST["ddata31"], $_POST["ddata41"]),
            'authorId'          => (int)$_POST['authid'],
            'categoryId'        => (int)$_POST["site"],
            'name'              => prepareText($_POST["dname"]),
            'keywords'          => prepareText($_POST["dkw"]),
            'description'       => prepareText($_POST["dds"]),
            'censor'            => $_POST["cens"],
            'materialSource'    => prepareText($_POST["realinfo"]),
            'commentsValue'     => (int)$_POST["comms"],
            'autoTimerFlag'     => (bool)$_POST["autoon"],
            'materialViewFlags' => array(
                'commercialNewsFlag'     => (bool)$_POST["comrs"],
                'onTvFlag'               => (bool)$_POST["ontv"],
                'specialViewFlag'        => (bool)$_POST["spec"],
                'yandexRssFlag'          => (bool)$_POST["yarss"],
                'mailRssFlag'            => (bool)$_POST["mailrss"],
                'tavtoTeaserFlag'        => (bool)$_POST["tavto"],
                'contributionColumnFlag' => (bool)$_POST["redak"],
                'mailTeaserFlag'         => (bool)$_POST["mailtizer"],
                'gisFlag'                => (bool)$_POST["gis"],
            )
        );

        $rubricId = createRubric($createRubricItem);

        navigate("?cat={$raz["link"]}_edit&id=$rubricId");
    }

    $categoriesData     = getWikiCategoryList();
    $selectedCategories = GetSelected($categoriesData, 0);

    $selectedMaterialAuthors = GetSelected(getUsersList(), $_SESSION['userid']);

    $AdminText = "<h2>Добавление материала &laquo; {$raz['shortname']} &raquo;</h2>" . $_SESSION["Msg"];

    $AdminText .= "<form action=\"{$_SERVER['REQUEST_URI']}\" enctype=\"multipart/form-data\" method=\"post\">";

    //Форма создания вики раздела
    $AdminText .= "
        <div class=\"RoundText\">
            <table>
                <tr class=\"TRLine0\">
                    <td style=\"width:22%;\"></td>
                    <td style=\"width:78%;\"></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Заголовок материала<star>*</star></td>
                    <td class=\"LongInput\"><input name=\"dname\" id=\"dname\" type=\"text\" class=\"JsVerify2\" maxlength=\"80\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Категория</td>
                    <td class=\"LongInput\"><div class=\"sdiv\"><select name=\"site\">$selectedCategories</select></div></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarName\"></td>
                    <td><a href=\"javascript:void(0);\" onclick=\"ShowSets();\" id=\"ShowSets\">Показать дополнительные настройки</a></td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Автор материала</td>
                    <td class=\"LongInput\"><div class=\"sdiv\"><select name=\"authid\">$selectedMaterialAuthors</select></td>
                </tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Ключевые слова (keywords)</td>
                    <td class=\"LongInput\"><input name=\"dkw\" type=\"text\"></td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Описание (description)</td>
                    <td class=\"LongInput\"><input name=\"dds\" type=\"text\"></td>
                </tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Цензор материала</td>
                    <td class=\"LongInput\"><input name=\"cens\" type=\"text\" value=\"16+\"></td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Источник материала</td>
                    <td class=\"LongInput\"><input name=\"realinfo\" type=\"text\"></td>
                </tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Комментарии</td>
                    <td class=\"LongInput\">
                        <div class=\"sdiv\">
                            <select name=\"comms\">
                                <option value=\"0\">Чтение и добавление</option>
                                <option value=\"1\">Только чтение</option>
                                <option value=\"2\">Запретить комментарии</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr class=\"TRLine1 ShowSets\">
                    <td class=\"VarName\">Дата создания</td>
                    <td class=\"DateInput\">" . GetDataSet() . "</td><tr>
                <tr class=\"TRLine0 ShowSets\">
                    <td class=\"VarName\">Автопубликация</td>
                    <td class=\"DateInput\">" . GetDataSet(0, 1) . " включить таймер: <input type=\"checkbox\" name=\"autoon\" id=\"autoon\" value=\"1\"></td>
                <tr>
	        </table>
	    </div>";

    //экспорт материала
    $AdminText .= "
        <h2>Отображение и экспорт материала</h2>
        <div class='RoundText TagsList'>
            <table>
                <tr class='TRLine0'>
                    <td width='1%'><input name='comrs' id='comrs' type='checkbox' value='1'></td><td width='20%'>Коммерческая новость</td>
                    <td width='1%'><input name='ontv' id='ontv' type='checkbox' value='1'></td><td width='20%'>Поместить в телевизор</td>
                    <td width='1%'><input name='spec' id='spec' type='checkbox' value='1'></td><td width='20%'>Спец. размещение</td>
                </tr>
                <tr class='TRLine1'>
                    <td width='1%'><input name='yarss' id='yarss' type='checkbox' value='1'></td><td width='20%'>Отправить в Яндекс RSS</td>
                    <td width='1%'><input name='mailrss' id='mailrss' type='checkbox' value='1' checked></td><td width='20%'>Отправить в Mail RSS</td>
                    <td width='1%'><input name='tavto' id='tavto' type='checkbox' value='1'></td><td width='20%'>Отправить в тизер TAVTO</td>
                </tr>
                <tr class='TRLine0'>
                    <td width='1%'><input name='mailtizer' id='mailtizer' type='checkbox' value='1'></td><td width='20%'>Отправить в тизер Mail</td>
                    <td width='1%'><input name='redak' id='redak' type='checkbox' value='1'></td><td width='20%'>Редакционная колонка</td>
                    <td width='1%'><input name='gis' id='gis' type='checkbox' value='1'></td><td width='20%'>Отправлять в ГисМетео</td>
                </tr>
            </table>
        </div>";

    $tagList = getTagsList();

    $tagCounter = 1;
    foreach ($tagList as $tagKey => $tagValue) {
        $tags .= "<td width=\"1%\">
            <input name=\"tags[{$tagKey}]\" id=\"tags[{$tagKey}]\" type=\"checkbox\" class=\"tags\" value=\"1\" />
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

    $AdminText .= "
        <div class=\"CenterText\"'><input type=\"submit\" name=\"savebutton\" id=\"savebutton\" class=\"SaveButton\" value=\"Создать запись\"></div>
    </form>";

    $AdminRight = "<br><br>
        <div class=\"SecondMenu2\">
            <a href=\"{$_SERVER['REQUEST_URI']}\">Основные настройки</a>
        </div>
        <br>После сохранения основных настроек, вы сможете перейти к наполнению публикации контентом, загрузить фотографии и править остальные параметры записи.";
}