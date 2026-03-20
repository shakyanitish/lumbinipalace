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
			$record = new Testimonial();	
					
			$record->name 		= $_REQUEST['name'];
            $record->image		= $_REQUEST['imageArrayname'];
			$record->content 	= $_REQUEST['content'];			
            // $record->rating 	= $_REQUEST['rating'];
			$record->status		= $_REQUEST['status'];
			// $record->country	= $_REQUEST['country'];
			$record->via_type	= $_REQUEST['via_type'];
			// $record->linksrc	= $_REQUEST['linksrc'];
			$record->sortorder	= Testimonial::find_maximum();
			$db->begin();
			if($record->save()): $db->commit();
			
			$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Testimonial '".$record->name."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("Testimonial [".$record->name."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;

			
			
			
		break;
			
		case "edit":
			$record = Testimonial::find_by_id($_REQUEST['idValue']);
							
			$record->name 		= $_REQUEST['name'];
			$record->image		= $_REQUEST['imageArrayname'];
			$record->content 	= $_REQUEST['content'];			
			// $record->rating 	= $_REQUEST['rating'];
			$record->status		= $_REQUEST['status'];
			//$record->country	= $_REQUEST['country'];
			$record->via_type	= $_REQUEST['via_type'];
            // $record->linksrc	= $_REQUEST['linksrc'];

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Testimonial '".$record->name."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Testimonial [".$record->name."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = Testimonial::find_by_id($id);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_testimonial WHERE id='{$id}'");
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_testimonial", "sortorder");
			
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Testimonial '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));					
			log_action("Testimonial  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Testimonial::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Testimonial::find_by_id($allid[$i]);
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
				$record = Testimonial::find_by_id($allid[$i]);
				log_action("Testimonial  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);				
				$res = $db->query("DELETE FROM tbl_testimonial WHERE id='".$allid[$i]."'");				
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_testimonial", "sortorder");
			
			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Testimonial"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_testimonial', $sortIds, "sortorder", '', '',1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Testimonials"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;
		case "metadata":
			$page_name=$_REQUEST['page_name'];
			$metatitle=$_REQUEST['meta_title'];
			$metakeywords=$_REQUEST['meta_keywords'];
			$metadescription=$_REQUEST['meta_description'];
			$addeddate= registered();
			// pr("SELECT * FROM tbl_metadata WHERE page_name='$page_name' LIMIT 1");
			$metasql= $db->query("SELECT * FROM tbl_metadata WHERE page_name='$page_name'LIMIT 1");
			$metadata= $metasql->fetch_array();
			
			$metaexist= !empty($metadata) ? array_shift($metadata) : false;
			if ($metaexist) {
				$metadata = "UPDATE tbl_metadata SET meta_title='" . $_REQUEST['meta_title'] . "', meta_keywords='" . $_REQUEST['meta_keywords'] . "', meta_description='" . $_REQUEST['meta_description'] . "' WHERE page_name='" . $_REQUEST['page_name'] . "'";
			}
			else{
				$metadata = "INSERT INTO tbl_metadata SET module_id='" . $_REQUEST['module_id'] . "', page_name='" . $_REQUEST['page_name'] . "', meta_title='" . $_REQUEST['meta_title'] . "', meta_keywords='" . $_REQUEST['meta_keywords'] . "', meta_description='" . $_REQUEST['meta_description'] . "', added_date='" . $addeddate . "'";
			}
			$db->begin();
			$sucess=$db->query($metadata);
			if ($sucess==1): $db->commit();
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Testimonial Meta Data saved successfully");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Testimonial Meta Data Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;	
		break;
	}
?>