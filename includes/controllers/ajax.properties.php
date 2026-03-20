<?php 
	// Load the header files first
	header("Expires: 0"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("cache-control: no-store, no-cache, must-revalidate"); 
	header("Pragma: no-cache");

	// Load necessary files then...
	require_once('../initialize.php');
	
	$action = $_REQUEST['action'];
	
	switch($action) 
	{		
		case "menu-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "level"=>$_REQUEST['level']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Menu Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Menu Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "articles-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Articles Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Articles Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "slideshow-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Slideshow Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Slideshow Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "offers-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "bimgwidth"=>$_REQUEST['bimgwidth'],
							"bimgheight"=>$_REQUEST['bimgheight'],
							"imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Offers Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Offers Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "gallery-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight'],
							"simgwidth"=>$_REQUEST['simgwidth'],
							"simgheight"=>$_REQUEST['simgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Gallery Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Gallery Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "news-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "News Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully News Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
		case "mservice-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "News Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully News Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "nearby-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "News Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully News Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "blog-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "News Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully News Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;


		case "popup-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "News Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully News Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "tourpkg-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Tour Package Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Tour Package Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "pkg-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight'],
							"subbimgwidth"=>$_REQUEST['subbimgwidth'],
							"subbimgheight"=>$_REQUEST['subbimgheight'],
							"subimgwidth"=>$_REQUEST['subimgwidth'],
							"subimgheight"=>$_REQUEST['subimgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Package Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully  Package Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "touractv-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Tour Activity Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Tour Activity Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "testimonial-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Testimonial Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Testimonial Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "advertisment-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "limgwidth" =>$_REQUEST['limgwidth'],
							"limgheight"=>$_REQUEST['limgheight'],
							"timgwidth" =>$_REQUEST['timgwidth'],
							"timgheight"=>$_REQUEST['timgheight'],
							"rimgwidth" =>$_REQUEST['rimgwidth'],
							"rimgheight"=>$_REQUEST['rimgheight'],
							"bimgwidth" =>$_REQUEST['bimgwidth'],
							"bimgheight"=>$_REQUEST['bimgheight']);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Advertisment Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Advertisment Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "social-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight']
							// "oimgwidth"=>$_REQUEST['oimgwidth'],
							// "oimgheight"=>$_REQUEST['o	imgheight']
						);			
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Social Link Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Social Link Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "preference-properties":				
			$record = Module::find_by_id($_REQUEST['modelId']);

			$myArr = array( "imgwidth"=>$_REQUEST['imgwidth'],
							"imgheight"=>$_REQUEST['imgheight'],
							"simgwidth"=>$_REQUEST['simgwidth'],
							"simgheight"=>$_REQUEST['simgheight'],
							"fbimgwidth"=>$_REQUEST['fbimgwidth'],
							"fbimgheight"=>$_REQUEST['fbimgheight'],
							"timgwidth"=>$_REQUEST['timgwidth'],
							"timgheight"=>$_REQUEST['timgheight'],
							"gimgwidth"=>$_REQUEST['gimgwidth'],
							"gimgheight"=>$_REQUEST['gimgheight'],
							"cimgwidth"=>$_REQUEST['cimgwidth'],
							"cimgheight"=>$_REQUEST['cimgheight'],
							"oimgwidth"=>$_REQUEST['oimgwidth'],
							"oimgheight"=>$_REQUEST['oimgheight'],
							"fimgwidth"=>$_REQUEST['fimgwidth'],
							"fimgheight"=>$_REQUEST['fimgheight'],
							"ofimgwidth"=>$_REQUEST['ofimgwidth'],
							"ofimgheight"=>$_REQUEST['ofimgheight']
						);
									
			$record->properties = serialize($myArr);
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Preference Properties Settings");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Successfully Preference Properties Settings",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
	}
?>