<?php
// Load the header files first
header("Expires: 0"); // no cache
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("cache-control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Load necessary files then...
require_once('../initialize.php');

$action = $_REQUEST['action'];

switch ($action) {
	case "slug":
		$slug = $msg = '';
		if (!empty($_REQUEST['title'])) {
			$nslug = create_slug($_REQUEST['title']);
			$chk = check_slug($_REQUEST['actid'], $nslug);
			if ($chk == '1') {
				$msg = "Slug already exists !";
			} else {
				$slug = $nslug;
			}
		}
		echo json_encode(array('msgs' => $msg, 'result' => $slug));
		break;
	case "add":
		$record = new Page();

		$record->slug 		= $_REQUEST['slug'];
		$record->title 		= $_REQUEST['title'];
		$record->image			= !empty($_REQUEST['imageArrayname']) ? $_REQUEST['imageArrayname'] : '';
		$record->homepage	= $_REQUEST['homepage'];

		$record->gallery_images = serialize(array_values(array_filter($_REQUEST['galleryArrayname']))); // for gallery images serialize	
		$record->content	= $_REQUEST['content'];
		$record->content2	= $_REQUEST['content2'];
		$record->content3	= $_REQUEST['content3'];
		$record->status		= $_REQUEST['status'];
		$record->meta_title		= $_REQUEST['meta_title'];
		$record->meta_keywords		= $_REQUEST['meta_keywords'];
		$record->meta_description	= $_REQUEST['meta_description'];
		$record->sortorder	= Page::find_maximum();
		$record->added_date = registered();
		$record->modified_date = registered();
		$record->date = $_REQUEST['date'];



		$checkDupliName = Page::checkDupliName($record->title);
		if ($checkDupliName):
			echo json_encode(array("action" => "warning", "message" => "Pages Title Already Exists."));
			exit;
		endif;
		$db->begin();
		if ($record->save()): $db->commit();
			// Global slug table storeSlug(class name, main slug, store id);
			// $act_id = $db->insert_id();
			$qry = $db->query("SELECT LAST_INSERT_ID() as lastId");
			$row = $db->fetch_object($qry);
			$act_id = $row->lastId;
			storeSlug('Page', $_REQUEST['slug'], $act_id);
			// End function
			$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Page '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action($message, 1, 3);
		else:
			$db->rollback();
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
		endif;
		break;

	case "edit":
		$record = Page::find_by_id($_REQUEST['idValue']);

		if ($record->title != $_REQUEST['title']) {
			$checkDupliName = Page::checkDupliName($_REQUEST['title']);
			if ($checkDupliName):
				echo json_encode(array("action" => "warning", "message" => "Pages title is already exist."));
				exit;
			endif;
		}

		$record->slug 		= $_REQUEST['slug'];
		$record->title 		= $_REQUEST['title'];
		$record->upcoming 		= $_REQUEST['upcoming'];
		$record->homepage	= $_REQUEST['homepage'];

		$record->image			= !empty($_REQUEST['imageArrayname']) ? $_REQUEST['imageArrayname'] : '';
		$record->content	= $_REQUEST['content'];
		$record->content2	= $_REQUEST['content2'];
		$record->content3	= $_REQUEST['content3'];
		$record->status		= $_REQUEST['status'];
		$record->meta_title		= $_REQUEST['meta_title'];
		$record->meta_keywords		= $_REQUEST['meta_keywords'];
		$record->meta_description	= $_REQUEST['meta_description'];
		$record->modified_date      = registered();
		$record->gallery_images = serialize(array_values(array_filter($_REQUEST['galleryArrayname']))); // for gallery images
		$record->date = $_REQUEST['date'];



		$db->begin();
		if ($record->save()): $db->commit();
			// Global slug table storeSlug(class name, main slug, store id);
			$act_id = $_REQUEST['idValue'];
			storeSlug('Page', $_REQUEST['slug'], $act_id);
			// End function
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Page '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action($message, 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;
		break;

	case "delete":
		$id = $_REQUEST['id'];
		$record = Page::find_by_id($id);
		// Global slug table deleteSlug(class name, store id);
		deleteSlug('Page', $id);
		// End function
		$db->begin();
		$res = $db->query("DELETE FROM tbl_pages WHERE id='{$id}'");
		if ($res): $db->commit();
		else: $db->rollback();
		endif;
		reOrder("tbl_pages", "sortorder");
		$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Page '" . $record->title . "'");
		echo json_encode(array("action" => "success", "message" => $message));
		log_action("Pages  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

	// Module Setting Sections  >> <<
	case "toggleStatus":
		$id = $_REQUEST['id'];
		$record = Page::find_by_id($id);
		$record->status = ($record->status == 1) ? 0 : 1;
		$record->save();
		echo "";
		break;

	case "bulkToggleStatus":
		$id = $_REQUEST['idArray'];
		$allid = explode("|", $id);
		$return = "0";
		for ($i = 1; $i < count($allid); $i++) {
			$record = Page::find_by_id($allid[$i]);
			$record->status = ($record->status == 1) ? 0 : 1;
			$record->save();
		}
		echo "";
		break;

	case "bulkDelete":
		$id = $_REQUEST['idArray'];
		$allid = explode("|", $id);
		$return = "0";
		$db->begin();
		for ($i = 1; $i < count($allid); $i++) {
			$res  = $db->query("DELETE FROM tbl_pages WHERE id='" . $allid[$i] . "'");
			deleteSlug('Page', $allid[$i]);
			$return = 1;
		}
		if ($res) $db->commit();
		else $db->rollback();
		reOrder("tbl_pages", "sortorder");

		if ($return == 1):
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Page");
			echo json_encode(array("action" => "success", "message" => $message));
		else:
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
		endif;
		break;

	case "sort":
		$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
		$sortIds = $_REQUEST['sortIds'];
		datatableReordering('tbl_pages', $sortIds, "sortorder", '', '', 1);
		$message  = sprintf($GLOBALS['basic']['sorted_'], "Page");
		echo json_encode(array("action" => "success", "message" => $message));
		break;
}
