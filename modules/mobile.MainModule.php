<?
$SafeMode=0; $IsIndex=0;
if ($SafeMode==1) { error_reporting(E_ALL & ~E_NOTICE); } // 0-рабочий режим, 1-режим отладки с выводом логов

// Переменные шаблона (В HTML файле шаблона подставляются значения этих переменных) ========================================================================
// Например: если в index.html поместить "$Caption" эта строка заменится на значение переменной $Page["Caption"] ===========================================
// Так же такое же замещение идет с переменными, определенными через систему управления: "Основные настройки" и "Параметры сайта" (массив VARS) ============
$Page=array();  # В этом массиве хранится весь контент (например: $Page["Caption"], $Page["Content"], $Page["Title"] массив должен быть доступен в функциях модулей)

$VarsToHtml = array(
	// Стандартные переменные - должны определяться в модулях, вывод в "Заполнение шаблона сайта"
	"Заголовок страницы (H1)"						=> "Caption", 		// формируется в модулях
	"Заголовок страницы (title)"					=> "Title", 		// формируется ниже, перед выводом дизайна
	"Содержание страницы"							=> "Content", 		// формируется в модулях
	"Ключевые слова (keywords)"						=> "KeyWords", 		// формируется ниже, перед выводом дизайна
	"Описание страницы (description)"				=> "Description", 	// формируется ниже, перед выводом дизайна
	"Дочерние страницы"								=> "ChildPages", 	// формируется ниже, только для статичных страниц.
	"Авторизация пользователей"						=> "UserAuth", 		// формируется в UserLogin.php
	"Форма авторизации"								=> "UserForm", 		// формируется в UserLogin.php
	// пользовательские переменные - должны определяться в модулях, выводятся в "Заполнение шаблона сайта"
	// переменые определяемые через систему администрирования обрабатываются ниже в "Заполнение шаблона сайта"
);

// Список необходимых файлов и модулей =====================================================================================================================
// $JSmodules и $CSSmodules можно пополнять в модулях сайта ================================================================================================
// $JSmodules и $CSSmodules дополняются автоматически при запросе модуля (запрашиваются соответствующие js и css файлы) ====================================
$PHPmodules = array(
	"Работа с кэшем"					=> "modules/standart/Cache.php",
	"Общие функции"						=> "modules/standart/Settings.php",
	"Отправка E-mail"					=> "modules/standart/MailSend.php",
	"Авторизация и данные"				=> "modules/standart/mobile.UsersLogin.php",
	"Комментарии пользователей"			=> "modules/standart/UsersComments.php",
	"Навигация сайта"					=> "modules/standart/CreateMenu.php",
);

$JSmodules = array(
	"Библиотека JQuery"					=> "/modules/standart/js/JQuery.js",
	"Передача данных JsHttpRequest"		=> "/modules/standart/js/JsHttpRequest.js",
	"Мобильный JS сайта"				=> "/modules/standart/js/mobile.MainModule.js",
	"Авторизация ULogin"				=> "http://ulogin.ru/js/ulogin.js",
);

$CSSmodules = array(
	/* "Стандартный Pro.CMS"				=> "/template/standart/standart.css", */
);

// Подключение БД ==========================================================================================================================================
$GLOBAL=array(); $GLOBAL["sitekey"]=1;													// Глобальный массив сайта
@require("modules/standart/DataBase.php"); 												// подключение БД
$GLOBAL["StartTime"]=GetMicroTime(); 													// начало работы скриптов
$RealPage = trim($_SERVER["REQUEST_URI"], "/");											// Текущая страница
$RealHost = str_replace(array('http://', 'www.'), '', $_SERVER['HTTP_HOST']);			// Текущий адрес сайта
// Oпределение данных из URL ===============================================================================================================================
$dir=explode("/", $RealPage);

$link	= $dir[0];
$start	= $dir[1];
$page	= $dir[2];
$id		= $dir[3];
$part	= $dir[4];
$sel	= $dir[5];

if (!isset($link) || $link=="" || $link=="/") { $link = ""; $IsIndex=1; }
if (!isset($start) || $start=="") $s = 0;
if (!isset($page) || $page=="")	$page = 0;
if (!isset($id) || $id=="")		$id = 0;
if (!isset($part) || $part=="")	$part = 0;
if (!isset($sel) || $sel=="")	$sel = 0;
if (!isset($fd) || $fd=="")		$fd = 0;

if ($link=="kazan-news") { $RealPage="kazan-news"; }

// Запрос стандартных модулей PHP ====================================================================================================================================
foreach ($PHPmodules as $name=>$module) {
	if (is_file(trim($module,"/"))) { $GLOBAL["log"].="<i>Подключение PHP</i>: модуль &laquo;".$name."&raquo; подключен<hr>";
	@require($module); } else { $GLOBAL["log"].="<u>Подключение PHP</u>: модуль &laquo;".$name."&raquo; не найден (<b>".$module."</b>)<hr>"; }
}


### НЕУНИВЕРСАЛЬНО!!!
list($MENU["mobile"], $a)=GetCache("site_all_menus-menu6", 0);

if ($GLOBAL["USER"]["role"]>1) {
	$VARS["cachemenu"] = 0;
	$VARS["cacheblock"] = 0;
	$VARS["cachepages"] = 0;
}

// Содержание страницы =======================================================================================================================================
### Массив доменов и субдоменов и Определяем текущий поддомен
$Domains = GetDomains(); $tmp=array(); $tmp=array_flip($Domains); $sd=explode(".", $RealHost); $SubDomain = $tmp[$sd[(sizeof($sd)-3)]];

# Запрос стандартного модуля со общими данными (контент для всего сайта, а не определенного раздела)
if (is_file("modules/mobile.StaticBlocks.php")) { @require("modules/mobile.StaticBlocks.php"); $GLOBAL["log"].="<i>Подключение PHP</i>: общий модуль &laquo;mobile.StaticBlocks.php&raquo; подключен<hr>"; }


if ($IsIndex==1) {
	# Если это главная страница сайта
	$data=DB("SELECT * FROM `_pages` WHERE (`isindex`='1' && `stat`='1' && domain='".(int)$SubDomain."') LIMIT 1");
} else {
	# Если НЕ главная страница сайта
	if (Dbsel($RealPage)!=Dbsel($link)) { $q="((`link`='".Dbsel($RealPage)."' && `module`='') || (`link`='".Dbsel($link)."' && `module`!=''))"; } else { $q="`link`='".Dbsel($link)."'"; }
	$data=DB("SELECT * FROM `_pages` WHERE (".$q." && `stat`='1') LIMIT 1");
}

if ($data["total"]==0){
	@header("HTTP/1.1 404 Not Found"); $Robots='<meta name="robots" content="noindex" />'; $Page404=1;
	$Page["Content"]=@file_get_contents($ROOT."/template/404.html"); $Page["Caption"]="Страница не найдена - 404";
	$GLOBAL["log"].="<u>Содержание</u>: страница &laquo;".$RealPage."&raquo; не найдена<hr>";
} else {
	@mysql_data_seek($data["result"], 0); $node=@mysql_fetch_array($data["result"]); if ($IsIndex==1) { $link=$node["link"]; }

	# Если данный раздел принадлежит другому поддомену
	//if (trim($Domains[$node["domain"]].".".$VARS["mdomain"], ".")!=$RealHost) { @header("location: http://".trim($Domains[$node["domain"]].".".$VARS["mdomain"], ".")."/".$RealPage); exit(); }

	# Генерация контента
	$Page["Node"]		 = $node;
	$Page["Link"]	 	 = $node["link"];
	$Page["KeyWords"]	 = $node["kw"];
	$Page["Description"] = $node["ds"];
	$Page["Data"]		 = $node["data"];
	$Page["Caption"]	 = $node["name"];
	$Page["ShortName"]	 = $node["shortname"];
	$Page["Content"]	 = $node["text"];

	if ($node["module"]=="") {
		#Если найдена статичная страница
		@header ("HTTP/1.0 200 Ok"); $Robots='<meta name="robots" content="index, follow" />'; $Page404=0;
		$GLOBAL["log"].="<i>Содержание</i>: вывод статичной страницы &laquo;<b>".$Page["Link"]."</b>&raquo;<hr>";
		$Page["Tags"] = GetTagList($node["tags"]);
		$Page["ChildPages"] = ChildPages($node["id"]);
	} else {
		$GLOBAL["log"].="<h1>Работа основного модуля: ".$node["module"]."</h1>";
		#Если запрошенная страница выводится через модуль
		if (is_file("modules/".$node["module"]."/mobile/".$node["module"].".php")) {
			/* PHP */ @header ("HTTP/1.0 200 Ok"); $Robots='<meta name="robots" content="index, follow" />'; @require ("modules/".$node["module"]."/mobile/".$node["module"].".php");
			/* JS */  if (is_file("modules/".$node["module"]."/mobile/".$node["module"].".js")) { $JSmodules[$node["name"]]="/modules/".$node["module"]."/mobile/".$node["module"].".js"; }
			/* CSS */ if (is_file("modules/".$node["module"]."/mobile/".$node["module"].".css")) { $CSSmodules[$node["name"]]="/modules/".$node["module"]."/mobile/".$node["module"].".css"; }
			$GLOBAL["log"].="<i>Подключение PHP</i>: модуль &laquo;modules/".$node["module"]."/mobile/".$node["module"].".php&raquo; раздела &laquo;".$link."&raquo; подключен<hr>";
		} else {
			@header("HTTP/1.1 404 Not Found"); $Robots='<meta name="robots" content="noindex" />'; $Page404=1;
			$Page["Content"]=@file_get_contents($ROOT."/template/404.html"); $Page["Caption"]="Страница не найдена - 404";
			$GLOBAL["log"].="<u>Подключение PHP</u>: модуль &laquo;mobile/".$node["module"]."&raquo; раздела &laquo;".$link."&raquo; не найден<hr>";
		}
		$GLOBAL["log"].="<h1>Работа дополнительных модулей</h1>";
	}

//	if (is_file("modules/page_mods/".$node["link"].".php") && $Page404!=1) { @require("modules/page_mods/".$node["link"].".php"); }
//	if (is_file("modules/page_mods/".$node["link"].".js") && $Page404!=1) { $JSmodules["modules/page_mods/".$node["link"].".js"]="/modules/page_mods/".$node["link"].".js"; }
//	if (is_file("modules/page_mods/".$node["link"].".css") && $Page404!=1) { $CSSmodules["modules/page_mods/".$node["link"].".css"]="/modules/page_mods/".$node["link"].".css"; }

	if (is_file("modules/test/".$node["link"]."-".$page.".php") && $Page404!=1) { @require("modules/test/".$node["link"]."-".$page.".php"); }

	# Если для запрошенной страницы есть дополнительные модули
	# Таким образом можно подгружать разные доп. модули для разных разделов, даже если основной модуль для них один
	if (is_file("modules/page_mods/mobile/".$node["link"].".php") && $Page404!=1) { @require("modules/page_mods/mobile/".$node["link"].".php"); $GLOBAL["log"].="<i>Подключение PHP</i>: модуль &laquo;modules/page_mods/mobile/".$node["link"].".php&raquo; подключен<hr>"; }
	if (is_file("modules/page_mods/mobile/".$node["link"].".js") && $Page404!=1) { $JSmodules["modules/page_mods/mobile/".$node["link"].".js"]="/modules/page_mods/mobile/".$node["link"].".js"; }
	if (is_file("modules/page_mods/mobile/".$node["link"].".css") && $Page404!=1) { $CSSmodules["modules/page_mods/mobile/".$node["link"].".css"]="/modules/page_mods/mobile/".$node["link"].".css"; }
}

############################################################################################################################################

// Определение шаблона сайта ==================================================================================================================================================
$design="mobile"; if (strpos($RealPage,"ls")!==false || strpos($RealPage,"afisha")!==false) { $design="mobile7ya"; }
$GLOBAL["log"].="<i>Определен шаблон дизайна</i>: [".$design."] мобильная версия<hr>";

if ($link=="mykazan") { $design="instakazan"; }
//if ($node["design"]=="blank") { $design="blank"; }

// Загрузка шаблона сайта =====================================================================================================================================================
if (is_file("template/".$design."/".$design.".html")) {
	$DesignHtml=@file_get_contents("template/".$design."/".$design.".html");
	$GLOBAL["log"].="<i>Шаблон дизайна</i>: загружен шаблон &laquo;"."template/".$design."/".$design.".html"."&raquo;<hr>";
} else {
	$DesignHtml="<h1>Не подключен шаблон дизайна</h1>";
	$GLOBAL["log"].="<u>Шаблон дизайна</u>: не найден шаблон &laquo;"."template/".$design."/".$design.".html"."&raquo;<hr>";
}

if (is_file("template/".$design."/".$design.".css")) {
	$CSSmodules["template/".$design."/".$design.".css"]="/template/".$design."/".$design.".css";
}

if (is_file("template/".$design."/".$design.".js")) {
	$JSmodules["template/".$design."/".$design.".js"]="/template/".$design."/".$design.".js";
}

// Заполнение шаблона сайта ===================================================================================================================================================
if ($node["isindex"]==1) { $Page["Title"]=$VARS["sitename"]; } else {
if ($Page["Caption"]!="") { $Page["Title"]=strip_tags($Page["Caption"])." ".$VARS["splitter"]." ".$VARS["sitename"]; } else { $Page["Title"]=$Page["Title"]." ".$VARS["splitter"]." ".$VARS["sitename"]; }}
if ($Page["KeyWords"]=="") { $Page["KeyWords"]=$Page["Caption"].", ".$VARS["keywords"]; } else { $Page["KeyWords"]=$Page["KeyWords"]/*.", ".$VARS["keywords"]*/; }
if ($Page["Description"]=="") { $Page["Description"]=$Page["Caption"].", ".$VARS["description"]; } else { $Page["Description"]=$Page["Description"]/*.", ".$VARS["description"]*/; }

if ($Page["Caption"]!="" && $Page404==0) { $Page["Caption"]="<h1>".$Page["Caption"]."</h1>"; }
if ($Page["Caption"]!="" && $Page404==1) { $Page["Caption"]="<h1 align='center'>".$Page["Caption"]."</h1>"; }

$Page["Caption"]=Hsc($Page["Caption"]); $Page["Title"]=Hsc($Page["Title"]);

foreach ($VarsToHtml as $key=>$value) { $DesignHtml=str_replace('$'.$value, $Page[$value], $DesignHtml); } # Переменные шаблона дизайна (определяются в начале этого файла)
foreach ($VARS as $key=>$value) { $DesignHtml=str_replace('$'.$key, $value, $DesignHtml); } # Параметры и настройки сайта (определяются в панели администрирования)
foreach ($MENU as $key=>$value) { $DesignHtml=str_replace('$'.$key, $value, $DesignHtml); } # Меню сайта (определяются в панели администрирования)

// Запрос вспомогательных модулей JS ====================================================================================================================================
$GLOBAL["log"].="<h1>Запрос дополнительных скриптов</h1>";
foreach ($JSmodules as $name=>$module) {
	if (strpos($module, "http:")===false) {
		if (is_file(trim($module,"/"))) { $GLOBAL["log"].="<i>Подключение JS</i>: скрипт &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["JSModules"].="<script src='".$module."' type='text/javascript'></script>"."\r\n";
		} else { $GLOBAL["log"].="<u>Подключение JS</u>: скрипт &laquo;".$name."&raquo; не найден (<b>".$module."</b>)<hr>"; }
	} else {
		$GLOBAL["log"].="<i>Подключение JS</i>: внешний скрипт &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["JSModules"].="<script src='".$module."' type='text/javascript'></script>"."\r\n";
	}
}

// Запрос CSS для вспомогательных модулей =====================================================================================================================================
$GLOBAL["log"].="<h1>Запрос дополнительных стилей</h1>";
foreach ($CSSmodules as $name=>$module) {
	if (strpos($module, "http")===false) {
		if (is_file(trim($module,"/"))) { $GLOBAL["log"].="<i>Подключение CSS</i>: стиль &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["CSSModules"].="<link rel='stylesheet' type='text/css' href='".$module."' media='all' />"."\r\n";
		} else { $GLOBAL["log"].="<u>Подключение CSS</u>: стиль &laquo;".$name."&raquo; не найден (<b>".$module."</b>)<hr>"; }
	} else {
		$GLOBAL["log"].="<i>Подключение CSS</i>: внешний стиль &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["CSSModules"].="<link rel='stylesheet' type='text/css' href='".$module."' media='all' />"."\r\n";
	}
}

@mysql_close();

// Вывод шаблона сайта ========================================================================================================================================================
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.$r;
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr">'.$r.'<head>'.$r;
echo '<title>'.$Page["Title"].'</title>'.$r;
echo $Robots.$r;
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$r;
echo '<meta name="keywords" content=\''.trim($Page["KeyWords"], ",").'\' />'.$r;
echo '<meta name="description" content=\''.trim($Page["Description"], ",").'\' />'.$r;
echo '<meta name="apple-itunes-app" content="app-id=1057890184">';
echo '<meta name="google-play-app" content="app-id=air.ru.prokazan.newsapp">';
echo '<meta name="viewport" content="width=device-width. initial-scale=1.0">'.$r;
echo '<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />'.$r;

echo '<!--В head сайта один раз подключите библиотеку-->
<script src="https://yastatic.net/pcode/adfox/loader.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://oriondigital.ru/ad/adorion.js?574856"></script>
<script type="text/javascript">
(function loadExtData(w) {
  w.adv = new Adv();
})(window);
</script>';
echo $GLOBAL["CSSModules"];
echo $GLOBAL["JSModules"];
echo '<link rel="stylesheet" href="/template/mobile/jquery.smartbanner.css" type="text/css" media="screen">';
echo '<script src="/template/mobile/jquery.smartbanner.js"></script>';
echo '</head>'.$r.'<body>'.$r;
echo "<div class='Banner' id='Banner-35-1' style='text-align:center;'></div>";
echo $DesignHtml.$r;
echo '
<script type="text/javascript">
adv.banner(function(webmd) {
    console.log("Showing banner 1", webmd);
           var wmclusters = webmd["clusters"].toString();
           var audiences =  webmd["audiences"].toString();
           wmclusters = wmclusters.replace(/,/g,":");
           audiences = audiences.replace(/,/g,":");
    window.Ya.adfoxCode.create({
        ownerId: 251657,
        containerId: "adfox_149699503432432432",
        params: {
            pp: "g",
            ps: "cjof",
            p2: "fkuh",
            puid1: webmd["socio_demographics"]["age"],
            puid2: webmd["socio_demographics"]["gender"],
            puid3: webmd["socio_demographics"]["social_class"],
            puid4: wmclusters,
            puid5: audiences
        },
           onRender: function() { console.log("otag_rendered"); },
           onError: function(error) { console.log("otag_error"); },
           onStub: function() { console.log("otag_stub"); }
});
});
</script>';
echo "<script>$(function () { $.smartbanner({ daysHidden:30, daysReminder:30, title:'Новости Казани', button: 'Скачать', price: 'Бесплатно!', icon: 'http://progorodchelny.ru/template/index/app.png' }) });</script>";
echo '<input type="hidden" id="gidvk" value="0" /></body>'.$r.'<input type="hidden" id="BoxCount" value="0" /><input type="hidden" id="DomainId" value="'.(int)$SubDomain.'" /></html>';

// Вывод логов сайта ===========================================================================================================================================================
if ($SafeMode==1) {
	$GLOBAL["StopTime"]=GetMicroTime();
	$GLOBAL["RunTime"]=$GLOBAL["StopTime"]-$GLOBAL["StartTime"];
	echo "<div id='SystemLogs'>";
		echo "<h1>Лог выполнения скриптов</h1>".$GLOBAL["log"];
		if (isset($_SESSION)) {
			echo "<h1>Значения  в ".'$_SESSION'."</h1>";
			foreach ($_SESSION as $key=>$value) { echo "<b>$key</b> -> &laquo;<i>$value</i>&raquo;<hr>"; }
		}
		echo "<h1>Время выполнения и количество запросов</h1>";
		echo "<i>Количество запросов SQL:</i> <b>".round($GLOBAL["sqlcount"], 3)."</b><hr>";
		echo "<i>Время выполнения SQL:</i> <b>".round($GLOBAL["sqltime"], 3)."</b> с.<hr>";
		echo "<i>Время выполнения PHP:</i> <b>".round($GLOBAL["RunTime"], 3)."</b> с.";
	echo "</div>";
} else {
	echo "<!-- CountSQL: ".round($GLOBAL["sqlcount"], 3)." | TimeSQL: ".round($GLOBAL["sqltime"], 3)."c. | TotalTime: ".round($GLOBAL["RunTime"], 3)."c. -->";
}
// =============================================================================================================================================================================
?>
