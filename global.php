<?php ini_set('display_startup_errors', 1);
ini_set('display_errors', 1); 
require_once("includes/initialize.php");
$slug = !empty($_REQUEST['slug'])? addslashes($_REQUEST['slug']):'';
$mRec = Mlink::find_by_slug($slug);
if(!empty($mRec)) {
	switch ($mRec->mod_class) {
		case 'Article':
			require_once ('inner.php');
			break;
		
		case 'Package':
			require_once ('package.php');
			break;

		case 'Subpackage':
			require_once ('subpkgdetail.php');
			break;	
		case 'Services':
			require_once ('service_list.php');
			break;	

		default:
			require_once ('index.php');
			break;
	}
}
else {
	require_once ('404.php');
}