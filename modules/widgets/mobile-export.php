<?
### Виджет: Новости для мобильных платформ

$GLOBAL["sitekey"]=1; $G=$_GET; $limit=50; $FROM="real_query";
@require($_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php");
@require($_SERVER['DOCUMENT_ROOT']."/modules/standart/Settings.php");
@require($_SERVER['DOCUMENT_ROOT']."/modules/standart/Cache.php");

$GLOBAL["pathpic1"]="http://".$GLOBAL["host"]."/userfiles/pictavto/";
$GLOBAL["pathpic2"]="http://".$GLOBAL["host"]."/userfiles/picintv/";
$GLOBAL["StartTime"]=GetMicroTime(); 

$tables=array(
	"Казань"=>"news_lenta",
	"Авто"=>"auto_lenta",
	"Спорт"=>"sport_lenta",
	"Бизнес"=>"business_lenta",
	"Семья"=>"ls_lenta",
	"Афиша"=>"afisha_lenta",
	"В мире"=>"world_lenta",
);

################################################################################################################################################################################################################################################
################################################################################################################################################################################################################################################

if ($G["act"]=="init" || $G["act"]=="") { 
	//list($text1, $cap)=getDataDB(); list($text2, $cap)=getCatsDB();
	$file="mobile-first.query"; if (RetCache($file)=="true") { list($text1, $cap)=GetCache($file, 0); $FROM="cache_file"; } else { list($text1, $cap)=getDataDB(); SetCache($file, $text1, ""); }
	list($text2, $cap)=getCatsDB(); $text="<items>".$text2.$text1."</items>";	
}

if ($G["act"]=="previos") { 
	list($text, $cap)=getDataDB(" && `[table]`.`data`<'".(int)$G["id"]."'");
}


function getDataDB($where='') {
	GLOBAL $GLOBAL, $tables, $limit; foreach($tables as $name=>$table) { $tmp=explode("_", $table); $link=$tmp[0];
	$q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`lid`, `$table`.`text`, `$table`.`endtext`, `$table`.`data`, `$table`.`pic`, `_pages`.`link`, `_pages`.`shortname` as `lenta` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'
	WHERE (`$table`.`stat`=1 && `$table`.`promo`<>1 ".str_replace("[table]", $table, $where).") GROUP BY 1) UNION "; } $data=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT ".$limit); 
	if ($data["total"]>0) { for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $text.=CreateXMLitems($ar); endfor; } return array($text,'');
}

function getCatsDB() { GLOBAL $tables; $text="<cats><cat>_all_</cat><lenta>Все новости</lenta></cats>";
foreach($tables as $name=>$table) { $tmp=explode("_", $table); $text.="<cats><cat>".$tmp[0]."</cat><lenta>".$name."</lenta></cats>"; } return array($text,''); }

################################################################################################################################################################################################################################################
// $GLOBAL["RunTime"]=GetMicroTime()-$GLOBAL["StartTime"]; $QT=round($GLOBAL["RunTime"], 5); <querytime>".$QT."</querytime><datafrom>".$FROM."</datafrom><servertime>".time()."</servertime>
echo "<answer>".str_replace(array("\r","\n","\t"), '', $text)."</answer>";
################################################################################################################################################################################################################################################

function CreateXMLitems($ar) { global $GLOBAL; $d=ToRusData($ar["data"]); $path="http://".$GLOBAL["host"]."/".$ar["link"]."/view/".$ar["id"];
$xml="
<news>
<id>".(int)$ar["id"]."</id>
<data>".$d[0]."</data>
<stamp>".$ar["data"]."</stamp>
<lenta>".$ar["lenta"]."</lenta>
<cat>".$ar["link"]."</cat>
<link>".$path."</link>
<pic>http://progorodchelny.ru/userfiles/picnews/".$ar["pic"]."</pic>
<picbig>http://progorodchelny.ru/userfiles/picitem/".$ar["pic"]."</picbig>
<name>".$ar["name"]."</name>
<lid>".ClearText($ar["lid"])."</lid>
<text>".ClearText($ar["text"])."</text>
<endt>".ClearText("   ".$ar["endtext"])."</endt>
</news>";
return $xml; }

################################################################################################################################################################################################################################################

function ClearText($text) {
	$text=strip_tags($text,"<b><u><i><strong><em>"); $text=str_replace(array("\r","\n"), '|', $text); for ($i=0; $i<5; $i++): $text=str_replace('|| ','|',$text); $text=str_replace('||','|',$text); endfor;
	$text=str_replace(array("&nbsp;","&mdash;","&ndash;","&quot;"), array(" ","—","—",'"'), $text); ereg_replace('[ ]+',' ',$text); $text=trim($text); $text=trim($text, "|"); $text=str_replace(PHP_EOL,'',$text); return $text;
}
?>