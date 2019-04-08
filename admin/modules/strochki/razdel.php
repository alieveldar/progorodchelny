<?
### Разделы строчных объявлений
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
$AdminRight=""; $data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
if ($data["total"]!=1) { 
### Запись не найдена	
$AdminText=ATextReplace('Item-Module-Error', $id, $table); $GLOBAL["error"]=1;
} else {
	### Запись найдена
	@mysql_data_seek($data["result"], 0); $pg=@mysql_fetch_array($data["result"]); $table=$pg["link"]."_razdels";	
	$AdminText='<h2 style="float:left;">Разделы объявлений</h2><div style="float:right;" class="LinkG"><a href="javascript:void(0);" onclick="AddNewMenu(0, \''.$table.'\');">Добавить раздел</a></div>'.
	"$C5<div id='Msg2' class='InfoDiv'>Вы можете менять порядок разделов, а так же изменять их название и стоимость</div>"; $items=array();
	$data=DB("SELECT * FROM `".$table."` ORDER BY `rate` DESC"); $items[0]["id"]=0; $items[0]["pid"]=-1; for ($i=1; $i<=$data["total"]; $i++): @mysql_data_seek($data["result"], ($i-1));
	$ar=@mysql_fetch_array($data["result"]); $items[$ar["id"]]=$ar; endfor; $stotal=$data["total"]+1; GetChild(0); $AdminText.="<div class='RoundText' id='Tgg'><table>".$itext."</table></div>";
}}
//=============================================

function GetChild($i, $lvl=-1) {
	global $itext, $items, $mvl; if ($i!=0) { $itext.=HtmlChild($lvl, $i); }
	foreach ($items as $key=>$item) { if ($item["pid"]==$items[$i]["id"]) { $pid=$item["pid"]; if ($mvl[$pid]==0) { $lvl++; $mvl[$pid]=1; } if ($key!=0) { GetChild($key, $lvl); }}}
} 

function HtmlChild($lvl, $idi) {
	global $items, $count, $id, $stotal, $prev, $table, $VARS; $pid=$items[$idi]["pid"]; $count++; if ($items[$idi]["stat"]==1) { $chk="checked"; }
	$spacer="<img src='/admin/images/icons/sp.png' style='width:".($lvl*15)."px;' class='spacer' />";
	$text='<tr class="TRLine'.($count%2).'" id="Line'.$i.'"><td class="CheckInput"><input type="checkbox" id="RS-'.$idi.'-'.$table.'" '.$chk.'></td>';	

	if ($lvl==0) { $text.="<td class='BigText'><B>".$spacer.$items[$idi]["name"]."</B></td>".'<td class="Act"><a href="javascript:void(0);" onclick="AddNewMenu(\''.$idi.'\', \''.$table.'\')" title="Добавить подраздел">'.AIco('11').'</a></td>';
	} else { $text.="<td class='BigText'>".$spacer.$items[$idi]["name"]." <i>стоимость: ".$items[$idi]["price"]." рублей</i></td>".'<td></td>'; }
	
	if ($count!=1) { $text.='<td class="Act"><a href="javascript:void(0);" onclick="ItemUp(\''.$idi.'\', \''.$items[$idi]["pid"].'\', \''.$table.'\')" title="Поднять">'.AIco('3').'</a></td>'; } else { $text.='<td></td>'; }
	if ($count<($stotal-1)) { $text.='<td class="Act"><a href="javascript:void(0);" onclick="ItemDown(\''.$idi.'\', \''.$items[$idi]["pid"].'\', \''.$table.'\')" title="Опустить">'.AIco('4').'</a></td>'; } else { $text.='<td></td>'; }
	
	$edit="ItemEdit('".$idi."','".$items[$idi]["pid"]."','".$items[$idi]["name"]."','".$items[$idi]["price"]."','".$table."')"; $text.='<td class="Act"><a href="javascript:void(0);" onclick="'.$edit.'" title="Править">'.AIco('28').'</a></td>';
	$text.='<td class="Act">  </td><td class="Act"><a href="javascript:void(0);" onclick="ItemDelete(\''.$idi.'\', \''.$items[$idi]["pid"].'\', \''.$table.'\')" title="Удалить">'.AIco('exit').'</a></td>'; $text.="</tr>"; return $text;
}
$_SESSION["Msg"]="";
?>