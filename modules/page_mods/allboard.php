<?
if($GLOBAL["USER"]["role"] > 2){
	if (isset($_SESSION['Data']["sendbutton"])) {
		$P = $_SESSION['Data'];
		$F = $_SESSION['Files'];
		if (isset($F["advert"]["name"]) && $F["advert"]["name"]!="") {
			if($F["advert"]["ext"] != 'txt') {
				$msg="<div class='ErrorDiv'>Недопустимый формат файла</div>";
				unlink($F['advert']['file']);
			}
			else{
				$advertFile = file($F['advert']['file']);
				unlink($F['advert']['file']);
				
				$advRubs = array();
				$rubSubs = array();
				$adverts = array();
				
				$rubKey = 0;
				$rubSubKey = 0;
				
				foreach ($advertFile as $line){
					#$line = ucs2_to_utf8($line, 'LE');
					if (preg_match('#Rubrika#', $line)){
						$rubKey++;
						$rubSubKey = 0;
						$advRubs[$rubKey] = AdvTagsClear($line);
					} else if (preg_match('#Podrubrika#', $line)){
						$line = AdvTagsClear($line);
						if (!empty($line)){
							$rubSubKey++;
							$rubSubs[$rubKey][$rubSubKey] = $line;
						}
					} else if (preg_match('#Obyava#', $line)){
						$adverts[$rubKey][$rubSubKey][] = AdvTagsClear($line);
					}
				}
				
				asort($advRubs);
				
				$html = '<ul class="AdvertsRub">'."\n";
				foreach ($advRubs as $rubKey=>$rubr){
					$html .= '<li class="ParentAdv"><a href="javascript:void(0)">'.$rubr.'</a>'."\n";
					if (isset($rubSubs[$rubKey])){
						asort($rubSubs[$rubKey]);
						$html .= '<ul class="AdvertsSub">'."\n";						
						foreach ($rubSubs[$rubKey] as $rubSubKey=>$rubrSub){
							foreach ($adverts[$rubKey][$rubSubKey] as $advert){
								$html .= '<li class="ChildAdv"><u>'.$rubrSub.'</u> '.str_replace(array(",",".",")","(","-",";",":"), array(", ",". ",") "," ("," - ","; ",": "), $advert).'</li>'."\n";
							}
						}
						$html .= '</ul>';
					} else if (isset($adverts[$rubKey][0])) {
						asort($adverts[$rubKey][0]);
						$html .= '<ul class="AdvertsSub">'."\n";
						foreach ($adverts[$rubKey][0] as $advert){
							$html .= '<li>'.str_replace(array(",",".",")","(","-",";",":"), array(", ",". ",") "," ("," - ","; ",": "), $advert).'</li>'."\n";
						}
						$html .= '</ul>'."\n";
					}
					$html .= '</li>'."\n";
				}
				$html .= '</ul>';
				
				$handle = fopen($ROOT.'/template/allboard.html', 'w');
				fwrite($handle, $html);
				fclose($handle);
				$msg="<div class='SuccessDiv'>Файл загружен успешно</div>";
			}
		} 
		SD();
	}	
}

$text=$msg.file_get_contents($ROOT.'/template/allboard.html');

if($GLOBAL["USER"]["role"] > 2){
	$text.=$C20.'<h4>Загрузка объявлений</h4>';
	$text.=$C5."<form action='/modules/SubmitForm.php?bp=".$RealPage."' enctype='multipart/form-data' method='post'>";
	$text.="<div class='RoundText'>";
		$text.="<div title='Нажмите для выбора файла' class='BrowseButton'>Выбрать файл с компьютера<input type='file' id='advert' name='advert' accept='image/jpeg,image/gif,image/x-png' onChange='getFileName();' /></div>";		
		$text.="<div id='FileName'></div><div style='float:right;'><input type='submit' name='sendbutton' id='savebutton' class='SaveButton' value='Загрузить файл'></div>";
		$text.=$C5."<div class='Info' align='center'>Вы можете загружать файлы txt</div>";
	$text.="</div></form>";	
}
	
$Page["Content"]=$text."<div class='WhiteBlock'>".$Page["Content"]."</div>";

function AdvTagsClear($t){
	return trim(strip_tags($t));
}
?>