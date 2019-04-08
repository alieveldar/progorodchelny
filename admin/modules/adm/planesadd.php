<?
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	
// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["savebutton"])) {
		$ar=explode(".", $P["ddata1"]); $sdata=mktime($P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2]); 
		$q="INSERT INTO `_planes` (`name`,`data`,`auth`,`text`) VALUES ('".$P['dname']."', '$sdata', '".$P['dkw']."', '".$P["PostText"]."')";
		$_SESSION["Msg"]="<div class='SuccessDiv'>Новое событие успешно создано! <a href='?cat=adm_planes'>Перейти к записям календаря</a></div>"; $data=DB($q); $last=DBL(); @header("location: ?cat=adm_planesadd"); exit();
	}
// ВЫВОД ПОЛЕЙ И ФОРМ

	$AdminText='<h2>Добавление нового события</h2>'.$_SESSION["Msg"];
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";

	### Основные данные
	$AdminText.="<div class='RoundText'><table>".'<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	<tr class="TRLine0"><td class="VarText">Событие<star>*</star></td><td class="LongInput"><input name="dname" id="dname" type="text" class="JsVerify2" maxlength="255"></td><tr>
	<tr class="TRLine1"><td class="VarName">Ответственный</td><td class="LongInput"><input name="dkw" type="text"></td><tr>
	<tr class="TRLine0"><td class="VarName">Дата события</td><td class="DateInput">'.GetDataSet().'</td><tr>'."</table>";
	$AdminText.=$C10."<h2>Примечания</h2><textarea name='PostText' id='textedit' style='outline:none;'>".$node["alttext"]."</textarea>";
	$AdminText.="</div><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Создать запись'></div></form>";
	$AdminText.="<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/adapters/jquery.js'></script>";
	$AdminText.="<script type='text/javascript'>$(document).ready(function() { $('#textedit').ckeditor({customConfig:'/admin/texteditor/config_sm.js'});});</script>";

// ПРАВАЯ КОЛОНКА
$AdminRight=""; } $_SESSION["Msg"]="";
?>