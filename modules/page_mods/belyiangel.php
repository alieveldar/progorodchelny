<?
$Page["Content"]=""; $text="";

if (isset($_SESSION['Data']["sendbutton"])) {
	$P = $_SESSION['Data']; $name=jscut($P['name']); $ptext=jscut($P['text']); if ($ptext!=""){ $msg = '<div class="SuccessDiv">Ваш вопрос отправлен.</div>';
	$q="INSERT INTO `_widget_ask` (`link`,`pid`,`data`,`askname`,`asktext`)VALUES('belyiangel','1','".time()."','".$name."','".$ptext."');"; DB($q); SD(); }
}

if (isset($_SESSION['Data']["savebutton"])) { if ((int)$GLOBAL["USER"]["role"]>1) {
	foreach($_SESSION['Data']["ask"] as $key=>$v){  } $P=$_SESSION['Data']; $F=$_SESSION['Files']["ansava"];
	$q=DB("UPDATE `_widget_ask` SET `asktext`='".$P["ask"][$key]."', `ansname`='".$P["face"][$key]."', `anstext`='".$P["ans"][$key]."' WHERE (`id`=$key)");
	if ($F["name"]!="") {
		list($width, $height)=@getimagesize($F["file"]); $ext=$F["ext"];
		if ($width>100 || $height>100) {
			$kk=$width/$height; $image_v=imagecreatetruecolor(100,100);
			if ($ext=='jpg' || $ext=='jpeg') { $image=imagecreatefromjpeg($F["file"]); }
			if ($ext=='png') { $image=imagecreatefrompng($F["file"]); }
			if ($ext=='gif') { $image=imagecreatefromgif($F["file"]); }
			if ($height >= $width) { $neW=100; $neH=100/$kk; $image_p=imagecreatetruecolor($neW, $neH); imagecopyresampled($image_p, $image, 0, 0, 0, 0, $neW, $neH, $width, $height); $y=($neH - 100)/2;
			imagecopyresized($image_v, $image_p, 0, 0, 0, $y, 100, 100, 100, 100); }
			if ($width > $height) { $neH=100; $neW=100*$kk; $image_p=imagecreatetruecolor($neW, $neH); imagecopyresampled($image_p, $image, 0, 0, 0, 0, $neW, $neH, $width, $height); $x=($neW - 100)/2;
			imagecopyresized($image_v, $image_p, 0, 0, $x, 0, 100, 100, 100, 100); }
			if ($ext=='jpg' || $ext=='jpeg') { imagejpeg($image_v, $F["file"], 90);	}
			if ($ext=='png') { imagepng($image_v, $F["file"]); }
			if ($ext=='gif') { imagegif($image_v, $F["file"]); }
		}
		copy($F["file"], $_SERVER['DOCUMENT_ROOT']."/userfiles/picsquare/".$F["name"].".".$F["ext"]);
		$q=DB("UPDATE `_widget_ask` SET `anspic`='".$F["name"].".".$F["ext"]."' WHERE (`id`=$key)");
	}
} SD(); }

$text="<div style='text-align:center; font-size:19px;'>Задайте нам вопрос:</div>".$C10.$msg;
$text.='<div class="RoundText" id="Tgg"><form action="/modules/SubmitForm.php?bp='.$RealPage.'" enctype="multipart/form-data" method="post"><table><tr class="TRLine0"><td class="VarText" width="5%">Имя или псевдоним</td><td class="LongInput"><input name="name" type="text" style="width:98% !important;"></td></tr><tr class="TRLine1"><td class="VarText" style="vertical-align:top; padding-top:10px;">Текст&nbsp;вопроса</td><td class="LongInput"><textarea name="text" style="height:30px; width:98% !important;"></textarea></td></tr></table>'.$C10.'<div class="CenterText"><input type="submit" name="sendbutton" id="sendbutton" class="SaveButton" value="Задать вопрос"></div></form></div>'.$C30;

$data=DB("SELECT * FROM `_widget_ask` WHERE (`link`='belyiangel' && `pid`='1') ORDER BY `id` DESC"); $text.="<table width='100%' cellpadding='0' cellspacing='0'>";
for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);
if ((int)$GLOBAL["USER"]["role"]>1) { $text.=askadmintext($ar); } else { $text.=askusertext($ar); } endfor;  $text.="</table>";

$Page["Content"].=$text.$node["text"];

// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
function jscut($value){ $value=trim($value); $value=strip_tags($value); $value=str_replace('"',"",$value); $value=str_replace(';',"",$value); $value=str_replace("'","",$value); return $value; }
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
function askusertext($ar) {
if ($ar["anstext"]!="") {
	if ($ar["anspic"]!="") { $av="<img src='/userfiles/picsquare/".$ar["anspic"]."'>"; } else { $av="<img src='/userfiles/avatar/no_photo.jpg'>"; }
	if ($ar["ansname"]!="") { $ansname="<h2>".$ar["ansname"]."</h2>"; } else { $ansname=""; }
	$text.="<tr class='div".$ar["id"]." asktr'>";
		$text.="<td width='1%' class='uavatar'><img src='/userfiles/avatar/no_photo.jpg'></td>";
		$text.="<td width='99%' class='asktext'>&#8212; ".nl2br($ar["asktext"])."</td>";
	$text.="</tr><tr class='div".$ar["id"]." anstr'>";
		$text.="<td width='1%' class='uavatar'>".$av."</td>";
		$text.="<td width='99%' class='asktext'>".$ansname."&#8212; <i>".nl2br($ar["anstext"])."</i></td>";
	$text.="</tr>";
return $text; }}
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
function askadmintext($ar) { global $C5, $RealPage;
	if ($ar["anspic"]!="") { $av="<img src='/userfiles/picsquare/".$ar["anspic"]."'>"; } else { $av="<img src='/userfiles/avatar/no_photo.jpg'>"; }
	$del="<a href='javascript:void(0);' onclick='HidePic(\"".$ar["id"]."\");' style='color:red; padding-top:10px; float:right; display:inline-block;'>Удалить</a>";
	$text.='<form action="/modules/SubmitForm.php?bp='.$RealPage.'" enctype="multipart/form-data" method="post">';
	$text.="<tr class='div".$ar["id"]." asktr'>";
		$text.="<td width='1%' class='uavatar'><img src='/userfiles/avatar/no_photo.jpg'></td>";
		$text.="<td width='99%' class='asktext'><textarea name='ask[".$ar["id"]."]' style='width:700px;' placeholder='Вопрос'>".($ar["asktext"])."</textarea></td>";
	$text.="</tr><tr class='div".$ar["id"]." anstr'><td width='1%' class='uavatar'>".$av."</td>";
		$text.="<td width='99%' class='asktext'><input name='face[".$ar["id"]."]' value='".$ar["ansname"]."' style='width:700px; margin-bottom:7px;' placeholder='Кто отвечает'><br>
		<textarea name='ans[".$ar["id"]."]' style='width:700px; height:100px; margin-bottom:7px;' placeholder='Ответ'>".$ar["anstext"]."</textarea><br>
		Аватарка: <input type='file' name='ansava'>".$C5."<input type='submit' name='savebutton' class='SaveButton' value='Сохранить'>".$del."</td>";
	$text.="</tr></form>";	
return $text; }
?>