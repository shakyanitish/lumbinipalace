<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

define('INNER_PAGE', 1); // Track homepage.
define('TEAM_PAGE', 1);  // ADD THIS LINE - Required for team module
define('JCMSTYPE', 0); // Track Current site language.

require_once("includes/initialize.php");

$currentTemplate	= Config::getCurrentTemplate('template');
$jVars 				= array();
$template 			= "template/{$currentTemplate}/team.html";

require_once('views/modules.php');

template($template, $jVars, $currentTemplate);
?>