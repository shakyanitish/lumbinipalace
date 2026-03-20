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
			$record = new News();		
			
			$record->slug 		= create_slug($_REQUEST['title']);	
			$record->title 		= $_REQUEST['title'];
			$record->author 	= $_REQUEST['author'];	
			$record->brief 		= $_REQUEST['brief'];		
			$record->content	= $_REQUEST['content'];
			$record->type 		= $_REQUEST['type'];			
			if($_REQUEST['type']==1){
				$record->image		= $_REQUEST['imageArrayname'];
			}else{
				$record->source 	= $_REQUEST['source'];
			}			
			$record->news_date 	= $_REQUEST['news_date'];			
			$record->status		= $_REQUEST['status'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];
					
			$record->sortorder	= News::find_maximum();
			$record->added_date = registered();			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "News '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("News [".$record->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
			
		case "edit":
			$record = News::find_by_id($_REQUEST['idValue']);
			
			$record->slug 		= create_slug($_REQUEST['title']);							
			$record->title 		= $_REQUEST['title'];
			$record->author 	= $_REQUEST['author'];	
			$record->brief 		= $_REQUEST['brief'];		
			$record->content	= $_REQUEST['content'];
			$record->type 		= $_REQUEST['type'];
			if($_REQUEST['type']==1){
				$record->image		= $_REQUEST['imageArrayname'];
				$record->source 	= '';
			}else{
				$record->source 	= $_REQUEST['source'];
				$record->image		= '';
			}	    		    
			$record->news_date 	= $_REQUEST['news_date'];			
			$record->status		= $_REQUEST['status'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "News '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("News [".$record->title."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = News::find_by_id($id);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_news WHERE id='{$id}'");
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_news", "sortorder");
			
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "News '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));					
			log_action("News  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = News::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = News::find_by_id($allid[$i]);
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
				$record = News::find_by_id($allid[$i]);
				log_action("News  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);				
				$res = $db->query("DELETE FROM tbl_news WHERE id='".$allid[$i]."'");				
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_news", "sortorder");
			
			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "News"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_news', $sortIds, "sortorder", '', '',1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "News"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;

		case "comments":
			$cmtrec = new Blogcomment();
			$cmtrec->news_id = $_REQUEST['newsid'];
			$cmtrec->name 	 = $_REQUEST['fullname'];
			$cmtrec->email 	 = $_REQUEST['mailaddress'];
			$cmtrec->comment = $_REQUEST['comments'];
			$cmtrec->status 	= 1;
			$cmtrec->sortorder  = Blogcomment::find_maximum();
			$cmtrec->added_date = registered();

			$db->begin();
			if($cmtrec->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Blog Comment '".$cmtrec->name."'");
				echo json_encode(array("action"=>"success","message"=>$message));				
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
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
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Blog Meta Data saved successfully");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Blog Meta Data Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;	
		break;
	}
?>