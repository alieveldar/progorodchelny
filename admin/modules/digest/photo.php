<?
### НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {

// РАЗДЕЛ
	$data=DB("SELECT `id`,`shortname`,`link`, `sets` FROM `_pages` WHERE (`link`='".$alias."') LIMIT 1");
	if ($data["total"]!=1) { $AdminText=ATextReplace('Item-Module-Error', $id, "_pages"); $GLOBAL["error"]=1; } else {
	@mysql_data_seek($data["result"], 0); $raz=@mysql_fetch_array($data["result"]);

// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	# upload
	if (isset($P["addbutton"])) {
		if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["name"]!="") { 
		if ($P["picname"]!="") { foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) { @unlink("../userfiles/".$path."/".$P["picname"]); }} @require("modules/UploadPhoto.php");
		if ($loaded==1) { $class="SuccessDiv"; $q="UPDATE `".$alias."_lenta` SET `pic`='".$picname."', `picxy`='".$picxy."' WHERE (id='".(int)$id."')"; DB($q); } else { $class="ErrorDiv";	}
		} $_SESSION["Msg1"]="<div class='".$class."'>".$msg."</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}

	# save cors
	if (isset($P["savebutton"])) {
		$picxy=""; $picname=$P["picname"]; foreach ($P["XYS"] as $key=>$val) { $picxy.=$key."=".$val.";"; } $picxy=trim($picxy, ";");
		require("modules/ResizePhoto.php"); $q="UPDATE `".$alias."_lenta` SET `picxy`='".$picxy."', `picauth`='".$P["auth"]."' WHERE (id='".(int)$id."')"; DB($q);
		DB("INSERT INTO `_lentalog` (`link`, `id`, `uid`, `data`, `ip`, `text`) VALUES ('".$alias."', '".$id."', '".$_SESSION['userid']."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', 'Сохранение фотографии (photo): ".$P["auth"]."');");
		$_SESSION["Msg2"]="<div class='SuccessDiv'>".$msg."</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}	
	
	
	// ВЫВОД ПОЛЕЙ И ФОРМ
	$data=DB("SELECT `pic`, `picxy`, `name`, `picauth`, `stat` FROM `".$alias."_lenta` WHERE (`id`='".(int)$id."') LIMIT 1"); 
	if ($data["total"]!=1) { $AdminText=ATextReplace('ItemError', $raz["shortname"]." (".$alias.")", $id); $GLOBAL["error"]=1; } else {
		
	### Загрузка фотографии
	@mysql_data_seek($data["result"], 0); $node=@mysql_fetch_array($data["result"]); if ($node["stat"]==1) { $chk="checked"; }
	$OriginalPic="userfiles/picoriginal/".$node["pic"]; $PreViewPic="userfiles/picintv/".$node["pic"]."?r=".time(); 
	
	
	$AdminText='<h2>Редактирование: &laquo'.$node["name"].'&raquo;</h2>'.$_SESSION["Msg1"];
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";
	$AdminText.="<div class='RoundText'>";
	$AdminText.="<div class='Info'>Вы можете загружать файлы jpg, png, gif до 10М и размером не более 10.000px на 10.000px</div>".$C5;
	$AdminText.="<input name='picname' type='hidden' value='".$node["pic"]."'>";
	$AdminText.="<div title='Нажмите для выбора файла' id='Podstava3' class='Podstava4'><input type='file' id='photo' name='photo' accept='image/jpeg,image/gif,image/x-png' onChange='getFileName();' /></div>";
	$AdminText.="<div id='FileName'></div><div style='float:right;'><input type='submit' name='addbutton' id='savebutton' class='SaveButton' value='Загрузить фотографию'></div>";
	$AdminText.=$C5."</div></form>";
	
	### Параметры фотографии
	if ($node["pic"]!="") { 
		$AdminText.='<h2>Параметры фотографии</h2>'.$_SESSION["Msg2"]."<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'>";
		### Получаем предыдущие координаты обрезки фотографий из БД
		$xy=array(); $i=0; $tmp=explode(";", $node["picxy"]); foreach ($tmp as $val) { $tmp2=explode("=", $val); $xy[$tmp2[0]]=explode(",", $tmp2[1]); }
		### Создаем поля с областями резки (только для тех определений $AutoPicPaths (Settings.php), где точно известны ширина и высота)
		foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) { if ($path=="picintv") { 
		$i++; list($sw, $sh)=explode("-", $size); if ($sw!=0 && $sh!=0) { $sk=$sw/$sh; $cs=array(); $cs=$xy[$path]; list($ow, $oh)=getimagesize("../".$OriginalPic);
		$AdminText.="<div class='CropAreaDiv".($i%2)."'><h2>$path (".$sw."px на ".$sh."px)</h2><img src='/".$OriginalPic."' id='pic-".$path."' style='width:355px;'></div>";
		$AdminText.="<input id='inp-".$path."' name='XYS[".$path."]' type='hidden' value='".$cs[0].",".$cs[1].",".$cs[2].",".$cs[3]."'>";
		$AdminText.="<script>$(document).ready(function(){ $('#pic-".$path."').imgAreaSelect({ aspectRatio:'".$sw.":".$sh."',
		imageWidth:".$ow.", imageHeight:".$oh.", x1:".$cs[0].",y1:".$cs[1].",x2:".$cs[2].",y2:".$cs[3].", onSelectEnd: function (img, selection) { EndSelect('".$path."', selection); } }); });</script>";
		if ($i%2==0) { $AdminText.=$C; }}}} 
		$AdminText.="<input name='picname' type='hidden' value='".$node["pic"]."'>";
		$AdminText.=$C10."<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить настройки'></div>";
	} 


	// ПРАВАЯ КОЛОНКА
	$AdminRight="<br><br>
	<div class='RoundText'><table><tr class='TRLine'><td class='CheckInput'><input type='checkbox' id='RS-".$id."-".$alias."_lenta' ".$chk."></td><td><b>Опубликовано</b></td></tr></table></div>
	<div class='SecondMenu'><a href='?cat=".$alias."_edit&id=".$id."'>Содержание</a></div><div class='SecondMenu2'><a href='?cat=".$alias."_photo&id=".$id."'>Фотография</a></div>
	<br><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div><br><br></form>";
	if ($node["pic"]!="") { $AdminRight.=$C10.$C10.$C10."<h2>Пример превью:</h2>".$C5; $AdminRight.="<img src='/".$PreViewPic."' style='width:220px;'>"; }
		
	}}
}
$_SESSION["Msg1"]="";
$_SESSION["Msg2"]="";
?>