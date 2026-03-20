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
			// pr($_REQUEST,1);
			$record = new Slideshow();
			
			$record->title 			= $_REQUEST['title'];
			// $record->sub_title 			= $_REQUEST['sub_title'];
			// $record->title_greek 	= $_REQUEST['title_greek'];
			$record->upcoming 		= $_REQUEST['upcoming'];
			$record->image			= !empty($_REQUEST['imageArrayname'])?$_REQUEST['imageArrayname']:'';
			//$record->linksrc 		= $_REQUEST['linksrc'];
			$record->linktype 		= !empty($_REQUEST['linktype '])?$_REQUEST['linktype ']:'0';
			$record->content		= $_REQUEST['content'];			
			// $record->content_greek	= $_REQUEST['content_greek'];
			// $record->status			= $_REQUEST['status'];
			// $record->m_status		= $_REQUEST['m_status'];
            $record->mode           = $_REQUEST['mode'];
			if($_REQUEST['mode']==2){
				$record->type           = $_REQUEST['type'];
				if($_REQUEST['type']==1){
					$record->source		= !empty($_REQUEST['source'])?$_REQUEST['source']:'';  
				}
				else{
					$record->source_vid		= $_REQUEST['videoArrayname'];
				}
			}
			else{
				if(empty($_REQUEST['imageArrayname'])):
					echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
					exit;
				endif;
			}
			$record->url_type  	= !empty($_REQUEST['url_type'])?$_REQUEST['url_type']:'';
			// $record->thumb_image= $_REQUEST['thumb_image'];
			// $record->url 		= $_REQUEST['url'];
			// $record->host 		= $_REQUEST['host'];
			// $record->class 		= $_REQUEST['class'];
			$record->status 		= $_REQUEST['status'];
			
			
			
			$record->sortorder		= Slideshow::find_maximum();
			$record->added_date 	= registered();
			
			// if(empty($_REQUEST['imageArrayname'])):
				// 	echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
				// 	exit;
				// endif;
				
				$db->begin();
				if($record->save()): $db->commit();
				$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Slideshow '".$record->title."'");
				echo json_encode(array("action"=>"success","message"=>$message));
				log_action("Slideshow [".$record->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
			echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
		endif;
		break;
		
		case "edit":
			// pr($_REQUEST,1); 	
			$record = Slideshow::find_by_id($_REQUEST['idValue']);
			
			$record->title 			= $_REQUEST['title'];
			// $record->sub_title 			= $_REQUEST['sub_title'];
			$record->upcoming 		= $_REQUEST['upcoming'];
			// $record->title_greek 	= $_REQUEST['title_greek'];
			//$record->linksrc 		= $_REQUEST['linksrc'];
			// $record->linktype 		= $_REQUEST['linktype'];
			$record->content		= $_REQUEST['content'];	
			$record->image			= !empty($_REQUEST['imageArrayname'])?$_REQUEST['imageArrayname']:'';

			// $record->thumb_image= $_REQUEST['thumb_image'];
			// $record->url 		= $_REQUEST['url'];
			// $record->host 		= $_REQUEST['host'];
			// $record->class 		= $_REQUEST['class'];	
			$record->mode           = $_REQUEST['mode'];
			if($_REQUEST['mode']==2){
            $record->type           = $_REQUEST['type'];
				if($_REQUEST['type']==1){
					$record->source		= !empty($_REQUEST['source'])?$_REQUEST['source']:'';  
					$record->source_vid	= '';
				}
				else{
					$record->source_vid		= $_REQUEST['videoArrayname'];
					$record->source			= '';
				}
			}
			else{

				if(empty($_REQUEST['imageArrayname'])):
					// $record->image		= $_REQUEST['imageArrayname'];
					echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
					exit;
				endif;
				
			}
			$record->url_type  	= !empty($_REQUEST['url_type'])?$_REQUEST['url_type']:'';
			// $record->content_greek	= $_REQUEST['content_greek'];
			$record->status			= $_REQUEST['status'];
			// $record->m_status		= $_REQUEST['m_status'];
//			$record->type 			= 1;
//
//			

           

            $db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Slideshow '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Slideshow [".$record->title."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = Slideshow::find_by_id($id);
			log_action("Slideshows  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->query("DELETE FROM tbl_slideshow WHERE id='{$id}'");
			
			reOrder("tbl_slideshow", "sortorder");			
			
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Slideshow '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));					
			log_action("Slideshow  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Slideshow::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Slideshow::find_by_id($allid[$i]);
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
				$record = Slideshow::find_by_id($allid[$i]);
				log_action("Slideshow  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);				
				$res = $db->query("DELETE FROM tbl_slideshow WHERE id='".$allid[$i]."'");				
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_slideshow", "sortorder");
			
			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Slideshow"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":			
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Slideshow::field_by_id($id,'type');
			datatableReordering('tbl_slideshow', $sortIds, "sortorder", "type",$posId,1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Slideshow"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;
		case "setschoolsId" :
			$session->set('type_id', $_REQUEST['type_id']);
			echo json_encode(array("action"=>"success","message"=>"User hotel updated successfully"));
		break;
	}
?>
