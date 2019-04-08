<?
### ВЫХОД ИЗ АДМИНКИ
if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
	$_SESSION['userrole']=0;
	$_SESSION['userid']=0;
	$_SESSION['admincount']=0;
	$_SESSION['adminblock']=0;
	$_SESSION['adminlevel']=0;
	unset($_SESSION);
	header("location: /admin/");
	exit();
}
?>