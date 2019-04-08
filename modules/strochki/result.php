<?
$GLOBAL["sitekey"]=1; $G=$_GET; @require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php"; $data=DB("SELECT `link`,`sets` FROM `_pages` WHERE (`module`='strochki' && `stat`='1') LIMIT 1");
@mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $SETS=explode("|", $ar["sets"]); $newsig=strtoupper($G["SignatureValue"]); $orderid=(int)$G["InvId"]; $sum="".$G["OutSum"];
$realsig=strtoupper(md5($sum.":".$orderid.":".$SETS[2])); if ($realsig==$newsig) { DB("UPDATE `".$ar["link"]."_pays` SET `stat`='1' WHERE (`id`='".(int)$orderid."') LIMIT 1"); echo "OK".$orderid; }
?>