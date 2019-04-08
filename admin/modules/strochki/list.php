<?
### НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	global $pg; $onpage=50; $from=($pg-1)*$onpage; $where=""; $dp=""; $AdminRight=""; $data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
	@mysql_data_seek($data["result"], 0); $pag=@mysql_fetch_array($data["result"]); $table=$pag["link"]."_razdels"; $table1=$pag["link"]."_objects"; $table2=$pag["link"]."_users"; $table3=$pag["link"]."_pays";

	$q1="SELECT ".$table1.".*, ".$table.".`name` as `rname`, ".$table2.".`login`, ".$table2.".`name` as `uname`, ".$table2.".`phone` as `uphone`, ".$table3.".`id` as `payid`, ".$table3.".`price` as `tprice`, ".$table3.".`stat` as `paystat` 
	FROM ".$table1." LEFT JOIN ".$table3." ON ".$table3.".`oid`=".$table1.".`id`	LEFT JOIN ".$table2." ON ".$table2.".`id`=".$table1.".`uid` LEFT JOIN ".$table." ON ".$table.".`id`=".$table1.".`rid` 
	ORDER BY ".$table1.".`data` DESC  LIMIT $from, $onpage"; $data=DB($q1);
	$q2="SELECT ".$table1.".id FROM ".$table1; $data2=DB($q2); $pager=Pager($pg, $onpage, ceil($data2["total"]/$onpage)); 
	$AdminText='
	<h2 style="float:left;">Список объявлений</h2>
	';
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $datan=ToRusData($ar["data"]); $text.='
	<tr class="TRLine TRLine'.($i%2).'" id="Line'.$ar["id"].'">';
	$price="<b>".$ar["tprice"]."р.</b>"; $a=explode(",", $ar["sets"]); if ($ar["paystat"]==1) { $stat2="<B style='color:green;'>ОПЛАЧЕНО</B>"; } else { $stat2="<B style='color:red;'>ждет оплаты</B>"; } 
	if ($ar["paystat"]==1) { $stat="<B style='font-size:10px; color:green;'>ОПЛАЧЕНО</B>"; } else { $stat="<B style='font-size:9px; color:red;'>ждет оплаты</B>"; } if ($a[0]==1){ $d1="<B style='color:green;'>ДА</B>"; } else { $d1="нет"; } 
	if ($a[1]==1){ $d2="<B style='color:green;'>ДА</B>"; } else { $d2="нет"; } if ($a[2]==1){ $d3="<B style='color:green;'>ДА</B>"; } else { $d3="нет"; } if ($a[3]==1){ $d4="<B style='color:green;'>ДА</B>"; } else { $d4="нет"; }
	$ds=explode(",", $ar["datas"]); 
			
	
$obj=str_replace(array("\r\n", "\r", "\n", "'"), "", "<h2 style='margin:0; padding:10px 0 0 0;'>Пользователь ID=".$ar["uid"]."</h2>Имя: <b>".$ar["uname"]."</b>  ,  Логин: <b>".$ar["login"]."</b>  ,  Телефон: <b>".$ar["uphone"]."</b><div class='C20' style='width:700px;'></div><h2 style='margin:0; padding:10px 0 0 0;'>Объявление ID=".$ar["id"]." , Счет на оплату ID=".$ar["payid"]."</h2>Сумма: ".$price."<br>Статус: <b>".$stat2."</b><br>Создано: <b>".$datan[1]."</b><br>Выходов: <b>".count($ds)."</b><br>Текст: <b>".str_replace('"', "`", $ar["text"])."</b><br>Контакт: <b>".$ar["phone"]."</b><br>Раздел: <b>".$ar["rname"]."</b><br>Выходы: <b>".$ar["datas"]."</b><br><div class='C20' style='width:700px;'></div><h2 style='margin:0; padding:10px 0 0 0;'>Дополнительно:</h2>Выделение цветом: <b>".$d1."</b><br>Выделено рамкой: <b>".$d2."</b><br>Выделено жирным шрифтом: <b>".$d3."</b><br>Выделено ЗАГЛАВНЫМИ БУКВАМИ: <b>".$d4."</b><div class='C20' style='width:700px;'></div><h2 style='margin:0; padding:10px 0 0 0;'>Админ инфо:</h2>".$ar["dop"]);

	$text.='<td class="Act" width="1%" style="white-space:nowrap; font-size:11px; padding:8px; text-align:left;" ><i>'.$datan[4].'<br><span style="font-size:9px; color:#777;">Объявление: '.$ar["id"].'<br>Счет оплаты: '.$ar["payid"].'</span></i></td>';
	$text.='<td class="Act" width="1%" style="white-space:nowrap; font-size:11px; padding:8px; text-align:left;" ><i><a href="?cat=strochki_usersedit&id='.$ar["uid"].'">'.$ar["uname"].'</a><br>';
	$text.='<span style="font-size:9px; color:#999;">логин: '.$ar["login"].'<br>телефон: '.$ar["phone"].'</span></i></td>';
	$text.="<td class='BigText' style='padding:8px; text-align:left; color:#333; font-size:11px;'>Текст: ".$ar["text"]."<div class='C5'></div>Контакт: ".$ar["phone"]."<div class='C5'></div>Раздел: ".$ar["rname"]."</td>";
	$text.="<td class='BigText' style='padding:8px; text-align:left;'>".$price."<div class='C5'></div>".$stat."<div class='C5'></div><a href='javascript:void(0);' style='color:#333; font-size:11px;'";
	$text.="onclick='ViewBlank(\"Объявление ".$ar["id"]."\",\"".$obj."\");'>подробно</a></td>"; $text.="</tr>"; endfor;
	
	$AdminText.=$pager."<div class='C15'></div><div class='RoundText' id='Tgg'><table><tr class='TRLineC'><td>Дата и время</td><td>Пользователь</td><td>Объявление</td><td width='1%'>Статус</td></tr>".$text."</table></div>".$pager;
} $_SESSION["Msg"]="";
?>