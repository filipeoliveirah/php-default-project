<?php
if (isset($_GET['token'])) session_id($_GET['token']);
session_start();

date_default_timezone_set('America/Sao_Paulo');

if((float)PHP_VERSION < 5.5) {
	throw new Exception("PHP 5.5 required!");
	exit;
}

require_once("vendor/autoload.php");
require_once("consts.php");
require_once("functions.php");
?>