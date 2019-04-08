<?php

require_once('realEstate.functions.php');
require_once('realEstate.viewFunctions.php');

// НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {
    $raz = getPageName($alias);

    $priceListFileName = null;
    if (isset($_FILES["price"]["name"]) && $_FILES["price"]["name"]!="") {

        $valid_types=array("doc", "docx", "xls", "xlsx", "odt", "pdf", "rar", "zip", "rtf", "djvu");
        $max_file_size=10000;

        $userfile = $_FILES['price']['tmp_name'];
        $visibleName = $_FILES['price']['name'];
        $ext = substr($visibleName, 1 + strrpos($visibleName, "."));
        $newName = "{$alias}_{$id}.$ext";
        if (!is_dir($ROOT."/userfiles/realEstate")) {
            mkdir($ROOT."/userfiles/realEstate", 0777);
        }

        $fileSize = filesize($userfile);
        $maxFileSizeBytes = $max_file_size * 1024;
        if ($fileSize  > ($maxFileSizeBytes)) {
            $msg = "Файл больше $max_image_size килобайт";
        } elseif (!in_array($ext, $valid_types)) {
            $msg = "Файл не является допустимым форматом";
        } else {
            unlink($ROOT . "/userfiles/realEstate/" . $newName);
            if (@move_uploaded_file($userfile, $ROOT . "/userfiles/realEstate/" . $newName)) {
                $priceListFileName = $newName;
            }
        }

    }

    if (isset($_POST["savebutton"])) {
        $editContactsItem = array(
            'id' => (int)$id,
            'forum_theme' => (int)$_POST["forum_theme"],
            'address' => prepareText($_POST["address"]),
            'phone' => prepareText($_POST["phone"]),
            'site' => prepareText($_POST["site"]),
            'email' => prepareText($_POST["email"]),
            'socNet1' => prepareText($_POST["socNet1"]),
            'socNet2' => prepareText($_POST["socNet2"]),
            'socNet3' => prepareText($_POST["socNet3"]),
            'price' => $priceListFileName
        );

        editRealEstateContacts($editContactsItem);

        $_SESSION["Msg"] = "<div class='SuccessDiv'>Запись успешно сохранена!</div>";

        navigate("?cat={$raz["link"]}_contacts&id=$id");
    }

    $rubricItem = getRealEstateById($id);

    if ($rubricItem["stat"] == 1) {
        $chk = "checked";
    }

    $realEstateContactItem = getRealEstateContactsById($id);

    $AdminText = "<h2>Редактирование контактов ЖК &laquo; {$rubricItem['name']} &raquo;</h2>" . $_SESSION["Msg"];

    $AdminText .= "<form action=\"{$_SERVER['REQUEST_URI']}\" enctype=\"multipart/form-data\" method=\"post\">";
    //Форма контактов раздела
    $AdminText .= "
        <div class=\"RoundText\">
            <table>
                <tr class=\"TRLine0\">
                    <td style=\"width:22%;\"></td>
                    <td style=\"width:78%;\"></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Адрес</td>
                    <td class=\"LongInput\"><input name=\"address\" id=\"address\" type=\"text\" maxlength=\"80\" value=\"{$realEstateContactItem['address']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Телефон</td>
                    <td class=\"LongInput\"><input name=\"phone\" id=\"phone\" type=\"text\" maxlength=\"80\" value=\"{$realEstateContactItem['phone']}\"></td>
                </tr>
                <tr class=\"TRLine0\">
                    <td class=\"VarText\">Сайт</td>
                    <td class=\"LongInput\"><input name=\"site\" id=\"site\" type=\"text\" maxlength=\"80\" value=\"{$realEstateContactItem['site_url']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">E-mail</td>
                    <td class=\"LongInput\"><input name=\"email\" id=\"email\" type=\"text\" maxlength=\"80\" value=\"{$realEstateContactItem['email']}\"></td>
                </tr>
                <tr class=\"TRLine01\">
                    <td class=\"VarText\">Прайс-лист</td>
                    <td><input type=\"file\" id=\"price\" name=\"price\" onChange=\"getFileName();\" /></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Социальная сеть 1</td>
                    <td class=\"LongInput\"><input name=\"socNet1\" id=\"socNet1\" type=\"text\" maxlength=\"255\" value=\"{$realEstateContactItem['soc_net_link1']}\"></td>
                </tr>
                <tr class=\"TRLine01\">
                    <td class=\"VarText\">Социальная сеть 2</td>
                    <td class=\"LongInput\"><input name=\"socNet2\" id=\"socNet2\" type=\"text\" maxlength=\"255\" value=\"{$realEstateContactItem['soc_net_link2']}\"></td>
                </tr>
                <tr class=\"TRLine1\">
                    <td class=\"VarText\">Социальная сеть 3</td>
                    <td class=\"LongInput\"><input name=\"socNet3\" id=\"socNet3\" type=\"text\" maxlength=\"255\" value=\"{$realEstateContactItem['soc_net_link3']}\"></td>
                </tr>
                            <tr class=\"TRLine0\">
                    <td class=\"VarText\">ID рубрики на форуме</td>
                    <td class=\"LongInput\"><input name=\"forum_theme\" id=\"forum_theme\" type=\"text\" maxlength=\"255\" value=\"{$realEstateContactItem['forum_theme']}\"></td>
                </tr>
            </table>
        </div>
        <div class=\"CenterText\">
            <input type=\"submit\" name=\"savebutton\" id=\"savebutton\" class=\"SaveButton\" value=\"Сохранить данные\">
        </div>
    </form>";

    $AdminRight .= "<br><br>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_edit&amp;id=$id\">Основные настройки</a></div>";

    $AdminRight .= "<div class=\"SecondMenu\"><a href=\"?cat={$alias}_photo&amp;id=$id\">Основная фотография</a></div>
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_text&amp;id=$id\">Основное содержание</a></div>";

    $AdminRight .= "<div class=\"SecondMenu2\"><a href=\"?cat={$alias}_contacts&id=$id\">Контакты</a></div>";

    $AdminRight .= "
	<div class=\"SecondMenu\"><a href=\"?cat={$alias}_album&amp;id=$id\">Виджет: Фото-альбом</a></div>
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