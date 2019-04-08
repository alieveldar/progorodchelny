<?php
session_start();
$dir         = explode( "/", $_SERVER['HTTP_REFERER'] );
$HTTPREFERER = $dir[2];
if ( $HTTPREFERER == $_SERVER['HTTP_HOST'] ) {

    $GLOBAL["sitekey"] = 1;
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/DataBase.php";
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/Settings.php";
    @require $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/JsRequest.php";
    $JsHttpRequest = new JsHttpRequest( "utf-8" );

    // полученные данные ================================================

    $R        = $_REQUEST;
    $lastdata = $R["lastdata"];
    $lastdata = preg_replace( '/[^0-9]+/i', '', $lastdata );
    $list     = array();

    // операции =========================================================

    $q    = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`redak`=1 && `[table]`.`data`<'" . $lastdata . "')";
    $endq = "ORDER BY `data` DESC LIMIT 100";
    $data = getNewsFromLentas( $q, $endq );
    $sc   = 0;
    for ( $i = 0; $i < $data["total"]; $i ++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar          = @mysql_fetch_array( $data["result"] );
        $ar["style"] = "Editors";
        $ar["link"]  = "/" . $ar["link"] . "/view/" . $ar["id"];
        if ( $ar["pic"] != "" && $ar["tavto"] == 1 ) {
            $ar["pic"] = $src . "/userfiles/pictavto/" . $ar["pic"];
        } else {
            $ar["pic"] = "";
        }
        $list[] = $ar;
    }

    $img_wrap = '<img class="news-small__picture" src="%1$s" alt="%2$s">';

    foreach ( $list as $ar ) {
        $result["lastdata"] = $ar["data"];
        if ( strpos( $ar["link"], "ls" ) !== false || strpos( $ar["link"], "bubr" ) !== false ) {
            $rel = "target='_blank' rel='nofollow'";
        } else {
            $rel = "";
        }
        list($time, $date) = explode(' ', ToRusData($ar['data'])[10]);
        $safeTitle = str_replace('"', '&quot;', $ar['name']);
        If(isset($ar['comcount'])) {
            $comments = "<p class=\"news-mid__comments\">{$ar['comcount']}</p>";
        } else {
            $comments = '';
        }
        if ( $ar["tavto"] == 1 && $ar["pic"] != "" ) {
            $image = sprintf($img_wrap, $ar['pic'], $ar['name']);
        } else {
            $image = '';
        }
        $text .= <<<HTML
			<div class="news-mid">
				<div class="news-mid__media-wrapper">
					{$image}
				</div>
				<div class="news-mid__content">
					<a class="news-mid__header" href="{$ar['link']}" {$rel} title="{$safeTitle}">{$ar['name']}</a>
					<p class="news-mid__text">{$ar['lid']}</p>
					<div class="news-mid__info">
						<div class="news-mid__date">
							<p class="news-mid__day">{$date}</p>
							<p class="news-mid__time">{$time}</p>
						</div>
						{$comments}
					</div>
				</div>
			</div>
HTML;
    }

    $result["text"] = $text;
} else {
    $result["Text"]  = "--- Security alert ---";
    $result["Class"] = "ErrorDiv";
    $result["Code"]  = 0;
}

// отправляемые данные ==============================================
$GLOBALS['_RESULT'] = $result;

function getLentasOnModules() {
    global $lentas;
    if ( sizeof( $lentas ) == 0 ) {
        $modules = array( "lenta" );
        $notin   = array( "vtorzhilio", "world", "uncensored", "realestatenews", "gadgets" );
        $q       = "SELECT `link` FROM `_pages` WHERE (`module` IN ('" . implode( "','", $modules ) . "') && `link` NOT IN ('" . implode( "','", $notin ) . "')) LIMIT 50";
        $data    = DB( $q );
        for ( $i = 0; $i < $data["total"]; $i ++ ) {
            @mysql_data_seek( $data["result"], $i );
            $ar                  = @mysql_fetch_array( $data["result"] );
            $lentas[$ar["link"]] = $ar["link"] . "_lenta";
        }
    }

    return $lentas;
}

function getNewsFromLentas( $q = '', $endq = '' ) {
    global $used;
    $lentas = getLentasOnModules();
    $query  = '';
    if ( false !== ( $limwpos = strpos( strtoupper( $endq ), 'LIMIT' ) ) && ( $colonpos = strpos( $endq, ',', $limwpos ) ) ) {
        $oldlimit = explode( 'LIMIT', strtoupper( $endq ) );
        if ( 1 > (int) substr( $endq, $limwpos + 5, $colonpos - strlen( $endq ) ) ) {
            $newlimit    = substr( $endq, $colonpos + 1 );
            $single_endq = str_replace( $oldlimit, ' ' . trim( $newlimit ), $endq );
        } else {
            $single_endq = str_replace( 'LIMIT' . $oldlimit[count( $oldlimit ) - 1], '', $endq );
        }
    } else {
        $single_endq = $endq;
    }
    foreach ( $lentas as $l => $t ) {
        $usedtext = "";
        if ( sizeof( $used[$l] ) > 0 ) {
            $usedtext = " && `" . $t . "`.`id` NOT IN (0, " . implode( ",", $used[$l] ) . ")";
        } // не включаем в выборку ранее взятые новости
        $qitem = "(" . str_replace( array( "[table]", "[link]" ), array( $t, $l ), $q ) . $single_endq . ") UNION ALL ";
        $query .= str_replace( "[used]", $usedtext, $qitem );
    }
    $query = trim( $query, "UNION ALL" ) . ' ' . $endq;
    $data  = DB( $query );

    return $data;
} // заменяем таблицу и ссылку на нужное и формируем запрос


function DrawNewsItem( $ar, $datas = '', $class = '' ) {
    $text = "";
    if ( $datas == '' ) {
        $data = ToRusData( $ar["data"] );
    } else {
        $data = ToRusData( $datas );
    }
    $pic  = "<img src='/userfiles/picsquare/" . $ar["pic"] . "' title='" . $ar["name"] . "' alt='" . $ar["name"] . "' />";
    $text .= "<div class='itemlist " . $class . "'><data>" . ToLocalDay( $data[4] ) . "</data><a href='/" . $ar["link"] . "/view/" . $ar["id"] . "'>";
    if ( $ar["pic"] != "" )
    {
        $text .= $pic;
    }
    $text .= $ar["name"] . "</a>";
    if ( (int) $ar["comcount"] != 0 ) {
        $text .= " <span class='ComCnt' title='Комментарии'>" . $ar["comcount"] . "</span>";
    }
    $text .= "</div>";

    return $text;
}

function Dater( $ar, $float = "left", $view = "comcount" ) {
    if ( $view == "comcount" ) {
        $coms = "";
        if ( $ar["comcount"] != 0 ) {
            $coms = "<i title='Посмотреть комментарии'>" . $ar["comcount"] . "</i>";
        }
    }
    if ( $view == "seens" ) {
        $coms = "";
        if ( $ar["seens"] != 0 ) {
            $coms = "<s title='Количество просмотров статьи'>" . $ar["seens"] . "</s>";
        }
    }
    if ( $view == "likes" ) {
        $coms = "";
        if ( $ar["likes"] != 0 ) {
            $coms = "<u title='Количество положительных отзывов'>" . $ar["likes"] . "</u>";
        }
    }
    $data = "";
    if ( $ar["data"] != "" ) {
        $d    = ToRusData( $ar["data"] );
        $data = "<b>" . $d[10] . "</b>";
    }
    if ( $coms != "" || $data != "" ) {
        $text = "<div class='dater' style='float:" . $float . "'><a href='/" . $ar["link"] . "/view/" . $ar["id"] . "#comments'>" . $data . $coms . "</a></div>";
    }

    return $text;
}

function ToLocalDay( $data ) {
    return ( str_replace( array( date( "d.m.Y" ), date( "d.m.Y", time() - 60 * 60 * 24 ) ), array( "Сегодня", "Вчера" ), $data ) );
}

function ArraySort( $a, $b ) {
    if ( $a["data"] == $b["data"] ) {
        return 0;
    }

    return ( $a["data"] > $b["data"] ) ? - 1 : 1;
}

?>