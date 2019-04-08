<?php

function getCalendarWidget($dateString, $add="") {
    $text="<input id=\"datepick$add\" name=\"ddata1$add\" type=\"text\" value=\"$dateString\" >";
    return $text;
}

function getShortDateString($dateInIsoFormat) {
    list($year, $month, $day) = explode("-", $dateInIsoFormat);
    return "$day.$month.$year";
}
