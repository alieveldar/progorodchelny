<?
require_once('realEstate.functions.php');
require_once('realEstate.viewFunctions.php');

if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {

    $raz = getPageName($alias);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
    $P = $_POST;
    # save
    if (isset($P["savebutton"])) {
        foreach ($P["Inp"] as $key => $val) {
            $name = $val;
            $text = $P["PostText"][$key];
            $q = "UPDATE `_widget_pics` SET `name`='" . $name . "' WHERE (`id`='" . (int)$key . "' && pid='" . (int)$id . "')";
            DB($q);
        }
        $_SESSION["Msg"] = "<div class='SuccessDiv'>Настройки успешно сохранены</div>";
        @header("location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

    $rubricItem = getRealEstateById($id);

    if ($rubricItem["stat"] == 1) {
        $chk = "checked";
    }


    $AdminText = '<h2>Редактирование: &laquo' . $rubricItem["name"] . '&raquo;</h2>' . $_SESSION["Msg"];
    $AdminText .= "<form action='" . $_SERVER["REQUEST_URI"] . "' enctype='multipart/form-data' method='post'><div class='RoundText' align='center'>" . '<div id="uploader"></div>';
    $AdminText .= "<div class='Info' align='center'>Вы можете загружать файлы jpg, png, gif до 10М и размером не более 10.000px на 10.000px</div>" . '</div></form>';

    $data = DB("SELECT * FROM `_widget_pics` WHERE (`pid`='" . (int)$id . "' && `link`='" . $alias . "' && `point`='album') ORDER BY rate ASC");
    if ($data["total"] > 0) {
        $AdminText .= "<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/adapters/jquery.js'></script>";
        $AdminText .= "<form action='" . $_SERVER["REQUEST_URI"] . "' enctype='multipart/form-data' method='post'><div class='RoundText'><div class='LinkR MultiDel'><a href='javascript:void(0);' onclick='MultiDelete()'>Удалить выбранные</a></div><table>";
        for ($i = 0; $i < $data["total"]; $i++): @mysql_data_seek($data["result"], $i);
            $ar = @mysql_fetch_array($data["result"]);
            if ($ar["stat"] == 1) {
                $chk0 = "checked";
            }
            $img = "<img src='/userfiles/picpreview/" . $ar["pic"] . "' width='150' />";

            $AdminText .= '<tr class="TRLine" id="Line' . $ar["id"] . '" style="border-bottom:2px dotted #CCC;">
			<td class="LongInput" style="width:10%;" valign="top" align="center">' . $img . $C10 . '<input type="checkbox" id="RS-' . $ar["id"] . '-_widget_pics" value="1" ' . $chk0 . ' /></td>
			<td class="LongInput" style="width:80%;" valign="top"><input name="Inp[' . $ar["id"] . ']" value="' . $ar["name"] . '" placeholder="Название фотографии">' . $C5;
            #$AdminText.="<textarea name='PostText[".$ar["id"]."]' id='textedit".$ar["id"]."' style='outline:none;' class='texteditors'>".$ar["text"]."</textarea>";
            $AdminText .= '</td><td style="padding-top:10px !important;" valign="top">
				<div  class="Act"><input type="checkbox" id="' . $ar["id"] . '" class="selectItem"></div>' . $C15 . '
				<div id="Act' . $ar["id"] . '" class="Act"><a href="javascript:void(0);" onclick="ItemDelete(\'' . $ar["id"] . '\', \'' . $ar["pic"] . '\')">' . AIco('exit') . '</a></div>' . $C25 . '
				<div  class="Act"><a href="javascript:void(0);" onclick="ItemUp(\'' . $ar["id"] . '\')" title="Поднять">' . AIco(3) . '</a></div>' . $C15 . '
				<div  class="Act"><a href="javascript:void(0);" onclick="ItemDown(\'' . $ar["id"] . '\')" title="Опустить">' . AIco(4) . '</a></div>
			</td>';
            $AdminText .= '</tr>';
        endfor;
        $AdminText .= "</table>" . $C15 . "<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить настройки'></div></div>";
    }

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_photo&amp;id=$id\">Основная фотография</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_text&amp;id=$id\">Основное содержание</a></div>";

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_contacts&id=$id\">Контакты</a></div>";

    $AdminRight .= "
	<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_album&amp;id=$id\">Виджет: Фото-альбом</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_film&amp;id=$id\">Виджет: Видео-вставка</a></div>";


    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_eventmap&id=$id\">Виджет: Расположение</a></div>";

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