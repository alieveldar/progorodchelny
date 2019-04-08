<?php
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	@require_once($ROOT."/modules/standart/ImageResizeCrop.php");
	@require_once($ROOT.'/modules/standart/watermark/watermark.php');
	$loaded=0; $picxy="";
	$max_image_width=10000;
	$max_image_height=10000;
	$max_image_size=10000;
	
	$valid_types=array("gif", "jpg", "png", "jpeg");
	
	$userfile=$_FILES['photo']['tmp_name'];
	$ext=str_replace("jpeg", "jpg", strtolower(substr($_FILES['photo']['name'], 1+strrpos($_FILES['photo']['name'], "."))));
	$picname=$GLOBAL["pic"].".".$ext; 
	if (!is_dir($ROOT."/userfiles/picoriginal")) { mkdir($ROOT."/userfiles/picoriginal", 0777); }
	if (filesize($userfile)>($max_image_size*1024)) { $msg="Файл больше $max_image_size килобайт"; } elseif (!in_array($ext, $valid_types)) { $msg="Файл не является форматом gif, jpg или png!"; } else {
	$size=getimagesize($userfile); if ($size[0]<$max_image_width && $size[1]<$max_image_height) { if (@move_uploaded_file($_FILES['photo']['tmp_name'], $ROOT."/userfiles/picoriginal/".$picname)) {

		# Все загрузилось
		$msg="Фотография успешно загружена на сервер"; $loaded=1;
		
		# Обработка фотографий под все размеры
		foreach ($GLOBAL['AutoPicPaths'] as $path=>$size) {			
			if (!is_dir($ROOT."/userfiles/".$path)) { mkdir($ROOT."/userfiles/".$path, 0777); }
			list($w,$h)=getimagesize($ROOT."/userfiles/picoriginal/".$picname);
			list($sw, $sh)=explode("-", $size);
			
			if ($path!="picoriginal") {
				if($path=="picpreview") {
					resize($ROOT."/userfiles/picoriginal/".$picname, $ROOT."/userfiles/".$path."/".$picname, $sw, $sh);
				} else {					
					$k = min($w / $sw, $h / $sh);
					$x = round(($w - $sw * $k) / 2); $y = round(($h - $sh * $k) / 2);
					$type = crop($ROOT."/userfiles/picoriginal/".$picname, $ROOT."/userfiles/".$path."/".$picname, array($x, $y, round($sw * $k), round($sh * $k)));
					$type = resize($ROOT."/userfiles/".$path."/".$picname, $ROOT."/userfiles/".$path."/".$picname, $sw, $sh);
					$picxy.=$path."=".$x.",".$y.",".round($sw * $k + $x).",".round($sh * $k + $y).";";				
				}			
			}
		}
		
		watermark($ROOT."/userfiles/picoriginal/".$picname);	
		$msg.= $type; $picxy=trim($picxy, ";");
	} else { $msg="Ошибка сервера. Свяжитесь с администратором!"; }} else { $msg="Картинка больше, чем $max_image_width на $max_image_height пикселей!"; }}
} else { $msg="Ошибка сервера. Отказано в доступе!"; }
?>
