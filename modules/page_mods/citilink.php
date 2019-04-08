<?
$Page["Content"]=""; $text="";
$Page["Content"].="<img src='/template/citilink/cityhead.jpg' />";
$Page["Content"].="<div class='citipage'>".$node["text"]."</div>".$C;

$news=DB("SELECT * FROM `_widget_vk` WHERE (`stat`=1) ORDER BY `data` DESC LIMIT 30");
for ($i=0; $i<$news["total"]; $i++): @mysql_data_seek($news["result"], $i); $ar=@mysql_fetch_array($news["result"]);
	if (trim($ar["text"])!="" || trim($ar["pic"])!="") {
		if ($ar["pic"]!="") { $ar["text"].=$C10."<img src='".$ar["pic"]."' class='citipic' />"; }
		$text.="<div class='citivk' id='div".$ar["vkid"]."'><img src='".$ar["avatar"]."' class='citiava' />";
		$text.="<div class='cititxt'><b><a href='http://vk.com/id".$ar["userlink"]."' target='_blank'>".$ar["name"]."</a></b>";
		if ((int)$GLOBAL["USER"]["role"]>1) { $text.=" - <a href='javascript:void(0);' onclick='HidePic(\"".$ar["vkid"]."\");' style='color:red;'>УДАЛИТЬ</a>"; } 
		$text.=$C10.$ar["text"]."</div></div>".$C20;
	}
endfor;
$Page["Content"].="<div class='citilist' id='boxes'>".$text."</div>".$C;
if ($news["total"]==30) { $Page["Content"].="<div id='ShowMoreVK'><a href='javascript:void(0);' onclick='ShowMoreCiti(".$ar["data"].")'>Показать ещё записи</a></div>"; }
$Page["Content"].='<div class="citilist"><script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script><script type="text/javascript">VK.init({apiId: 5298227, onlyWidgets: true});</script><div id="vk_comments"></div><script type="text/javascript">VK.Widgets.Comments("vk_comments", {limit: 30, width: "930", attach: "*"});</script></div>';
?>