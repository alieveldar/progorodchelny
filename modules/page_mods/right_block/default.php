<?php
$file="_rightblock-rightdefault"; if (RetCache($file, "cacheblock")=="true") { list($Page["RightContent"], $cap)=GetCache($file, 0); } else { list($Page["RightContent"], $cap)=CreateRightBlock(); SetCache($file, $Page["RightContent"], "", "cacheblock"); }

$yandex1='<!-- Яндекс.Директ --><div id="yandex1ad"></div><script type="text/javascript">(function(w, d, n, s, t) { w[n] = w[n] || []; w[n].push(function() { Ya.Direct.insertInto(125901, "yandex1ad", { ad_format: "direct", font_size: 0.8, type: "vertical", border_type: "block", limit: 3, title_font_size: 1, site_bg_color: "FFFFFF", header_bg_color: "CCCCCC", border_color: "CCCCCC", title_color: "0066CC", url_color: "006600", text_color: "000000", hover_color: "0066FF", no_sitelinks: true}); }); t = d.getElementsByTagName("script")[0]; s = d.createElement("script"); s.src = "//an.yandex.ru/system/context.js"; s.type = "text/javascript"; s.async = true; t.parentNode.insertBefore(s, t);})(window, document, "yandex_context_callbacks");</script>'.$C25;
$yandex2='<!-- Яндекс.Директ --><div id="yandex2ad"></div><script type="text/javascript">(function(w, d, n, s, t) { w[n] = w[n] || []; w[n].push(function() { Ya.Direct.insertInto(125901, "yandex2ad", { ad_format: "direct", font_size: 0.8, type: "vertical", border_type: "block", limit: 3, title_font_size: 1, site_bg_color: "FFFFFF", header_bg_color: "CCCCCC", border_color: "CCCCCC", title_color: "0066CC", url_color: "006600", text_color: "000000", hover_color: "0066FF", no_sitelinks: true}); }); t = d.getElementsByTagName("script")[0]; s = d.createElement("script"); s.src = "//an.yandex.ru/system/context.js"; s.type = "text/javascript"; s.async = true; t.parentNode.insertBefore(s, t);})(window, document, "yandex_context_callbacks");</script>'.$C25;
$google='<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:240px;height:400px" data-ad-client="ca-pub-2073806235209608" data-ad-slot="7095611817"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

if ($link!="adverting") { $Page["RightContent"]=str_replace(array("<!--yandex1-->","<!--yandex2-->","<!--google-->"), array($yandex1, $yandex2, $google), $Page["RightContent"]); }

function CreateRightBlock() {
    global $used, $C25, $VARS;
    $ban10=2;
    $text = '<style>.news-mid:nth-child(2) .news-mid__content, .news-mid:nth-child(3) .news-mid__content {max-width: 215px;font-size: 13.44px;}.news-mid:nth-child(2) .news-mid__content .news-mid__header, .news-mid:nth-child(3) .news-mid__content .news-mid__header{line-height: 1;}</style>
    <div class="banner banner-right" id="Banner-1-1"></div>';

    $social = '<div class="divider divider_margin-top-0 divider_bottom-space">';
    $social_icons = ['social-vk' => 'vk', 'social-instagram' => 'insta'];
    foreach($social_icons as $social_var => $icon) {
        if( ! empty( $VARS[ $social_var ] )) {
            $social .= '
            <a href="' . $VARS[ $social_var ] . '" target="_blank" rel="nofollow"
            class="divider__icon divider__icon_' . $icon . '"></a>';
        }
    }
    $social .= '<span class="divider__text">подписывайся!</span></div>';
    $text .= $social;
    $text .= '<h3 class="news-short-header">Новости <span class="news-short-header_black"> РОССИИ</span></h3>';

    $q = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, '[link]' as `link` FROM `[table]` WHERE `[table]`.`stat`='1' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) && '[link]' !='ls' && `[table]`.`data`<'" . ( time() - 4 * 24 * 60 * 60 ) . "' && `[table]`.`data`>'" . ( time() - 7 * 24 * 60 * 60 ) . "'";
    $endq = "ORDER BY `data` DESC";
    $data = getNewsFromLentas( $q, $endq );
    for ( $i = 0; $i < $data["total"]; $i++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        $ar["pic"]  = "";
        if ( $ar["link"] != "ls" ) {
            $ar['link'] = "/{$ar['link']}/view/{$ar['id']}";
            $adv[] = $ar;
            $used[$ar['link']][] = $ar['id'];
        }
    }

    $q = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, '[link]' as `link` FROM `[table]` WHERE `[table]`.`stat`='1' && '[link]' !='ls' && `[table]`.`tags` LIKE '%125,%'";
    $endq = "ORDER BY `data` DESC LIMIT 75";
    $data = getNewsFromLentas( $q, $endq );

    for ( $i = 0; $i < $data["total"]; $i ++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        $ar['link'] = "/{$ar['link']}/view/{$ar['id']}";
        $list[] = $ar;
        $used[$ar['link']][] = $ar['id'];
    }


    /* ProGorodChelny */
    // $proChelnyJson = getChelnyNews('tags', ['limit' => 70,'tags' => array('55')]);
    // $proChelnyList = json_decode($proChelnyJson, true);
    // $gorodZelenodolskJson = getZelenodolskNews('tags', ['limit' => 70,'tags' => array('55')]);
    // $gorodZelenodolskList = json_decode($gorodZelenodolskJson, true);

    // $JsonList = getBlockJsonNews(['progorodchelny' => $proChelnyList, 'gorodzelenodolsk' => $gorodZelenodolskList]);
    // usort( $JsonList, 'ArraySort' );

    $tmplist = $list;
    $list = [];
    $advid = 0;
    $cnt2 = 0;
    foreach($tmplist as $i => $tmpar) {
        if (( $cnt2 + 1 ) % 3 == 0 && $adv[$advid]["name"] != "" ) {
            $list[] = $adv[$advid];
            $advid ++;
            if($adv[$advid]["name"] != "") {
                $list[] = $adv[$advid];
                $advid ++;
                $cnt2 ++;
            }
        } 
        $list[] = $tmpar;
        $cnt2 ++;
        if(( $cnt2 + 1 ) % 4 == 0) $cnt2 = 0;
    }
// Если есть ещё коммерческая новость, то она добавляется в список после всех
    if(count($adv) > $advid)
    {
        while($advid != count($adv))
        {
            if($adv[$advid]["name"] != "") 
            {
                $list[] = $adv[$advid];
                $advid ++;
            }
        }
    }

    $cnt = 1;

    foreach ( $list as $ar ) {

        $text .= getBlocksContent($ar);

        if ( $cnt % 4 == 0 )
        {
            if ( $cnt == 4 )
            {
                $text .= "<noindex>";

                $text .= "<div class='banner' id='Banner-9-1' class='123'></div>";

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
                    $text .= '<div style="height: 25px;"></div>';
                }

                $text .= "</noindex>";
            }

            if ($ban10 <= 8) {
                $text .= "<div class='banner3' id='Banner-10-" . $ban10 . "'></div>";
                $ban10 = $ban10 + 2;
            }
            //         if( ! empty($block) ) {
//             $text .= $block;
//             if ($ban10 <= 8 && 'ad' != $section['tag']) {
//                 $text .= "<div class='banner3' id='Banner-10-" . $ban10 . "'></div>";
//                 $ban10 = $ban10 + 2;
//             }

        }
        $cnt ++;
    }

    return array($text, "");
}