<?
### СВОДНАЯ СТАТИСТИКА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) { $P=$_POST;
	# ============================================================================================================================================================================================================
	$df=$_GET["df"]; $dt=$_GET["dt"]; $GLOBAL["error"]=1; $d1=ToRusData($df); $d2=ToRusData($dt); $tmp=ToRusData(time()); $today=$tmp[2];
	if (isset($P["savebutton"])) { $ar=explode(".", $P["ddata1"]); $sdata1=mktime($P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2]); 
	$ar=explode(".", $P["ddata11"]); $sdata2=mktime($P["ddata21"], $P["ddata31"], $P["ddata41"], $ar[1], $ar[0], $ar[2]); @header("location: ?cat=banners_artstat&df=".($sdata1)."&dt=".($sdata2)); exit();
	} if (!isset($dt) || !isset($df)) { @header("location: ?cat=banners_artstat&df=".(time()-30*24*60*60)."&dt=".(time()+30*24*60*60)); exit(); } $days=round(($dt-$df)/60/60/24)+1;
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post'><a href='javascript:void(0);' onclick='ShowSets();' id='ShowSets' style='float:right;'>Показать дополнительные настройки</a><h2 style='float:left;'>Отчетный период: $d1[2] - $d2[2], дней: $days</h2><div class='C'></div>";
	$AdminText.="<div class='RoundText ShowSets'><table>".'<tr class="TRLine1"><td class="VarName">Начало отчетного периода</td><td class="VarName">Конец отчетного периода</td><td></td><tr><tr class="TRLine0"><td class="DateInput">'.GetDataSet($df).'</td><td class="DateInput">'.GetDataSet($dt, 1).'</td><td width="1%"><input type="submit" name="savebutton" id="savebutton" class="SaveButton" value="Определить период"></td><tr>'."</table></div><div class='C10'></div></form>";
	# ============================================================================================================================================================================================================
	
		
	### Создание строк месяцев и дней
	$ms=array(); for($i=$df; $i<=$dt; $i=$i+86400): $tmp=ToRusData($i); $d=explode(" ", $tmp[2]); $ms[$d[1]]++;	endfor; $m=""; $d=""; $lm=""; for($i=$df; $i<=$dt; $i=$i+86400): $tmp=ToRusData($i); $dp=explode(" ", $tmp[2]); $cm=(date("m", $i))+0; 
	if ($dp[1]!=$lm) { $lm=$dp[1]; $m.="<td colspan='".($ms[$dp[1]])."' class='Moth".$cm."'>$dp[1], $dp[2]</td>"; } if ($tmp[2]==$today) { $d.="<td style='background:#000; color:FFF;' title='Сегодня'>".$dp[0]."</td>"; } else { $d.="<td class='Moth".$cm."' title='$tmp[2]'>".$dp[0]."</td>"; } endfor;
	$calendar="<tr class='InfoZayMoths'>".$m."</tr><tr class='InfoZayDays'>".$d."</tr>";
	
	# ============================================================================================================================================================================================================
	### Список заявок в указанном периоде
	$z=array(); $zid=array(); $q="SELECT `_banners_orders`.*, `_domains`.`name` as `dname`, `companies_items`.`name` as `cname`, `_banners_pos`.`name` as `pname` FROM `_banners_orders` LEFT JOIN `_domains` ON `_domains`.`id`=`_banners_orders`.`did` LEFT JOIN `companies_items` ON `companies_items`.`id`=`_banners_orders`.`cid` LEFT JOIN `_banners_pos` ON `_banners_pos`.`id`=`_banners_orders`.`pid`
	WHERE (((`_banners_orders`.`datafrom`>='$df' && `_banners_orders`.`datafrom`<='$dt') || (`_banners_orders`.`datato`>='$df' && `_banners_orders`.`datato`<='$dt') || (`_banners_orders`.`datafrom`<='$df' && `_banners_orders`.`datato`>='$dt' )) && `_banners_orders`.`stat`='1' && `_banners_orders`.`pid`='5') GROUP BY 1 ORDER BY `_banners_orders`.`zid` DESC"; $data=DB($q);
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $d1=ToRusData($ar['datafrom']); $d2=ToRusData($ar['datato']); if ($d1[2]==$d2[2]) { $razm=$d1[2]; } else { $razm=$d1[2]." - ".$d2[2]; }
	$ar["ztd"]="<a href='?cat=banners_editorder&id=$ar[id]'><b>#$ar[zid]</b></a> : <a href='?cat=companies_edit&id=$ar[cid]'><b>$ar[cname]</b></a>   <span style='font-size:10px;'>$ar[pname], <i>размещение: $razm</i></span>"; $z[$ar["zid"]]=$ar; $zid[]=$ar["zid"]; endfor; 

	### Список баннеров в указанном периоде
	$b=array(); $azid=implode(",", $zid); $lentas=getLentasOnModulesAdm(); $q="";
	foreach ($lentas as $l=>$t) { $q.="(SELECT `$t`.`id`, `$t`.`bid` as `zid`, `$t`.`data`, '$l' as `link` FROM `$t` WHERE (`$t`.`bid`<>0 && `$t`.`data`>='$df' && `$t`.`data`<='$dt')) UNION "; } $q=trim($q, "UNION "); $data=DB($q);
	for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $zd=$ar["zid"]; $dd=date("Y.m.d",$ar["data"]); $b[$zd][$dd]=$ar["link"].";".$ar["id"]; endfor;
	
	### Вывод статистики
	$AdminText.="Всего заявок за указанный период: <b>".count($zid)."</b><div class='C5'></div>";
	$AdminText.="<div class='RoundText'><table>";
	foreach ($z as $zid=>$item) {
		$AdminText.="<tr><td class='InfoZay' colspan='$days'>".$item["ztd"]."</td></tr>";
		$AdminText.=$calendar."<tr class='InfoZayStat'>".DaysRend($item)."</tr>";
		$AdminText.="<tr><td class='InfoZaySpace' colspan='$days'></td></tr>";
	}
	$AdminText.="</table></div>";
	
	$AdminText.="<span>[ • ]</span> - запланированная дата выхода статьи<hr>";
	$AdminText.="<span class='GreenBullet'>[ • ]</span> - статья вышла в день по расписанию заявки<hr>";
	$AdminText.="<span class='BlueBullet'>[ • ]</span> - статья вышла в незапланированный в заявке день<hr>";
	$AdminText.="<span class='RedBullet'>[ • ]</span> - статья не вышла по расписанию заявки<hr>";
}

# ============================================================================================================================================================================================================
# ============================================================================================================================================================================================================
# ============================================================================================================================================================================================================

function DaysRend($item){ global $df, $dt, $b, $cntart; $zd=$item["zid"]; $dats=explode(",",$item["dataart"]); for($i=$df; $i<=$dt; $i=$i+86400): $dd=date("Y.m.d", $i); $cnt=$b[$zd][$dd];

if (in_array($dd, $dats) || $cnt!="") {
	if ($cnt!="" && in_array($dd, $dats)) { $t.="<td class='GreenBullet' title='$cnt'>●</td>";
	} elseif($cnt!="" && !in_array($dd, $dats)) { $t.="<td class='BlueBullet' title='$cnt'>●</td>";
	} elseif($cnt=="" && in_array($dd, $dats) && $i<=time()) { $t.="<td class='RedBullet'>● $cnt</td>";
	} else { $t.="<td>● $cnt</td>"; }
} else { $t.="<td> </td>"; } endfor; return $t; }


function getLentasOnModulesAdm() { $modules=array("lenta"); $q="SELECT `link` FROM `_pages` WHERE (`module` IN ('".implode("','", $modules)."')) LIMIT 50"; $data=DB($q);
for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"],$i); $ar=@mysql_fetch_array($data["result"]); $lentas[$ar["link"]]=$ar["link"]."_lenta"; } return $lentas; }

?>

























