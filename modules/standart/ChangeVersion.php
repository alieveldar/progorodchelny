<?php
session_start();
$_SESSION['full'] = (int) $_GET['to'];
header('Location: ' . $_SERVER['HTTP_REFERER'], true, 307);
die;
