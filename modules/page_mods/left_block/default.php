<?php
$file="_leftblock-pkdefault"; if (RetCache($file, "cacheblock")=="true") { list($Page["LeftContent"], $cap)=GetCache($file, 0); } else { list($Page["LeftContent"], $cap)=CreateLeftBlock(); SetCache($file, $Page["LeftContent"], "", "cacheblock"); }

$google='<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins style="display:inline-block; overflow:hidden; height:200px; width:200px;" class="adsbygoogle" data-ad-client="ca-pub-2073806235209608" data-ad-slot="9007081016"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

if ($link!="adverting") { $Page["LeftContent"]=str_replace(array("<!--yandex1-->","<!--yandex2-->","<!--google-->"), array($yandex1, $yandex2, $google), $Page["LeftContent"]); }

function CreateLeftBlock() {
    global $C25, $used, $VARS;
    $text    = '';
    $list    = array();
    $tmplist = array();
    $advsid  = 0;
    $cnt     = 1;

    /*TV*/
    $q    = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`tavto`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1 [used])";
    $endq = "ORDER BY `data` DESC LIMIT 1";
    $data = getNewsFromLentas( $q, $endq );
    for ( $i = 0; $i < $data["total"]; $i ++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        $used[$ar["link"]][] = $ar["id"];

        $link = "/{$ar['link']}/view/{$ar['id']}";
        $date = ToRusData( $ar["data"] )[10];
        $ar['name'] = Hsc($ar['name']);
        if(file_exists( $_SERVER['DOCUMENT_ROOT'] . "/userfiles/picsmnews/" . $ar["pic"] )) {
            // Новый размер
            $ar["pic"] = "/userfiles/picsmnews/" . $ar["pic"];
        } else {
            // Близжайший по разрешению
            $ar["pic"] = "/userfiles/pictavto/" . $ar["pic"];
        }
        $text .= <<<HTML
        <div class="news-short">
        <img src="{$ar['pic']}">
        <div class="news-short__time">{$date}</div>
        <a class="news-short__header news-short_important" href="{$link}" title="{$ar['name']}">{$ar['name']}</a>
        </div>
HTML;
    }

    /*OLD PROMO*/
    $q    = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`<'" . ( time() - 1 * 24 * 60 * 60 ) . "' && `[table]`.`data`>'" . ( time() - 4 * 24 * 60 * 60 ) . "' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) [used])";
    $endq = "ORDER BY `data` DESC";
    $data = getNewsFromLentas( $q, $endq );
    for ( $i = 0; $i < $data["total"]; $i ++ )
    {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        if ( $ar["link"] != "ls" )
        {
            $ar["style"] = "Oldest";
            $ar["link"]  = "/" . $ar["link"] . "/view/" . $ar["id"];
            if ( $ar["pic"] != "" && $ar["tavto"] == 1 )
            {
                if(file_exists( $_SERVER['DOCUMENT_ROOT'] . "/userfiles/picsmnews/" . $ar["pic"] )) {
                    // Новый размер
                    $ar["pic"] = "/userfiles/picsmnews/" . $ar["pic"];
                } else {
                    // Близжайший по разрешению
                    $ar["pic"] = "/userfiles/pictavto/" . $ar["pic"];
                }
            }
            else
            {
                $ar["pic"] = "";
            }
            $avds[] = $ar;
        }
    }

    /*NEWS*/
    $q    = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' [used])";
    $endq = "ORDER BY `data` DESC LIMIT 58";
    $data = getNewsFromLentas( $q, $endq );
    for ( $i = 0; $i < $data["total"]; $i ++ )
    {
        @mysql_data_seek( $data["result"], $i );
        $ar          = @mysql_fetch_array( $data["result"] );
        $ar["style"] = "Editors";
        $ar["link"]  = "/" . $ar["link"] . "/view/" . $ar["id"];
        if ( $ar["pic"] != "" && $ar["tavto"] == 1 )
        {
            if(file_exists( $_SERVER['DOCUMENT_ROOT'] . "/userfiles/picsmnews/" . $ar["pic"] )) {
                // Новый размер
                $ar["pic"] = "/userfiles/picsmnews/" . $ar["pic"];
            } else {
                // Близжайший по разрешению
                $ar["pic"] = "/userfiles/pictavto/" . $ar["pic"];
            }
        }
        else
        {
            $ar["pic"] = "";
        }
        $tmplist[] = $ar;
    }

    usort( $tmplist, 'ArraySort' );
    foreach ( $tmplist as $ar )
    {
        $list[] = $ar;
        if ( ( $cnt + 1 ) % 4 == 0 )
        {
            if ( $avds[$advsid]["name"] != "" )
            {
                $list[] = $avds[$advsid];
                $advsid ++;
                $cnt ++; /*Staruhi*/
            }
        }
        $cnt ++;
    }

    $cnt   = 1;
    $ban10 = 1;
    foreach ( $list as $ar ) {
        $text .= getBlocksContent($ar);

        if($cnt < 4)
        {
            $text .= '<div style="height: 15px;"></div>';
        }

        if ($cnt % 4 == 0) {
            if ($ban10 < 10) {
                $text .= "<div class='banner3' id='Banner-10-" . $ban10 . "'></div>";
                $ban10 = $ban10 + 2;
            }
            if ($ban10 >= 10 && $ban10 < 16) {
                $text .= '<!--google-->' . $C25;
                $ban10 = $ban10 + 2;
            }
        }


        if ( $cnt / 5 == 1 ) {
            $text .= "<noindex>";

            $text .= "<div class='banner' id='Banner-9-1'></div>";

            $longBannerData = [
                'show' => false,
                'items' => [
                    [
                        'url' => 'http://www.medel.ru/directions/flebologiya/',
                        'text' => 'Лечение варикоза ног уникальная лазерная методика',
                        'image' => '/template/advert/medel1.jpg',
                    ],
                    [
                        'url' => 'http://www.medel.ru/directions/podtyazhka-litsa-smas-extra-lifting/',
                        'text' => 'Подтяжка лица без операции и без уколов',
                        'image' => '/template/advert/medel2.jpg',
                    ],
                ],
                'afterText' => 'Реклама',
                'bannerId' => '1907',
            ];

            if( $longBannerData['show'] ) {
                $text .= '<div class="banner-toggleable" style="margin-bottom:45px;">';
                foreach ($longBannerData['items'] as $item) {
                    if (empty($item['url']) && empty($item['text'])) {
                        continue;
                    }
                    $url = '/advert/clickBanner.php?id=' . $longBannerData['bannerId'] . '%26away=' . urlencode($item['url']);
                    $text .= '<div class="banner-toggleable__picture-wrapper">
                    <a href="' . $url . '" rel="nofollow" target="_blank">
                    <img class="banner-toggleable__picture" src="' . $item['image'] . '">
                    </a>
                    </div>
                    <h3 class="banner-toggleable__header">
                    <a href="' . $url . '" rel="nofollow" target="_blank">
                    ' . $item['text'] . '
                    </a>
                    </h3>';
                }
                $text .= '
                <span class="banner-toggleable__text banner-toggleable__text_stroke1">имеются противопоказания</span>
                <span class="banner-toggleable__text banner-toggleable__text_stroke2">необходима консультация специалиста</span>
                <span class="banner-toggleable__text-small">' . $longBannerData['afterText'] . '</a></span>
                <div class="banner-toggleable__button-wrapper">
                <button class="banner-toggleable__button"></button>
                </div>
                </div>
                <img src="/advert/showBanner.php?ids=' . $longBannerData['bannerId'] . '" style="width:0px;height:0px;">';
            } else {
                // $text .= '<div style="height: 25px;"></div>';
            }

            $text .= "</noindex>";
        }

        $cnt ++;
    }

    return ( array( $text, "" ) );
}
?>
