<?
$file="_index-olympicfire"; $VARS["cachepages"]=15;
if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=FireTable(); SetCache($file, $text, ""); }

$Page["Content"].=$C20;
$Page["RightContent"]=$text;

$Page["Content"].='<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script><script type="text/javascript">
        ymaps.ready(function () {
            var myMap = new ymaps.Map("YMapsID", {
                    center: [49.122853, 55.786764],
                    zoom: 9,
                    behaviors: ["default", "scrollZoom"]
                });
                
                myMap.controls.add("zoomControl", { left: 5, top: 5 }).add("typeSelector").add("mapTools", { left: 35, top: 5 });
                ';

$Page["Content"].='ymaps.geoXml.load("http://maps.yandex.ru/export/usermaps/GgTUMiGKPPQvDS2tWhVCPTHAVqP6XAV7/")
                .then(function (res) {
                    var bounds = res.mapState && res.mapState.getBounds();
                    if(bounds) {
                        myMap.setBounds(bounds);
                    }
                    res.geoObjects.each(function (geoObject) {
                        geoObject.options.set({
                        });
                    });
                    myMap.geoObjects.add(res.geoObjects);
                });
        });
</script>
<style type="text/css">#YMapsID { width: 730px; height: 600px; }</style>';



function FireTable() { global $C, $C20, $C10, $C25, $C15; $tables = array(); $onpage=20; $orderby=" order by data DESC";
	$data=DB("SHOW TABLES"); for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $tables[] = $ar[0]; }
	foreach ($tables as $tab) {
		if(!preg_match('/(_lenta)$/', $tab)) continue;
		else {
			list($alias) = explode('_', $tab);
			if(!in_array($alias.'_cats', $tables)) continue;
		}		
		if($query) $query.=" UNION "; $query.="(SELECT `".$tab."`.name, `".$tab."`.pic, `".$tab."`.data, `".$tab."`.id, '".$alias."' as `alias` FROM `".$tab."` WHERE (`".$tab."`.`tags` LIKE '%,132,%' && `$tab`.`stat`=1))";
	}
	$data=DB($query." ".$orderby." LIMIT ".$onpage);
	
	
	if ($data["total"]>0) { $text.="<h2 style='font:16px/22px Georgia;'>Новости эстафеты Олимпийского огня в Казани:</h2>".$C10;
		for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $d=ToRusData($ar["data"]); $pic="";
			if ($i%4==0) { $pic="<a href='/".$ar["alias"]."/view/".$ar["id"]."'><img src='/userfiles/picitem/".$ar["pic"]."' title='".$ar["name"]."' style='margin-bottom:5px; width:240px; border-radius:5px;' border='0' /></a>"; } else { $pic=""; }
			$text.=$pic."<a href='/".$ar["alias"]."/view/".$ar["id"]."' style='font:11px/15px Tahoma;'><u>".$ar["name"]."</u></a><div style='font:11px/14px Cuprum; color:#777; margin-top:5px; margin-bottom:10px; padding-bottom:11px; border-bottom:1px dashed #777;'>".$d[0].$C5."</div>".$C5;
		}
	}
 	return (array($text, $C));
}
?>