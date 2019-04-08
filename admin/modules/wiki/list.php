<?

require_once('wiki.functions.php');

### КРОССЛИНКОВКА СТРАНИЦ
if ($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {
    global $pg;
    $table_list = $alias . "_lenta";
    $table_cats = $alias . "_cats";

    $raz = getPageName($alias);

    $AdminText .= "<h2 style=\"float:left;\">{$raz['name']}</h2>
	<div class=\"LinkG\" style=\"float:right;\">
	    <a href=\"?cat={$alias}_add\">Добавить материал</a>
    </div>
    {$_SESSION["Msg"]}$C5
    <div id=\"Msg2\" class=\"InfoDiv\">Вы можете редактировать и удалять записи</div>";


    $onpage = $raz["onpage"];
    $orderby = $ORDERS[$raz["orderby"]];
    $from = ($pg - 1) * $onpage;

    $wikiList = getWikiList($orderby, $from, $onpage);

    $text = "";

    foreach($wikiList as $key => $item) {
        $dateTimeLocalizedList = ToRusData($item["data"]);

        if ($item["stat"] == 1) {
            $chk = "checked";
        } else {
            $chk = "";
        }

        $lineOdd = ($key % 2);

        $editIcon = AIco('28');

        $text .=
        "<tr class=\"TRLine TRLine{$lineOdd}\" id=\"Line{$item['id']}\">
            <td class=\"CheckInput\">
                <input type=\"checkbox\" id=\"RS-{$item["id"]}-$table_list\" $chk>
            </td>
            <td class=\"BigText\">
                <a href=\"/$alias/view/{$item['id']}\"  target=\"_blank\">{$item['name']}</a> <i>{$item['catn']}</i>
            </td>
            <td class=\"Act\" width=\"1%\" style=\"white-space:nowrap; font-size:10px;\" >
                <i>{$dateTimeLocalizedList[4]}</i>
            </td>
            <td class=\"Act\">
                <a href=\"?cat={$alias}_edit&id={$item['id']}\" title=\"Править\">$editIcon</a>
            </td>";

        if ($raz["orderby"] == 5 || $raz["orderby"] == 6) {
            $text .=
            "<td class=\"Act\">
                <a href=\"javascript:void(0);\" onclick=\"ItemUp('" . $item["id"] . "', '" . $table_list . "', '" . $raz["orderby"] . "') title=\"Поднять\">" . AIco(3) . '</a>
            </td>
		    <td class="Act">
		        <a href="javascript:void(0);" onclick="ItemDown(\'' . $item["id"] . '\', \'' . $table_list . '\', \'' . $item["orderby"] . '\')" title="Опустить">' . AIco(4) . '</a>
		    </td>';
        }

        $text .=
            "<td class=\"Act\" id=\"Act{$ar['id']}\" >
                <a href=\"javascript:void(0);\" onclick=\"ItemDelete('{$ar['id']}', '$table_list')\" title=\"Удалить\">" . AIco('exit') . "</a>
            </td>
            <td class=\"Act\">
                <input type=\"checkbox\" id=\"{$item["id"]}\" class=\"selectItem\">
            </td>";
    }

    $AdminText .=
        "<div class=\"RoundText\" id=\"Tgg\">
            <div class=\"LinkR MultiDel\">
                <a href=\"javascript:void(0);\" onclick=\"MultiDelete('$table_list')\">Удалить выбранные</a>
            </div>
            <table>$text</table>
        </div>";

    $data = DB("SELECT `id` FROM `" . $table . "`");

    $AdminText .= Pager($pg, $onpage, ceil($data["total"] / $onpage));

    // ПРАВАЯ КОЛОНКА
    $AdminRight = "<div class='C20'></div><h2>Категории раздела</h2>";

    $text = "";
    $sortedCategoryList = getWikiCategoryListSorted();

    foreach($sortedCategoryList as $key => $item) {
        if ($item["stat"] == 1) {
            $chk = "checked";
        } else {
            $chk = "";
        }

        $lineOdd = ($key % 2);

        $text .=
            "<tr class=\"TRLine TRLine$lineOdd\">
                <td class=\"CheckInput\">
                    <input type=\"checkbox\" id=\"RS-{$item['id']}-{$table2}\" $chk >
                </td>
                <td>{$item['name']}</td>
            </tr>";
    }

    $AdminRight .= "<div class='RoundText' id='Tgg'><table>" . $text . "</table></div>" . $C10 . "<div class='LinkR' align='center'><a href='?cat=" . $alias . "_cats'>Редактировать список</a></div>";

}

//=============================================
$_SESSION["Msg"] = "";
?>