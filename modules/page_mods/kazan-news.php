<?php
$pg = $dir[1] ? $dir[1] : 1;
$file = "_index-kazan2news_" . (int)$pg;
$VARS["cachepages"] = 0;
$Page["Caption"] = "Новости Казани";
$CSSmodules["авто включение ленты"] = "/modules/lenta/lenta.css";
if (RetCache($file) == "true") {
    list($text, $cap) = GetCache($file, 0);
} else {
    list($text, $cap) = KazanNews();
    SetCache($file, $text, "");
}
$Page["Content"] = $text;


function KazanNews() {
    global $C30, $C, $dir;
    $onpage = 50;
    $list = array();
    $pg = $dir[1] ? $dir[1] : 1;
    $from = ($pg - 1) * $onpage;

    // Находим все таблицы с lenta ==================
    $q = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1')";
    $endq = "ORDER BY `data` DESC LIMIT " . $from . ", " . $onpage;
    $data = getNewsFromLentas($q, $endq);
    for ($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek($data["result"], $i);
        $ar = @mysql_fetch_array($data["result"]);
        $ar["link"] = "/" . $ar["link"] . "/view/" . $ar["id"];
        if( ! empty($ar['pic']) ) {
            $ar["pic"] = "/userfiles/pictavto/" . $ar["pic"];
        }
        $list[] = $ar;
    }

    usort($list, 'ArraySort');

    // выводим новости ==============================

    $cnt = 1;
    $text = '';
    $ban6 = 0;
    foreach ($list as $ar) {
        if (strpos($ar["link"], "ls") !== false || strpos($ar["link"], "bubr") !== false) {
            $rel = "target='_blank' rel='nofollow'";
        } else {
            $rel = "";
        }
        $safeTitle = str_replace('"', '&quot;', $ar['name']);
        if ($ar["pic"] != "") {
            $pic = '<img class="news-mid__picture" src="' . $ar['pic'] . '" alt="' . $safeTitle . '">';
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
        if ($cnt % 4 == 0) {
            if ($ban6 < 10) {
                $text .= "<div class='banner2' id='Banner-6-" . $ban6 . "'></div>";
                $ban6++;
            }
        }
        $cnt++;
    }

    // строим пагер =================================


    $q = "SELECT `[table]`.`id` FROM `[table]` WHERE (`[table]`.`stat`='1')";
    $endq = "";
    $data = getNewsFromLentas($q, $endq);
    $text .= Pager2($pg, $onpage, ceil($data["total"] / $onpage), $dir[0] . "/" . "[page]");
    // ==============================================
    return (array($text, $C));
}
