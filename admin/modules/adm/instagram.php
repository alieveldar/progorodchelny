<?
### МЕНЮ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {

$html=file_get_contents('../template/404.html');
// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
$P=$_POST;

if (isset($P["editbutton"])) {
	$q="UPDATE `_pages` SET `sets`='".$P["sets"]."', `text`='".$P["text"]."' WHERE (`link`='instagram')"; DB($q);
	$_SESSION["Msg"]="<div class='SuccessDiv'>Cтраница сохранена!</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
}
	
// ФОРМА ДОБАВЛЕНИЯ
	$AdminText='<h2>Редактирование страницы импорта из Инстаграм</h2>'.$_SESSION["Msg"];
	$data=DB("SELECT `text`,`sets`,`id`,`link` FROM `_pages` WHERE (`link`='instagram') LIMIT 1"); @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]);
	
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post' onsubmit='return JsVerify();'>";
	$AdminText.="<div class='RoundText' id='Tgg'><div class='C5'></div>".'<div class="LongInput"><input name="sets" type="text" value=\''.$ar["sets"].'\' placeholder="Хэштэг для импорта из Instagram"></div>'.
	"<div class='C20'></div><script type='text/javascript' src='/admin/texteditor/ckeditor.js'></script><script type='text/javascript' src='/admin/texteditor/filemanager/ajex.js'></script>
	<textarea name='text' id='textedit' style='outline:none;'>".$ar["text"]."</textarea><script type='text/javascript'>var editor=CKEDITOR.replace('textedit'); AjexFileManager.init({ returnTo: 'ckeditor', editor: editor});</script>
	<div class='C15'></div><div class='CenterText'><input type='submit' name='editbutton' id='editbutton' class='SaveButton' value='Сохранить страницу'></div></div></form>";

	// ПРАВАЯ КОЛОНКА
	$AdminRight="<div class='SecondMenu'><a href='?cat=adm_razdelsets&id=".$ar["id"]."'>Настройки «Инстаграм»</a></div><div class='SecondMenu'><a href='/".$ar["link"]."' target='_blank'>Фотографии «Инстаграм»</a></div>";
	
}
$_SESSION["Msg"]="";

?>