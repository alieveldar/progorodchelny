<?php

error_reporting(0);
ini_set('display_errors', 0);

session_start();
mb_internal_encoding("UTF-8");

if(preg_match( '/^m\./', $_SERVER['HTTP_HOST'] )) {
    $r      = explode( ".", $_SERVER['HTTP_HOST'] );
    $t      = count( $r );
    $domain = trim( $r[ $t - 2 ] . "." . $r[ $t - 1 ] . "/" . $_SERVER['REQUEST_URI'], "/" );
    $domain = preg_replace( '#\/+#', '/', $domain );
    $domain = "http://" . $domain;
    header( 'Location: ' . $domain );
    exit();
} else {
    require("modules/MainModule.php");
}

?>
