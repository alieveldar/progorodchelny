<?php

if (empty($_POST['prokazanexport']) || $_POST['prokazanexport'] !== 'C?r~LeG@N3b$') {
    die('[]');
}

error_reporting(0);
ini_set('display_errors', 0);

$GLOBAL["sitekey"] = 1;
require_once $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/DataBase.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/Settings.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/modules/standart/Cache.php";

$tables = array("auto_lenta", "business_lenta", "news_lenta", "sport_lenta");

$req = array_merge(['action' => '', 'limit' => 2, 'tags' => [], 'link' => ''], $_POST);
$req['limit'] = max((int)$req['limit'], 2);
$req['tags'] = array_filter(array_map('intval', $req['tags']));
if( ! empty( $req['link'] ) ) {
    $valid_links = ['news', 'business', 'auto', 'sport'];
    if( ! in_array($req['link'], $valid_links) ) {
        $req['link'] = reset($valid_links);
    }
}

$query = "";
$order = "ORDER BY `data` DESC";
$limit = "LIMIT " . $req['limit'];
$cached = false;
$afterAction = null;

switch ($req['action']) {
    case 'redak':
        $cacheFile = '_prokazan-redak';
        if (RetCache($cacheFile, "cacheblock") == 'true') {
            list($cached) = GetCache($cacheFile);
        } else {
            $query = 'SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`pic`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`tavto`, "[link]" as `link` FROM `[table]` 
            WHERE (`[table]`.`stat` = "1" AND `[table]`.`prokazanxml` = "1" AND `[table]`.`redak` = "1")';
        }
        break;
    case 'lenta':
        $cacheFile = '_prokazan-lenta';
        if (RetCache($cacheFile, "cacheblock") == 'true') {
            list($cached) = GetCache($cacheFile);
        } else {
            $query = 'SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`pic`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`tavto`, "[link]" as `link` FROM `[table]` 
            WHERE (`[table]`.`stat` = "1" AND `[table]`.`prokazanxml` = "1" AND `[table]`.`redak` != "1")';
        }
        break;
    case 'news':
        $cacheFile = '_prokazan-news';
        if( $req['link'] ) {
            $cacheFile .= '_' . $req['link'];
        }
        if (RetCache($cacheFile, "cacheblock") == 'true') {
            list($cached) = GetCache($cacheFile);
        } else {
            $query = 'SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`pic`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`tavto`, "[link]" as `link` FROM `[table]` 
            WHERE (`[table]`.`stat` = "1" AND `[table]`.`prokazanxml` = "1")';
            if( ! empty( $req['link'] ) ) {
                $query = str_replace(['[table]', '[link]'], [$req['link'] . '_lenta', $req['link']], $query);
            }
        }
        break;
        break;
    case 'tags':
        $tags = $req['tags'];
        $cachedtags = $tagscache = $subqueries = [];
        $limit = 'LIMIT 60';
        foreach ($tags as $tag) {
            $cacheTagFile = '_prokazan-tag_' . $tag;
            if (RetCache($cacheTagFile, "cacheblock") == 'true') {
                list($tagscache[]) = GetCache($cacheTagFile);
                $cachedtags[] = $tag;
            } else {
                $subqueries[] = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`pic`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`tavto`, `[table]`.`tags`, '[link]' as `link` FROM `[table]` 
            WHERE (`[table]`.`stat` = '1' AND `[table]`.`prokazanxml` = '1' AND `[table]`.`tags` LIKE '%,$tag,%') $order $limit";
            }
        }
        $query = implode(") UNION ALL \n(", $subqueries);
        $limit = '';
        $afterAction = function ($news, &$response) use ($req, $tagscache, $cachedtags) {
            $tagnews = $news;
            if (!empty($tagscache)) {
                foreach ($tagscache as $tc) {
                    $tagnews = array_merge($tagnews, json_decode($tc, true));
                }
                array_filter($tagnews, function ($news) {
                    static $ids = [];
                    if (!is_array($ids[$news['link']]) || !in_array($news['id'], $ids[$news['link']])) {
                        $ids[$news['link']][] = $news['id'];
                        return true;
                    }
                    return false;
                });
                usort($tagnews, function ($a, $b) {
                    $a = !empty($a['data']) ? $a['data'] : 0;
                    $b = !empty($b['data']) ? $b['data'] : 0;
                    return $b - $a;
                });
            }
            $response = json_encode(array_splice($tagnews, 0, $req['limit']));
            if (!empty($news)) {
                $tags = [];
                foreach ($news as $nk => $art) {
                    if (!empty($art['tags'])) {
                        $artTags = array_filter(explode(',', $art['tags']));
                        foreach ($artTags as $tag) {
                            if (is_numeric($tag)) {
                                $tags[$tag][] = &$news[$nk];
                            }
                        }
                    }
                }
                foreach ($req['tags'] as $tag) {
                    if (!in_array($tag, $cachedtags) && !empty($tags[$tag])) {
                        SetCache('_prokazan-tag_' . $tag, json_encode($tags[$tag]), '');
                    }
                }
            }
        };
        break;
    default:
        mysql_close();
        die('[]');
}

$news = [];
if (!empty($query)) {
    $queries = [];
    foreach ($tables as $table) {
        $link = explode("_", $table)[0];
        $queries[] = str_replace(['[link]', '[table]'], [$link, $table], $query);
    }
    $sqlquery = '(' . implode(") UNION ALL \n(", $queries) . ')';

    $newsdb = DB(trim($sqlquery) . " $order $limit");

    while ($newsdbitem = mysql_fetch_assoc($newsdb["result"])) {
        $news[] = $newsdbitem;
    }

    $response = json_encode($news);
} elseif (!empty($cached)) {
    $response = $cached;
} else {
    $response = '[]';
}

mysql_close();

if (is_callable($afterAction)) {
    $afterAction($news, $response);
}

if (!empty($cacheFile) && !empty($query)) {
    SetCache($cacheFile, $response, '', 'cacheblock');
}

echo $response;
die;
