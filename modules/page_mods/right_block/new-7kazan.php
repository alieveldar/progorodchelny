<?	
$file="_rightblock-semya"; if (RetCache($file, "cacheblock")=="true") { list($Page["RightContent"], $cap)=GetCache($file, 0); } else { list($Page["RightContent"], $cap)=CreateRightBlock(); SetCache($file, $Page["RightContent"], "", "cacheblock"); }	

function CreateRightBlock() {
	global $Domains, $SubDomain, $GLOBAL, $C20, $C; $text=''; $used=array(0);
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	
	$text.="<div class='banner' id='Banner-33-1'></div>".$C20;	
	
	$text.="<h2 class='Cat9'>Новое в «Семье»</h2>"; $tab="ls";
	$data=DB("SELECT `id`,`pic`,`name`,`comcount` FROM `".$tab."_lenta` WHERE (`stat`=1 && `promo`=1 && `data`>'".(time()-7*24*60*60)."') ORDER BY `data` DESC LIMIT 3");
	$spec=array(); $total=$data["total"]; for ($i=0; $i<$total; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $spec[]=$ar; $used[]=$ar["id"]; endfor;
	if ($total<3) { $data=DB("SELECT `id`,`pic`,`name` FROM `".$tab."_lenta` WHERE (`stat`=1 && `spec`=1 && `id` NOT IN (".implode(",",$used).")) ORDER BY `data` DESC LIMIT ".(3-$total));
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $spec[]=$ar; $used[]=$ar["id"]; endfor; }
	$text.="<div>"; foreach($spec as $ar) { $text.="<div class='sem1td rm'><a href='/".$tab."/view/".$ar["id"]."'><img src='/userfiles/picintv/".$ar["pic"]."' />".$ar["name"]."</a></div>"; } $text.="</div>";
	
	$text.="<div class='banner' id='Banner-32-1'></div>".$C20;
	
	$text.="<h2 class='Cat9'>Новости Казани</h2>"; $tab="news"; $data=DB("SELECT `id`,`pic`,`name` FROM `".$tab."_lenta` WHERE (`stat`=1 && `onind`=1) ORDER BY `data` DESC LIMIT 5");
	$spec=array(); $total=$data["total"]; for ($i=0; $i<$total; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $spec[]=$ar; $used[]=$ar["id"]; endfor;
	$text.="<div>"; foreach($spec as $ar) { $text.="<div class='sem2td'><a href='/".$tab."/view/".$ar["id"]."'><img src='/userfiles/picintv/".$ar["pic"]."' />".$ar["name"]."</a></div>"; } $text.="</div>";
	
	$text.="<div class='banner' id='Banner-32-2'></div>".$C20;

	$text.='<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script><div id="vkbgroups"></div><script type="text/javascript">VK.Widgets.Group("vkbgroups", {mode: 0, width: "240", height: "270", color1: "FFFFFF", color2: "3a4267", color3: "3a4267"}, 40881158);</script>'.$C20;

	$text.='<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:240px;height:400px" data-ad-client="ca-pub-2073806235209608" data-ad-slot="7095611817"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>'.$C20;
	
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	$text.="<div class='C10'></div>"; return(array($text, ""));
}
?>