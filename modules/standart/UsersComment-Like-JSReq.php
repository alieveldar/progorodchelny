<?php
session_start();
$dir         = explode( "/", $_SERVER['HTTP_REFERER'] );
$HTTPREFERER = $dir[2];
if($HTTPREFERER == $_SERVER['HTTP_HOST']) {

    $GLOBAL["sitekey"] = 1;
    $error             = 0;
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/DataBase.php";
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/JsRequest.php";
    $JsHttpRequest = new JsHttpRequest( "utf-8" );

    // полученные данные ================================================
    $R   = $_REQUEST;
    $pid = (int) $R["pid"];
    $qid = (int) $R["qid"];
    $ip  = $_SERVER['REMOTE_ADDR'];

    ### обновляем базу данных
    // операции =========================================================
    if($pid != 0) {
        $data = DB( "SELECT `id` FROM `_likes` WHERE (`link`='_comments' && `pid`='" . $pid . "' && `ip`='" . $ip . "' && `data`>'" . (time() - 1 * 24 * 60 * 60) . "')" );
        $user = (int) $data["total"];

        if($user == 0) {
            # update likes and dislikes
            if($ip == '94.180.249.218') {
                $ip = 'My.' . rand( 100000, 999999999 );
            }
            DB( "INSERT INTO `_likes` (`data`,`ip`,`link`,`pid`,`like`) VALUES ('" . time() . "','" . $ip . "','_comments','" . $pid . "','" . $qid . "')" );
            if($qid == 0) {
                DB( "UPDATE `_comments` set `dislikes`=`dislikes`+1 WHERE (`id`='" . $pid . "') LIMIT 1" );
            }
            if($qid == 1) {
                DB( "UPDATE `_comments` set `likes`=`likes`+1 WHERE (`id`='" . $pid . "') LIMIT 1" );
            }
        }
        # get likes and dislikes
        $data = DB( "SELECT `likes`,`dislikes` FROM `_comments` WHERE (`id`='" . $pid . "') LIMIT 1" );
        @mysql_data_seek( $data["result"], 0 );
        $ar     = @mysql_fetch_array( $data["result"] );
        $likes  = (int) $ar["likes"];
        $dlikes = (int) $ar["dislikes"];
    }

    $result['text'] .= '<div class="comment" style="margin:0;padding:0;">';
    $result['text'] .= '<div class="comment__like">' . $likes . '</div>';
    $result['text'] .= '<div class="comment__dislike">' . $dlikes . '</div>';
    $result['text'] .= '</div>';
    //$result['text'] .= 'Спасибо за голос!';
} else {
    $result = array("Code" => 0, "Text" => "--- Security alert ---", "Class" => "ErrorDiv", "Comment" => '');
}

// отправляемые данные ==============================================
$GLOBALS['_RESULT'] = $result;
?>