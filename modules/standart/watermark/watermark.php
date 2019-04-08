<?php
function watermark($img_file) {
	$offset = 10;

	if (strpos($_GET["cat"], "auto")!==false) { $watermark_file = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/proauto.png';
	} elseif (strpos($_GET["cat"], "ls")!==false) { $watermark_file = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/7ya.png';
	} else { $watermark_file=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/logo.png'; }
	

		

	
	/* обычный watermark */
	$watermark = imagecreatefrompng($watermark_file); list($sx, $sy, $stype) = getimagesize($watermark_file);
	imagecopy($img, $watermark, imagesx($img) - $sx - $offset, imagesy($img) - $sy - $offset, 0, 0, $sx, $sy);
	
	
	
	
	

	/* watermark эксклюзив */
	$exf=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/exclusive.png';			
	if($_POST["exclusive"]==1 && is_file($exf)) {
		$font = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/KomikaTitle.ttf';
		list($w_i, $h_i, $type) = getimagesize($img_file); $types = array('','gif','jpeg','png');
		if (!$w_i || !$h_i) { return 'Невозможно получить длину и ширину изображения'; } $ext = strtolower($types[$type]);
  		if ($ext) {	$func = 'imagecreatefrom'.$ext;	$img = $func($img_file);  } else {	return 'Некорректный формат файла'; }
		$black = imagecolorallocate($img, 0, 0, 0); $c = imagecolorallocatealpha($img, 255, 255, 255, 50); $size = $h_i / 15;
		$box  = imagettfbbox ($size, 0, $font, $watermark); $x = $w_i - abs($box[4] - $box[0]) - $offset;
		$y = $h_i - $offset; imagettftext($img, $size, $angle, $x, $y, $c, $font, $watermark);
		$exc = imagecreatefrompng($exf); list($sx, $sy, $stype)=getimagesize($exf);
		imagecopy($img, $exc, imagesx($img)-$sx-$offset, $offset, 0, 0, $sx, $sy);
		if ($type == 2) { imagejpeg($img, $img_file, 100); } else { $func = 'image'.$ext; $func($img, $img_file); }
	}
	
	/* watermark народня новость */
	$exf=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/publishnews.png';			
	if($_POST["publishnews"]==1 && is_file($exf)) {
		$font = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/KomikaTitle.ttf';
		list($w_i, $h_i, $type) = getimagesize($img_file); $types = array('','gif','jpeg','png');
		if (!$w_i || !$h_i) { return 'Невозможно получить длину и ширину изображения'; } $ext = strtolower($types[$type]);
  		if ($ext) {	$func = 'imagecreatefrom'.$ext;	$img = $func($img_file);  } else {	return 'Некорректный формат файла'; }
		$black = imagecolorallocate($img, 0, 0, 0); $c = imagecolorallocatealpha($img, 255, 255, 255, 50); $size = $h_i / 15;
		$box  = imagettfbbox ($size, 0, $font, $watermark); $x = $w_i - abs($box[4] - $box[0]) - $offset;
		$y = $h_i - $offset; imagettftext($img, $size, $angle, $x, $y, $c, $font, $watermark);
		$exc = imagecreatefrompng($exf); list($sx, $sy, $stype)=getimagesize($exf);
		imagecopy($img, $exc, imagesx($img)-$sx-$offset, $offset, 0, 0, $sx, $sy);
		if ($type == 2) { imagejpeg($img, $img_file, 100); } else { $func = 'image'.$ext; $func($img, $img_file); }
	}

	/* watermark prokazan */
	$exf=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/prokazan.png';			
	if($_POST["ownnews"]==1 && is_file($exf)) {
		$font = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/KomikaTitle.ttf';
		list($w_i, $h_i, $type) = getimagesize($img_file); $types = array('','gif','jpeg','png');
		if (!$w_i || !$h_i) { return 'Невозможно получить длину и ширину изображения'; } $ext = strtolower($types[$type]);
  		if ($ext) {	$func = 'imagecreatefrom'.$ext;	$img = $func($img_file);  } else {	return 'Некорректный формат файла'; }
		$black = imagecolorallocate($img, 0, 0, 0); $c = imagecolorallocatealpha($img, 255, 255, 255, 60); $size = $h_i / 15;
		$box  = imagettfbbox ($size, 0, $font, $watermark); $x = $w_i - abs($box[4] - $box[0]) - $offset;
		$y = $h_i - $offset; imagettftext($img, $size, $angle, $x, $y, $c, $font, $watermark);
		$exc = imagecreatefrompng($exf); list($sx, $sy, $stype)=getimagesize($exf);
		imagecopy($img, $exc, imagesx($img)-$sx-$offset, imagesy($img)-$sy-$offset, 0, 0, $sx, $sy);
		if ($type == 2) { imagejpeg($img, $img_file, 100); } else { $func = 'image'.$ext; $func($img, $img_file); }
	}

	/* watermark хорошие новости */
	$exf=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/goodnewsvz.png';			
	if($_POST["goodnewsvz"]==1 && is_file($exf)) {
		$font = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/KomikaTitle.ttf';
		list($w_i, $h_i, $type) = getimagesize($img_file); $types = array('','gif','jpeg','png');
		if (!$w_i || !$h_i) { return 'Невозможно получить длину и ширину изображения'; } $ext = strtolower($types[$type]);
  		if ($ext) {	$func = 'imagecreatefrom'.$ext;	$img = $func($img_file);  } else {	return 'Некорректный формат файла'; }
		$black = imagecolorallocate($img, 0, 0, 0); $c = imagecolorallocatealpha($img, 255, 255, 255, 60); $size = $h_i / 15;
		$box  = imagettfbbox ($size, 0, $font, $watermark); $x = $w_i - abs($box[4] - $box[0]) - $offset;
		$y = $h_i - $offset; imagettftext($img, $size, $angle, $x, $y, $c, $font, $watermark);
		$exc = imagecreatefrompng($exf); list($sx, $sy, $stype)=getimagesize($exf);
		imagecopy($img, $exc, imagesx($img)-$sx-$offset, imagesy($img)-$sy-$offset, 0, 0, $sx, $sy);
		if ($type == 2) { imagejpeg($img, $img_file, 100); } else { $func = 'image'.$ext; $func($img, $img_file); }
	}


}
?>