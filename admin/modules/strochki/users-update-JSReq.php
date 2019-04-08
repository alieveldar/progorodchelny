<?
session_start();
if ($_SESSION['userrole']>2) {
	$GLOBAL["sitekey"]=1;
	@require "../../../modules/standart/DataBase.php";
	//@require "../../../modules/standart/Settings.php";
	@require "../../../modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");
	// полученные данные ================================================
	
	$R=$_REQUEST;
	$item=(int)$R["id"];
	$items=$R["id"];
	$pg=$R["pg"];
	$table="advertise_users";
	$limit=50;
	$from=($pg - 1) * $limit;
	
		
	// операции =========================================================
	if ($R["act"]=="DEL") {
		DB("DELETE FROM `".$table."` WHERE (`id` IN (".$items."))");
	}

	// отправляемые данные ==============================================
	/*
	$data=DB("SELECT * FROM `".$table."` ORDER BY `name` ASC LIMIT $from, $limit"); $text="";
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); if ($ar["stat"]==1) { $chk="checked"; } else { $chk=""; }
		$info='ViewBlank("Имя: '.$ar["name"].'","Логин: '.$ar["login"].'<div class=C10></div>Пароль: '.$ar["pass"].'<div class=C10></div>Телефон: '.$ar["phone"].'<div class=C></div>");';
		$text.='<tr class="TRLine'.($i%2).'" id="Line'.$i.'"><td class="CheckInput"><input type="checkbox" id="RS-'.$ar["id"].'-'.$table.'" '.$chk.'></td>';
		$text.="<td class='BigText'>".$ar["name"]." <i>логин: ".$ar["login"]."</i> <i>телефон: ".$ar["phone"]."</i></td>";
		$text.='<td class="Act"><a href="javascript:void(0);" onclick=\''.$info.'\' title="Информация">'.AIco('49').'</a></td>';		
		$text.='<td class="Act"><a href="?cat=strochki_usersedit&id='.$ar["id"].'" title="Править">'.AIco('28').'</a></td>';
		$text.='<td class="Act"> </td>';
		$text.='<td class="Act"><a href="javascript:void(0);" onclick="ItemDelete(\''.$ar["id"].'\', \''.$pg.'\')" title="Удалить">'.AIco('exit').'</a></td>';
		$text.='<td class="Act"><input type="checkbox" id="'.$ar["id"].'" class="selectItem"></td>';
		$text.="</tr>";
	endfor;	
	$AdminText.="<table>".$text."</table>";
	*/
	$result["content"]="ok";
	$GLOBALS['_RESULT']	= $result;
}
?>