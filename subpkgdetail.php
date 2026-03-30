<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
//include ("jpcache/jpcache.php");

// define('SUBPACKAGE_PAGE', 1); // Track homepage.
define('ROOM_PAGE', 1); // Track homepage.
define('JCMSTYPE', 0); // Track Current site language.

require_once("includes/initialize.php");

$currentTemplate	= Config::getCurrentTemplate('template');
$jVars 				= array();
$template 			= "template/{$currentTemplate}/room_details.html";

require_once('views/modules.php');

template($template, $jVars, $currentTemplate);

?>