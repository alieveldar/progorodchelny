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
		
		if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["name"]!="") { 
		$userfile=$_FILES['photo']['tmp_name']; $ext=str_replace("jpeg", "jpg", strtolower(substr($_FILES['photo']['name'], 1+strrpos($_FILES['photo']['name'], "."))));
		if (!is_dir($ROOT."/userfiles/picoriginal")) { mkdir($ROOT."/userfiles/picoriginal", 0777); } $picname=$GLOBAL["pic"].".".$ext;
		@move_uploaded_file($_FILES['photo']['tmp_name'], $ROOT."/userfiles/picoriginal/".$picname); } else { $picname=$P["picname"]; }
		
		if (isset($_FILES["icon"]["name"]) && $_FILES["icon"]["name"]!="") { 
		$userfile=$_FILES['icon']['tmp_name']; $ext=str_replace("jpeg", "jpg", strtolower(substr($_FILES['icon']['name'], 1+strrpos($_FILES['icon']['name'], "."))));
		if (!is_dir($ROOT."/userfiles/mapicon")) { mkdir($ROOT."/userfiles/mapicon", 0777); } $iconname=$GLOBAL["pic"].".".$ext;
		@move_uploaded_file($_FILES['icon']['tmp_name'], $ROOT."/userfiles/mapicon/".$iconname); } else { $iconname=$P["iconname"]; }
		
		
		$q="UPDATE `".$alias."_cats` SET 
		`name`='".str_replace("'", "\'", $P["dname"])."',
		`pic`='".$picname."',
		`icon`='".$iconname."',
		`text`='".str_replace("'", "\'", $P["text2"])."',
		`lid`='".str_replace("'", "\'", $P["text1"])."'
		WHERE (id='".(int)$id."')";
		DB($q); $_SESSION["Msg"]="<div class='SuccessDiv'>Запись успешно сохранена!</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}

	// ВЫВОД ПОЛЕЙ И ФОРМ
	$data=DB("SELECT * FROM `".$alias."_cats` WHERE (`id`='".(int)$id."') LIMIT 1"); 
	if ($data["total"]!=1) { $AdminText=ATextReplace('ItemError', $raz["shortname"]." (".$alias.")", $id); $GLOBAL["error"]=1; } else {
		
	### Заполнение данных
	@mysql_data_seek($data["result"], 0); $node=@mysql_fetch_array($data["result"]); if ($node["stat"]==1) { $chk="checked"; }
	

	$site=array(); $data=DB("SELECT `id`, `name` FROM `".$alias."_cats` ORDER BY `rate` DESC");
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $site[$ar["id"]]=$ar["name"]; endfor;
	
	
	$AdminText='<h2>Редактирование: &laquo'.$node["name"].'&raquo;</h2>'.$_SESSION["Msg"]."<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";	
	$AdminText.="<script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/filemanager/ajex.js'></script>";
	$AdminText.="<div class='RoundText'><table>".'<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	<tr class="TRLine0"><td class="VarText">Название категории<star>*</star></td><td class="LongInput"><input name="dname" id="dname" type="text" value=\''.$node["name"].'\' maxlength="120"></td><tr>'."
	<tr class='TRLine0'><td class='VarText'>Лид</td><td class='LongInput'><textarea name='text1' id='text1' style='height:50px; font-size:11px; padding:4px;' maxlength='300'>".$node["lid"]."</textarea></td></tr>
	<tr class='TRLine0'><td class='VarText'>Фотография-фон</td><td><input type='file' name='photo' accept='image/jpeg,image/gif,image/x-png' /></td></tr>
	<tr class='TRLine0'><td class='VarText'>Иконка раздела</td><td><input type='file' name='icon' accept='image/jpeg,image/gif,image/x-png' /></td></tr>
	<tr class='TRLine0'><td colspan='2'><textarea name='text2' id='textedit'>".$node["text"]."</textarea></td></tr>
	</table></div>";
	$AdminText.="<input name='picname' type='hidden' value='".$node["pic"]."'><input name='iconname' type='hidden' value='".$node["icon"]."'>";


	### Сохранение
	$AdminText.="<script type='text/javascript'>$(document).ready(function() { var beditor=CKEDITOR.replace('textedit'); AjexFileManager.init({ returnTo: 'ckeditor', editor: beditor}); });</script>";
	$AdminText.="<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div>";

	// ПРАВАЯ КОЛОНКА
	$AdminRight="<br><br><div class='RoundText'><table><tr class='TRLine'>
	<td class='CheckInput'><input type='checkbox' id='RS-".$id."-".$alias."_cats' ".$chk."></td><td><b>Опубликовано</b></td></tr></table></div><div class='SecondMenu'>";
	
	foreach ($site as $k=>$v) { $AdminRight.="<a href='?cat=".$alias."_catedit&id=".$k."'>".$v."</a>"; }
	
	if ($node["pic"]!="") { $AdminRight.="<b>Фон:</b><br><img src='/userfiles/picoriginal/".$node["pic"]."' style='width:100%; height; auto'><br><br>"; }
	if ($node["icon"]!="") { $AdminRight.="<b>Иконка:</b><br><img src='/userfiles/mapicon/".$node["icon"]."' style='width:100%; height; auto'>"; }
	
	$AdminRight.="</div></form>";
	
	}}
}
$_SESSION["Msg"]="";
?>