<?php

define('HOMEPAGE', 0); // Track homepage.
define('ALUMINI_PAGE', 1);// Track Article page.
define('JCMSTYPE', 0); // Track Current site language.

require_once("includes/initialize.php");

$currentTemplate	= Config::getCurrentTemplate('template');
$jVars 				= array();
$template 			= "template/web/alumini.html";

require_once('views/modules.php');

template($template, $jVars, $currentTemplate);

?>