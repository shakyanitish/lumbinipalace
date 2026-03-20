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
			$record = new Blog();		
			
			$record->slug 		= create_slug($_REQUEST['title']);	
			$record->title 		= $_REQUEST['title'];
			$record->author 	= $_REQUEST['author'];	
			$record->brief 		= $_REQUEST['brief'];		
			$record->content	= $_REQUEST['content'];
			$record->category	= $_REQUEST['category'];
			// $record->linksrc 		= $_REQUEST['linksrc'];
			// $record->linktype 		= $_REQUEST['linktype'];
			// $record->type 		= $_REQUEST['type'];			
			// if($_REQUEST['type']==1){
			// 	$record->image		= $_REQUEST['imageArrayname'];
			// }else{
			// 	$record->source 	= $_REQUEST['source'];
			// }	
			!(empty($_REQUEST['imageArrayname'])) ? ($record->image	= $_REQUEST['imageArrayname']): ($record->image	= '');
			$record->blog_date 	= $_REQUEST['blog_date'];			
			$record->status		= $_REQUEST['status'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];
					
			$record->sortorder	= Blog::find_maximum();
			$record->added_date = registered();			
			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Blog '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("Blog [".$record->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
			
		case "edit":
			$record = Blog::find_by_id($_REQUEST['idValue']);
			
			$record->slug 		= create_slug($_REQUEST['title']);							
			$record->title 		= $_REQUEST['title'];
			$record->author 	= $_REQUEST['author'];
			$record->category	= $_REQUEST['category'];
			$record->brief 		= $_REQUEST['brief'];		
			$record->content	= $_REQUEST['content'];
			// $record->linksrc 		= $_REQUEST['linksrc'];
			// $record->linktype 		= $_REQUEST['linktype'];
			// $record->type 		= $_REQUEST['type'];
			// if($_REQUEST['type']==1){
			// 	$record->image		= $_REQUEST['imageArrayname'];
			// 	$record->source 	= '';
			// }else{
			// 	$record->source 	= $_REQUEST['source'];
			// 	$record->image		= '';
			// }	    		    


			!(empty($_REQUEST['imageArrayname'])) ? ($record->image	= $_REQUEST['imageArrayname']): ($record->image	= '');


			$record->blog_date 	= $_REQUEST['blog_date'];			
			$record->status		= $_REQUEST['status'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Blog '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Blog [".$record->title."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = Blog::find_by_id($id);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_blog WHERE id='{$id}'");
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_blog", "sortorder");
			
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Blog '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));					
			log_action("Blog  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Blog::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Blog::find_by_id($allid[$i]);
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
				$record = Blog::find_by_id($allid[$i]);
				log_action("Blog  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);				
				$res = $db->query("DELETE FROM tbl_blog WHERE id='".$allid[$i]."'");				
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_blog", "sortorder");
			
			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Blog"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_blog', $sortIds, "sortorder", '', '',1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Blog"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;

		case "comments":
			$cmtrec = new Blogcomment();
			$cmtrec->blog_id = $_REQUEST['blogid'];
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

		/*************************** Blog Image Functions ***************************/
		case "addBlogImage":
			$blogid = intval($_REQUEST['blogid']);
			$imageArrayname = isset($_REQUEST['imageArrayname']) ? $_REQUEST['imageArrayname'] : array();
			$titleArray = isset($_REQUEST['title']) ? $_REQUEST['title'] : array();
			
			if(!empty($imageArrayname)):
				$db->begin();
				$i = 0;
				foreach($imageArrayname as $imagename):
					$BlogImage = new BlogImage();
					$BlogImage->blogid = $blogid;
					$BlogImage->title = isset($titleArray[$i]) ? addslashes($titleArray[$i]) : '';
					$BlogImage->detail = '';
					$BlogImage->status = 1;
					$BlogImage->sortorder = BlogImage::find_maximum_byparent("sortorder", $blogid);
					$BlogImage->registered = registered();
					$BlogImage->image = $imagename;
					$BlogImage->save();
					$i++;
				endforeach;
				$db->commit();
				$message = sprintf($GLOBALS['basic']['addedSuccess_'], "Blog Image(s)");
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>"No images to save"));
			endif;
		break;

		case "deleteBlogSubimage":
			$id = intval($_REQUEST['id']);
			$record = BlogImage::find_by_id($id);
			if($record):
				// Delete the image files
				if(file_exists(SITE_ROOT."images/blog/blogimages/".$record->image)):
					unlink(SITE_ROOT."images/blog/blogimages/".$record->image);
				endif;
				if(file_exists(SITE_ROOT."images/blog/blogimages/thumbnails/".$record->image)):
					unlink(SITE_ROOT."images/blog/blogimages/thumbnails/".$record->image);
				endif;
				
				$db->begin();
				$res = $db->query("DELETE FROM tbl_blog_images WHERE id='{$id}'");
				if($res): $db->commit();
					reOrderSub("tbl_blog_images", "sortorder", "blogid", $record->blogid);
					$message = sprintf($GLOBALS['basic']['deletedSuccess_'], "Blog Image");
					echo json_encode(array("action"=>"success","message"=>$message));
				else: $db->rollback();
					echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToDelete']));
				endif;
			else:
				echo json_encode(array("action"=>"error","message"=>"Image not found"));
			endif;
		break;

		case "updateBlogImageTitle":
			$id = intval($_REQUEST['id']);
			$title = addslashes($_REQUEST['title']);
			$db->query("UPDATE tbl_blog_images SET title='{$title}' WHERE id='{$id}'");
			echo json_encode(array("action"=>"success","title"=>$title));
		break;

		case "toggleBlogImageStatus":
			$id = intval($_REQUEST['id']);
			$status = intval($_REQUEST['status']);
			$newStatus = ($status == 1) ? 0 : 1;
			$db->query("UPDATE tbl_blog_images SET status='{$newStatus}' WHERE id='{$id}'");
			echo json_encode(array("action"=>"success","status"=>$newStatus));
		break;

		case "sortBlogImages":
			$sortIds = $_REQUEST['sortIds'];
			$record = BlogImage::find_by_id(intval(explode(";", $sortIds)[1]));
			datatableReordering('tbl_blog_images', $sortIds, "sortorder", 'blogid', $record->blogid);
			$message = sprintf($GLOBALS['basic']['sorted_'], "Blog Images");
			echo json_encode(array("action"=>"success","message"=>$message));
		break;
	}
?>