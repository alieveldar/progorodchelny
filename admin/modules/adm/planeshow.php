<?
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	
// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["savebutton"])) {
		$ar=explode(".", $P["ddata1"]); $sdata=mktime($P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2]);
		$q="UPDATE `_planes` SET `name`='".$P['dname']."', `data`='$sdata', `auth`='".$P['dkw']."', `text`='".$P["PostText"]."' WHERE (`id`='$id') LIMIT 1";
		$_SESSION["Msg"]="<div class='SuccessDiv'>Cобытие успешно сохранено! <a href='?cat=adm_planes'>Перейти к записям календаря</a></div>";
		$data=DB($q); @header("location: ?cat=adm_planeshow&id=".$id); exit();
	}
// ВЫВОД ПОЛЕЙ И ФОРМ

	
	$data=DB("SELECT * FROM `_planes` WHERE (`id`='$id') LIMIT 1"); @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);
	
	$AdminText='<h2>Просмотр события</h2>'.$_SESSION["Msg"]."<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";
	### Основные данные
	$AdminText.="<div class='RoundText'><table>".'<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	<tr class="TRLine0"><td class="VarText">Событие<star>*</star></td><td class="LongInput"><input name="dname" id="dname" type="text" class="JsVerify2" maxlength="255" value=\''.$ar["name"].'\'></td><tr>
	<tr class="TRLine1"><td class="VarName">Ответственный</td><td class="LongInput"><input name="dkw" type="text" value=\''.$ar["auth"].'\'></td><tr>
	<tr class="TRLine0"><td class="VarName">Дата события</td><td class="DateInput">'.GetDataSet($ar["data"]).'</td><tr>'."</table>";
	
	$AdminText.=$C10."<h2>Примечания</h2><textarea name='PostText' id='textedit' style='outline:none;'>".$ar["text"]."</textarea>";
	$AdminText.="</div><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить запись'></div></form>";
	$AdminText.="<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/adapters/jquery.js'></script>";
	$AdminText.="<script type='text/javascript'>$(document).ready(function() { $('#textedit').ckeditor({customConfig:'/admin/texteditor/config_sm.js'});});</script>";

// ПРАВАЯ КОЛОНКА
$AdminRight=""; } $_SESSION["Msg"]="";
?>