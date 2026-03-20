<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
//include ("jpcache/jpcache.php");

define('HOME_PAGE', 1); // Track homepage.
define('JCMSTYPE', 0); // Track Current site language.

require_once("includes/initialize.php");

$currentTemplate	= Config::getCurrentTemplate('template');
$jVars 				= array();
$maintenance =  Config::find_by_field('upcoming');
// pr($maintenance);
$index= ($maintenance==1)? 'uc.html':'index.html';
// pr($index);
$template 			= "template/{$currentTemplate}/{$index}";

require_once('views/modules.php');

template($template, $jVars, $currentTemplate);

?>