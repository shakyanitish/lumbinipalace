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
			$record = new Popup();
			
			$record->slug 		= create_slug($_REQUEST['title']);
			$record->title 		= $_REQUEST['title'];
			$record->date1 	= $_REQUEST['date1'];
			$record->date2 	= $_REQUEST['date2'];
			if($_REQUEST['type']==1){
				$record->image		= serialize(array_values(array_filter($_REQUEST['imageArrayname'])));	
			}else{
				$record->source 	= $_REQUEST['source'];
			}		
			$record->linksrc 	= $_REQUEST['linksrc'];
			$record->linktype 	= $_REQUEST['linktype'];
			$record->status		= $_REQUEST['status'];
			$record->type 		= $_REQUEST['type'];
			$record->position	= $_REQUEST['orientation'];
			
			$record->sortorder	= Popup::find_maximum();
			
			
			$checkDupliName=Popup::checkDupliName($record->title);			
			if($checkDupliName):
				echo json_encode(array("action"=>"warning","message"=>"Popup Title Already Exists."));		
				exit;		
			endif;
			$db->begin();
			if($record->save()): $db->commit();
				$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Popup '".$record->title."'");
				echo json_encode(array("action"=>"success","message"=>$message));
				log_action($message,1,3);
			else: 
				$db->rollback(); echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
			
		case "edit":
			$record = Popup::find_by_id($_REQUEST['idValue']);
			
			if($record->title!=$_REQUEST['title']){
				$checkDupliName=Popup::checkDupliName($_REQUEST['title']);
				if($checkDupliName):
					echo json_encode(array("action"=>"warning","message"=>"Popup title is already exist."));		
					exit;		
				endif;
			}
			
			$record->slug 		= create_slug($_REQUEST['title']);
			$record->title 		= $_REQUEST['title'];
			$record->date1 	= $_REQUEST['date1'];
			$record->date2 	= $_REQUEST['date2'];
			if($_REQUEST['type']==1){
				$record->image		= serialize(array_values(array_filter($_REQUEST['imageArrayname'])));
				$record->source 	= '';
			}else{
				$record->source 	= $_REQUEST['source'];
				$record->image		= '';
			}	
			
			$record->linksrc 	= $_REQUEST['linksrc'];
			$record->linktype 	= $_REQUEST['linktype'];
			$record->status		= $_REQUEST['status'];
			$record->type 		= $_REQUEST['type'];
			$record->position	= $_REQUEST['orientation'];
		
		
			$db->begin();
			if($record->save()):$db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Popup '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action($message,1,4);
			else: $db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = Popup::find_by_id($id);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_popup WHERE id='{$id}'");
			if($res):$db->commit();	else: $db->rollback();endif;
			reOrder("tbl_popup", "sortorder");
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Popup '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));	
			log_action("Popup  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Popup::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Popup::find_by_id($allid[$i]);
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
				$res  = $db->query("DELETE FROM tbl_popup WHERE id='".$allid[$i]."'");
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_popup", "sortorder");
			
			if($return==1):
			    $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Popup"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":			
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_popup', $sortIds, "sortorder", '','',1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Popup"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;
	}
?>