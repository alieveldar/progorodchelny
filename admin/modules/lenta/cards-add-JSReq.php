<?
session_start();
if ($_SESSION['userrole']>1) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$R=$_REQUEST; $pid=$R["pid"]; $lenta=$R["lenta"]; $C10="<div class='C10'></div>";
		
	// операции =========================================================
	
	DB("INSERT INTO `_widget_cards` (`link`, `pid`) VALUES ('".$lenta."', '".$pid."')"); $last=DBL();
	
	$text="<div class='card' id='card".$last."'><input name='num[".$last."]' placeholder='Номер карточки'><a href='javascript:void(0);' onclick='RemoveField(".$last.")'>Удалить</a>".$C10."
	<textarea name='name[".$last."]' placeholder='Заголовок карточки'></textarea>".$C10."<textarea name='text[".$last."]' placeholder='Текст карточки'></textarea></div>";
	
	$result["content"]=$text; $GLOBALS['_RESULT']	= $result;
}
?>