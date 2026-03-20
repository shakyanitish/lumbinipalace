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
			$Gallery = new Gallery();
			
			$Gallery->slug 			= create_slug($_REQUEST['title']);
			$Gallery->title 		= $_REQUEST['title'];
			$Gallery->type 			= $_REQUEST['type'];
			// $Gallery->image			= $_REQUEST['imageArrayname'];			
			$Gallery->status		= 1;
			$Gallery->sortorder		= Gallery::find_maximum();														
			$Gallery->registered	= registered();	
			
			$checkDupliName = Gallery::checkDupliName($Gallery->title);			
			if($checkDupliName):
				echo json_encode(array("action"=>"warning","message"=>"Gallery Title Already Exists."));		
				exit;		
			endif;

			// if(empty($_REQUEST['imageArrayname'])):				
			// 	echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
			// 	exit;					
			// endif;
			
			$db->begin();
			if($Gallery->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Gallery Image '".$Gallery->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("Gallery [".$Gallery->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
		
		case "edit":			
			$Gallery = Gallery::find_by_id($_REQUEST['idValue']);
			
			if($Gallery->title!=$_REQUEST['title']){
				$checkDupliName = Gallery::checkDupliName($_REQUEST['title']);
				if($checkDupliName):
					echo json_encode(array("action"=>"warning","message"=>"Gallery title is already exist."));		
					exit;		
				endif;
			}

			// if(empty($_REQUEST['imageArrayname'])):				
			// 	echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
			// 	exit;					
			// endif;
			
			// $Gallery->image	= $_REQUEST['imageArrayname']; 
			$Gallery->slug 	= create_slug($_REQUEST['title']);		
			$Gallery->title = $_REQUEST['title'];			
			$Gallery->type 	= $_REQUEST['type'];
			$db->begin();				
			if($Gallery->save()):$db->commit();	
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Gallery '".$Gallery->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Gallery Image [".$Gallery->title."] Edit Successfully",1,4);
			else:$db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;							
		break;
		
		case "addSubGalleryImage":
					
			$imageName  = !empty($_REQUEST['imageArrayname'])?$_REQUEST['imageArrayname']:'';
			$title      = !empty($_REQUEST['title'])?$_REQUEST['title']:'';
			$galleryid  = $_REQUEST['galleryid'];
			if(!empty($imageName)):
			foreach($imageName as $key=>$val):
				$FimageName		= $imageName[$key];
				$Ftitle	        = $title[$key];																	
				//Save Record
				if(!empty($FimageName)):
				$Gallery	 = new GalleryImage();

				$Gallery->image			= $FimageName; 		
				$Gallery->title     	= $Ftitle;
				$Gallery->status		= 1;
				$Gallery->galleryid		= $galleryid;
				$Gallery->sortorder		= GalleryImage::find_maximum_byparent("sortorder",$galleryid);														
				$Gallery->registered	= registered();						
				$db->begin();						
				$res   =  $Gallery->save();
				   if($res):$db->commit();	else: $db->rollback();endif;
				log_action("Sub Gallery Image [".$Gallery->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
				endif;
			endforeach;				
				echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['changesSaved'],"galleryid"=>$galleryid));				
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;						
		break;

        case "editSubGalleryImageText":

            $GalleryImage = GalleryImage::find_by_id($_REQUEST['id']);
            if(!empty($GalleryImage)){
                $GalleryImage->title = $_REQUEST['title'];
                $db->begin();
                if($GalleryImage->save()):$db->commit();
                    $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Gallery Image '".$GalleryImage->title."'");
                    echo json_encode(array("action"=>"success","message"=>$message));
                    log_action("Gallery Image [".$GalleryImage->title."] Edit Successfully",1,4);
                else:$db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
                endif;
            }else{
                echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
            }

        break;
				
		case "delete":
			$id = $_REQUEST['id'];
			$record = Gallery::find_by_id($id);
			log_action("Gallery Image  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_galleries WHERE id='{$id}'");
            if ($res):
                $db->query("DELETE FROM tbl_gallery_images WHERE galleryid='{$id}'");
                $db->commit();
            else: $db->rollback();endif;
			reOrder("tbl_galleries", "sortorder");						
			echo json_encode(array("action"=>"success","message"=>"success"));							
		break;

        case "bulkDelete":
            $id = $_REQUEST['idArray'];
            $allid = explode("|", $id);
            $return = "0";
            $db->begin();
            for($i=1; $i<count($allid); $i++){
                $res  = $db->query("DELETE FROM tbl_galleries WHERE id='".$allid[$i]."'");
                $db->query("DELETE FROM tbl_gallery_images WHERE galleryid='".$allid[$i]."'");
                $return = 1;
            }
            if($res)$db->commit();else $db->rollback();
            reOrder("tbl_galleries", "sortorder");

            if($return==1):
                $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Gallery");
                echo json_encode(array("action"=>"success","message"=>$message));
            else:
                echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
            endif;
        break;
		
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Gallery::find_by_id($id);
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
                $record = Gallery::find_by_id($allid[$i]);
                $record->status = ($record->status == 1) ? 0 : 1 ;
                $record->save();
            }
            echo "";
        break;
		
		case "deleteSubimage":
			$id = $_REQUEST['id'];
			$record = GalleryImage::find_by_id($id);
			log_action("Sub Gallery Image  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->begin();  		    	
			$res =  $db->query("DELETE FROM tbl_gallery_images WHERE id='{$id}'");
			if($res):$db->commit();	else: $db->rollback();endif;
			reOrderSub("tbl_gallery_images", "sortorder", "galleryid", $record->galleryid);					
			echo json_encode(array("action"=>"success"));	
		break;
		
		case "toggleStatusSubimage":
			$id = $_REQUEST['id'];
			$record = GalleryImage::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$db->begin();  	
			$res = $record->save();
			if($res):$db->commit();	else: $db->rollback();endif;
			echo "";
		break;
		
		case "sort":
            $id 	= $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
//			$order	= ($_REQUEST['toPosition']==1)?0:$_REQUEST['toPosition'];// IS a line containing sortorder
//			$db->begin();
//			$res = $db->query("UPDATE tbl_galleries SET sortorder=".$order." WHERE id=".$id." ");
//			if($res):$db->commit();	else: $db->rollback();endif;
//			reOrder("tbl_galleries", "sortorder");
            $sortIds = $_REQUEST['sortIds'];
            datatableReordering('tbl_galleries', $sortIds, "sortorder", '','',1);
            $message  = sprintf($GLOBALS['basic']['sorted_'], "Gallery");
            echo json_encode(array("action"=>"success","message"=>$message));
		break;
		
		case "sortSubGalley":
			$id 	= $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$record = GalleryImage::find_by_id($id);
			$sortIds = $_REQUEST['sortIds'];
			
			datatableReordering('tbl_gallery_images', $sortIds, "sortorder", 'galleryid', $record->galleryid);
			echo json_encode(array("action"=>"success","message"=>$GLOBALS['basic']['sorted']));
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
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Gallery Meta Data saved successfully");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Gallery Meta Data Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;	
		break;
	}
?>