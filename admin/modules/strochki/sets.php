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
		if ($P["dsname"]=="") { $P["dsname"]=$P["dname"]; } if ($P["ddata1"]=="") { $P["ddata1"]=date("d.m.Y"); } 
		$ar=explode(".", $P["ddata1"]); $sdata=mktime($P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2]); 
		
		$res=DB("UPDATE `".$table."` SET
		`domain`='".(int)$P["ddom"]."',
		`design`='".$P["ddes"]."',
		`stat`='".(int)$P["dlvl"]."',
		`data`='".$sdata."',
		`name`='".$P["dname"]."',
		`text`='".$P["PostText"]."',
		`kw`='".$dkw."',
		`ds`='".$dds."',
		`sets`='".implode("|", $P["tst"])."'
		WHERE (`module`='strochki')");	
		
		$autolink="page-".$id; $P["dlink"]=str_replace('<autolink>', $autolink, $P["dlink"]); DB("UPDATE `".$table."` SET `link`='".$P["dlink"]."' WHERE  (`module`='strochki')");
		$_SESSION["Msg"]="<div class='SuccessDiv'>Раздел сохранен! Перейти в раздел: <a href='/".$P["dlink"]."/' target='_blank'>".$P["dlink"]."</a></div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}
	
// ДАННЫЕ СЕЛЕКТОВ
	### Список дизайнов
	$dess["0"]="- Основной шаблон дизайна -"; $data=DB("SELECT `folder`, `name` FROM `_designs` ORDER BY `name` ASC"); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i);
	$ar=@mysql_fetch_array($data["result"]); $sid=$ar["folder"]; $dess[$sid]=$ar["name"]; endfor;
	### Список доменов
	$doms[0]="- Основной домен сайта -"; $data=DB("SELECT `id`, `name` FROM `_domains` ORDER BY `name` ASC"); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i);
	$ar=@mysql_fetch_array($data["result"]); $sid=$ar["id"]; $doms[$sid]=$ar["name"]; endfor;
	
// ФОРМА ДОБАВЛЕНИЯ
	$AdminText='<h2>Редактирование настроек строчных объявлений</h2>'.$_SESSION["Msg"]; $tst=explode("|", $pg["sets"]);
	
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post' onsubmit='return JsVerify();'>";
	$AdminText.="<div class='RoundText' id='Tgg'><table>".'<tr class="TRLine0"><td style="width:25%;"></td><td style="width:75%;"></td></tr>
	<tr class="TRLine0"><td class="VarText">Заголовок страницы<star>*</star></td><td class="LongInput"><input name="dname" id="dname" type="text" class="JsVerify2" value=\''.$pg["name"].'\'></td><tr>
	<tr class="TRLine0"><td class="VarName"></td><td><a href="javascript:void(0);" onclick="ShowSets();" id="ShowSets">Показать дополнительные настройки</a></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Адрес страницы</td><td class="LongInput"><input name="dlink" id="dlink" type="text" class="JsVerify" value="'.$pg["link"].'"></td><tr>
	
	<tr class="TRLine1 ShowSets"><td class="VarName">Робокасса: Логин</td><td class="LongInput"><input name="tst[0]" type="text" value=\''.$tst[0].'\'></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Робокасса: Пароль #1</td><td class="LongInput"><input name="tst[1]" type="text" value=\''.$tst[1].'\'></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Робокасса: Пароль #2</td><td class="LongInput"><input name="tst[2]" type="text" value=\''.$tst[2].'\'></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Адрес сервера запроса</td><td class="LongInput"><input name="tst[3]" type="text" value=\''.$tst[3].'\'></td><tr>
	
	<tr class="TRLine1 ShowSets"><td class="VarName">Ключевые слова (keywords)</td><td class="LongInput"><input name="dkw" type="text" value=\''.$pg["kw"].'\'></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Описание (description)</td><td class="LongInput"><input name="dds" type="text" value=\''.$pg["ds"].'\'></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Домен страницы</td><td class="LongInput"><div class="sdiv"><select name="ddom">'.GetSelected($doms, $pg["domain"]).'</select></div></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Дизайн страницы</td><td class="LongInput"><div class="sdiv"><select name="ddes">'.GetSelected($dess, $pg["design"]).'</select></div></td><tr>
	<tr class="TRLine1 ShowSets"><td class="VarName">Дата создания</td><td class="DateInput">'.GetDataSet($pg["data"]).'</td><tr>'."</table><div class='C5'></div>
	<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/filemanager/ajex.js'></script>
	<textarea name='PostText' id='textedit' style='outline:none;'>".$pg["text"]."</textarea>
	<script type='text/javascript'>var editor=CKEDITOR.replace('textedit'); AjexFileManager.init({ returnTo: 'ckeditor', editor: editor, extraPlugins : 'autogrow'});</script>
	<div class='C15'></div><div class='CenterText'><input type='submit' name='addbutton' id='addbutton' class='SaveButton' value='Сохранить настройки'></div></div>";

	### Существующие занятые имена переменных
	$data=DB("SELECT `link` FROM `".$table."` WHERE (`id`!='".$id."')"); $AdminText.="<script type='text/javascript'>var NotAvaliable=new Array("; for ($i=0; $i<$data["total"]; $i++):
	@mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $AdminText.="'".$ar["link"]."',"; endfor; $AdminText.="'error');</script>";


// ПРАВАЯ КОЛОНКА
if ($pg["stat"]==1) { $p1="checked"; }
	$AdminRight="<h2>Настройки раздела</h2><div class='RoundText' id='Tgg'><table>".'<tr class="TRLine0"><td class="VarName">Раздел активен</td><td width="1%"><input type="checkbox" id="Inc0" name="dlvl" value="1" '.$p1.' /></td></tr>
	<tr class="TRLine0"><td class="VarName" colspan="2"><a href="/'.$pg["link"].'/" target="_blank">Перейти в раздел</a>: <a href="/'.$pg["link"].'/" target="_blank">'.$pg["link"].'</a></td></tr></table></div></form>';
}} $_SESSION["Msg"]="";
?>