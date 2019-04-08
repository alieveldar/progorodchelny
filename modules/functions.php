<?php

function getNewsByTags($tags, $limit, $alias = null) {
    global $dir, $used;
    if(empty( $tags ) || ! (is_numeric( $tags ) || is_array( $tags ))) {
        return ['total' => 0, 'result' => false];
    }
    if(is_numeric( $tags )) {
        $tags = [$tags];
    }

    $tagsql = implode( ' OR ', array_map( function($tag) {
        return "`[link]_lenta`.`tags` LIKE '%,{$tag},%'";
    }, array_filter( $tags ) ) );

    $fields = ['id', 'pic', 'data', 'seens', 'comcount', 'lid', 'name'];
    if( $alias === 'livestream' ) {
        $fields = array_merge($fields, ['end', 'start', 'stream_link']);
    }
    $fieldsSQL = implode(', ', array_map(function( $field ){
        return '`[link]_lenta`.`' . $field . '`';
    }, $fields));

    $query = "SELECT {$fieldsSQL}, `_users`.`nick`, '[link]' AS `link` FROM `[link]_lenta`
    LEFT JOIN `_users` ON `[link]_lenta`.`uid` = `_users`.`id`
    WHERE (`[link]_lenta`.`stat`=1 AND ({$tagsql}) AND '[link]' != 'ls') [used]";
    $endq  = " ORDER BY `data` DESC LIMIT $limit";

    if(empty( $alias )) {
        $news = getNewsFromLentas( $query, $endq );
    } else {
        $query = str_replace( ['[used]', '[link]'], ["&& `[link]_lenta`.`id`!='{$dir[2]}'", $alias], $query );
        $news  = DB( $query . $endq );
    }

    mysql_data_seek($news['result'], 0);
    while( $tmp = mysql_fetch_assoc($news['result']) ) {
        $used[$tmp['link']][] = $tmp['id'];
    }

    return $news;
}

function getNewsByAlias( $alias, $limit ) {
    global $used;
    if( empty( $alias ) || 'ad' == $alias ) {
        return ['total' => 0, 'result' => false];
    }
    if( 'partner' === $alias ) {
        return getRightBlockPartnersNews();
    }

    $usedtext = "";
    if ( is_array($used[ $alias ]) && count( $used[ $alias ] ) > 0 ) {
        $usedtext = "AND `" . $alias . "_lenta`.`id` NOT IN (0, " . implode( ",", $used[ $alias ] ) . ")";
    }

    $fields = ['id', 'pic', 'data', 'seens', 'comcount', 'lid', 'name'];
    if( $alias === 'livestream' ) {
        $fields = array_merge($fields, ['end', 'start', 'stream_link']);
    }
    $fieldsSQL = implode(', ', array_map(function( $field ) use($alias){
        return '`' . $alias . '_lenta`.`' . $field . '`';
    }, $fields));

    $query = "SELECT {$fieldsSQL}, `_users`.`nick`, '{$alias}' AS `link` FROM `{$alias}_lenta`
    LEFT JOIN `_users` ON `{$alias}_lenta`.`uid` = `_users`.`id`
    WHERE (`{$alias}_lenta`.`stat`=1) {$usedtext}
    ORDER BY `data` DESC LIMIT $limit";

    $news  = DB( $query );

    mysql_data_seek($news['result'], 0);
    while( $tmp = mysql_fetch_assoc($news['result']) ) {
        $used[$tmp['link']][] = $tmp['id'];
    }

    return $news;
}

function getRightSectionsNews() {
    static $right_sections = [];

    if (empty($right_sections)) {
        $right_sections = [
            ['title' => 'Обзоры и Рейтинги', 'tag' => 144, 'view' => 'center-counts'],
            ['title' => 'Развлечения', 'tag' => 89, 'view' => 'author'],
            ['title' => '', 'tag' => 'ad', 'view' => 'smi24'],
            ['title' => 'Тендеры', 'tag' => 122, 'view' => 'center-counts'],
            ['title' => 'Партнерские Материалы', 'tag' => 'partner', 'view' => 'partner', 'limit' => 3],
            ['title' => 'Жалобы', 'tag' => 123, 'view' => 'author'],
            ['title' => 'Обзоры и Рейтинги', 'tag' => 144, 'view' => 'center-counts'],
            ['title' => 'Тендеры', 'tag' => 122, 'view' => 'center-counts'],
            ['title' => 'Жалобы', 'tag' => 123, 'view' => 'author'],
            ['title' => 'Обзоры и Рейтинги', 'tag' => 144, 'view' => 'center-counts'],
            ['title' => 'Тендеры', 'tag' => 122, 'view' => 'center-counts'],
            ['title' => 'Жалобы', 'tag' => 123, 'view' => 'author'],
        ];
        foreach($right_sections as &$section) {
            if (empty($section['limit'])) {
                $section['limit'] = 2;
            }
            if (!is_numeric($section['tag']) && !is_array($section['tag'])) {
                $section['news'] = getNewsByAlias($section['tag'], $section['limit']);
                $section['link'] = '';
            } else {
                $section['news'] = getNewsByTags($section['tag'], $section['limit']);
                $section['link'] = '/tags/' . $section['tag'] . '/';
            }
        }
    }

    return $right_sections;
}

function getRightBlockPartnersNews() {
    $q    = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`lid`, `[table]`.`pic`, '[link]' as `link`
    FROM `[table]`
    WHERE `[table]`.`stat`='1'
    AND ( `[table]`.`spromo` = 1 OR `[table]`.`promo` = 1 )
    AND (
    `[table]`.`data` BETWEEN '" . (time() - 7 * 24 * 60 * 60) . "' AND '" . (time() - 4 * 24 * 60 * 60) . "'
) [used] ";
$endq = "ORDER BY `data` DESC";
$news = getNewsFromLentas( $q, $endq );

return $news;
}

function getRightBlockNewsSection($title, $link, $news, $view = 'normal') {
    global $used;
    $html = '';

    if($news['total'] > 0) {
        $titleHtml = '<span class="divider__text">' . $title . '</span>';
        if( ! empty($link) ) {
            $titleHtml = '<a href="' . $link . '">' . $titleHtml . '</a>';
        }
        $html .= '<div class="divider">' . $titleHtml . '</div>';
        mysql_data_seek($news['result'], 0);
        while($article = mysql_fetch_assoc( $news['result'] )) {
            $used[$article['link']][] = $article['id'];
            $html .= getRightBlockArticle( $article, $view );
        }
    } elseif( $view == 'smi24' ) {
        $html = '<div id="smi_teaser_12084"><center><a href="https://24smi.info/?utm_source=informer_12084">Агрегатор новостей 24СМИ</a></center></div><style>#smi_teaser_12084 > div > div > div[class^="smiteaser-container"], #smi_teaser_12084_mobile > div > div > div[class^="smiteaser-container"]{ width: auto; }</style><script type="text/JavaScript" encoding="utf8">(function() {var sm = document.createElement("script");sm.type = "text/javascript";sm.async = true;sm.src = "//jsn.24smi.net/8/c/12084.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(sm, s);})();</script>';
    }

    return $html;
}

function getRightBlockArticle($article, $view = 'normal') {
    if( ! empty( $article['pic'] )) {
        if(file_exists( $_SERVER['DOCUMENT_ROOT'] . '/userfiles/picarticle/' . $article['pic'] )) {
            $picsrc = '/userfiles/picarticle/' . $article['pic'];
        } else {
            $picsrc = '/userfiles/picintv/' . $article['pic'];
        }
        $picture = '<img class="news-small__picture" src="' . $picsrc . '" alt="">';
    } else {
        $picture = '';
    }
    list( $date, $time ) = explode( ', ', ToRusData( $article['data'] )[4] );
    $link = '/' . $article['link'] . '/view/' . $article['id'];

    switch($view) {
        case 'livestream':
        return getLivestreamBlock($article, $picsrc);
        case 'author':
        $fields = ['author'];
        break;
        case 'center-counts':
        $fields = ['pic_counters', 'lid'];
        break;
        case 'partner':
        $fields = ['partner', 'lid', 'counters'];
        break;
        case 'normal':
        $fields = ['lid', 'counters'];
        break;
    }
    $fields = array_flip( $fields );

    $html = '<div class="news-small">';
    $html .= '<div class="news-small__media-wrapper">' . $picture;
    if(isset( $fields['pic_counters'] )) {
        $html .= '
        <div class="news-small__info">
        <span class="news-small__views">' . $article['seens'] . '</span>
        <span class="news-small__comments">' . $article['comcount'] . '</span>
        </div>';
    }
    if(isset( $fields['partner'] )) {
        $html .= '
        <div class="news-mid__partner">
        <span class="news-mid__partner-word">Партнерский материал</span>
        </div>';
    }
    $html .= '</div>';
    $html .= '<div class="news-small__content">';
    if(isset( $fields['author'] ) && ! empty( $article['nick'] )) {
        $html .= '<p class="interview__text" style="padding-left:0;">' . $article['nick'] . '</p>';
    }
    $html .= '<a class="news-small__header" href="' . $link . '">' . Hsc( $article['name'] ) . '</a>';
    if(isset( $fields['lid'] )) {
        $html .= '<p class="news-small__text">' . Hsc( $article['lid'] ) . '</p>';
    }
    $html .= '</div>';
    if(isset( $fields['counters'] )) {
        $html .= '
        <div class="news-mid__info">
        <div class="news-mid__date">
        <p class="news-mid__day">' . $date . '</p>
        <p class="news-mid__time">' . $time . '</p>
        </div>
        <p class="news-mid__comments">' . $article['comcount'] . '</p>
        </div>';
    }
    $html .= '</div>';

    return $html;
}

function getLivestreamBlock( $article, $picsrc ) {
    global $GLOBAL;
    $status = time() < $article['start'] ? 'future' : (time() < $article['end'] ? 'current' : 'past');
    switch( $status ) {
        case 'future':
        $class = 'live_disabled';
        $startMonth = $GLOBAL["moths"][ (int) date( 'm', $article['start'] ) ];
        $time = ToRusData( $article['start'] )[7];
        $date = date( 'd', $article['start'] ) . ' ' . $startMonth . "\n" . $time;
        $timeText = '<span class="live__time">' . $date . '</span>';
        break;
        case 'past':
        $class = 'live_active';
        $timeText = '';
        break;
        case 'current':
        return '';
        break;
    }
    if( empty($picsrc) ) {
        $picsrc = 'javascript:void(0);';
    }
    $html = '<div class="live ' . $class . '">';
    if( false !== strpos($class, 'active') ) {
        $html .= '
        <div class="live__buttons">
        <button class="live__button"><span class="live__icon_expand"></span></button>
        <button class="live__button"><span class="live__icon_hide"></span></button>
        <button class="live__button"><span class="live__icon_close"></span></button>
        </div>';
    }
    $html .= '
    <a class="fancybox" data-fancybox-type="iframe" href="' . $article['stream_link'] . '">
    <img class="live__picture" src="' . $picsrc . '" alt="">
    </a>
    <span class="live__name">' . Hsc($article['title']) . '</span>
    ' . $timeText . '
    </div>';
    return $html;
}

// Показ новостей в левом и правом блоках
function getBlocksContent($data) {
    if ( $data["link"] == "ls" || 
        strpos($data['link'], 'progorodchelny') !== false || 
        strpos($data['link'], 'gorodzelenodolsk') !== false || 
        strpos($data['link'], 'bubr') !== false) {
        $rel = "target='_blank' rel='nofollow'";
} else {
    $rel = "";
}
$date = ToRusData( $data["data"] )[10];
$data['name'] = Hsc( $data['name'] );
$text .= <<<HTML
<div class="news-short">
<div class="news-short__time">{$date}</div>
<a class="news-short__header" href="{$data['link']}" {$rel} title="{$data['name']}">{$data['name']}</a>
</div>
HTML;
return $text;
}


function getCenterContent($data, $page = "") {
    if ( strpos( $data["link"], "ls" ) !== false ||
        strpos( $data["link"], "bubr" ) !== false ||
        strpos( $data['link'], 'progorodchelny') !== false ) {
        $rel = "target='_blank' rel='nofollow'";
} else {
    $rel      = "";
    $lastdata = $data["data"];
}
if($data['name'])
{
    list($time, $date) = explode(', ', ToRusData($data['data'])[10]);
    $safeTitle = str_replace('"', '&quot;', $data['name']);
    If(isset($data['comcount'])) {
        $comments = "<p class=\"news-mid__comments\">{$data['comcount']}</p>";
    } else {
        $comments = '';
    }
    if( ! empty($data['pic']) ) {
        if($page == "index") $pic = '<img class="news-mid__picture" src="' . $data['pic'] . '" alt="' . $safeTitle . '">';
        else $pic = '<img class="news-mid__picture" src="/userfiles/pictavto/' . $data['pic'] . '" alt="' . $safeTitle . '">';
        $picHolderClass = '';
        $newsAttr = '';
        $news_mid = ''; // Стиль блока новости
        $new_mid_ht = ''; // Стиль названия и описания
    } else {
        $pic = "";
        $picHolderClass = ' hidden-desktop hidden-mobile';
        $newsAttr = ' style="margin-left:0; min-width: 100%;"';
        $news_mid = ' style="height: auto;"';
        $new_mid_ht = ' style="margin-bottom: 10px;"';
    }

    $date_text = "";

    $date_text = <<<HTML
    <div class="news-mid__date">
    <p class="news-mid__day">{$date}</p>
    <p class="news-mid__time">{$time}</p>
    </div>
HTML;
    $text .= <<<HTML
    <div class="news-mid"{$news_mid}>
    <div class="news-mid__media-wrapper{$picHolderClass}">
    <a class="news-mid__header" href="{$data['link']}" {$rel} title="{$safeTitle}">{$pic}</a>
    </div>
    <div class="news-mid__content"{$newsAttr}>
    <a class="news-mid__header" href="{$data['link']}" {$rel} title="{$safeTitle}"{$new_mid_ht}>{$data['name']}</a>
    <p class="news-mid__text"{$new_mid_ht}>{$data['lid']}</p>
    <div class="news-mid__info">
    {$date_text}
    {$comments}
    </div>
    </div>
    </div>
HTML;
    return $text;
}
else return false;
}