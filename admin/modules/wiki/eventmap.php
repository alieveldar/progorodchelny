<?
require_once('wiki.functions.php');
require_once('wiki.viewFunctions.php');

if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {

// РАЗДЕЛ
    $data = DB("SELECT `id`,`shortname`,`link`, `sets` FROM `_pages` WHERE (`link`='" . $alias . "') LIMIT 1");
    if ($data["total"] != 1) {
        $AdminText = ATextReplace('Item-Module-Error', $id, "_pages");
        $GLOBAL["error"] = 1;
    } else {
        @mysql_data_seek($data["result"], 0);
        $raz = @mysql_fetch_array($data["result"]);

        $table = $alias . '_lenta';
        $table2 = '_widget_eventmap';
        $table3 = '_widget_eventtype';

        $data = DB("SELECT `$table`.`id` AS `iid`, `$table`.`name` AS `iname`, `$table`.`text` AS `itext`, `$table`.`pic` AS `ipic`, `$table`.`stat` AS `istat`, `$table2`.* FROM `$table` LEFT JOIN `$table2` ON `$table2`.`pid`=$id AND `$table2`.`link`='$alias' WHERE (`$table`.`id`=$id) LIMIT 1");
        if ($data["total"] != 1) {
            $AdminText = ATextReplace('ItemError', $raz["shortname"] . " (" . $alias . ")", $id);
            $GLOBAL["error"] = 1;
        } else {
            @mysql_data_seek($data["result"], 0);
            $node = @mysql_fetch_array($data["result"]);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ

            if (isset($_POST["savebutton"])) {
                $name = $_POST['name'] ? $_POST['name'] : $node['iname'];
                $text = '';
                $pic = '';
                $icon = '';
                $maps = $_POST['maps'];
                $stat = $_POST['stat'];
                $tid = 0;
                $itemId = $_POST['id'];
                $promo = (int)$_POST['promo'];

                if ($itemId) {
                    DB("UPDATE
                            `$table2`
                        SET
                            `name`='$name', `pic`='$pic', `text`='$text', `maps`='$maps', `icon`='$icon', `stat`=$stat, `promo`=$promo, `tid`=$tid
                        WHERE(`id`=$itemId)");
                } else {
                    $itemDate = time();
                    DB("INSERT INTO
                        `$table2`
                            (`name`, `pic`, `text`, `maps`, `icon`, `stat`, `promo`, `link`, `pid`, `tid`, `data`)
                         VALUES('$name', '$pic', '$text', '$maps', '$icon', $stat, $promo, '$alias', $id, $tid, '$itemDate')");
                }

                $_SESSION["Msg"] = "<div class='SuccessDiv'>Запись успешно сохранена!</div>";
                @header("location: " . $_SERVER["REQUEST_URI"]);
                exit();
            }

            // ВЫВОД ПОЛЕЙ И ФОРМ

            $data = DB("SELECT `id`, `name` FROM `" . $table3 . "` WHERE (`stat`=1) ORDER BY `rate` DESC");
            for ($i = 0; $i < $data["total"]; $i++): @mysql_data_seek($data["result"], $i);
                $ar = @mysql_fetch_array($data["result"]);
                $types[$ar["id"]] = $ar["name"]; endfor;

            ### Заполнение данных
            if ($node["istat"] == 1) {
                $chk = "checked";
            }
            if ($node["stat"] == 1) {
                $chk2 = "checked";
            }
            if ($node["promo"] == 1) {
                $chk3 = "checked";
            }

            $AdminText = '<script type="text/javascript" src="http://maps.api.2gis.ru/1.0"></script><script type="text/javascript" src="/admin/texteditor/ckeditor.js"></script><script type="text/javascript" src="/admin/texteditor/adapters/jquery.js"></script>';
            $AdminText .= '<h2>Карта событий: &laquo' . $node["iname"] . '&raquo;</h2>' . $_SESSION["Msg"] . $C5 . "<div id='Msg2' class='InfoDiv'>Вы можете добавить только одно событие</div>";
            $AdminText .= "<form action='" . $_SERVER["REQUEST_URI"] . "' enctype='multipart/form-data' method='post' onsubmit='return JsVerify();'>";

            // Основные данные
            $AdminText .=
                "<div class=\"RoundText\">
                    <table>
	                    <tr class=\"TRLine0\">
	                        <td style=\"width:22%;\"></td>
	                        <td style=\"width:78%;\"></td>
	                    </tr>
	                    <tr class=\"TRLine0\">
	                        <td class=\"VarText\">Как проехать</td>
	                        <td class=\"LongInput\">
	                            <textarea name=\"name\" id=\"name\">{$node['name']}</textarea>
                            </td>
                        </tr>
                        <tr class=\"TRLine1\">
                            <td class=\"VarText\">Включено</td>
                            <td class=\"NormalInput\">
                                <input name=\"stat\" type=\"checkbox\" value=\"1\"$chk2>
                            </td>
                        </tr>
                        <tr class=\"TRLine0\">
                            <td class=\"VarText\" style=\"vertical-align:top;\">Координаты на карте</td>
                            <td class=\"LongInput\">
                                <div id=\"Map{$node['iid']}\" class=\"Map\"></div>
                            </td>
                        <tr>
                    </table>
                    <input name=\"maps\" class=\"maps_{$node['iid']}\" type=\"hidden\" value=\"{$node['maps']}\">
                    <input class=\"maps_default\" type=\"hidden\" value=\"{$VARS['maps']}\">
                </div>";

            ### Сохранение
            $AdminText .= "<div class='CenterText'><input name='id' type='hidden' value='" . $node["id"] . "'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div>";

            $catsToShowContactWidget = array(1, 2, 3);

            $catsToShowMapWidget = array(2, 3, 4);

            $rubricItem = getRubricById($id);

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
                            <div class=\"SecondMenu\"><a href=\"?cat={$alias}_film&amp;id=$id\">Виджет: Видео-вставка</a></div>";

            if (in_array($rubricItem['cat'], $catsToShowMapWidget)) {
                $AdminRight .= "<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_eventmap&id=$id\">Виджет: Карта событий</a></div>";
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
    }
}
$_SESSION["Msg"] = "";