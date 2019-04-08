<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/realEstate/realEstate.functions.php";


$Page["BottomContent"] 	= "";
$Page["RightContent"] 	= "";
$Page["LeftContent"] 	= "";
$Page["Content"] 		= "";
$Page["Caption"] 		= "";


$forums = array(
    news => array("tms" => "8,42,45,41,39,37,43,38", "bg" => "#1A74BE"),
    auto => array("tms" => "30,31,32,33,34,35,36", "bg" => "#814D34"),
    sport => array("tms" => "25,26,24,23,22,21", "bg" => "#349934"),
    oney => array("tms" => "23,24,15,19,18,20,17,44,16", "bg" => "#EC7D46"),
    business => array("tms" => "9,10,11,12,13,14,8,42,45", "bg" => "#777"),
);

$alias = $link;

if ($start == "") {
    $start = "list";
    $dir[1] = "list";

}

switch ($start) {
    case 'list' :
        $Page["Title"] = $node["name"];

        $file = "{$alias}_lenta-$start.$page.$id";

        /*if (RetCache($file) == "true") {
            list($text, $cap) = GetCache($file, 0);
        } else {
            $realEstateItemList = getRealEstateListFull();

            $text = displayRealEstateList($realEstateItemList, $dir[0]);
            SetCache($file, $text, "");
        }

        var_dump($text);
        die('');*/

        $realEstateItemList = getRealEstateListFull();

        $text = displayRealEstateList($realEstateItemList, $dir[0]);

        $Page["TopContent"].=$text;
        break;
    case 'view' :
        $itemId = (int)$dir[2];
        $realEstateItem = getRealEstateById($itemId);
		
        if ($realEstateItem == null) {
            $cap = "Материал не найден";
            $text = @file_get_contents($ROOT . "/template/404.html");
            $Page["Content"] = $text;
            $Page["Caption"] = $cap;
            $Page["Title"] = $cap;
        } else {

            $Page["Caption"] = '';
            $Page["Description"] = $realEstateItem["ds"];
            $Page["KeyWords"] = $realEstateItem["kw"];
            $Page["Title"] = $realEstateItem["name"];
			$Page["BottomContent"] = displayRealEstateBottomContentItem();

            if ($GLOBAL["USER"]["role"] > 1) {  $text = $C10 . "<div id='AdminEditItem'><a href='" . $GLOBAL["mdomain"] . "/admin/?cat=" . $link . "_edit&id=" . (int)$dir[2] . "'>Редактировать</a></div>" . $C15 . InitImageMaster($cap, $new["pic"]) . $C15 . $text;  }

           // if ($realEstateItem['spec'] == 1) {
                $text .= displayRealEstateMainContentItem($realEstateItem, $dir[0], $forums);
                $Page["TopContent"].=displayRealEstateTopContentItem($realEstateItem, $dir[0], $forums);
                $contacts = getRealEstateContactsById($itemId);
                $Page["RightContent"] = displayRealEstateRightContentItem($realEstateItem, $dir[0], $contacts) . $Page["RightContent"];
                $Page["LeftContent"] = displayRealEstateLeftContentItem($realEstateItem, $dir[0]) . $Page["LeftContent"];
                $file = "{$alias}_lenta-$start.$page.$id";
				if ((int)$contacts['forum_theme']!=0) { $CSSmodules["Форумная часть CSS"]="/modules/forum/forum.css"; $text.=ForumPartInsert($contacts['forum_theme'], $Page["Title"]); }
				
                /* if (RetCache($file) == "true") {
                    list($text, $cap) = GetCache($file);
                } else {
                    SetCache($file, array($Page["TopContent"], $Page["LeftContent"], $Page["RightContent"], $text), $cap);
                }*/

            //} else {
              //  $Page["TopContent"] = displayRealEstateTopContentItem($realEstateItem, $dir[0], $forums);
               // $text = "<strong>Раздел не заполнен</strong>";
               // $Page["TopContent"] = displayRealEstateTopContentItemSingle($realEstateItem);
           // }

        }
		$Page["Content"] = $text;
        break;
}



function getRealEstateMap()
{
    global $VARS, $link, $node, $C10, $C20;
    $text = "<h1>" . $node["name"] . "</h1>";
    $q = "SELECT `_widget_eventmap`.`id` as `id`, `_widget_eventmap`.`pid`,`_widget_eventmap`.`maps`,`_widget_eventmap`.`icon`, `" . $link . "_lenta`.`spec` as `spec`, `" . $link . "_lenta`.`name` as `name`
	FROM `_widget_eventmap` LEFT JOIN `" . $link . "_lenta` ON `" . $link . "_lenta`.`id`=`_widget_eventmap`.`pid` WHERE (`_widget_eventmap`.`link`='" . $link . "' AND `" . $link . "_lenta`.`stat`=1) GROUP BY `id` ORDER BY `name` ASC";
    $data = DB($q);

    $events = '[';
    $dates = '';
    for ($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek($data["result"], $i);
        $ar2 = @mysql_fetch_array($data["result"]);
        if ($events != '[') $events .= ', ';
        $events .= '[' . $ar2['pid'] . ', "' . htmlspecialchars($ar2['name']) . '", "' . $ar2['maps'] . '"';
        $events .= ',"' . $link . '"';
        $events .= ',"' . (int)$ar2["spec"] . '"';
        if ($ar2['icon']) {
            $events .= ', "/userfiles/mapicon/' . $ar2['icon'] . '"';
            list($w, $h) = getimagesize("/userfiles/mapicon/" . $ar2['icon']);
            $events .= ',' . ($w / $h);
        }
        $events .= ']';

        if ($ar2['data']) $dates .= $dates != '' ? ',' . date('d-n-Y', $ar2['data']) : date('d-n-Y', $ar2['data']);
    }
    $events .= ']';

    return $text;
}


function displayRealEstateBottomContentItem() {
	global $GLOBAL, $VARS, $ROOT, $RealHost, $C, $C5, $C15, $C20, $C10, $link,$pg; $text=$C20;
	$data=DB("SELECT `id`,`pic`,`name` FROM `".$link."_lenta` WHERE (`id`!='".$pg."') ORDER BY RAND() LIMIT 3");
	for ($i = 0; $i < $data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);
	
		$text.="<div ></div>";
	
	} $text.=$C10; return $text;
}

function displayRealEstateMainContentItem($realEstateItem, $baseUrl, array $forums = null)
{
    global $GLOBAL, $VARS, $ROOT, $RealHost, $C, $C5, $C15, $C20, $C10, $link;

    $id = (int)$realEstateItem['itemId'];

    //Претекст текст
    if ($realEstateItem["lid"] != "") {
        $lid = "<div class='ItemLid'>" . $realEstateItem["lid"] . "</div>" . $C15;
    }

    // Основной текст
    $mainText = CutEmptyTags($realEstateItem["text"]);

    // Лайки
    $likes = "<div class='Likes'>" . Likes(Hsc($cap), "", "http://" . $RealHost . $path, Hsc(strip_tags($lid))) . $C . "</div>" . $C10;

    //Видео
    $videoItem = getVideoItem($id, $baseUrl);
    $video = '';
    if (isset($videoItem)) {
        if ($videoItem["text"] != "") {
            if ($videoItem["name"] != "") {
                $video .= "<h2>" . $videoItem["name"] . "</h2>";
            }
            $vid = GetNormalVideo($videoItem["text"]);
            $video .= $vid . $C10;
        }
    }

    //Заключительный текст
    $endText = '';
    if ($realEstateItem["endtext"] != "") {
        $endText = $C5 . "<div class=\"hiteBlock EndText\">{$realEstateItem['endtext']}</div>" . $C;
    }

    return "<div class=\"ArticleContent\">
                $lid
                <div class=\"ItemText\">$mainText</div>
                ".$C20."
				$likes
				".$C20."
                $video
            </div>";
}

function displayRealEstateLeftContentItem($realEstateItem, $baseUrl)
{
	 
    global $GLOBAL, $VARS, $ROOT, $RealHost, $C, $C20, $C10;
    $id = (int)$realEstateItem['itemId'];
    
    //Фото-альбом
    if ((int)$realEstateItem["spec"]==0){
    $photoAlbumList = getPhotoAlbumList($id, $baseUrl);
    $album = '';
    if (!empty($photoAlbumList)) {
        $album = "<h3>Фотоальбом:</h3>$C10<div class='ItemAlbum'>";
        foreach ($photoAlbumList as $key => $photoAlbumItem) {
            $class = '';
            if (0 === $key) {
                $picpath = 'picpreview';
                $class = "class=\"albumFirst\"";
            } else {
                $picpath = 'picsquare';
            }
            $album .= "
            <a href=\"/userfiles/picoriginal/{$photoAlbumItem['pic']}\" title=\"{$photoAlbumItem["name"]}\" rel=\"prettyPhoto[gallery]\" $class>
                <img src=\"/userfiles/$picpath/{$photoAlbumItem['pic']}\" title=\"{$photoAlbumItem['name']}\" alt=\"{$photoAlbumItem['name']}\"  />
            </a>";
        }
        $album .= "</div>" . $C10;
    }}
	

    $tag = getFirstTag($realEstateItem);
    $news = '';
    if ($tag !== null) {
       $news = getNewsInRealEstate($tag);
    }


	$ret="<div style='width:240px; height:2px;'></div>".$album.$news;
    return $ret;
}


function displayRealEstateRightContentItem($realEstateItem, $baseUrl, $contacts = null)
{
    global $GLOBAL, $VARS, $ROOT, $RealHost, $C20, $C10;
    $id = (int)$realEstateItem['itemId'];
    // Карта событий
    $eventMap = getEventMap($id, $baseUrl);
    $event = '';
    if (isset($eventMap)) {
        if ($eventMap['maps']) {
            $event = '<script type="text/javascript" src="	http://maps.api.2gis.ru/1.0"></script><div id="Map" style="width:240px; height:280px;"></div>' . $C10;
            $event .= '<script type="text/javascript">initMap([' . $eventMap['id'] . ', "' . htmlspecialchars($eventMap['name']) . '", "' . $eventMap['maps'] . '", "' . ($eventMap['icon'] ? '/userfiles/mapicon/' . $eventMap['icon'] : '') . '"]);</script>';
        }
    }

    $priceText = ''; 
    $contactsText = '';
    if ($contacts !== null) {
        if (isset($contacts['address']) && $contacts['address']!="") {
            $contactsText .= "<b>Адрес:</b> <span>{$contacts['address']}</span><br />";
        }
        if (isset($contacts['phone']) && $contacts['phone']!="") {
            $contactsText .= "<b>Телефон:</b> <span>{$contacts['phone']}</span><br />";
        }
        if (isset($contacts['site_url']) && $contacts['site_url']!="" && $contacts['site_url']!="http://") {
            $contactsText .= "<b>Сайт:</b> <a href=\"{$contacts['site_url']}\">{$contacts['site_url']}</a><br />";
        }
        if (isset($contacts['email']) && $contacts['email']!="") {
            $contactsText .= "<b>Почта:</b> <a href=\"mailto:{$contacts['email']}\">{$contacts['email']}</a><br />";
        }
        if (isset($contacts['price_list']) && $contacts['price_list']!="") {
            $priceText .= "<a href=\"/userfiles/realEstate/{$contacts['price_list']}\">Скачать прайс-лист</a>";
        }

    }
	
	
	//Фото-альбом
    if ((int)$realEstateItem["spec"]==1){
    $photoAlbumList = getPhotoAlbumList($id, $baseUrl);
    $album = $C20;
    if (!empty($photoAlbumList)) {
        $album = "<h3>Фотоальбом:</h3>$C10<div class='ItemAlbum'>";
        foreach ($photoAlbumList as $key => $photoAlbumItem) {
            $class = '';
            if (0 === $key) {
                $picpath = 'picpreview';
                $class = "class=\"albumFirst\"";
            } else {
                $picpath = 'picsquare';
            }
            $album .= "
            <a href=\"/userfiles/picoriginal/{$photoAlbumItem['pic']}\" title=\"{$photoAlbumItem["name"]}\" rel=\"prettyPhoto[gallery]\" $class>
                <img src=\"/userfiles/$picpath/{$photoAlbumItem['pic']}\" title=\"{$photoAlbumItem['name']}\" alt=\"{$photoAlbumItem['name']}\"  />
            </a>";
        }
        $album .= "</div>" . $C10;
    }}

    return $event . $C10 . $contactsText . $C20 . $priceText . $album;
}

function displayRealEstateTopContentItem($realEstateItem, $baseUrl, $contacts)
{
    global $GLOBAL, $VARS, $ROOT, $RealHost, $C20;
    $cap = $realEstateItem["name"];

    $pic = '';
    $path = '';
    // Фотография
    if ($realEstateItem["pic"] != "") {
        $path = '/userfiles/picoriginal/' . $realEstateItem["pic"];
        $pic .= "<img src='" . $path . "' title='$cap' alt='$cap' />";
    }

    $markerPic = '';
    if ($realEstateItem["markerPic"]) {
        $markerPic = "<img src=\"/userfiles/mapicon//{$realEstateItem['markerPic']}\" alt=\"text\"/>";
    }

    return "<div class=\"realEstateTop\">
                $pic
                <div>
                    $markerPic
                    <h2 class=\"realCap\">$cap</h2>
                </div>
            </div>";
}

function displayRealEstateTopContentItemSingle($realEstateItem)
{
    $cap = $realEstateItem["name"];

    return "<div class=\"realEstateTop\">
                <div>
                    <h2 class=\"realCap\">$cap</h2>
                </div>
            </div>";
}

function displayRealEstateList(array $realEstateItems, $baseUrl)
{
    $map = getRealEstateMap();
    $listText = '';
    foreach ($realEstateItems as $item) {
        if ($item['spec'] == 1) {
            $listText .=
                "<div class=\"realEstateList\">
                    <a href=\"/$baseUrl/view/{$item['id']}/\" class=\"realEstateMarker\">
                        <img src=\"/userfiles/mapicon/{$item['markerPic']}\" alt=\"{$item['name']}\">
                    </a>
                    <div>
                        <a href=\"/$baseUrl/view/{$item['id']}\" class=\"realEstateName\">{$item['name']}</a>
                        <p>{$item['lid']}</p>
                    </div>
                </div>";
        } else {
            $listText .=
                "<div class=\"realEstateList\">
                    <a href=\"/$baseUrl/view/{$item['id']}\" class=\"realEstateSingleName\">{$item['name']}</a>
                </div>";
        }

    }
    return $map . $listText;
}

function getFirstTag(array $realEstateItem)
{
    $tags = explode(',', $realEstateItem['tags']);
    return isset($tags[1]) ? (int)$tags[1] : null;
}

function getNewsInRealEstate($tag, $limit = 10, $from = 0) {
    global $C10;
    $text = '';

    $q = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`tags` LIKE '%," . $tag . ",%')";
    $endq = "ORDER BY `data` DESC LIMIT " . $from . "," . $limit;
    $tv = getNewsFromLentas($q, $endq);
    $totalItems =  (int)$tv["total"];

    if ($totalItems > 0) {
        $text .= "<h3>Новости</h3>".$C10;
        for ($i = 0; $i < $totalItems; $i++) {
            @mysql_data_seek($tv["result"], $i);
            $ar = @mysql_fetch_array($tv["result"]);

            $data = ToRusData($ar["data"]);
            $text .=
                "<div class=\"itemlistNews\">
                    <a href=\"/{$ar['link']}/view/{$ar['id']}\">{$ar['name']}</a><br />
                    <span>$data[2]</span>
                </div>$C10";
        }
    }

    return $text;
}

// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
function ForumPartInsert($fid, $cap) {
	//var_dump($fid);
	global $GLOBAL, $VARS, $ROOT, $RealHost, $C, $C5, $C15, $C20, $C10, $link; $data=DB("SELECT `link` FROM `_pages` WHERE (`module`='forum') LIMIT 1");
	if ($data["total"]=1) { $text=$C15."<h2>Обсуждение «".$cap."»</h2>"; @mysql_data_seek($data["result"], 0); $ap=@mysql_fetch_array($data["result"]);
		$lnk=$ap["link"]; $node["link"]=$lnk; $TABLES=array(0=>$lnk."_forum", 1=>$lnk."_cat", 2=>$lnk."_lenta");
		$add="<div class='AddForumTheme'><a href='/$lnk/addtheme/$fid' rel='nofollow' target='_blank'>Создать новую тему</a></div>";
		if ($add!="" || $pager!="") { $text.=$C10.$add.$C5; } $text.="<div class='WhiteBlock'><table class='ForumTable' cellpadding='0' cellspacing='0'>";
		/* --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ----- */
		$text.="<tr class='TableHeader'><td>Темы обсуждения</td><td width='1%'>Ответ</td><td width='1%'>Обновлено</td></tr>";
		$q="SELECT `".$TABLES[2]."`.`id`, `".$TABLES[2]."`.`name`, `".$TABLES[2]."`.`data`, `".$TABLES[2]."`.`update`, `".$TABLES[2]."`.`uid`, `".$TABLES[2]."`.`lock`, `".$TABLES[2]."`.`comcount`, `".$TABLES[2]."`.`clast`, `_comments`.`uid` as `updater_id`, `u1`.`nick` as `creator`, `u2`.`nick` as `updater`   
		FROM `".$TABLES[2]."` LEFT JOIN `_comments` ON `_comments`.`id`=`".$TABLES[2]."`.`clast` LEFT JOIN `_users` `u1` ON `u1`.`id`=`".$TABLES[2]."`.`uid` LEFT JOIN `_users` `u2` ON `u2`.`id`=`_comments`.`uid`
		WHERE (`".$TABLES[2]."`.`stat`=1 && `".$TABLES[2]."`.`cid`='".(int)$fid."') GROUP BY 1 ORDER BY `".$TABLES[2]."`.`lock` DESC, `".$TABLES[2]."`.`update` DESC"; $data=DB($q);
		for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); if ($ar["lock"]==1) { $ads="LockedTheme"; $adt="Тема закреплена: ".$ar["name"]; } else { $ads=""; $adt=$ar["name"]; }
		$text.="<tr><td class='ForumItem0 $ads' title='$adt'><h2 class='SmallH2'><a href='/".$node["link"]."/view/".$ar["id"]."' target='_blank'>".trim($ar["name"])."</a></h2><!--<span class='Update'>Создано: ".ForumUpdatedBy($ar["uid"], $ar["creator"], $ar["data"], 1)."</span>--></td>";
		$text.="<td class='Digit'><a href='/".$node["link"]."/view/".$ar["id"]."' target='_blank'>".(int)$ar["comcount"]."</a></td><td class='Update'>".ForumUpdatedBy($ar["updater_id"], $ar["updater"], $ar["update"])."</td></tr>"; endfor;
		/* --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ----- */		
		$text.="</table></div><div class='C10'></div>"; $text.=$C10.$add;
	} return $text;
}
function ForumUpdatedBy($uid, $nick, $update, $type=0) { $text=""; $p1=ToRusData(time()); $p2=ToRusData(time()-24*60*60); $t=$p1[5]; $y=$p2[5]; $d=ToRusData($update); $data=$d[4]; $data=str_replace($t, "<b>Сегодня</b>", $data); $data=str_replace($y, "<b>Вчера</b>", $data); 
if ((int)$uid!=0 && $nick!="") { if ($type==0) { $text="<a href='/users/view/".$uid."'>".$nick."</a><br>".$data; } else { $text=$data." / <a href='/users/view/".$uid."'>".$nick."</a>"; } } else { $text=$data; } return $text; }