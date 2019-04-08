<?
### Виджет: Новости для мобильных платформ

$GLOBAL["sitekey"]=1; $G=$_GET; $limit=30; $FROM="real_query"; $Coms='';
@require($_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php");
@require($_SERVER['DOCUMENT_ROOT']."/modules/standart/Settings.php");
@require($_SERVER['DOCUMENT_ROOT']."/modules/standart/Cache.php");

$GLOBAL["pathpic1"]="http://".$GLOBAL["host"]."/userfiles/pictavto/";
$GLOBAL["pathpic2"]="http://".$GLOBAL["host"]."/userfiles/picintv/";
$GLOBAL["StartTime"]=GetMicroTime(); 

$VARS["cachepages"]=0;

$tables=array(
	"Казань"=>"news_lenta",
//	"Авто"=>"auto_lenta",
//	"Спорт"=>"sport_lenta",
//	"Бизнес"=>"business_lenta",
//	"Женский"=>"oney_lenta",
//	"В мире"=>"world_lenta",
);

################################################################################################################################################################################################################################################
################################################################################################################################################################################################################################################

if ($G["act"]=="getnews" || $G["act"]=="") { 

	list($text1, $cap)=getDataDB(); list($text2, $cap)=getCatsDB();
	//$file="mobile-newversion.query"; if (RetCache($file)=="true") { list($text1, $cap)=GetCache($file, 0); $FROM="cache_file"; } else { list($text1, $cap)=getDataDB(); SetCache($file, $text1, ""); } list($text2, $cap)=getCatsDB();
	$text="<items>".$text2.$text1."</items>";	
}

if ($G["act"]=="previos") { 
	list($text, $cap)=getDataDB(" && `[table]`.`data`<'".(int)$G["id"]."'");
}


function getDataDB($where='') {
	GLOBAL $GLOBAL, $tables, $limit;
	
	//foreach($tables as $name=>$table) { $tmp=explode("_", $table); $link=$tmp[0];
	//$q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`lid`, `$table`.`text`, `$table`.`endtext`, `$table`.`data`, `$table`.`pic`, `_pages`.`link`, `_pages`.`shortname` as `lenta` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link'
	//WHERE (`$table`.`stat`=1 && `$table`.`comcount`!=0 && `$table`.`promo`<>1 ".str_replace("[table]", $table, $where).") GROUP BY 1) UNION "; } $data=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT ".$limit); 
	
	$table="news_lenta"; $link="news"; $q="SELECT `$table`.`id`, `$table`.`name`, `$table`.`lid`, `$table`.`text`, `$table`.`endtext`, `$table`.`data`, `$table`.`pic`
	FROM `$table` WHERE (`$table`.`stat`=1 && `$table`.`promo`<>1) GROUP BY 1 ORDER BY `data` DESC LIMIT ".$limit; $data=DB($q); 
	

	if ($data["total"]>0) {
	for ($i=0; $i<$data["total"]; $i++):
		@mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $ar["coms"]=""; $cid=$ar["id"];
		$dcom=DB("SELECT `_comments`.`data`,`_comments`.`text`,`_comments`.`uname`,`_users`.`nick` FROM `_comments` LEFT JOIN `_users` ON `_users`.`id`=`_comments`.`uid` WHERE (`link`='$link' && `pid`='$cid') GROUP BY 1 ORDER BY `_comments`.`data` ASC");
		if ($dcom["total"]>0) {
		for ($j=0; $j<$dcom["total"]; $j++):
			@mysql_data_seek($dcom["result"], $j); $aj=@mysql_fetch_array($dcom["result"]); $d=ToRusData($aj["data"]);
			if ($aj["nick"]=='') { $aj["nick"]=$aj["uname"]; if ($aj["nick"]=='') { $aj["nick"]='Горожанин'; }}
			$ar["coms"].=ClearText("<div class='Comts'><b>".trim($aj["nick"]).", ".$d[0]."</b><br />".$aj["text"]."</div>");
		endfor; } $text.=CreateXMLitems($ar);
	endfor;
	}
	return array($text,'');
}

function getCatsDB() { GLOBAL $tables; $text="<cats><cat>_all_</cat><lenta>Все новости</lenta></cats>"; foreach($tables as $name=>$table) { $tmp=explode("_", $table); $text.="<cats><cat>".$tmp[0]."</cat><lenta>".$name."</lenta></cats>"; } return array($text,''); }

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
<cat>".$ar["link"]."</cat>
<pic>".$ar["pic"]."</pic>
<cap>".$ar["name"]."</cap>
<lid>".ClearText($ar["lid"])."</lid>
<text>".ClearText($ar["text"])."</text>
<endt>".ClearText($ar["endtext"])."</endt>
<coms>".$ar["coms"]."</coms>
</news>";
return $xml; }

################################################################################################################################################################################################################################################

function ClearText($text) { $text=strip_tags($text,"<b><u><i><strong><em><div><br>"); $text=str_replace(array("\r","\n"), '|', $text); for ($i=0; $i<5; $i++): $text=str_replace('|| ','|',$text); $text=str_replace('||','|',$text); $text=str_replace('||','|',$text); $text=str_replace('||','|',$text); endfor; $text=str_replace(array("&nbsp;","&mdash;","&ndash;","&quot;","&laquo;","&ldquo;","&raquo;","&rdquo;"), array(" ","—","—",'"','«','«','»','»'), $text); ereg_replace('[ ]+',' ',$text); $text=trim($text); $text=trim($text, "|"); $text=str_replace(PHP_EOL,'',$text); return $text; }
?>