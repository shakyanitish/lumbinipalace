<?php
// Load the header files first
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("cache-control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Load necessary files then...
require_once('../initialize.php');

$action = $_REQUEST['action'];

switch ($action) {
	case "add":
		$record = new Video();

		$record->source = $_REQUEST['source'];
		$record->url_type = $_REQUEST['url_type'];
		// $record->meta_title = $_REQUEST['meta_title'];
		// $record->meta_keywords = $_REQUEST['meta_keywords'];
		// $record->meta_description = $_REQUEST['meta_description'];

		$vRow = getMyvideo($record->source, $record->url_type);
		$record->title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : $vRow['title'];
		$record->thumb_image = $vRow['thumb_image'];
		$record->url = $vRow['url'];
		$record->host = $vRow['host'];
		$record->content = $vRow['content'];
		$record->class = $vRow['class'];


		$record->status = 1;
		$record->sortorder = Video::find_maximum();
		$record->added_date = registered();

		$db->begin();
		if ($record->save()):
			$db->commit();
			$message = sprintf($GLOBALS['basic']['addedSuccess_'], "Video '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Video [" . $record->title . "]" . $GLOBALS['basic']['addedSuccess'], 1, 3);
		else:
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
		endif;
		break;

	case "editExistsRecord":
		$id = addslashes($_REQUEST['id']);
		$record = Video::find_by_id($id);
		echo json_encode(array("editId" => $record->id, "title" => $record->title, "host" => $record->host, "vsource" => $record->source, "url_type" => $record->url_type));
		break;

	case "edit":
		$record = Video::find_by_id($_REQUEST['idValue']);



		
//			$record->title 		= $_REQUEST['title'];
		$record->source = $_REQUEST['source'];
		$record->url_type = $_REQUEST['url_type'];

		$vRow = getMyvideo($record->source, $record->url_type);
		$record->title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : $vRow['title'];
		$record->thumb_image = $vRow['thumb_image'];
		$record->url = $vRow['url'];
		$record->host = $vRow['host'];
		$record->content = $vRow['content'];
		$record->class = $vRow['class'];
		$record->meta_title = $_REQUEST['meta_title'];
		$record->meta_keywords = $_REQUEST['meta_keywords'];
		$record->meta_description = $_REQUEST['meta_description'];

		/* if($record->title!=$_REQUEST['title']){
		 $checkDupliUname=Video::checkDupliTitle($_REQUEST['title']);
		 if($checkDupliUname):
		 echo json_encode(array("action"=>"warning","message"=>"Video Title Already Exists."));		
		 exit;		
		 endif;
		 } */

		$db->begin();
		if ($record->save()):
			$db->commit();
			$message = sprintf($GLOBALS['basic']['changesSaved_'], "Video '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Video [" . $record->title . "] Edit Successfully", 1, 4);
		else:
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;
		break;

	case "editVideoTitle":
		$id = intval($_REQUEST['id']);
		$title = addslashes($_REQUEST['title']);
		$record = Video::find_by_id($id);
		$record->title = $title;
		$db->begin();
		if ($record->save()):
			$db->commit();
			echo json_encode(array("action" => "success", "message" => "Title updated successfully."));
		else:
			echo json_encode(array("action" => "error", "message" => "Unable to update title."));
		endif;
		break;

	case "delete":
		$id = $_REQUEST['id'];
		$record = Video::find_by_id($id);
		$db->query("DELETE FROM tbl_video WHERE id='{$id}'");

		reOrder("tbl_video", "sortorder");

		$message = sprintf($GLOBALS['basic']['deletedSuccess_'], "Video '" . $record->title . "'");
		echo json_encode(array("action" => "success", "message" => $message));
		log_action("Video  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

	// Module Setting Sections  >> <<
	case "toggleStatus":
		$id = $_REQUEST['id'];
		$record = Video::find_by_id($id);
		$record->status = ($record->status == 1) ? 0 : 1;
		$record->save();
		echo "";
		break;

	case "sort":
		$id = $_REQUEST['id']; // IS a line containing ids starting with : sortIds
		$record = Video::find_by_id($id);
		$sortIds = $_REQUEST['sortIds'];

		datatableReordering('tbl_video', $sortIds, "sortorder", '', '', 1);
		$message = sprintf($GLOBALS['basic']['sorted_'], "Video");
		echo json_encode(array("action" => "success", "message" => $message));
		break;

	case "metadata":
		$page_name = $_REQUEST['page_name'];
		$metatitle = $_REQUEST['meta_title'];
		$metakeywords = $_REQUEST['meta_keywords'];
		$metadescription = $_REQUEST['meta_description'];
		$addeddate = registered();
		// pr("SELECT * FROM tbl_metadata WHERE page_name='$page_name' LIMIT 1");
		$metasql = $db->query("SELECT * FROM tbl_metadata WHERE page_name='$page_name'LIMIT 1");
		$metadata = $metasql->fetch_array();

		$metaexist = !empty($metadata) ? array_shift($metadata) : false;
		if ($metaexist) {
			$metadata = "UPDATE tbl_metadata SET meta_title='" . $_REQUEST['meta_title'] . "', meta_keywords='" . $_REQUEST['meta_keywords'] . "', meta_description='" . $_REQUEST['meta_description'] . "' WHERE page_name='" . $_REQUEST['page_name'] . "'";
		}
		else {
			$metadata = "INSERT INTO tbl_metadata SET module_id='" . $_REQUEST['module_id'] . "', page_name='" . $_REQUEST['page_name'] . "', meta_title='" . $_REQUEST['meta_title'] . "', meta_keywords='" . $_REQUEST['meta_keywords'] . "', meta_description='" . $_REQUEST['meta_description'] . "', added_date='" . $addeddate . "'";
		}
		$db->begin();
		$sucess = $db->query($metadata);
		if ($sucess == 1):
			$db->commit();
			$message = sprintf($GLOBALS['basic']['changesSaved_'], "Video Meta Data saved successfully");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Video Meta Data Edit Successfully", 1, 4);
		else:
			$db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;
		break;

	case "editVideoTitle":
		$id = addslashes($_REQUEST['id']);
		$title = addslashes($_REQUEST['title']);
		$record = Video::find_by_id($id);
		$record->title = $title;
		$db->begin();
		if ($record->save()):
			$db->commit();
			echo json_encode(array("action" => "success", "message" => "Video title updated successfully."));
			log_action("Video [" . $record->title . "] title updated", 1, 4);
		else:
			$db->rollback();
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
		endif;
		break;
}
?>