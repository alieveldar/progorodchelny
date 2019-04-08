<?	
$a="https://play.google.com/store/apps/details?id=com.taxi.samara";
$i="https://itunes.apple.com/ru/app/samtaxi/id906400042?mt=8";
$w="http://samtaxi.ru/";

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
require_once("mobiledetect.php"); $detect=new Mobile_Detect;


if( $detect->isiOS() ){ @header('Location: '.$i); exit();
} elseif( $detect->isAndroidOS()){ @header('Location: '.$a); exit();
} else { @header('Location: '.$w); exit(); }


?>