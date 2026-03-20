<?php
http_response_code(404);

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

define('ERROR_404', 1); // Track homepage.

require_once("includes/initialize.php");

$currentTemplate	= Config::getCurrentTemplate('template');
$jVars 				= array();
$template 			= "template/{$currentTemplate}/404.html";

require_once('views/modules.php');

template($template, $jVars, $currentTemplate);

?>