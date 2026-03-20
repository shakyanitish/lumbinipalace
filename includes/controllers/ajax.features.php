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
			
			$Features = new Features();
			
			$Features->parentId 	= $_REQUEST['parentId'];
			$Features->title    	= $_REQUEST['title'];	
			$Features->image		= !empty($_REQUEST['imageArrayname']) ? $_REQUEST['imageArrayname'] : '';
			$Features->icon    	= $_REQUEST['icon'];			
			$Features->brief    	= $_REQUEST['brief'];	
			$Features->status		= $_REQUEST['status'];
			$Features->sortorder	= Features::find_maximum_byparent("sortorder",$_REQUEST['parentId']);
			$Features->added_date 	= registered();
			
			$checkDupliTitle = Features::checkDupliTitle($Features->title,$_REQUEST['parentId']);			
			if($checkDupliTitle):
				echo json_encode(array("action"=>"warning","message"=>"Features Title Already Exists."));		
				exit;		
			endif;

			// if(empty($_REQUEST['imageArrayname'])):				
			// 	echo json_encode(array("action"=>"warning","message"=>"Required Upload Features Image!"));
			// 	exit;					
			// endif;
			
			$db->begin();
			if($Features->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Features Image '".$Features->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("Features [".$Features->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;				
		break;
		
		case "edit":			
			$Features = Features::find_by_id($_REQUEST['idValue']);			
			
			$checkDupliTitle = Features::checkDupliTitle($_REQUEST['title'],$Features->parentId,$Features->id);
			if($checkDupliTitle):
				echo json_encode(array("action"=>"warning","message"=>"Features Title is already exist."));		
				exit;		
			endif;

			
			$Features->image		= !empty($_REQUEST['imageArrayname']) ? $_REQUEST['imageArrayname'] : '';	
			$Features->parentId 	= $_REQUEST['parentId'];				
			$Features->title    = $_REQUEST['title'];	
			$Features->brief    = $_REQUEST['brief'];
			$Features->icon    	= $_REQUEST['icon'];	
			$Features->status   = $_REQUEST['status'];	

			$db->begin();				
			if($Features->save()):$db->commit();	
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Features '".$Features->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Features [".$Features->title."] Edit Successfully",1,4);
			else:$db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;							
		break;
								
		case "delete":
			$id = $_REQUEST['id'];
			$record = Features::find_by_id($id);
			log_action("Features  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->begin();
			$db->query("DELETE FROM tbl_features WHERE parentId='{$id}'");
			$res = $db->query("DELETE FROM tbl_features WHERE id='{$id}'");
  		    if($res):$db->commit();	else: $db->rollback();endif;
			reOrder("tbl_features", "sortorder");						
			echo json_encode(array("action"=>"success","message"=>"Features  [".$record->title."]".$GLOBALS['basic']['deletedSuccess']));							
		break;
		
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Features::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$db->begin();						
				$res   =  $record->save();
				   if($res):$db->commit();	else: $db->rollback();endif;
			echo "";
		break;

		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Features::find_by_id($allid[$i]);
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
						$db->query("DELETE FROM tbl_features WHERE parentId='".$allid[$i]."'");
				$res  = $db->query("DELETE FROM tbl_features WHERE id='".$allid[$i]."'");
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_features", "sortorder");
			
			if($return==1):
			    $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Features"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
				
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Features::field_by_id($id,'parentId');
			datatableReordering('tbl_features', $sortIds, "sortorder", '', '',1);
			datatableReordering('tbl_features', $sortIds, "sortorder", "parentId",$posId);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Features "); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;		
	}
?>