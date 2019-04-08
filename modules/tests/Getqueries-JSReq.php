<?
session_start(); 
$dir=explode("/", str_replace("http://","",$_SERVER['HTTP_REFERER']));
$HTTPREFERER=$dir[0];
if ($HTTPREFERER==$_SERVER['SERVER_NAME']) {
	$GLOBAL["sitekey"]=1;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/Settings.php";	
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
    @require $_SERVER['DOCUMENT_ROOT']."/modules/standart/MailSend.php";		
	$JsHttpRequest=new JsHttpRequest("utf-8");
	if (!isset($dir[4]) || $dir[4]=="") $dir[4] = 0;	
	// полученные данные ================================================
	$R = $_REQUEST;
	$uid=$_SESSION["userid"];
	$oid=(int)$dir[3];
	$table = $dir[1]."_queries";
	$file=$table."-".$dir[2].".".$pid.".".$dir[4];
	$qid=$_SESSION["Tests"][$oid];
	//$qid=(int)$dir[4];
	// операции =========================================================
	$result = array();
	$sql="SELECT * FROM ".$table." WHERE  `pid`=".$oid." order by id desc LIMIT ".$qid.",1";
	$req=DB($sql);
	if($req["total"]>0){
	@mysql_data_seek($req["result"], 0); 
	$node=@mysql_fetch_array($req["result"]);
	$result["text"]="<div >".$node["name"]."</div>"."<div>".$node["text"]."</div>";
	$qtypes=(int)$node["types"];
	$vopid= $node["id"];
	$sql2="SELECT * FROM tests_answers WHERE `qid`=".$node["id"];
	$req2=DB($sql2);
	
	switch($qtypes)
	{
	 case 0:$result["text"].="Введите текст ответа в поле : <br><br>";break;
	 case 1:$result["text"].="Выберите  картинку <br><br>";break;
	 case 3:$result["text"].="Выберите один или несколько ответов : <br><br>";break;
	 case 2:$result["text"].="Выберите один  ответ : <br><br>";break;
	 default : $result["text"]="Ответы отсутвуют";
	}
	
	if ($qtypes==0) 
	{
	$result["text"].="<div><br>"."<textarea id='tests_votes'  placeholder='Введите ваш ответ' 
	style='border:3px #CCCCCC solid; 
		 -moz-border-radius: 10px; 
		 -webkit-border-radius: 10px; 
		 -khtml-border-radius:10px; 
		 border-radius: 10px;
		 padding: 5px;width:100%;
		 height:100px;'
	>"."</textarea></div>";
	} else {
	for ($i=0; $i<$req2["total"]; $i++){
	@mysql_data_seek($req2["result"], $i); 
	$node2=@mysql_fetch_array($req2["result"]);
	switch($qtypes){
	
		case 1:$result["text"].="<div class='dimg' style='padding:1%;width:31%;height:10%;float:left;'>"."<img  src='/userfiles/picpreview/".$node2["pic"]."' onclick='on_tests_answer_image(".$node2["id"].")' class='aimg".$node2["id"]."' style='border:3px #CCCCCC solid; 
		 -moz-border-radius: 10px; 
		 -webkit-border-radius: 10px; 
		 -khtml-border-radius:10px; 
		 border-radius: 10px;
		 padding: 1%;width:95%;'></div>";break;
		case 2: $result["text"].="<br><div id='votes[]' name='".$node2["id"]."' ><input type='Radio' value='".$node2["id"]."' class='votes' name='Radio'>&nbsp;<b>".$node2["text"]."</b></div>";break; 
		case 3: $result["text"].="<br><div id='votes[]' name='".$node2["id"]."'><input type='checkbox' value='".$node2["id"]."' class='votes'>&nbsp;<b>".$node2["text"]."</b></div>";break;
	
	    default : $result["text"]="Что-то  пошло не так";
	}}
	$result["text"].="<div class='C10'></div>";
	}
	$result["log"]=$dir." ||| ".$qtypes;
	$result["qid"]=$qid;
	$result["type"]=$qtypes;
	$result["oid"]=$oid;
	$result["text"].="<br><div><input type='button' value='Ответить' onclick='on_tests_answer()'  class='SaveButton' style='width:300px;height:30px;'></div>";
	} else {///end of test
    $res1=DB("SELECT `showtouser`,`alttext`,`authoremail` FROM ".$dir[1]."_lenta"."  WHERE `id`= ".(int)$dir[3]." Limit 1");
    @mysql_data_seek($res1["result"], 0);
	$node1=@mysql_fetch_array($res1["result"]);
	$result["log"]=$req["total"];
	if($node1["showtouser"]==="1")
	  {  //show user
	        $result["text"].="<h2>Опрос завершен спасибо за участие!</h2>";  
			if($_SESSION['upoints'][$oid]!='0')$result["text"].="<b>Количество набранных баллов : ".$_SESSION['upoints'][$oid]."</b>";
			$result["text"].="<div class='C20'>".$node1["alttext"]."</div>";
	  }
	  else
	  { //don't show
	       $result["text"].="<h2>Опрос завершен спасибо за участие!</h2>";  
		   $result["text"].="<div class='c20'>".$node1["alttext"]."</div>";
	  }  
       if($node1["authoremail"]){
	   //send email   
        $endtxt=DB("SELECT `text` FROM " . $dir[1]."_results". " WHERE `id`='".$_SESSION["TestRID"][$oid]."'");	   
		@mysql_data_seek($endtxt["result"], 0);
		$endtext=@mysql_fetch_array($endtxt["result"]);
		MailSend($node1["authoremail"], "Прохождение опроса",$endtext['text'],$VARS["sitemail"]);
	   
       }  //end send
	    //=== clear session 
	      unset($_SESSION['upoints'][$oid]);
		  unset($_SESSION['Tests'][$oid]);
		  unset($_SESSION["TestRID"][$oid]);
		//===end clear
	
	}
} else {
	$result["Text"]="--- Security alert ---";
	$result["Class"]="ErrorDiv";
	$result["Code"]=0;
}
// отправляемые данные ==============================================
$GLOBALS['_RESULT']	= $result;	
?>