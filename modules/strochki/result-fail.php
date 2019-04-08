<?
$GLOBAL["sitekey"]=1; $G=$_GET; @require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php"; $data=DB("SELECT `link`,`sets` FROM `_pages` WHERE (`module`='strochki' && `stat`='1') LIMIT 1"); 
@mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $SETS=explode("|", $ar["sets"]); @header("location: /".$ar["link"]."/result/fail"); exit();
?>