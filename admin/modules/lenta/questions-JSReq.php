<?php
session_start();
if($_SESSION['userrole'] > 1) {
    $GLOBAL["sitekey"] = 1;
    @require "../../../modules/standart/DataBase.php";
    @require "../../../modules/standart/JsRequest.php";
    $JsHttpRequest = new JsHttpRequest( "utf-8" );
    // полученные данные ================================================

    $R      = $_REQUEST;
    $pid    = $R["pid"];
    $lenta  = $R["lenta"];
    $items  = $R["id"];
    $action = $R['action'];
    $C10    = "<div class='C10'></div>";

    // операции =========================================================

    if('add' === $action) {
        DB( "INSERT INTO `_widget_questions` (`link`, `pid`) VALUES ('" . $lenta . "', '" . $pid . "')" );
        $last = DBL();

        $text = "
            <div class='question' id='question" . $last . "'>
                <a href='javascript:void(0);' onclick='RemoveField(" . $last . ")'>Удалить</a>" . $C10 . "
	            <textarea name='name[" . $last . "]' placeholder='Заголовок карточки'></textarea>" . $C10 . "
	            <textarea name='text[" . $last . "]' placeholder='Текст карточки'></textarea>
            </div>";

        $result["content"]  = $text;
        $GLOBALS['_RESULT'] = $result;
    } elseif('remove' === $action) {
        DB( "DELETE FROM `_widget_questions` WHERE (`id` IN (" . $items . "))" );

        $result["content"]  = "ok";
        $GLOBALS['_RESULT'] = $result;
    }
}
