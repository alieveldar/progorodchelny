<?
### КРОССЛИНКОВКА СТРАНИЦ
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	global $pg; $table="_planes"; $onpage=50; $orderby="ORDER BY `data` DESC"; $from=($pg-1)*$onpage;
	$AdminText.='<h2 style="float:left;">События</h2><div class="LinkG" style="float:right;"><a href="?cat=adm_planesadd">Добавить событие</a></div>'.$_SESSION["Msg"];
	
	$data=DB("SELECT `".$table."`.* FROM `".$table."` ".$orderby." LIMIT $from, $onpage"); $text="";
	
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);
		if ($ar["stat"]==1) { $chk="checked"; } else { $chk=""; } $datan=ToRusData($ar["data"]);
		$text.='<tr class="TRLine TRLine'.($i%2).'" id="Line'.$ar["id"].'">';
		$text.='<td class="Act" width="1%" style="white-space:nowrap; font-size:11px; padding:7px;" >'.$datan[4].'</td>';
		$text.="<td class='BigText' style='padding:7px;'><a href='?cat=adm_planeshow&id=".$ar["id"]."'>".$ar["name"]."</a> <i>$ar[auth]</i></td>";	
		$text.='<td class="Act" style="padding:7px;" id="Act'.$ar["id"].'"><a href="javascript:void(0);" onclick="ItemDelete(\''.$ar["id"].'\', \''.$table.'\')" title="Удалить">'.AIco('exit').'</a></td>';
		$text.="</tr>";
	endfor;
	
	$AdminText.="<div class='RoundText' id='Tgg'><table>".$text."</table></div>";
	$data=DB("SELECT `id` FROM `".$table."`"); $AdminText.= Pager($pg, $onpage, ceil($data["total"]/$onpage));
	
	// ПРАВАЯ КОЛОНКА
	$AdminRight="<div class='C20'></div><h2>Выбрать дату календаря</h2><div class='DateInput' style='width:100%;' id='datepicks' ></div>";
	
}

//=============================================
$_SESSION["Msg"]="";
?>