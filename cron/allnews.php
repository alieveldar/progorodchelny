<?php
### Запрашиваемый файл должен определять переменную $rsstext

$query = "";
foreach ($tables as $table) {
    $link = explode("_", $table)[0];
    $query .= "
        (SELECT `$table`.`id`, `$table`.`name`, `$table`.`lid`, `$table`.`data`, `$table`.`pic`, `$table`.`comcount`, `$table`.`pic`, `_pages`.`link` 
            FROM `$table` 
            LEFT JOIN `_pages` ON `_pages`.`link`='$link' 
            WHERE (`$table`.`stat`='1' && `$table`.`promo`!='1' && `$table`.`spromo`!='1') GROUP BY 1) UNION ALL ";
}

$newsdb = DB(trim($query, "UNIOAL ") . " ORDER BY `data` DESC LIMIT 25");

$news = [];
while ($newsdbitem = mysql_fetch_assoc($newsdb["result"])) {
    $news[] = $newsdbitem;
}

$rsstext = json_encode($news);
