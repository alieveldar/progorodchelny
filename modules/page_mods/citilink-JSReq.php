<?
session_start(); $dir=explode("/", $_SERVER['HTTP_REFERER']); $HTTPREFERER=$dir[2];
if ($HTTPREFERER==$_SERVER['HTTP_HOST']) {
	
	$GLOBAL["sitekey"]=1; $error=0; 
	
	$C10="<div class='C10'></div>"; $C20="<div class='C20'></div>";	
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	
	$data=DB("SELECT `id`,`nick`,`avatar`,`stat`,`role` FROM `_users` WHERE (`id`='".(int)$_SESSION['userid']."') LIMIT 1");
	if ($data["total"]==1) { @mysql_data_seek($data["result"],0); $GLOBAL["USER"]=@mysql_fetch_array($data["result"]); }
	
	// полученные данные ================================================
	$R = $_REQUEST; $datar=preg_replace('/[^a-z0-9_]+/i', '', $R["data"]);
	$result["code"]=0; $result["text"]=""; $result["lastdata"]=0;

	// отправка данных ================================================
	$news=DB("SELECT * FROM `_widget_vk` WHERE (`stat`=1 && `data`<'".$datar."') ORDER BY `data` DESC LIMIT 30");
	for ($i=0; $i<$news["total"]; $i++): @mysql_data_seek($news["result"], $i); $ar=@mysql_fetch_array($news["result"]);
	if (trim($ar["text"])!="" || trim($ar["pic"])!="") {
		if ($ar["pic"]!="") { $ar["text"].=$C10."<img src='".$ar["pic"]."' class='citipic' />"; }
		$text.="<div class='citivk' id='div".$ar["vkid"]."'><img src='".$ar["avatar"]."' class='citiava' />";
		$text.="<div class='cititxt'><b><a href='http://vk.com/id".$ar["userlink"]."' target='_blank'>".$ar["name"]."</a></b>";
		if ((int)$GLOBAL["USER"]["role"]>1) { $text.=" - <a href='javascript:void(0);' onclick='HidePic(\"".$ar["vkid"]."\");' style='color:red;'>УДАЛИТЬ</a>"; } 
		$text.=$C10.$ar["text"]."</div></div>".$C20;
	}
	endfor;
	
	if ($news["total"]==30) { $result["code"]=1; } $result["text"]=$text; $result["lastdata"]=$ar["data"];
} else { $result=array("Code"=>0, "Text"=>"--- Security alert ---", "Class"=>"ErrorDiv", "Comment"=>''); }

// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;
?>