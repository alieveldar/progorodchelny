<?
### КРОССЛИНКОВКА СТРАНИЦ
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
global $pg; $limit=50; $from=($pg - 1) * $limit;
$AdminRight=""; $data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
if ($data["total"]!=1) { ### Запись не найдена	
$AdminText=ATextReplace('Item-Module-Error', $id, $table); $GLOBAL["error"]=1;
} else { @mysql_data_seek($data["result"], 0); $pg=@mysql_fetch_array($data["result"]); $table=$pg["link"]."_users";
	
	// ЭЛЕМЕНТЫ
	$AdminText.='<h2 style="float:left;">Пользователи: '.$pg["name"].'</h2>'.$_SESSION["Msg"].$C5."<div id='Msg2' class='InfoDiv'>Вы можете редактировать и удалять пользователей</div>";
	$data=DB("SELECT * FROM `".$table."` ORDER BY `name` ASC LIMIT $from, $limit"); $text="";
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); if ($ar["stat"]==1) { $chk="checked"; } else { $chk=""; }
		$info='ViewBlank("Имя: '.$ar["name"].'","Логин: '.$ar["login"].'<div class=C10></div>Пароль: '.$ar["pass"].'<div class=C10></div>Телефон: '.$ar["phone"].'<div class=C></div>");';
		$text.='<tr class="TRLine'.($i%2).'" id="Line'.$ar["id"].'"><td class="CheckInput"><input type="checkbox" id="RS-'.$ar["id"].'-'.$table.'" '.$chk.'></td>';
		$text.="<td class='BigText'>".$ar["name"]." <i>логин: ".$ar["login"]."</i> <i>телефон: ".$ar["phone"]."</i></td>";
		$text.='<td class="Act"><a href="javascript:void(0);" onclick=\''.$info.'\' title="Информация">'.AIco('49').'</a></td>';		
		$text.='<td class="Act"><a href="?cat=strochki_usersedit&id='.$ar["id"].'" title="Править">'.AIco('28').'</a></td>';
		$text.='<td class="Act"> </td>';
		$text.='<td class="Act"><a href="javascript:void(0);" onclick="ItemDelete(\''.$ar["id"].'\', \''.$pg.'\')" title="Удалить">'.AIco('exit').'</a></td>';
		$text.='<td class="Act"><input type="checkbox" id="'.$ar["id"].'" class="selectItem"></td>';
		$text.="</tr>";
	endfor;
	$AdminText.="<div class='RoundText' id='Tgg'><div class='LinkR MultiDel'><a href='javascript:void(0);' onclick='MultiDelete()'>Удалить выбранные</a></div><table>".$text."</table></div>";
	$data=DB("SELECT `id` FROM `".$table."`"); $total=ceil($data["total"] / $limit); $AdminText.= Pager($pg, $limit, $total);
}}

//=============================================
$_SESSION["Msg"]="";
?>