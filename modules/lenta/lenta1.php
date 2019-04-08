<?php

$table  = $link . "_lenta";
$table2 = "_widget_pics";
$table3 = "_widget_votes";
$table4 = "_widget_video";
$table5 = "_widget_voting";
$table6 = "_widget_contacts";
$table7 = "_widget_eventmap";
$table8 = "_widget_cards";
$tableQA = "_widget_questions";


if($start == "") {
    $start  = "list";
    $dir[1] = "list";
}
$file = $table . "-" . $start . "." . $page . "." . $id;

################################################### Вывод списка новостей общий
if($start == "list") {
    if(RetCache( $file ) == "true") {
        list( $text, $cap ) = GetCache( $file, 0 );
    } else {
        list( $text, $cap ) = GetLentaList();
        SetCache( $file, $text, "" );
    }
    $Page["Content"] = $text;
    $Page["Caption"] = $node["name"];
}

############################################# Вывод списка новостей в категории
if($start == "cat") {
    if(RetCache( $file ) == "true") {
        list( $text, $cap ) = GetCache( $file, 0 );
    } else {
        list( $text, $cap ) = GetLentaCat();
        SetCache( $file, $text, $cap );
    }
    $Page["Content"] = $text;
    $Page["Caption"] = $cap;
}

################################################################# Вывод новости
if($start == "view") {
    $where = $GLOBAL["USER"]["role"] == 0 ? "&& `stat`=1" : "";
    $data  = DB( "SELECT `id`,`comments`, `promo`,`alttext`,`name` FROM `" . $table . "` WHERE (`id`='" . (int) $dir[2] . "' " . $where . ") LIMIT 1" );
    if($data["total"] == 1) {
        @mysql_data_seek( $data["result"], 0 );
        $new = @mysql_fetch_array( $data["result"] );

        if($new["alttext"] == "") {
            if(RetCache( $file ) == "true") {
                list( $text, $cap ) = GetCache( $file );
            } else {
                list( $text, $cap ) = GetLentaId();
                SetCache( $file, $text, $cap );
            }
            $text .= "<img src='/modules/lenta/stat.php?ok=1&tab=" . $dir[0] . "&id=" . $new["id"] . "&uid=" . $_SESSION["userid"] . "' style='width:1px; height:1px;' />";
            if($new["promo"] != 1) {
                $text .= "<div class='banner hidden-mobile' id='Banner-6-2'></div>";
                $text .= "<div class='banner hidden-desktop' id='Banner-28-2'></div>";
                $text .= '<div id="unit_92471"><a href="http://smi2.ru/" >Новости СМИ2</a></div><script type="text/javascript" charset="utf-8">(function() {var sc = document.createElement(\'script\'); sc.type = \'text/javascript\'; sc.async = true;sc.src = \'//smi2.ru/data/js/92471.js\'; sc.charset = \'utf-8\';var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(sc, s);}());</script>';
            }
            $text .= UsersComments( $link, $page, $new["comments"] );
            if($new["promo"] != 1) {
                $text .= "<div class='banner hidden-mobile' id='Banner-6-3'></div>";
                $text .= "<div class='banner hidden-desktop' id='Banner-28-3'></div>";
            }

            $text .= '<!-- Яндекс.Директ --> <div id="yandex5ad"></div> <script type="text/javascript"> (function(w, d, n, s, t) { w[n] = w[n] || []; w[n].push(function() { Ya.Direct.insertInto(125901, "yandex5ad", { ad_format: "direct", font_size: 0.8, type: "horizontal", border_type: "block", limit: 1, title_font_size: 1, links_underline: true, site_bg_color: "FFFFFF", border_color: "CCCCFF", title_color: "0066CC", url_color: "333333", text_color: "000000", hover_color: "0066FF", no_sitelinks: true }); }); t = d.getElementsByTagName("script")[0]; s = d.createElement("script"); s.src = "//an.yandex.ru/system/context.js"; s.type = "text/javascript"; s.async = true; t.parentNode.insertBefore(s, t); })(window, document, "yandex_context_callbacks"); </script>';

            if($GLOBAL["USER"]["role"] > 1) {
                $text = $C10 . "<div id='AdminEditItem'><a href='" . $GLOBAL["mdomain"] . "/admin/?cat=" . $link . "_edit&id=" . (int) $dir[2] . "'>Редактировать</a></div>" . $text;
            }
        } else {
            $text           = $new["alttext"];
            $cap            = $new["name"];
            $node["design"] = "akbars";
        }
        $Page["Title"] = $cap;
        $cap = '';
    } else {
        $Page['Title'] = 'Материал не найден';
        $cap  = '';
        $text = @file_get_contents( $ROOT . "/template/404.html" );
        $text .= GetLastNews();
    }
    $Page["Content"] = $text;
    $Page["Caption"] = $cap;
    $Page['Progress'] = '<div class="container"><progress value="0" id="progress"><div class="progress-container">
                <span class="progress-bar"></span></div></progress></div>';
}

### ЛЕНТА НОВОСТЕЙ ОСТАЛЬНЫЕ ##################################################

function GetLentaList() {
    global $VARS, $dir, $ORDERS, $node, $UserSetsSite, $table, $C, $C25;
    $text = '';
    $onpage  = $node["onpage"];
    $pg      = $dir[2] ? $dir[2] : 1;
    $orderby = $ORDERS[ $node["orderby"] ];
    $from    = ($pg - 1) * $onpage;
    $onblock = 4; /* Новостей в каждом блоке */
    $data    = DB( "SELECT `{$table}`.id, `{$table}`.name, `{$table}`.lid, `{$table}`.`pic`, `{$table}`.data,`{$table}`.comcount
	FROM `{$table}` WHERE (`{$table}`.`stat`=1)  GROUP BY 1 {$orderby} LIMIT $from, $onpage" );
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $ar  = @mysql_fetch_array( $data["result"] );
        if (strpos($ar["link"], "ls") !== false || strpos($ar["link"], "bubr") !== false) {
            $rel = "target='_blank' rel='nofollow'";
        } else {
            $rel = "";
        }
        $safeTitle = str_replace('"', '&quot;', $ar['name']);
        if ($ar["pic"] != "") {
            $pic = '<img class="news-mid__picture" src="/userfiles/pictavto/' . $ar['pic'] . '" alt="' . $safeTitle . '">';
            $picHolderClass = '';
            $newsAttr = '';
        } else {
            $pic = "";
            $picHolderClass = ' hidden-desktop hidden-mobile';
            $newsAttr = ' style="margin-left:0; width:100%;"';
        }
        list($time, $date) = explode(', ', ToRusData($ar['data'])[10]);
        If(isset($ar['comcount'])) {
            $comments = "<p class=\"news-mid__comments\">{$ar['comcount']}</p>";
        } else {
            $comments = '';
        }
        $ar['link'] = '/' . $dir[0] . '/view/' . $ar['id'] . '/';
        $text .= <<<HTML
			<div class="news-mid">
				<div class="news-mid__media-wrapper{$picHolderClass}">
					<a class="news-mid__header" href="{$ar['link']}" {$rel} title="{$safeTitle}">{$pic}</a>
				</div>
				<div class="news-mid__content"{$newsAttr}>
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
        if($data["total"] > ($i + 1)) {
            if(($i + 1) % $onblock == 0) {
                $text .= $C25 . "<div class='banner2 hidden-mobile' style='margin-left:10px;' id='Banner-6-" . (floor( $i / $onblock ) + 1) . "'></div>" . $C;
                $text .= $C25 . "<div class='banner2 hidden-desktop' style='margin-left:10px;' id='Banner-28-" . (floor( $i / $onblock ) + 1) . "'></div>" . $C;
            } else {
                $text .= $C25;
            }
        }
    }
    $data = DB( "SELECT count(id) as `cnt` FROM `" . $table . "` WHERE `stat` = 1" );
    @mysql_data_seek( $data["result"], 0 );
    $ar   = @mysql_fetch_array( $data["result"] );
    $text .= Pager2( $pg, $onpage, ceil( $ar["cnt"] / $onpage ), $dir[0] . "/" . $dir[1] . "/[page]" );

    return (array($text, ""));
}

##### КАТЕГОРИЯ НОВОСТЕЙ ######################################################

function GetLentaCat() {
    global $VARS, $GLOBAL, $dir, $link, $ORDERS, $RealHost, $Page, $node, $UserSetsSite, $table, $table2, $table3, $table4, $table5, $C, $C20, $C10, $C25;
    $text = '';
    $onpage  = $node["onpage"];
    $pg      = $dir[3] ? $dir[3] : 1;
    $orderby = $ORDERS[ $node["orderby"] ];
    $from    = ($pg - 1) * $onpage;
    $onblock = 4; /* Новостей в каждом блоке */
    $data    = DB( "SELECT `{$table}`.name, `{$table}`.pic, `{$table}`.data, `{$table}`.lid, `{$table}`.id, `{$table}`.comcount, `{$dir[0]}_cats`.name as `ncat`
	FROM `{$table}`	LEFT JOIN `{$dir[0]}_cats` ON `{$dir[0]}_cats`.`id`=`{$table}`.`cat` WHERE (`{$table}`.`cat`='" . (int) $dir[2] . "' && `{$table}`.`stat`=1) GROUP BY 1 {$orderby} LIMIT $from, $onpage" );
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $ar   = @mysql_fetch_array( $data["result"] );
        if (strpos($ar["link"], "ls") !== false || strpos($ar["link"], "bubr") !== false) {
            $rel = "target='_blank' rel='nofollow'";
        } else {
            $rel = "";
        }
        $safeTitle = str_replace('"', '&quot;', $ar['name']);
        if ($ar["pic"] != "") {
            $pic = '<img class="news-mid__picture" src="/userfiles/pictavto/' . $ar['pic'] . '" alt="' . $safeTitle . '">';
            $picHolderClass = '';
            $newsAttr = '';
        } else {
            $pic = "";
            $picHolderClass = ' hidden-desktop hidden-mobile';
            $newsAttr = ' style="margin-left:0; width:100%;"';
        }
        list($time, $date) = explode(', ', ToRusData($ar['data'])[10]);
        If(isset($ar['comcount'])) {
            $comments = "<p class=\"news-mid__comments\">{$ar['comcount']}</p>";
        } else {
            $comments = '';
        }
        $ar['link'] = '/' . $dir[0] . '/view/' . $ar['id'] . '/';
        $text .= <<<HTML
			<div class="news-mid">
				<div class="news-mid__media-wrapper{$picHolderClass}">
					<a class="news-mid__header" href="{$ar['link']}" {$rel} title="{$safeTitle}">{$pic}</a>
				</div>
				<div class="news-mid__content"{$newsAttr}>
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
        if($data["total"] > ($i + 1)) {
            if(($i + 1) % $onblock == 0) {
                $text .= $C25 . "<div class='banner2 hidden-mobile' style='margin-left:10px;' id='Banner-6-" . (floor( $i / $onblock ) + 1) . "'></div>" . $C;
                $text .= $C25 . "<div class='banner2 hidden-desktop' style='margin-left:10px;' id='Banner-28-" . (floor( $i / $onblock ) + 1) . "'></div>" . $C;
            } else {
                $text .= $C25;
            }
        }
    }
    $ncat = $ar["ncat"];
    $data = DB( "SELECT count(id) as `cnt` FROM `{$table}` WHERE (`cat`='" . (int) $dir[2] . "' && `{$table}`.`stat`=1)" );
    @mysql_data_seek( $data["result"], 0 );
    $ar   = @mysql_fetch_array( $data["result"] );
    $text .= Pager2( $pg, $onpage, ceil( $ar["cnt"] / $onpage ), $dir[0] . "/" . $dir[1] . "/" . $dir[2] . "/[page]" );

    return (array($text, $ncat));
}

####### ВЫВОД СОДЕРЖАНИЯ НОВОСТИ ##############################################
function GetLentaId() {
    global $VARS, $GLOBAL, $dir, $Page, $table, $table2, $table4, $table5, $table6, $table7, $table8, $tableQA, $link;

    $yandex = '<!-- Яндекс.Директ -->
<div id="yandex3ad"></div>
<script type="text/javascript">
(function(w, d, n, s, t) {
    w[n] = w[n] || [];
    w[n].push(function() {
        Ya.Direct.insertInto(125901, "yandex3ad", {
            ad_format: "direct",
            font_size: 0.8,
            type: "horizontal",
            border_type: "block",
            limit: 1,
            title_font_size: 1,
            links_underline: true,
            site_bg_color: "FFFFFF",
            border_color: "CCCCFF",
            title_color: "0066CC",
            url_color: "333333",
            text_color: "000000",
            hover_color: "0066FF",
            no_sitelinks: true
        });
    });
    t = d.getElementsByTagName("script")[0];
    s = d.createElement("script");
    s.src = "//an.yandex.ru/system/context.js";
    s.type = "text/javascript";
    s.async = true;
    t.parentNode.insertBefore(s, t);
})(window, document, "yandex_context_callbacks");
</script>';

    ### Основной запрос
    $data = DB( "SELECT `{$table}`.*, `{$dir[0]}_cats`.`name` as `ncat`, `_users`.`nick`, `_users`.`signature`, `_users`.`avatar`, `{$table5}`.`id` as `vvid` FROM `{$table}`
	LEFT JOIN `_users` ON `{$table}`.`uid`=`_users`.`id` LEFT JOIN `{$table5}` ON `{$table5}`.`pid`=`{$table}`.`id` AND `{$table5}`.`link`='{$dir[0]}' AND `{$table5}`.`vid`='0' AND `{$table5}`.`stat`=1	
	LEFT JOIN `{$dir[0]}_cats` ON `{$dir[0]}_cats`.`id`=`{$table}`.`cat` WHERE (`{$table}`.`id`='{$dir[2]}') GROUP BY 1 LIMIT 1" );
    @mysql_data_seek( $data["result"], 0 );
    $item                = @mysql_fetch_array( $data["result"] );
    $Page["Description"] = $item["ds"];
    $Page["KeyWords"]    = $item["kw"];
    $cap                 = $item["name"];
    $item['name']        = Hsc( $item['name'] );
    $item['lid']         = Hsc( $item['lid'] );

    $author = $noauthor = '';
    if( empty($item["uid"]) || empty($item["nick"]) ) {
        $noauthor = '<span class="article-info__author">Автор: Народный корреспондент</span>';
    } elseif( empty($item['showauthor']) ) {
        $noauthor = '<span class="article-info__author">Автор: progorodchelny.ru</span>';
    } else {
        $authorRole = trim( $item['signature'] );
        $author = <<<HTML
            <div class="article-author">
                <div class="article-author__wrapper">
                    <a href="/users/view/{$item['uid']}">
                        <img class="article-author__picture" src="/{$item['avatar']}" alt="">
                    </a>
                </div>
                <a href="/users/view/{$item['uid']}">
                    <span class="article-author__author">{$item['nick']}</span>
                </a>
                <span class="article-author__prof">{$authorRole}</span>
            </div>
HTML;
    }
    $date = Replace_Data_Days(ToRusData( $item["data"] )[4]);
    if( 'adverting' == $dir[0] || $item['promo'] || $item['spromo'] ) {
        $seens = '';
    } else {
        $seens = '<span class="article-info__views">' . $item['seens'] . '</span>';
    }
    if( $item['promo'] || $item['spromo'] ) {
        $adinfo = '<div class="Cens"><img src="/template/standart/info.png"></div>';
        $VARS['official'] .= '<br><img src="/template/standart/info.png" style="height:0.893em;width:0.893em;"> - на правах рекламы</p>';
    } else {
        $adinfo = '';
    }
    if( ! empty($item['pic'])) {
        if(file_exists( $_SERVER['DOCUMENT_ROOT'] . '/userfiles/picarticle/' . $item["pic"] )) {
            // Новый размер
            $picsrc = '/userfiles/picarticle/' . $item["pic"];
        } else {
            // Близжайший по разрешению
            $picsrc = '/userfiles/picintv/' . $item["pic"];
        }
        if( ! empty($item['picauth']) ) {
            $picauth = '<span class="article-top-photo__text">' . $item['picauth'] . '</span>';
        } else {
            $picauth = '';
        }
        $header_picture = <<<HTML
        <div class="article-top-photo">
            <div class="article-top-photo__picture-wrapper">
                {$adinfo}
                <img class="article-top-photo__picture" src="{$picsrc}" alt="">
            </div>
            $picauth
        </div>
HTML;

    } else {
        $header_picture = '';
    }
    $heading = <<<HTML
        {$header_picture}
        <div class="article-top">
            <div class="article-top__text">
                <h1 class="article__name">{$item['name']}</h1>
                <div class="article-info">
                    <span class="article-info__date">$date</span>
                    {$seens}
                    <span class="article-info__comments">{$item['comcount']}</span>
                    {$noauthor}
                </div>
            </div>
            {$author}
        </div>
HTML;

    if((int) $item["promo"] != 1) {
        $ban = '<div class="banner5" id="Banner-11-1"></div>';
    } else {
        $ban = '';
    }

    $lid = '<blockquote class="article__lead">' . $item['lid'] . '</blockquote>';

    ### Основной текст
    $maintext = $item["text"];

    // Меняем картинки
    // 0 - картинка, 1 - ссылка на картинку, 2 - alt текст, 3 - цитата data-quote
    $image_wrap = <<<HTML
        <div class="article-photo">
            <div class="article-photo__picture-wrapper">
                <a href="$1" rel="prettyPhoto[gallery]">$0</a>
            </div>
            <span class="article-photo__text">$2</span>
            <blockquote class="article-photo__quote">$3</blockquote>
        </div>
HTML;
    $maintext = preg_replace( '#<img(?=.*src="([^"]*)")(?=.*alt="([^"]*)")(?=.*data-quote="([^"]*)")?[^>]*>#',
                              $image_wrap, $maintext );

    $maintext = str_replace( array("\r", "\n", "<div>&nbsp;</div>", '<blockquote class="article-photo__quote"></blockquote>', "<p>&nbsp;</p>"), '', $maintext );

    ### Голосование
    if((int) $item["vvid"] != 0 && $dir[0] == "adverting" && (int) $dir[2] == 804) {
        $maintext .= "<div id='ItemVotingDiv'></div><script>GetItemVoting(" . (int) $item["vvid"] . ");</script>";
    }

    ### Фото-отчет
    $p      = DB( "SELECT * FROM `{$table2}` WHERE (`pid`='{$dir[2]}' && `link`='{$dir[0]}' && `point`='report' && `stat`=1) order by `rate` ASC" );
    $report = '';
    if($p["total"] > 0) {
        $report = '<section class="slider"><div id="sliderArticle" class="owl-carousel">';
        for($i = 0; $i < $p["total"]; $i++) {
            mysql_data_seek( $p["result"], $i );
            $ar = @mysql_fetch_array( $p["result"] );
            $report .= '<div class="slide">';
            $report .= '<div class="slide__picture-wrapper">';
            $ar['name'] = Hsc( $ar['name'] );
            $report .= sprintf('<img class="slide__picture" src="/userfiles/picitem/%1$s" title="%2$s" alt="%2$s"></div>', $ar['pic'], $ar['name']);
            $report .= '<span class="slide__text"><strong>' . $ar["name"] . '</strong> '  . strip_tags($ar["text"]) . '</span>';
            $report .= '</div>';
        }
        $report .= '</div></section>';
    }

    ### Карточки
    $p     = DB( "SELECT * FROM `{$table8}` WHERE (`pid`='{$dir[2]}' && `link`='{$dir[0]}') order by `id` ASC" );
    $cards = '';
    if($p["total"] > 0) {
        for($i = 0; $i < $p["total"]; $i++): mysql_data_seek( $p["result"], $i );
            $ar    = @mysql_fetch_array( $p["result"] );
            $cards .= "<div class='card'>";
            if($ar["num"] != "") {
                $cards .= "<h2 class='cardnum'>" . nl2br( $ar["num"] ) . "</h2>";
            }
            if($ar["name"] != "") {
                $cards .= "<h2 class='cardname'>" . nl2br( $ar["name"] ) . "</h2>";
            }
            if($ar["text"] != "") {
                $cards .= "<div class='cardtext'>" . nl2br( $ar["text"] ) . "</div>";
            }
            $cards .= "</div>";
        endfor;
    }

    ### Видео
    $p = DB( "SELECT * FROM `{$table4}` WHERE (`pid`='{$dir[2]}' && `link`='{$dir[0]}') LIMIT 1" );
    $video = "";
    if($p["total"] > 0) {
        for($i = 0; $i < $p["total"]; $i++) {
            mysql_data_seek( $p["result"], $i );
            $ar = @mysql_fetch_array( $p["result"] );
            if($ar["text"] != "") {
                preg_match('#<iframe[^>]*?src="([^"]*)"[^>]*>#', $ar['text'], $videosrc);
                if( ! empty($videosrc[1]) ) {
                    $video .= '<div class="article-video">';
                    $video .= '<a class="fancybox" data-fancybox-type="iframe" href="' . $videosrc[1] . '">';
                    $video .= '<img class="article-video__picture" src="' . $picsrc . '" alt="">';
                    $video .= '</a></div>';
                } else {
                    $video .= GetNormalVideo( $ar["text"] );
                }
            }
        }
    }

    ### Заключительный текст
    if($item["endtext"] != "") {
        $endtext = "<div class='ItemLid'>" . $item["endtext"] . "</div>";
    }

    ### Голосование
    if((int) $item["vvid"] != 0 && ($dir[0] != "adverting" || (int) $dir[2] != 804)) {
        $voting = "<div id='ItemVotingDiv'></div><script>GetItemVoting(" . (int) $item["vvid"] . ");</script>";
    }

    ### Фото-альбом
    $p = DB( "SELECT * FROM `{$table2}` WHERE (`pid`='{$dir[2]}' && `link`='{$dir[0]}' && `point`='album' && `stat`=1) order by `rate` ASC" );
    if($p["total"] > 0) {
        $album = "<h3>Фотоальбом:</h3><div class='ItemAlbum'>";
        for($i = 0; $i < $p["total"]; $i++): mysql_data_seek( $p["result"], $i );
            $ar    = @mysql_fetch_array( $p["result"] );
            if(file_exists( $_SERVER['DOCUMENT_ROOT'] . "/userfiles/picsmnews/" . $ar["pic"] )) {
                // Новый размер
                $albumPic = "/userfiles/picsmnews/" . $ar["pic"];
            } else {
                // Близжайший по разрешению
                $albumPic = "/userfiles/pictavto/" . $ar["pic"];
            }
            $album .= "<a href='/userfiles/picoriginal/" . $ar["pic"] . "' title='" . $ar["name"] . "' rel='prettyPhoto[album]'><img src='" . $albumPic . "' title='" . $ar["name"] . "' alt='" . $ar["name"] . "'></a>"; endfor;
        $album .= "</div>";
    }

    ### Карта событий
    $edata = DB( "SELECT `{$table7}`.*, `_pages`.`sets` FROM `{$table7}` LEFT JOIN `_pages` ON `_pages`.`module`='eventmap' WHERE (`{$table7}`.`pid`={$item['id']} AND `{$table7}`.`link`='{$link}' AND `{$table7}`.`stat`=1)" );
    if($edata["total"]) {
        @mysql_data_seek( $edata["result"], 0 );
        $ev = @mysql_fetch_array( $edata["result"] );
        if($ev['maps']) {
            $event = '<script type="text/javascript" src="	http://maps.api.2gis.ru/1.0"></script><div id="Map" style="width:500px; height:300px;"></div>';
            $event .= '<script type="text/javascript">initMap([' . $ev['id'] . ', "' . htmlspecialchars( $ev['name'] ) . '", "' . $ev['maps'] . '", "' . ($ev['icon'] ? '/userfiles/mapicon/' . $ev['icon'] : '') . '"]);</script>';
        } elseif($ev['data']) {
            $event_month_days = date( 't', $ev['data'] );
            $event_day        = date( 'j', $ev['data'] );
            $event_month      = date( 'n', $ev['data'] );
            $event_first_day  = getdate( mktime( 0, 0, 0, date( 'm', $ev['data'] ), 1, date( 'Y', $ev['data'] ) ) );
            $event_last_day   = getdate( mktime( 0, 0, 0, date( 'm', $ev['data'] ), $event_month_days,
                                                 date( 'Y', $ev['data'] ) ) );
            $calendar         = '<div class="Calendar"><table>';
            $calendar         .= '<tr><th colspan="7">' . $GLOBAL["mothi"][ date( 'n', $ev['data'] ) ];
            $calendar         .= date( ' Y', $ev['data'] ) . '</th></tr>';
            $calendar         .= '<tr><th>ПН</th><th>ВТ</th><th>СР</th><th>ЧТ</th><th>ПТ</th><th>СБ</th><th>ВС</th></tr><tr>';
            $event_last_wday = $event_last_day['wday'] == 0 ? 7 : $event_last_day['wday'];
            $calendar_days = $event_month_days + (7 - $event_last_wday);
            for($i = 2 - $event_first_day['wday'], $j = 1; $i <= $calendar_days; $i++, $j++) {
                $calendar .= '<td><span' . ($i == $event_day ? ' class="active" title="Начало"' : '') . '>';
                $calendar .= ($i > 0 && $i <= $event_month_days ? $i : '') . '</span></td>';
                if($j % 7 == 0) {
                    $calendar .= '</tr><tr>';
                }
            }
            $calendar .= '</tr></table></div>';
            $event    = $calendar;
        }
    }

    ### Отзыв
    $p      = DB( "SELECT * FROM `{$table2}` WHERE (`pid`='{$dir[2]}' && `link`='{$dir[0]}' && `point`='review' && `stat`=1) order by `rate` ASC" );
    $reviews = [];
    if($p["total"] > 0) {
        for($i = 0; $i < $p["total"]; $i++) {
            mysql_data_seek( $p["result"], $i );
            $ar = @mysql_fetch_array( $p["result"] );
            $ar['name'] = Hsc( $ar['name'] );
            $ar['text'] = Hsc( $ar['text'] );
            $reviews[ $ar['id'] ] .= '<div class="review">';
            $reviews[ $ar['id'] ] .= '<div class="review__photo-wrapper">';
            $reviews[ $ar['id'] ] .= '<img class="review__photo" src="/userfiles/picsquare/' . $ar['pic'] . '" alt="">';
            $reviews[ $ar['id'] ] .= '</div>';
            $reviews[ $ar['id'] ] .= '<div class="review__user">' . $ar['author'] . '</div>';
            $reviews[ $ar['id'] ] .= '<div class="review__prof">' . $ar['name'] . '</div>';
            $reviews[ $ar['id'] ] .= '<div class="review__text">' . strip_tags($ar["text"]) . '</div>';
            $reviews[ $ar['id'] ] .= '</div>';
        }
    }
    preg_match_all('/\[!--(.*?)--\]/', $maintext, $matches);
    $codes = [];
    foreach ($matches[1] as $i => $match) {
        $tmp = array_map('trim', explode('-', $match));
        $code = strtolower(array_shift($tmp));
        if( $code == 'review' ) {
            $codes[$code][] = ['replace' => $matches[0][$i], 'args' => $tmp];
        }
    }
    if( ! empty($codes['review']) ) {
        foreach($codes['review'] as $code) {
            if( isset($reviews[ $code['args'][0] ]) ) {
                $maintext = str_replace($code['replace'], $reviews[ $code['args'][0] ], $maintext);
            }
        }
    }
    $maintext = preg_replace('/\[!--.*?--\]/', '', $maintext);

    ### Вопросы
    $p     = DB( "SELECT * FROM `{$tableQA}` WHERE (`pid`='{$dir[2]}' && `link`='{$dir[0]}') order by `id` ASC" );
    $QA = '';
    if($p["total"] > 0) {
        $QA .= '<div class="questions">';
        for($i = 0; $i < $p["total"]; $i++): mysql_data_seek( $p["result"], $i );
            $ar    = @mysql_fetch_array( $p["result"] );
            if($ar["name"] != "" && $ar["text"] != "") {
                $QA .= '<div class="questions__header">' . nl2br( $ar["name"] ) . "</div>";
                $QA .= '<div class="questions__text">' . nl2br( $ar["text"] ) . "</div>";
            }
        endfor;
        $QA .= '</div>';
    }

    ### Лого и контакты
    $cdata = DB( "SELECT * FROM `{$table6}` WHERE (`pid`={$item['id']} AND `link`='{$link}')" );
    if($cdata["total"]) {
        @mysql_data_seek( $cdata["result"], 0 );
        $contactsData = @mysql_fetch_array( $cdata["result"] );
        if( ! empty( $contactsData["pic"] )) {
            $image = '<img class="contacts__logo-picture" src="/userfiles/picpreview/' . $contactsData['pic'] . '" alt="">';
        } else {
            $image = '';
        }
        $contactsData['name'] = Hsc( $contactsData['name'] );
        $contactsHTML = <<<HTML
            <div class="contacts">
                <div class="contacts__logo">
                    <h2 style="text-align:center;">{$contactsData['name']}</h2>
                    {$image}
                </div>
HTML;
        $contacts = [];
        if( ! empty( $contactsData['phone'] )) {
            $contacts['phone'] = $contactsData['phone'];
        }
        if( ! empty( $contactsData['address'] )) {
            $contacts['map'] = $contactsData['address'];
        }
        if( ! empty( $contactsData['web'] )) {
            $contactUrl           = str_replace( array("http://", "https://"), "", $contactsData['web'] );
            $contactLink          = explode( "?", $contactUrl )[0];
            $contactLink          = explode( "/", $contactLink )[0];
            $contacts['internet'] = '<a href="http://' . $contactUrl . '" target="_blank" rel="nofollow">' . $contactLink . '</a>';
        }
        if( ! empty( $contacts )) {
            $contactsHTML .= '<div class="contacts__inner">';
            foreach($contacts as $icon => $text) {
                $contactsHTML .= '<div class="contacts__block">';
                $contactsHTML .= '<div class="contacts__icon contacts__icon_' . $icon . '"></div>';
                $contactsHTML .= '<span class="contacts__text">' . $text . '</span>';
                $contactsHTML .= '</div>';
            }
            $contactsHTML .= '</div>';
        }
        $contactsHTML .= '</div>';
    }

    ### Источник
    $realinfo = "";
    if($item["realinfo"] != "") {
        if(strpos( $item["realinfo"], "www." ) !== false ||
           strpos( $item["realinfo"], "http" ) !== false ||
           strpos( $item["realinfo"], ".ru" ) !== false ||
           strpos( $item["realinfo"], ".com" ) !== false) {
            $url      = str_replace( array("http://", "https://"), "", $item["realinfo"] );
            $ri       = explode( "?", $url )[0];
            $ri       = explode( "/", $ri )[0];
            $realinfo = "<a href='http://" . $url . "' target='_blank' rel='nofollow'>" . $ri . "</a>";
        } else {
            $realinfo = $item["realinfo"];
        }
        $realinfo = "<noindex><div class='RealInfo'>Источник: " . $realinfo . "</div></noindex>";
    }

    ### Тэги
    /*$t    = trim( $item["tags"], "," );
    $tags = "";
    if($t != "") {
        $ta = DB( "SELECT * FROM `_tags` WHERE (`id` IN ({$t})) LIMIT 3" );
        for($i = 0; $i < $ta["total"]; $i++){
            @mysql_data_seek( $ta["result"], $i );
            $ar   = @mysql_fetch_array( $ta["result"] );
            $tags .= "<a href='/tags/$ar[id]'>$ar[name]</a>, ";
        }
        $tags  = "Тэги: " . trim( $tags, ", " );
    }
    $mixblock = "<div class='MixBlock'><div class='ILeft'><div class='ItemAuth'>$tags<br />Нашли ошибку? Выделите фразу и нажмите Ctrl+Enter</div></div>";
    if($item["promo"] != 1) {
        $mixblock .= "<div class='IRight'><div id='ItemLikesDiv'><img src='/template/standart/loader.gif' style='margin:15px 40px;'></div><script>GetItemLikes(" . (int) $item["id"] . ", '{$dir[0]}');</script></div>";
    }
    if($item["pay"] != "") {
        $mixblock .= "<div class='PayBlock'>" . $item["pay"] . "</div>";
    }
    $mixblock .= "</div>";*/

    ### Платные ссылки
    if($item["adv"] != "") {
        $mixblock .= "<div class='CBG'></div>" . "<div class='AdvBlock'>" . $item["adv"] . "</div>";
    }

    # Кнопки поделиться

    $share_dbres = DB('SELECT `network`, COUNT(*) `cnt` FROM `_shared`
                       WHERE `pid` = ' . $item['id'] . ' GROUP BY `network`');
    $share_counts = [];
    while( $tmp = mysql_fetch_assoc($share_dbres['result']) ) {
        $share_counts[ $tmp['network'] ] = $tmp['cnt'];
    }
    unset($tmp);
    $share_counts['comments'] = $item['comcount'];

    $_share = [
        ['network' => 'ok', 'show_icon' => true],
        ['network' => 'vk', 'show_icon' => true],
        ['network' => 'comments', 'title' => 'Обсудить'],
    ];
    $share  = '<div class="share">';
    foreach($_share as $s_data) {
        $share .= '<div class="share__block">';
        if( ! empty( $s_data['show_icon'] )) {
            $share .= '<div class="share__icon share__icon_' . $s_data['network'] . '"></div>';
        }
        if( ! empty( $s_data['title'] )) {
            $share .= '<div class="share__text share__comments_title">' . $s_data['title'] . '</div>';
        }
        $share .= '<span class="share__text">' . (int) $share_counts[ $s_data['network'] ] . '</span>';
        $share .= '</div>';
    }
    $share .= '</div>';

    # Социальные сети
    $social = '<div class="divider divider_bottom-space"><span class="divider__text">подписывайся на наши соц.сети</span>';
    $social_icons = ['social-vk' => 'vk', 'social-instagram' => 'insta'];
    foreach($social_icons as $social_var => $icon) {
        if( ! empty( $VARS[ $social_var ] )) {
            $social .= '
            <a href="' . $VARS[ $social_var ] . '" target="_blank" rel="nofollow"
            class="divider__icon divider__icon_' . $icon . '"></a>';
        }
    }
    $social .= '</div>';

    ### Читайте также
    $readmore = "";
    if((int) $item["promo"] != 1 && (int) $item["spromo"] != 1 && $item["tags"] != ",") {
        $tags = explode( ",", trim( $item["tags"], "," ) );
        if(sizeof( $tags ) > 0) {
            $p = getNewsByTags( $tags, 20, $link );

            if($p["total"] > 0) {
                $readmore = '<div class="recommend"><h2 class="recommend__header">читайте также:</h2>';

                for($i = 0; $i < $p["total"]; $i++) {
                    mysql_data_seek( $p["result"], $i );
                    $ar       = @mysql_fetch_array( $p["result"] );
                    $rmurl = '/' . $link . '/view/' . $ar['id'];
                    $ar['name'] = Hsc( $ar['name'] );
                    $readmore .= '<a href="'.$rmurl.'" title="'.$ar['name'].'" class="recommend__link">'.$ar['name'].'</a>';
                    if( $p['total'] - $i > 1 ) {
                        $readmore .= '<hr class="recommend__line">';
                    }
                    if($i == 3) {
                        $readmore .= '<div class="hiddenlis">';
                    }
                }
                if( $p["total"] > 3 ) {
                    $readmore .= '</div><span id="morelibtn"><a href="javascript:void(0);" class="recommend__link" onclick="showmorelis();"><b>Показать больше...</b></a></span></div>';
                }
            }
        }
    }

    ### компановка на вывод
    $text = '<article class="article" id="article" data-id="' . $item['id'] . '">' . $heading . $lid . '<hr class="article__line">' . "<div class='ArticleContent js-mediator-article'>" . $maintext . "</div>" . $report . $cards . $video . $endtext . $voting . $album . $event . $QA . $contactsHTML . $realinfo . $yandex . $mixblock . $share . $social . $readmore . $ban . '</article>';

    return (array($text, $cap));
}

function GetLastNews() {
    global $link;
    $readmore = "";
    $data = DB( "SELECT `id`,`name` FROM `{$link}_lenta` WHERE (`stat`=1) ORDER BY `data` DESC LIMIT 20" );
    if($data["total"] > 0) {
        $readmore = '<div class="recommend"><h2 class="recommend__header">Но может вам понравится:</h2>';

        for($i = 0; $i < $data["total"]; $i++) {
            mysql_data_seek( $data["result"], $i );
            $ar       = @mysql_fetch_array( $data["result"] );
            $rmurl = '/' . $link . '/view/' . $ar['id'];
            $ar['name'] = Hsc( $ar['name'] );
            $readmore .= '<a href="'.$rmurl.'" title="'.$ar['name'].'" class="recommend__link">'.$ar['name'].'</a>';
            if( $data['total'] - $i > 1 ) {
                $readmore .= '<hr class="recommend__line">';
            }
            if($i == 3) {
                $readmore .= '<div class="hiddenlis">';
            }
        }
        $readmore .= '</div><span id="morelibtn"><a href="javascript:void(0);" class="recommend__link" onclick="showmorelis();"><b>Показать больше...</b></a></span></div>';
    }
    return $readmore;
}
