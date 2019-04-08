<?php

function getPageName($alias, $id = null)
{
    global $AdminText, $GLOBAL;

    $data = DB("SELECT `id`,`shortname`,`link`, `sets`, name, orderby, onpage FROM `_pages` WHERE (`link`='" . $alias . "') LIMIT 1");
    if ($data["total"] != 1) {
        $AdminText = ATextReplace('Item-Module-Error', (int)$id, "_pages");
        $GLOBAL["error"] = 1;
    } else {
        @mysql_data_seek($data["result"], 0);
        return @mysql_fetch_array($data["result"]);
    }
}

function getRealEstateCategoryById($id)
{
    global $alias;

    $id = (int)$id;

    $data = DB("SELECT * FROM `{$alias}_cats` WHERE (`id`=$id) LIMIT 1");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return $res;
}

function getRealEstateList($order, $limitOffset, $limitSize)
{
    global $table_list, $table_cats;
    $res = array();
    $data = DB("
    SELECT
        `{$table_list}`.*, `{$table_cats}`.`name` AS catn
    FROM `{$table_list}`
        LEFT JOIN `{$table_cats}`
            ON `{$table_cats}`.`id`=`{$table_list}`.`cat`
    {$order}
    LIMIT {$limitOffset}, {$limitSize}");

    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getRealEstateListFull()
{
    global  $alias;
    $res = array();
    $data = DB("
    SELECT
        `{$alias}_lenta`.*, `{$alias}_cats`.`name` AS 'catn',
        _widget_eventmap.`icon` AS 'markerPic'
    FROM `{$alias}_lenta`
        LEFT JOIN `{$alias}_cats`
            ON `{$alias}_cats`.`id`=`{$alias}_lenta`.`cat`
        LEFT JOIN `_widget_eventmap`  ON
            `{$alias}_lenta`.`id` = `_widget_eventmap`.`pid` AND `_widget_eventmap`.link='{$alias}'
    ORDER BY `{$alias}_lenta`.`name`");
    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getRealEstateListAll($order, $limitOffset, $limitSize)
{
    global $table_list, $table_cats;
    $res = array();
    $data = DB("
    SELECT
        `{$table_list}`.*, `{$table_cats}`.`name` AS catn
    FROM `{$table_list}`
        LEFT JOIN `{$table_cats}`
            ON `{$table_cats}`.`id`=`{$table_list}`.`cat`
    {$order}
    LIMIT {$limitOffset}, {$limitSize}");

    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getRealEstateCategoryList()
{
    global $alias;

    $data = DB("SELECT * FROM `{$alias}_cats` ORDER BY `rate` DESC LIMIT 500");
    $res = array();
    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[$ar["id"]] = $ar["name"];
    }
    return $res;
}

function getRealEstateCategoryListSorted()
{
    global $alias;

    $data = DB("SELECT * FROM `{$alias}_cats`  ORDER BY `rate` DESC");
    $res = array();
    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[$ar["id"]] = $ar;
    }
    return $res;
}

function getTagsList()
{

    $data = DB("SELECT `id`, `name` FROM `_tags` ORDER BY `name` ASC");
    $res = array();
    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[$ar["id"]] = $ar["name"];
    }
    return $res;
}

function serializeTags(array $tags = null)
{
    if (isset($tags)) {
        return "," . implode(",", array_keys($tags)) . ",";
    } else {
        return '';
    }
}

/**
 * @param $dateString Строка времени в формате 'Y.m.d'
 * @param $hour       Час
 * @param $minute     Минута
 * @param $second     Секунда
 *
 * @return int Возвращает timestamp
 */
function makeTimestamp($dateString, $hour, $minute, $second)
{
    $ar = explode(".", $dateString);
    return mktime((int)$hour, (int)$minute, (int)$second, $ar[1], $ar[0], $ar[2]);
}

function makeDate($dateString)
{
    $ar = explode(".", $dateString);
    return "{$ar[2]}-{$ar[1]}-{$ar[0]}";
}

function prepareText($text)
{
    return str_replace("'", '&#039;', $text);
}

function prepareFlag($flag)
{
    return $flag ? 'true' : 'false';
}

function navigate($url)
{
    @header("location: $url");
    exit();
}

function createRealEstate(array $item)
{
    global $alias;

    $item = array(
        'tags' => (string)$item['tags'],
        'createTimestamp' => (int)$item['createTimestamp'],
        'publishTimestamp' => (int)$item['publishTimestamp'],
        'authorId' => (int)$item['authorId'],
        'categoryId' => (int)$item['categoryId'],
        'name' => (string)$item['name'],
        'keywords' => (string)$item['keywords'],
        'description' => (string)$item['description'],
        'censor' => (string)$item['censor'],
        'materialSource' => (string)$item['materialSource'],
        'commentsValue' => (string)$item['commentsValue'],
        'autoTimerFlag' => prepareFlag($item['autoTimerFlag']),
        'materialViewFlags' => array(
            'commercialNewsFlag' => prepareFlag($item['materialViewFlags']['commercialNewsFlag']),
            'onTvFlag' => prepareFlag($item['materialViewFlags']['onTvFlag']),
            'specialViewFlag' => prepareFlag($item['materialViewFlags']['specialViewFlag']),
            'yandexRssFlag' => prepareFlag($item['materialViewFlags']['yandexRssFlag']),
            'mailRssFlag' => prepareFlag($item['materialViewFlags']['mailRssFlag']),
            'tavtoTeaserFlag' => prepareFlag($item['materialViewFlags']['tavtoTeaserFlag']),
            'contributionColumnFlag' => prepareFlag($item['materialViewFlags']['contributionColumnFlag']),
            'mailTeaserFlag' => prepareFlag($item['materialViewFlags']['mailTeaserFlag']),
            'gisFlag' => prepareFlag($item['materialViewFlags']['gisFlag']),
        )
    );

    $query = "INSERT INTO `{$alias}_lenta` (
        `uid`, `cat`, `name`,
        `kw`, `ds`, `cens`,
        `realinfo`, `comments`, `data`,
        `astat`, `adata`, `tags`,
        `promo`, `onind`, `spec`,
        `yarss`, `mailrss`, `tavto`,
        `redak`,`gis`,`mailtizer`
    )
    VALUES ({$item['authorId']}, {$item['categoryId']}, '{$item['name']}',
            '{$item['keywords']}', '{$item['description']}', '{$item['censor']}',
            '{$item['materialSource']}', {$item['commentsValue']}, {$item['createTimestamp']},
		    {$item['autoTimerFlag']}, {$item['publishTimestamp']}, '{$item['tags']}',
		    {$item['materialViewFlags']['commercialNewsFlag']}, {$item['materialViewFlags']['onTvFlag']}, {$item['materialViewFlags']['specialViewFlag']},
		    {$item['materialViewFlags']['yandexRssFlag']}, {$item['materialViewFlags']['mailRssFlag']}, {$item['materialViewFlags']['tavtoTeaserFlag']},
		    {$item['materialViewFlags']['contributionColumnFlag']}, {$item['materialViewFlags']['mailTeaserFlag']}, {$item['materialViewFlags']['gisFlag']}
    )";

    DB($query);

    $id = DBL();

    DB("UPDATE `{$alias}_lenta` SET `rate`=$id WHERE  (id=$id)");

    return $id;
}

function editRealEstateFields(array $item)
{
    global $alias;

    $item['id'] = (int)$item['id'];
    $item['tags'] = (string)$item['tags'];
    $item['createTimestamp'] = (int)$item['createTimestamp'];
    $item['publishTimestamp'] = (int)$item['publishTimestamp'];
    $item['authorId'] = (int)$item['authorId'];
    $item['categoryId'] = (int)$item['categoryId'];
    $item['name'] = (string)$item['name'];
    $item['keywords'] = (string)$item['keywords'];
    $item['description'] = (string)$item['description'];
    $item['censor'] = (string)$item['censor'];
    $item['materialSource'] = (string)$item['materialSource'];
    $item['commentsValue'] = (string)$item['commentsValue'];
    $item['autoTimerFlag'] = prepareFlag($item['autoTimerFlag']);
    $item['materialViewFlags'] = array(
        'commercialNewsFlag' => prepareFlag($item['materialViewFlags']['commercialNewsFlag']),
        'onTvFlag' => prepareFlag($item['materialViewFlags']['onTvFlag']),
        'specialViewFlag' => prepareFlag($item['materialViewFlags']['specialViewFlag']),
        'yandexRssFlag' => prepareFlag($item['materialViewFlags']['yandexRssFlag']),
        'mailRssFlag' => prepareFlag($item['materialViewFlags']['mailRssFlag']),
        'tavtoTeaserFlag' => prepareFlag($item['materialViewFlags']['tavtoTeaserFlag']),
        'contributionColumnFlag' => prepareFlag($item['materialViewFlags']['contributionColumnFlag']),
        'mailTeaserFlag' => prepareFlag($item['materialViewFlags']['mailTeaserFlag']),
        'gisFlag' => prepareFlag($item['materialViewFlags']['gisFlag']),
    );


    $query = "UPDATE `{$alias}_lenta` SET
		`uid`={$item['authorId']},
		`name`='{$item['name']}',
		`kw`='{$item['keywords']}',
		`ds`='{$item['description']}',
		`cens`='{$item['censor']}',
		`realinfo`='{$item['materialSource']}',
		`comments`={$item['commentsValue']},
		`data`={$item['createTimestamp']},
		`astat`={$item['autoTimerFlag']},
		`adata`={$item['publishTimestamp']},
		`promo`={$item['materialViewFlags']['commercialNewsFlag']},
		`onind`={$item['materialViewFlags']['onTvFlag']},
		`spec`={$item['materialViewFlags']['specialViewFlag']},
		`yarss`={$item['materialViewFlags']['yandexRssFlag']},
		`mailrss`={$item['materialViewFlags']['mailRssFlag']},
		`tavto`={$item['materialViewFlags']['tavtoTeaserFlag']},
		`tags`='{$item['tags']}',
		`redak`={$item['materialViewFlags']['contributionColumnFlag']},
		`gis`={$item['materialViewFlags']['gisFlag']},
		`mailtizer`={$item['materialViewFlags']['mailTeaserFlag']}
		WHERE (id={$item['id']})";

    DB($query);
}

function checkIfCategoryRecordExists($tableName, $id)
{
    $checkResult = DB("SELECT TRUE FROM `$tableName` WHERE (`id`=$id) LIMIT 1");
    $res = mysql_fetch_row($checkResult["result"]);
    return $res !== false && $res[0] == '1';
}

function getUsersList()
{
    global $alias, $AdminText, $GLOBAL;

    $data = DB("SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC");
    $res = array();
    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[$ar["id"]] = $ar["nick"];
    }
    return $res;

}

function getRealEstateById($id)
{
    global $alias, $AdminText, $GLOBAL;

    $id = (int)$id;

    $data = DB(
        "SELECT
          re.*, re.id AS 'itemId', we.`icon` AS 'markerPic'
        FROM `{$alias}_lenta` re
        LEFT JOIN `_users` ON re.`uid`=`_users`.`id`
        LEFT JOIN `_widget_eventmap`  we ON
          re.`id` = we.`pid` AND link='{$alias}'
        WHERE (re.id=$id) LIMIT 1");
    if ($data["total"] != 1) {
        return null;
    } else {
        @mysql_data_seek($data["result"], 0);
        return @mysql_fetch_array($data["result"]);
    }

}



function getRealEstateListByCategoryId($categoryId, $limitOffset, $limitSize, $showOnlyEnabled = true)
{
    global $alias;

    $andWhere = getStatWhereExpression($showOnlyEnabled, "`{$alias}_lenta`.`stat`");

    $categoryId = (int)$categoryId;
    $res = array();
    $data = DB(
        "SELECT
            `{$alias}_lenta`.*,
            `{$alias}_lenta`.`id` AS 'itemId',
            `{$alias}_cats`.`id` AS 'categoryId',
            `{$alias}_cats`.`name` AS 'categoryName',
            `_users`.*
         FROM `{$alias}_lenta`
            LEFT JOIN `_users`
                ON `{$alias}_lenta`.`uid`=`_users`.`id`
            LEFT JOIN `{$alias}_cats` ON `{$alias}_cats`.`id`=`{$alias}_lenta`.`cat`
         WHERE (`cat`=$categoryId $andWhere)
         LIMIT {$limitOffset}, {$limitSize}");

    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getCountRealEstateByCategoryId($categoryId, $showOnlyEnabled = true)
{
    global $alias;

    $categoryId = (int)$categoryId;

    $andWhere = getStatWhereExpression($showOnlyEnabled, "`{$alias}_lenta`.`stat`");

    $data = DB(
        "SELECT
            COUNT(*) AS 'countItems'
         FROM
          `{$alias}_lenta`
         WHERE (`cat`=$categoryId $andWhere)
         ");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return (int)$res['countItems'];
}

function getStatWhereExpression($showOnlyEnabled, $fieldName) {
    $showOnlyEnabled = (bool)$showOnlyEnabled;
    $andWhere = '';
    if ($showOnlyEnabled) {
        $andWhere = "And $fieldName = 1";
    }
    return $andWhere;
}

function geRealEstateCategoryById($id)
{
    global $alias;

    $id = (int)$id;

    $data = DB("SELECT cat FROM `{$alias}_lenta` WHERE (`id`=$id) LIMIT 1");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return (int)$res['cat'];
}

function getCategoryByRealEstateId($id)
{
    global $alias, $AdminText, $GLOBAL;

    $id = (int)$id;

    $data = DB("SELECT `cat` FROM `{$alias}_lenta` WHERE (`id`=$id) LIMIT 1");
    if ($data["total"] != 1) {
        $AdminText = ATextReplace('Item-Module-Error', (int)$id, "_pages");
        $GLOBAL["error"] = 1;
    } else {
        @mysql_data_seek($data["result"], 0);
        $result = @mysql_fetch_array($data["result"]);
        return $result['cat'];
    }

}


function getPhotoReportList($parentId, $moduleName)
{
    global $alias, $AdminText, $GLOBAL;

    $parentId = (int)$parentId;
    $res = array();
    $data = DB(
        "SELECT * FROM `_widget_pics` WHERE (`pid`={$parentId} AND `link`='$moduleName' AND `point`='report' AND `stat`=1) ORDER BY `rate` ASC");

    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getPhotoAlbumList($parentId, $moduleName)
{
    $parentId = (int)$parentId;
    $res = array();
    $data = DB(
        "SELECT * FROM `_widget_pics` WHERE (`pid`={$parentId} AND `link`='$moduleName' AND `point`='album' AND `stat`=1) ORDER BY `rate` ASC");

    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getVideoItem($parentId, $moduleName)
{
    $parentId = (int)$parentId;
    $moduleName = (string)$moduleName;

    $data = DB("SELECT * FROM `_widget_video` WHERE `pid`=$parentId AND `link`='$moduleName' LIMIT 1");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return $res;

}

function getTagListByIdsString($tagIdsString)
{
    $tagIdsString = (string)$tagIdsString;
    $data = DB("SELECT * FROM `_tags` WHERE (`id` IN (" . $tagIdsString . ")) LIMIT 3");

    while ($ar = @mysql_fetch_array($data["result"])) {
        $res[] = $ar;
    }
    return $res;
}

function getEventMap($id, $moduleName)
{
    $id = (int)$id;

    $data = DB(
        "SELECT
            `_widget_eventmap`.*, `_pages`.`sets`
        FROM
            `_widget_eventmap`
            LEFT JOIN
                `_pages`
                ON `_pages`.`module`='eventmap'
        WHERE `_widget_eventmap`.`pid`=$id AND `_widget_eventmap`.`link`='$moduleName' AND `_widget_eventmap`.`stat`=1");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return $res;
}

function getContacts($id, $moduleName)
{
    $id = (int)$id;

    $data = DB("SELECT * FROM `_widget_contacts` WHERE `pid`=$id AND `link`='$moduleName'");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return $res;
}

function getRealEstateContactsById($id)
{
    global $alias;
    $id = (int)$id;

    $data = DB("SELECT * FROM `{$alias}_contacts` WHERE `id`=$id");

    @mysql_data_seek($data["result"], 0);
    $res = @mysql_fetch_array($data["result"]);

    return $res;
}

function editRealEstateContacts(array $item)
{
    global $alias;

    $id = $item['id'];

    $previousItem = getRealEstateContactsById($id);

    if (isset($item['site'])) {
        $item['site'] = trim($item['site']);
        if (strpos(trim($item['site']), 'http://' ) === false) {
            $item['site'] = 'http://' . $item['site'];
        }
    }


    $catQuery = $previousItem !== false ?
        "UPDATE
                    {$alias}_contacts
                 SET
                 	forum_theme = '{$item['forum_theme']}',
                    address = '{$item['address']}',
                    phone = '{$item['phone']}',
                    site_url = '{$item['site']}',
                    email = '{$item['email']}',
                    soc_net_link1 = '{$item['socNet1']}',
                    soc_net_link2 = '{$item['socNet2']}',
                    soc_net_link3 = '{$item['socNet3']}',
                    price_list = '{$item['price']}'
                 WHERE
                    id = $id" :
        "INSERT INTO
                    {$alias}_contacts
                VALUES(
                $id, '{$item['forum_theme']}', '{$item['site']}', '{$item['phone']}', '{$item['email']}',
                '{$item['address']}',
                 '{$item['socNet1']}', '{$item['socNet2']}', '{$item['socNet3']}', '{$item['price']}')";
    DB($catQuery);
}

function getRelevantNews($art, $limit, $tags2)
{
    global $alias, $dir, $table;
    $tab = $alias . '_lenta';
    $dTags = '<div class="Dtags">';
    $r = rand(0, 4);
    /* новость из телека */
    $tables = array("auto_lenta", "business_lenta", "news_lenta", "sport_lenta", "concurs_lenta", "demotivators_lenta");
    $q1 = '';
    foreach ($tables as $table) {
        $tmp = explode("_", $table);
        $link = $tmp[0];
        $q1 .= "(SELECT `$table`.`id`, `$table`.`name`, `$table`.`data`, `$table`.`pic`, `_pages`.`domain`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`onind`='1') GROUP BY 1) UNION ";
    }
    $data = DB(trim($q1, "UNION ") . " ORDER BY `data` DESC LIMIT 6");
    for ($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek($data["result"], $i);
        $ar = @mysql_fetch_array($data["result"]);
        $tv[] = $ar;
    }
    $new = $tv[$r];
    if ($new["name"] != "" && $new["name"]) {
        $d = ToRusData($new["data"]);
        $dTags .= "<a href='/$new[link]/view/$new[id]/' title='" . $new["name"] . "'><img src='/userfiles/picintv/" . $new["pic"] . "' style='width:200px; height:110px; border:none; border-radius:5px; margin-bottom:7px;' title='" . $new["name"] . "' alt='" . $new["name"] . "' /></a><a href='/$new[link]/view/$new[id]/' title='" . $new["name"] . "'>" . $new["name"] . "</a><br><b>" . $d[4] . "</b><div class='C'></div><div class='CB'></div>";
    }
    /* новости по тэгам */
    $q = "";
    foreach ($art as $k => $v) {
        if ($v != '') {
            $q .= "`tags` LIKE '%," . $v . ",%' OR ";
        }
    }
    $qr = "SELECT `pic`,`data`,`name`,`id` FROM `" . $tab . "` WHERE ((" . trim($q, "OR ") . ") AND (`id`!='" . (int)$dir[2] . "') AND (`stat`='1')) ORDER BY `data` DESC LIMIT " . $limit;
    $data2 = DB($qr);
    if ($data2["total"] > 0) {
        for ($i = 0; $i < $data2["total"]; $i++): @mysql_data_seek($data2["result"], $i);
            $ar = @mysql_fetch_array($data2["result"]);
            $d = ToRusData($ar["data"]);
            $dTags .= "<a href='/$dir[0]/view/$ar[id]/' title='" . $ar["name"] . "'>" . $ar["name"] . "</a><br><b>" . $d[4] . "</b><div class='C'></div><div class='CB'></div>"; endfor;
        $dTags .= "<div class='C10'></div>Темы: " . $tags2;
    }
    $dTags .= '</div>';
    return $dTags;
}

function getFormattedWikiDate($dateTimeString, $arrayFormatNumber = 3) {
    list($year, $month, $day) = explode("-", $dateTimeString);
    $resFormattedDateTimeArray = ToRusDataAlt("{$year}.{$month}.{$day}");
    return $resFormattedDateTimeArray[$arrayFormatNumber];
}