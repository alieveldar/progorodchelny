<?php

function getAdditionalFieldsTextArray($categoryId) {
    $additionalFieldsLinkTextArray = array();
    switch ($categoryId) {
        case 1:
            $additionalFieldsLinkTextArray = array("Образование и карьера", "Жизнь и смерть");
            break;
        case 2:
            $additionalFieldsLinkTextArray = array("События");
            break;
        case 3:
            $additionalFieldsLinkTextArray = array("О сооружении");
            break;
        case 4:
            $additionalFieldsLinkTextArray = array("Об объекте");
            break;
        case 5:
            $additionalFieldsLinkTextArray = array("Автор и дата принятия");
            break;
        case 6:
            $additionalFieldsLinkTextArray = array("Об предприятии");
            break;
        case 7:
            $additionalFieldsLinkTextArray = array("Автор и дата изобретения");
            break;
    }
    return $additionalFieldsLinkTextArray;
}

function displayAdminRightAdditionFieldLinks($rubricId, array $additionalFieldsLinkTextArray, $subGroupId = null) {
    global $alias;
    $rubricId = (int)$rubricId;
    $subGroupId = $subGroupId === null ? null : (int)$subGroupId;
    $txt = '';
    foreach($additionalFieldsLinkTextArray as $key => $value) {
        $cssClass = 'SecondMenu' . ($subGroupId !== null && $key == $subGroupId ? '2' : '');
        $txt .= "<div class=\"$cssClass\"><a href=\"?cat={$alias}_additional&amp;id={$rubricId}&amp;sub=$key\">$value</a></div>";
    }
    return $txt;
}

function getCalendarWidget($dateString, $add="") {
    $text="<input id=\"datepick$add\" name=\"ddata1$add\" type=\"text\" value=\"$dateString\" >";
    return $text;
}

function getShortDateString($dateInIsoFormat) {
    list($year, $month, $day) = explode("-", $dateInIsoFormat);
    return "$day.$month.$year";
}
