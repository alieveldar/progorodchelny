<?
### МЕНЮ САЙТА
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {

$data=DB("SELECT * FROM `_pages` WHERE (`module`='strochki') LIMIT 1");
@mysql_data_seek($data["result"], 0); $pg=@mysql_fetch_array($data["result"]); $table=$pg["link"]."_users";

### Получаем данные страницы в $user;
$data=DB("SELECT * FROM `$table` WHERE (id='".$id."') LIMIT 1");
if ($data["total"]!=1) { 
	### Запись не найдена	
	$AdminText=ATextReplace('Item-Module-Error', $id, $table); $GLOBAL["error"]=1;
} else {
	### Запись найдена
	@mysql_data_seek($data["result"], 0); $user=@mysql_fetch_array($data["result"]);

	// СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
	$P=$_POST;
	if (isset($P["addbutton"])) {
		$res=DB("UPDATE `".$table."` SET `login`='".$P["login"]."', `pass`='".$P["pass"]."', `name`='".$P["name"]."', `phone`='".$P["phone"]."' WHERE (id='".$id."')");
		$_SESSION["Msg"]="<div class='SuccessDiv'>Пользователь сохранен!</div>"; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
	}
	
// ФОРМА ДОБАВЛЕНИЯ
	$AdminText='<h2>Редактирование пользователя</h2>'.$_SESSION["Msg"];
	
	$AdminText.="<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post' onsubmit='return JsVerify();'>";
	$AdminText.="<div class='RoundText' id='Tgg'><table>".'<tr class="TRLine0"><td style="width:25%;"></td><td style="width:75%;"></td></tr>
		<tr class="TRLine0"><td class="VarText">Логин<star>*</star></td><td class="LongInput"><input name="login" id="login" type="text" value=\''.$user["login"].'\'></td></tr>
		<tr class="TRLine1"><td class="VarText">Пароль<star>*</star></td><td class="LongInput"><input name="pass" id="pass" type="text" value=\''.$user["pass"].'\'></td></tr>
	    <tr class="TRLine0"><td class="VarText">Имя<star>*</star></td><td class="LongInput"><input name="name" id="name" type="text" value=\''.$user["name"].'\'></td></tr>
    	<tr class="TRLine0"><td class="VarText">Телефон<star>*</star></td><td class="LongInput"><input name="phone" id="phone" type="text" value=\''.$user["phone"].'\'></td></tr>
	'."</table><div class='C5'></div><div class='C15'></div><div class='CenterText'><input type='submit' name='addbutton' id='addbutton' class='SaveButton' value='Сохранить'></div></div></form>";
	
	$AdminRight="";
}} $_SESSION["Msg"]="";

?>