<?
### НАСТРОЙКИ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	
	global $pg; $onpage=50; $from=($pg-1)*$onpage; $where=""; $dp=""; $AdminRight=""; $data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
	@mysql_data_seek($data["result"], 0); $page=@mysql_fetch_array($data["result"]);
	
	$table=$page["link"]."_razdels"; $table1=$page["link"]."_objects"; $table2=$page["link"]."_users"; $table3=$page["link"]."_pays";

	$q1="SELECT ".$table3.".*, ".$table3.".id as payid, ".$table1.".dop, ".$table1.".id as objid, ".$table2.".`login`, ".$table2.".`name` as `uname`, ".$table2.".`phone` as `uphone` 
	FROM ".$table3."  LEFT JOIN ".$table1." ON ".$table3.".`oid`=".$table1.".`id` LEFT JOIN ".$table2." ON ".$table2.".`id`=".$table1.".`uid`	
	ORDER BY ".$table3.".`data` DESC  LIMIT $from, $onpage"; $data=DB($q1); $q2="SELECT ".$table3.".id FROM ".$table3; $data2=DB($q2); 
 
 	//echo ceil($data2["total"]/$onpage);
  	$pager=Pager($pg, $onpage, ceil($data2["total"]/$onpage)); 
	
	$AdminText='<h2 style="float:left;">Статистика оплаты</h2><div class="C"></div>';

	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $datan=ToRusData($ar["data"]); $text.='<tr class="TRLine TRLine'.($i%2).'" id="Line'.$ar["id"].'">';
	$price="<b>".$ar["price"]."р.</b>"; $a=explode(",", $ar["sets"]); if ($ar["stat"]==1) { $stat="<B style='font-size:10px; color:green;'>ОПЛАЧЕНО</B>"; } else { $stat="<B style='font-size:9px; color:red;'>ждет оплаты</B>"; } 

	if ((int)$ar["objid"]==0) { $text.='<td class="Act" width="1%" style="white-space:nowrap; font-size:11px; padding:8px; text-align:left;" ><i>'.$datan[4].'<br><span style="font-size:9px; color:#777;">Счет оплаты: '.$ar["payid"].'</span></i></td>';
	} else { $text.='<td class="Act" style="white-space:nowrap; font-size:11px; padding:8px; text-align:left;" ><i>'.$datan[4].'<br><span style="font-size:9px; color:#777;">Объявление: '.$ar["objid"].'<br>Счет оплаты: '.$ar["payid"].'</span></i></td>';}
	
	if ((int)$ar["uid"]!=0) { $text.='<td class="Act" width="1%" style="white-space:nowrap; font-size:11px; padding:8px; text-align:left;" ><i><a href="?cat=strochki_usersedit&id='.$ar["uid"].'">'.$ar["uname"].'</a><br>';
	$text.='<span style="font-size:9px; color:#999;">логин: '.$ar["login"].'</span></i></td>';
	} else { $text.='<td class="Act" width="1%" style="white-space:nowrap; font-size:11px; padding:8px; text-align:left;" ><i>'.$ar["fio"].'<br>[счет без объявления]</i></td>'; }
	
	if ($ar["text"]!="") { $text.="<td class='BigText' style='padding:8px; text-align:left; color:#333; font-size:11px;'>Комментарий плательщика:<br><b>".$ar["text"]."</b></td>";
	} else { $text.="<td class='BigText' style='padding:8px; text-align:left; color:#333; font-size:11px;'>".$ar["dop"]."</td>"; }
	
	$text.="<td class='BigText' style='padding:8px; text-align:left;'>".$price."<div class='C5'></div>".$stat."</td>"; $text.="</tr>"; endfor;

	$AdminText.=$pager."<div class='C15'></div><div class='RoundText' id='Tgg'><table><tr class='TRLineC'><td>Дата и время</td><td>Пользователь</td><td>Объявление</td><td width='1%'>Статус</td></tr>".$text."</table></div>".$pager;
	 
	 
} $_SESSION["Msg"]="";
?>