<?
	$UserAuthLine=""; $UserAuthBox=""; $AutoGarage="";
	if (!isset($_SESSION['userid'])) { $_SESSION['userid']=0; }
	if (!isset($_SESSION['userreferer'])) { $_SESSION['userreferer']=$_SERVER['HTTP_REFERER']; }
	
	//$_SESSION['userid']=0; 
	//unset($_SESSION["UserSetsSiteS"]);

	$GLOBAL["Providers"]="vkontakte,facebook,twitter,google,mailru,odnoklassniki,yandex";	
	$UserSetsSite=array();
	$mailtext="Требуется подтверждение электронного адреса";
	#0 Разрешить регистрацию пользователей		
	#1 Требовать подтверждение E-mail
	#2 Разрешить регистрацию через соц. сети
	#3 Разрешить комментарии к материалам
	#4 Разрешить комментарии от анонимов
	#5 Запрашивать у анонимов CAPTCHA
	#6 Разрешить подписи в комментариях
	#7 Разрешить вложения в комментариях материалов
	
	### Настройки пользователей
	if (!isset($_SESSION["UserSetsSiteS"])) { $data=DB("SELECT `sets` FROM `_pages` WHERE (`module`='users') LIMIT 1"); if ($data["total"]==1) { @mysql_data_seek($data["result"],0); $ar=@mysql_fetch_array($data["result"]); $_SESSION["UserSetsSiteS"]=$ar["sets"]; }}
	$UserSetsSite=explode("|", $_SESSION["UserSetsSiteS"]);
	
	### Настройки форм: Авторизация
	# $FormsUserLogin="<h3>Авторизация по логину и паролю ".$VARS["mdomain"]."</h3>".$C5."<div class='UserLogin' id='UserForm1'><div id='UserLoginMsg'></div>";
	# $FormsUserLogin.="<input type='text' placeholder='Логин' id='UserLoginLogin' class='UserLoginInput' /><input id='UserLoginPassword' type='password' placeholder='Пароль' class='UserLoginInput' />";
	# $FormsUserLogin.="<a href='javascript:void(0);' onclick=UserLoginFunc('UserForm1');>Войти</a></div>";

	### Настройки форм: Регистрация
	# $FormsUserRegister="<h3>Регистрация пользователя ".$VARS["mdomain"]."</h3>".$C5."<div id='UserLoginMsg'></div><div class='UserLogin'>";
	# $FormsUserRegister.="<input id='UserRegLogin' type='text' placeholder='Новый логин' class='UserLoginInput' style='width:350px;' /><star>*</star>".$C5;
	# $FormsUserRegister.="<input id='UserRegNick' type='text' placeholder='Ник - так будут вас знать на сайте' class='UserLoginInput' style='width:350px;' /><star>*</star>".$C5;
	# $FormsUserRegister.="<input id='UserRegPassword' type='text' placeholder='Новый пароль' class='UserLoginInput' style='width:350px;' /><star>*</star>".$C5;
	# if ($UserSetsSite[1]==1) { $FormsUserRegister.="<input id='UserRegEmail' type='text' placeholder='Ваш E-mail' class='UserLoginInput' style='width:350px;' /><star>*</star>".$C."<div class='Info'>".$mailtext."</div>".$C5; }
	# $FormsUserRegister.=$C5."<a href='javascript:void(0);' onclick='UserRegisterFunc();'>Зарегистрироваться</a></div>";


	
	if ($UserSetsSite[2]==1) {
		$URL=rawurlencode("http://".$RealHost."/modules/standart/LoginSocial.php?back=http://".$RealHost."/".$RealPage);
		#$FormsUserLogin.="<div class='C10'></div><h3>Авторизация через социальные сети</h3>".$C5;
		$FormsUserLogin.="<div id='uLogin1' x-ulogin-params='display=panel&fields=first_name,last_name,photo&providers=".$GLOBAL["Providers"]."&redirect_uri=".$URL."'></div>";
		$FormsUserLogin.="<div class='C15'></div><div class='Info'><b style='color:red;'>Внимание!</b> Регистрируясь и совершая любые действия на сайте, вы автоматически соглашаетесь с <a href='/agreement'><u><b>Пользовательским соглашением</b></u></a>. Если вы не согласны с <a href='/agreement'>Cоглашением</a> или с отдельными его пунктами, пожалуйста, покиньте сайт.</div>";
		$FormsUserLogin.="<div class='C15'></div><div class='Info'>Социальные сети не передают нам ваши логин и пароль, мы получаем только имя и аватар пользователя.</div>";
		#$FormsUserRegister.="<div class='C10'></div><h3>Регистрация через социальные сети</h3>".$C5;
		$FormsUserRegister.="<div id='uLogin3' x-ulogin-params='display=panel&fields=first_name,last_name,photo&providers=".$GLOBAL["Providers"]."&redirect_uri=".$URL."'></div>";
		$FormsUserRegister.="<div class='C15'></div><div class='Info'><b style='color:red;'>Внимание!</b> Регистрируясь и совершая любые действия на сайте, вы автоматически соглашаетесь с <a href='/agreement'><u><b>Пользовательским соглашением</b></u></a>. Если вы не согласны с <a href='/agreement'>Cоглашением</a> или с отдельными его пунктами, пожалуйста, покиньте сайт.</div>";
		$FormsUserRegister.="<div class='C15'></div><div class='Info'>Социальные сети не передают нам ваши логин и пароль, мы получаем только имя и аватар пользователя.</div>";
	}
	
	$FormsUserLogin.="<div class='C'></div>"; $FormsUserRegister.="<div class='C'></div>";

	### Данные пользователя
	if ($UserSetsSite[0]==1) {
		$GLOBAL["log"].="<i>Пользователи</i>: данный функционал включен - ";
		if ((int)$_SESSION['userid']==0 && !preg_match('/^m\./', $_SERVER['HTTP_HOST'])) {
			### нет авторизации
			$GLOBAL["log"].="<b>нет авторизации</b><hr>";
			$UserAuthLine.="<div id='UserAuth-Enter' onclick=\"UserAuthEnter('Авторизация', '".$URL."');\">Войти</div>";
			$UserAuthLine.="<div id='UserAuth-Register' onclick=\"UserAuthEnter('Регистрация', '".$URL."');\">Регистрация</div>";
			$UserAuthLine.="<div id='UserAuth-AddNews' onclick=document.location='http://".$VARS["mdomain"]."/add/1/'>Добавить новость</div>";
			$UserAuthBox.="";
			$AutoGarage="<div class='SocA'><h2 style='margin:-2px 0 3px 0;'>Войти через социальную сеть:</h2><div id='uLogin1' x-ulogin-params='display=panel&fields=first_name,last_name,photo&providers=".$GLOBAL["Providers"]."&redirect_uri=".$URL."'></div></div>";
		} else if ((int)$_SESSION['userid']==0 && preg_match('/^m\./', $_SERVER['HTTP_HOST'])) {
			### нет авторизации
			$URL=rawurlencode("http://".$RealHost."/modules/standart/LoginSocial.php?back=http://".$RealHost."/".$RealPage);
			$GLOBAL["log"].="<b>нет авторизации</b><hr>";
			$UserAuthLine.="<div id='uLogin1' x-ulogin-params='display=panel&fields=first_name,last_name,photo&providers=".$GLOBAL["Providers"]."&redirect_uri=".$URL."'></div>";
			$UserAuthLine.="<div id='UserAuth-AddNews' onclick=document.location='http://".$VARS["mdomain"]."/add/1/'>Добавить новость</div>"; $UserAuthBox.="";
			$AutoGarage="<div class='SocA'><h2 style='margin:-2px 0 3px 0;'>Войти через социальную сеть:</h2><div id='uLogin1' x-ulogin-params='display=panel&fields=first_name,last_name,photo&providers=".$GLOBAL["Providers"]."&redirect_uri=".$URL."'></div></div>";			
		} else {
			### авторизован
			$GLOBAL["log"].="<b>авторизован [".$_SESSION['userid']."]</b><hr>";
			$data=DB("SELECT `id`,`nick`,`avatar`,`stat`,`role` FROM `_users` WHERE (`id`='".(int)$_SESSION['userid']."') LIMIT 1");
			if ($data["total"]==1) {@mysql_data_seek($data["result"],0); $GLOBAL["USER"]=@mysql_fetch_array($data["result"]);
				if ($GLOBAL["USER"]["stat"]!=1) { @header("Location: /users/exit/"); exit(); }
				
				### Трекер комментариев
				$res=DB("SELECT `pid` FROM `_tracker` WHERE (`uid`='".(int)$_SESSION['userid']."' && `stat`='1')"); $ups=$res["total"];
				$upslast=$res["total"]; if ((int)$ups>0) { $ups="<i>".(int)$ups."</i>"; } else { $ups="<b>".(int)$ups."</b>"; }
				
				### Аватар
				if (is_file($_SERVER['DOCUMENT_ROOT']."/".$GLOBAL["USER"]["avatar"])  && $GLOBAL["USER"]["avatar"]!="" && $GLOBAL["USER"]["avatar"]!="/") { 
					$avatar="<a target='_blank' href='/users/garage/'><img src='/".$GLOBAL["USER"]["avatar"]."'></a>";
				} else {
					$avatar="<a target='_blank' href='/users/garage/'><img src='/userfiles/avatar/no_photo.jpg'></a>";
				}
				
				### Вывод информации
				$UserAuthLine.="<div class='UsersLineLogined'>";
					$UserAuthLine.="<a href='/users/view/".(int)$_SESSION['userid']."'><u><b>".$GLOBAL["USER"]["nick"]."</b></u></a>|";
					$UserAuthLine.="<a href='/users/mylist/' id='TrackerCont'>Подписки: ".$ups."</a><input id='tracker-lst' type='hidden' value='".(int)$upslast."' />|";
					$UserAuthLine.="<a href='/users/mysets/'>Настройки</a>|";
					$UserAuthLine.="<a href='http://".$VARS["mdomain"]."/add/1/'>Добавить новость</a>|";
					$UserAuthLine.="<a href='/users/exit/'>Выход</a>";
				$UserAuthLine.="</div>";
				
				$AutoGarage="<div class='UsersAutoLogined'><div class='UsersAvatar'>".$avatar."</div><div class='UsersInform'>";
					$AutoGarage.="<a href='/users/garage/' class='name' title='Мои настройки'>".$GLOBAL["USER"]["nick"]."</a>";
					$AutoGarage.="<a href='/users/exit/' class='exit'><img src='/template/proauto/exit.png' border='none' title='Выйти' /></a>";
					//$AutoGarage.="<div class='C5'></div>";
					//	$AutoGarage.="<a href='/users/friends/' class='link'>Друзья</a>";
					//	$AutoGarage.="<a href='/users/notes/' class='link'>Заметки</a>";
					//	$AutoGarage.="<a href='/users/autoclub/' class='link'>Автоклуб</a>";
					//	$AutoGarage.="<a href='/live' class='link'>Форум</a>";
					$AutoGarage.="<div class='C5'></div>";
					$AutoGarage.="<a href='/users/mylist/' id='TrackerCont'>Подписки: ".(int)$ups."</a><input id='tracker-lst' type='hidden' value='".(int)$upslast."' />";
					//$AutoGarage.=" &#8230; <a href='/users/msgcenter/' id='MsgerCont'>Сообщения: ".(int)$upm."</a><input id='messenger-lst' type='hidden' value='".(int)$upmlast."' />";
					$AutoGarage.=" &#8230; <a href='/users/mysets/'>Настройки</a>";
				$AutoGarage.="</div><div class='C'></div></div>";
				
				$data=DB("UPDATE `_users` SET `lasttime`='".time()."' WHERE (`id`='".(int)$_SESSION['userid']."') LIMIT 1");
			} else {
				$_SESSION['userid']=0; @header("Location: /users/lostid/"); exit();
			}
		}
		
		
		
		$Page["UserAuth"]=$UserAuthLine;
		$Page["UserAuthBox"]=$UserAuthBox;		
		$Page["AutoGarage"]=$AutoGarage;
		
		
	} else {
		$GLOBAL["log"].="<u>Пользователи</u>: данный функционал отключен<hr>";
	}

function GetUserAuthForm() {
	global $GLOBAL, $FormsUserLogin; $text=""; if ($GLOBAL["USER"]["id"]==0) { $text=$FormsUserLogin; } 
	$text=str_replace("UserForm1", "UserForm2", $text);
	$text=str_replace("uLogin1", "uLogin2", $text);
	return $text;		
}


?>