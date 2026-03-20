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
		case "add":	
			$record = new SocialNetworking();
			
			$record->title 			= $_REQUEST['title'];
			$record->image			= !empty($_REQUEST['imageArrayname'])?$_REQUEST['imageArrayname']:'';	
			$record->icon			= $_REQUEST['icon'];
			$record->linksrc 		= $_REQUEST['linksrc'];
			$record->status 		= $_REQUEST['status'];
			$record->type 		    = $_REQUEST['type'];

			$record->sortorder		= SocialNetworking::find_maximum();
			$record->registered 	= registered();
			
			// if(empty($_REQUEST['imageArrayname'])):				
			// 	echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
			// 	exit;					
			// endif;
			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "SocialNetworking '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("SocialNetworking [".$record->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
			
		case "edit":
			$record = SocialNetworking::find_by_id($_REQUEST['idValue']);
			
			$record->title 			= $_REQUEST['title'];
			$record->image			= !empty($_REQUEST['imageArrayname'])?$_REQUEST['imageArrayname']:'';	
			$record->icon 			= $_REQUEST['icon'];
			$record->linksrc 		= $_REQUEST['linksrc'];
			$record->status 		= $_REQUEST['status'];
            $record->type 		    = $_REQUEST['type'];
			

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "SocialNetworking '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("SocialNetworking [".$record->title."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = SocialNetworking::find_by_id($id);
			log_action("SocialNetworking  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->query("DELETE FROM tbl_social_networking WHERE id='{$id}'");
			
			reOrder("tbl_social_networking", "sortorder");			
			
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "SocialNetworking '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));					
			log_action("SocialNetworking  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = SocialNetworking::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = SocialNetworking::find_by_id($allid[$i]);
				$record->status = ($record->status == 1) ? 0 : 1 ;
				$record->save();
			}
			echo "";
		break;
			
		case "bulkDelete":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			$db->begin();
			for($i=1; $i<count($allid); $i++){
				$record = SocialNetworking::find_by_id($allid[$i]);
				log_action("SocialNetworking  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);				
				$res = $db->query("DELETE FROM tbl_social_networking WHERE id='".$allid[$i]."'");				
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_social_networking", "sortorder");
			
			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "SocialNetworking"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_social_networking', $sortIds, "sortorder");
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Social Networking"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;

        case "setschoolsId" :
            $session->set('type_id_social', $_REQUEST['type_id_social']);
            echo json_encode(array("action"=>"success","message"=>"Social type updated successfully"));
        break;
	}
?>