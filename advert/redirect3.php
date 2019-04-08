<?	
$a="https://play.google.com/store/apps/details?id=com.its.rto";
$i="https://itunes.apple.com/ru/app/rutaxi-onlajn/id506360097?mt=8";
$w="https://www.microsoft.com/ru-ru/store/apps/rutaxi/9nblggh0855g";

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
require_once("mobiledetect.php"); $detect=new Mobile_Detect;


if( $detect->isiOS() ){ @header('Location: '.$i); exit();
} elseif( $detect->isAndroidOS()){ @header('Location: '.$a); exit();
} else { @header('Location: '.$w); exit(); }


?>