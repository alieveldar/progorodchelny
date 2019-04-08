<?
session_start(); 
$dir=explode("/", str_replace("http://","",$_SERVER['HTTP_REFERER']));
$HTTPREFERER=$dir[0];
if ($HTTPREFERER==$_SERVER['SERVER_NAME']) {
	$GLOBAL["sitekey"]=1;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/Settings.php";	
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";	
	$JsHttpRequest=new JsHttpRequest("utf-8");
	if (!isset($dir[4]) || $dir[4]=="") $dir[4] = 0;	
	// полученные данные ================================================
	$result = array();
	$R = $_REQUEST;
	$uid=$_SESSION["userid"];
	$qid=(int)$R["qid"];
    $table1 = $dir[1]."_lenta";
	$table2 = $dir[1]."_results";
	$table3 = $dir[1]."_answers";
	$table4=$dir[1]."_queries";
	$oid=(int)$dir[3];
	$aid=htmlspecialchars(str_replace(array("select","union","order","where","char","from","group","insert","drop","delete","update"), "", $R["aid"]));
	$aid=str_replace("'", "\'", $aid);
	// операции =========================================================
	//get all answers 
	$answerstext;
	$res3=DB("SELECT `points`,`text` FROM ".$table3." WHERE `id` in (".$aid.")");
	if($res3["total"]>0){
		for ($i=0; $i<$res3["total"]; $i++) {
			@mysql_data_seek($res3["result"], $i);
			$node=@mysql_fetch_array($res3["result"]);
			$_SESSION['upoints'][$oid]+=$node["points"];
			$answerstext.=$node["text"].", "; 
		}
		$answerstext=trim($answerstext, ", ");
	}else{
	    $answerstext=$aid;
	 }
	//end get
	$res1=DB("SELECT savebd FROM ".$table1."  WHERE `id`= ".$oid." Limit 1");
    @mysql_data_seek($res1["result"], 0);
	$node1=@mysql_fetch_array($res1["result"]);
	if($node1["savebd"]==="1")//проверяем сохранять ли в бд
	{   if ($_SESSION["TestRID"][$oid]) { 
	       
		   //oldtext votes  from rezults
			$oldtxt=DB("SELECT `text` FROM " . $table2. " WHERE `id`='".$_SESSION["TestRID"][$oid]."'");	   
			@mysql_data_seek($oldtxt["result"], 0);
			$oldtext=@mysql_fetch_array($oldtxt["result"]);
			//queries text 
			$qtxt=DB("SELECT `name` FROM " . $table4. " WHERE  `pid`=".$oid." order by id desc LIMIT ".$qid.",1");	   
			@mysql_data_seek($qtxt["result"], 0);
			$qtext=@mysql_fetch_array($qtxt["result"]);
	       //update results
           $query="UPDATE ".$table2." SET text='".$oldtext["text"]."<h3>".$qtext["name"]."</h3>".$answerstext."<hr>'  where `id`='".$_SESSION["TestRID"][$oid]."'";
		  
	   } else {
	       //queries text 
		   $qtxt=DB("SELECT `name` FROM " . $table4." WHERE  `pid`=".$oid." order by id desc LIMIT ".$qid.",1");	   
		   @mysql_data_seek($qtxt["result"], 0);
		   $qtext=@mysql_fetch_array($qtxt["result"]);
		  //insert into results
		   $query="insert into ".$table2 ."(`qid`,`text`,`date`,`uid`,`oid`) values (".$qid.",'<h3>".$qtext["name"]."</h3>".$answerstext."<hr>',".time().",".$uid.",".$oid.")";
		}
	   $otvet=DB($query);
	   if ((int)DBL()!=0) { 
	     $_SESSION["TestRID"][$oid]=(int)DBL();
	   }
	 
	}
	$_SESSION['Tests'][$oid]++;//qid id++
	
} else {
	$result["Text"]="--- Security alert ---";
	$result["Class"]="ErrorDiv";
	$result["Code"]=0;
}
// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;	
?>