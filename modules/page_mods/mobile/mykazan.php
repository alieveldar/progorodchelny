<?

$news=DB("SELECT `id`,`picpreview`,`picoriginal`,`username` FROM `_widget_insta` WHERE (`stat`=1) ORDER BY `data` DESC LIMIT 6");
if ($news["total"]>1) {
	for ($i=0; $i<$news["total"]; $i++): @mysql_data_seek($news["result"], $i); $ar=@mysql_fetch_array($news["result"]);
		$text.="<a href='".$ar["picoriginal"]."' title='Автор: ".$ar["username"]."' rel='prettyPhoto[gallery]'><img src='".$ar["picpreview"]."' alt='Автор: ".$ar["username"]."' title='Автор: ".$ar["username"]."' /></a>";
	endfor;
}

$Page["Content"]="<div id='works'>".$text."</div><div class='C10'></div><script>var lastid='".$ar["id"]."';</script><div id='More'><a href='javascript:void(0)' onclick='ShowMore()'>Показать больше фотографий</a></div>";
?>