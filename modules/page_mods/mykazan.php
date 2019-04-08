<?

$n=DB("SELECT `id` FROM `_widget_insta` WHERE (`stat`=1)");

$text.="<div id='RulesA' style='text-align:center; font-size:20px; margin-bottom:15px; color:#Fff;'>На сайте уже <b>".$n["total"]."</b> ваших фотографий</div>";

$news=DB("SELECT `id`,`picpreview`,`picoriginal`,`username`,`userlink` FROM `_widget_insta` WHERE (`stat`=1) ORDER BY `data` DESC LIMIT 9");
if ($news["total"]>1) {
	for ($i=0; $i<$news["total"]; $i++): @mysql_data_seek($news["result"], $i); $ar=@mysql_fetch_array($news["result"]);
		$text.="<div class='image' id='div".$ar["id"]."'><a href='".$ar["picoriginal"]."' title=\"Автор: <a href='http://instagram.com/".$ar["userlink"]."' target='_blank'>".txt($ar["username"])."</a>\" rel='prettyPhoto[gallery]'><img src='".$ar["picpreview"]."' alt='Автор: ".txt($ar["username"])."' title='Автор: ".txt($ar["username"])."' /></a>";
			if ($GLOBAL["USER"]["role"]>1) { $text.="<a href='javascript:void(0);' class='close' onclick=\"HidePic('".$ar["id"]."')\">X</a>"; }
		$text.="</div>";
	endfor;
}

$Page["Content"]="<div id='works'>".$text."</div><div class='C10'></div><script>var lastid='".$ar["id"]."';</script><div id='More'><a href='javascript:void(0)' onclick='ShowMore()'>Показать больше фотографий</a></div>";

function txt($text){ $text=str_replace(array("'",'"'), "", $text); }
?>