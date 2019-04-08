<?
### НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {

// РАЗДЕЛ
	$data=DB("SELECT `id`,`shortname`,`link`, `sets` FROM `_pages` WHERE (`link`='".$alias."') LIMIT 1");
	if ($data["total"]!=1) { $AdminText=ATextReplace('Item-Module-Error', $id, "_pages"); $GLOBAL["error"]=1; } else {
	@mysql_data_seek($data["result"], 0); $raz=@mysql_fetch_array($data["result"]); $bst="";

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["savebutton"])) {
		$ar=explode(".", $P["ddata1"]); $sdata1=mktime($P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2]); 
		$q="UPDATE `".$alias."_lenta` SET 
		`uid`='".(int)$P['authid']."',
		`name`='".str_replace("'", "\'", $P["dname"])."',
		`cat`='".$P["site"]."',
		`kw`='".str_replace("'", "\'", $P["dkw"])."', 
		`ds`='".str_replace("'", "\'", $P["dds"])."', 
		`data`='".$sdata1."',
		`lid`='".str_replace("'", "\'", $P["text1"])."',
		`endtext`='".str_replace("'", "\'", $P["text2"])."',
		`realinfo`='".str_replace("'","\'", $P["realinfo"])."',		
		`alttext`='".str_replace("'", "\'", $P["alttext"])."',
		`soctext`='".str_replace("'","\'", $P["soctext"])."'		
		WHERE (id='".(int)$id."')";
		DB($q); $_SESSION["Msg"]="<div class='SuccessDiv'>Запись успешно сохранена!</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}

	// ВЫВОД ПОЛЕЙ И ФОРМ
	$data=DB("SELECT * FROM `".$alias."_lenta` WHERE (`id`='".(int)$id."') LIMIT 1"); 
	if ($data["total"]!=1) { $AdminText=ATextReplace('ItemError', $raz["shortname"]." (".$alias.")", $id); $GLOBAL["error"]=1; } else {
	### Заполнение данных
	@mysql_data_seek($data["result"], 0); $node=@mysql_fetch_array($data["result"]); if ($node["stat"]==1) { $chk="checked"; }
	$site=array(); $data=DB("SELECT `id`, `name` FROM `".$alias."_cats` ORDER BY `rate` DESC"); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $site[$ar["id"]]=$ar["name"]; endfor; $usr=array(); $data=DB("SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC"); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $usr[$ar["id"]]=$ar["nick"]; endfor;	
		
	$AdminText='<h2>Редактирование: &laquo'.$node["name"].'&raquo;</h2>'.$_SESSION["Msg"]."<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";	
	$AdminText.="<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/filemanager/ajex.js'></script>";
	$AdminText.="<div class='RoundText'><table>".'<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	<tr class="TRLine0"><td class="VarText">Название<star>*</star></td><td class="LongInput"><input name="dname" id="dname" type="text" value=\''.$node["name"].'\' maxlength="120"></td><tr>
	<tr class="TRLine1"><td class="VarText">Категория</td><td class="LongInput"><div class="sdiv"><select name="site">'.GetSelected($site, $node["cat"]).'</select></div></td><tr>
	<tr class="TRLine0"><td class="VarName"></td><td><a href="javascript:void(0);" onclick="ShowSets();" id="ShowSets">Показать дополнительные настройки</a></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Автор материала</td><td class="LongInput"><div class="sdiv"><select name="authid">'.GetSelected($usr, $node["uid"]).'</select></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Ключевые слова (keywords)</td><td class="LongInput"><input name="dkw" type="text" value=\''.$node["kw"].'\'></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Описание (description)</td><td class="LongInput"><input name="dds" type="text" value=\''.$node["ds"].'\'></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Дата создания</td><td class="DateInput">'.GetDataSet($node["data"],"").'</td><tr>
	<tr class="TRLine0"><td class="VarText">Телефон</td><td class="LongInput"><input name="alttext" type="text" value=\''.$node["alttext"].'\'></td></tr>
	<tr class="TRLine1"><td class="VarText">Адрес</td><td class="LongInput"><input name="soctext" type="text" value=\''.$node["soctext"].'\'></td></tr>
	<tr class="TRLine0"><td class="VarText">Ссылка (сайт или VK)</td><td class="LongInput"><input name="realinfo" type="text" value=\''.$node["realinfo"].'\'></td></tr>
	'."<tr class='TRLine0'><td class='VarText'>Юринфа</td><td class='LongInput'>
	<textarea name='text2' id='text2' style='height:50px; font-size:11px; padding:4px;' maxlength='300'>".$node["endtext"]."</textarea></td></tr>
	<tr class='TRLine1'><td class='VarText' colspan='2'><textarea name='text1' id='text1'>".$node["lid"]."</textarea></td></tr>	
	</table></div>";
	$AdminText.="<script type='text/javascript'>$(document).ready(function() { var beditor=CKEDITOR.replace('text1'); AjexFileManager.init({ returnTo: 'ckeditor', editor: beditor}); });</script>";

	### Сохранение
	$AdminText.="<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div>";

	// ПРАВАЯ КОЛОНКА
	$AdminRight="<br><br>
	<div class='RoundText'><table><tr class='TRLine'><td class='CheckInput'><input type='checkbox' id='RS-".$id."-".$alias."_lenta' ".$chk."></td><td><b>Опубликовано</b></td></tr></table></div>
	<div class='SecondMenu2'><a href='?cat=".$alias."_edit&id=".$id."'>Содержание</a></div><div class='SecondMenu'><a href='?cat=".$alias."_photo&id=".$id."'>Фотография</a></div>";
	$AdminRight.="<br><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div></form>";
	
	}}
}
$_SESSION["Msg"]="";
?>