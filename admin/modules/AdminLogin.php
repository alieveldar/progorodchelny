<?
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	$DATA=$_POST; $Msg='';
	if ($_SESSION['admincount']<5) { 
		if (isset($DATA["loginbtn"]) && $DATA["login"]!='' && $DATA["password"]!='') {
			$l=cutdata($DATA["login"]); $p=md5(cutdata($DATA["password"])); $_SESSION['admincount']++; $_SESSION['adminblock']=time()+(5*60);
			$data=DB("SELECT `id`, `role` FROM `_users` WHERE (`login`='$l' && `pass`='$p' && `role`>'1' && `stat`='1') LIMIT 1");		
			if ($data["total"]==1) { @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $_SESSION['admincount']=0; 
			DB("INSERT INTO `_lentalog` (`link`, `id`, `uid`, `data`, `ip`, `text`) VALUES ('[login]', '0', '".$ar['id']."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', 'Вход в систему администрирования (login)')");
			$_SESSION['userrole']=(int)$ar["role"]; $_SESSION['userid']=(int)$ar["id"]; @header("location: ".$_SERVER["REQUEST_URI"]); exit();
			} else { $Msg="<div class='ErrorDiv'>".ATextReplace('LoginErrorEnter')."</div>"; }
		}
		$AdminLogin="<div class='SystemInfo'><h1>Авторизация</h1><div class='C5'></div>
		<form action='".$_SERVER["REQUEST_URI"]."' enctype='multipart/form-data' method='post' onsubmit='return LoginInput();'>".$Msg."
		<div class='Left190 AuthForm' style='margin-right:20px;'> Логин<br /><input type='text' name='login' id='login' autofocus></div>
		<div class='Left190 AuthForm'> Пароль<br /><input type='password' name='password' id='password'></div>
		<div class='C10'></div><div class='CenterText AuthSbm'><input type='submit' name='loginbtn' value='Войти'></div></form></div>";	
	} else {
		if ($_SESSION['adminblock']<time()) { $_SESSION['admincount']=0; $_SESSION['adminblock']=0; @header("location: /admin/"); exit(); }
		$AdminLogin="<div class='SystemAlert'>".ATextReplace('LoginErrorText', $_SESSION['adminblock']-time())."</div>";
	}
}
?>