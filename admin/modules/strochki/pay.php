<?
### МЕНЮ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {

$table="_pages";

### Получаем данные страницы в $pg;
$data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
if ($data["total"]!=1) { 
	### Запись не найдена	
	$AdminText=ATextReplace('Item-Module-Error', $id, $table); $GLOBAL["error"]=1;
} else {
	### Запись найдена
	@mysql_data_seek($data["result"], 0); $pg=@mysql_fetch_array($data["result"]);	

	// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["addbutton"])) {
		$res=DB("UPDATE `".$table."` SET `stat`='".(int)$P["dlvl"]."', `pretext`='".$P["PostText"]."' WHERE (`module`='strochki')");	
		$_SESSION["Msg"]="<div class='SuccessDiv'>Раздел сохранен!</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}
	

// ФОРМА ДОБАВЛЕНИЯ
	$AdminText='<h2>Редактирование текста оплаты</h2>'.$_SESSION["Msg"]; $tst=explode("|", $pg["sets"]);
	
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post' onsubmit='return JsVerify();'>";
	$AdminText.="<div class='RoundText' id='Tgg'><table>".'<tr class="TRLine0"><td style="width:25%;"></td><td style="width:75%;"></td></tr>'."</table><div class='C5'></div>
	<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/filemanager/ajex.js'></script>
	<textarea name='PostText' id='textedit' style='outline:none;'>".$pg["pretext"]."</textarea>
	<script type='text/javascript'>var editor=CKEDITOR.replace('textedit'); AjexFileManager.init({ returnTo: 'ckeditor', editor: editor, extraPlugins : 'autogrow'});</script>
	<div class='C15'></div><div class='CenterText'><input type='submit' name='addbutton' id='addbutton' class='SaveButton' value='Сохранить настройки'></div></div>";


// ПРАВАЯ КОЛОНКА
if ($pg["stat"]==1) { $p1="checked"; }
	$AdminRight="<h2>Настройки раздела</h2><div class='RoundText' id='Tgg'><table>".'<tr class="TRLine0"><td class="VarName">Раздел активен</td><td width="1%"><input type="checkbox" id="Inc0" name="dlvl" value="1" '.$p1.' /></td></tr>
	<tr class="TRLine0"><td class="VarName" colspan="2"><a href="/'.$pg["link"].'/" target="_blank">Перейти в раздел</a>: <a href="/'.$pg["link"].'/" target="_blank">'.$pg["link"].'</a></td></tr></table></div></form>';
}} $_SESSION["Msg"]="";
?>