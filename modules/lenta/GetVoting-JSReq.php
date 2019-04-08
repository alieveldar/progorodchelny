<?php
session_start();
$dir         = explode( "/", $_SERVER['HTTP_REFERER'] );
$HTTPREFERER = $dir[2];
if($HTTPREFERER == $_SERVER['SERVER_NAME']) {

    $GLOBAL["sitekey"] = 1;
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/Cache.php";
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/DataBase.php";
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/Settings.php";
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/JsRequest.php";
    $JsHttpRequest = new JsHttpRequest( "utf-8" );

    $ip = $_SERVER['REMOTE_ADDR'];

    // полученные данные ================================================

    $R   = $_REQUEST;
    $qid = $R["qid"];

    $table  = '_widget_voting';
    $table2 = '_widget_votes';

    // операции =========================================================
    function getVotingForm( $votingOptions ) {
        global $qid, $table;
        $file = $table . "-form." . $qid;
        $text = '';
        if(RetCache( $file ) == "true") {
            list( $text ) = GetCache( $file, 0 );
        } else {
            if(count($votingOptions) >= 3) {
                $form = '';
                for($i = 0; $i < count($votingOptions); $i++) {
                    $option = $votingOptions[$i];
                    if($option['vid'] == 0) {
                        $cap = $option["name"];
                    } else {
                        $form .= '
                            <label class="vote__block" for="vote-' . $option['id'] . '">
                                <div class="vote__circle">
                                    <input type="radio" name="vote" value="' . $option['id'] . '"
                                           id="vote-' . $option['id'] . '" />
                                    <svg class="vote-progress" width="45" height="45" viewBox="0 0 120 120">
                                        <circle class="vote-progress__meter" cx="60" cy="60" r="54" stroke-width="12" />
                                        <circle class="vote-progress__value" cx="60" cy="60" r="54" stroke-width="12" stroke-dasharray="339.292" stroke-dashoffset="0"/>
                                    </svg>
                                </div>
                                <span class="vote__text">' . $option["name"] . '</span>
                            </label>';
                    }
                }
                $text = '<div id="ItemVotingDiv"><div class="vote"><div class="vote__header">' . $cap . '</div>';
                $text .= '<div class="vote__inner">' . $form . '</div>';
                $text .= '<div class="votingButton"><a href="javascript:void(0);" onclick="voteSavelenta(' . $qid . ', ' . $option["pid"] . ', \'' . $option["link"] . '\')" style="height:30px;">Голосовать</a><span style="display:none;"><img src="/template/standart/loader.gif" style="vertical-align:middle;" /> Сохранение голоса</span></div></div>';
                $text .= '<style>label.vote__block:hover .vote-progress, input[type="radio"]:checked + .vote-progress{border-radius:50%;background-color:#adddff;}label.vote__block:hover{cursor:pointer;}.vote__block input[type="radio"]{display:none;}</style></div>';
                SetCache( $file, $text, "" );
            }
        }

        return $text;
    }


    function getVotingResult( $votingOptions ) {
        $votesWords = array('голосов', 'голос', 'голоса');
        global $qid, $table;
        $file = $table . "-result." . $qid;
        $text = '';
        if(RetCache( $file ) == "true") {
            list( $text ) = GetCache( $file );
        } else {
            if(count($votingOptions) >= 3) {
                $max = 0;
                $total = 0;
                for($i = 0; $i < count($votingOptions); $i++) {
                    $option = $votingOptions[$i];
                    if($option["vn"] > $max) {
                        $max = $option["vn"];
                    }
                    $total += $option["vn"];
                }
                $votesHTML = '';
                for($i = 0; $i < count($votingOptions); $i++) {
                    $option = $votingOptions[$i];
                    if($option['vid'] == 0) {
                        $cap = $option["name"];
                    } else {
                        $vnmod10 = (int) $option['vn'] % 10;
                        $vnmod100 = (int) $option['vn'] % 100;
                        if($vnmod10 == 1 && $vnmod100 != 11) {
                            $index = 1;
                        } elseif($vnmod10 == 0 || $vnmod10 > 4 || $vnmod100 > 10 && $vnmod100 < 20) {
                            $index = 0;
                        } else {
                            $index = 2;
                        }
                        $votesHTML .= '
                            <div class="vote__block">
                                <div class="vote__circle">
                                    <svg class="vote-progress" width="45" height="45" viewBox="0 0 120 120">
                                        <circle class="vote-progress__meter" cx="60" cy="60" r="54" stroke-width="12" />
                                        <circle class="vote-progress__value" cx="60" cy="60" r="54" stroke-width="12" stroke-dasharray="339.292" stroke-dashoffset="' . 360 * (1 - $option["vn"] / $total) . '"/>
                                    </svg>
                                    <span class="vote__value">' . round($option["vn"] / $total * 100, 2) . '</span>
                                </div>
                                <span class="vote__text">' . $option["name"] . '</span>
                                <span class="vote__text">' . $option['vn'] . ' ' . $votesWords[ $index ] . '</span>
                            </div>';
                    }
                }
                $text = '<div class="vote"><div class="vote__header">' . $cap . '</div>';
                $text .= '<div class="vote__inner">' . $votesHTML . '</div></div>';
                SetCache( $file, $text, "" );
            }
        }

        return $text;
    }

    $userVoted   = $_SESSION['userid'] ? "`$table2`.`uid`=" . $_SESSION['userid'] : "`$table2`.`ip`='$ip'";
    $data = DB( "SELECT `$table`.*, COUNT(`$table2`.`id`) as `vn`, `$table2`.`uid` FROM `$table` LEFT JOIN `$table2` ON `$table2`.`vid`=`$table`.`id` AND `$table2`.`pid`=`$table`.`pid` AND `$table2`.`link`=`$table`.`link` AND $userVoted WHERE (`$table`.`id`=$qid OR `$table`.`vid`=$qid) GROUP BY 1" );
    if($data["total"]) {
        $ar = [];
        $voted = false;
        while($tmp = mysql_fetch_assoc($data['result'])){
            $ar[] = $tmp;
            $voted = $voted || ! is_null($tmp['uid']);
        }
        if( $voted ) {
            $result['text'] = getVotingResult( $ar );
        } else {
            $result['text'] = getVotingForm( $ar );
        }
    }
}


// отправляемые данные ==============================================
$GLOBALS['_RESULT'] = $result;
