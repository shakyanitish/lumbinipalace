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
		$record = new Offers();

		$record->slug 			= create_slug($_REQUEST['title']);
		$record->title 			= $_REQUEST['title'];
		$record->tag 			= $_REQUEST['tag'];
		$record->image			= (!empty($_REQUEST['imageArrayname'])) ? $_REQUEST['imageArrayname'] : '';
		$record->list_image 	= (!empty($_REQUEST['imageArrayname3'])) ? $_REQUEST['imageArrayname3'] : '';
		$record->rate			= $_REQUEST['rate'];
		$record->discount		= $_REQUEST['discount'];
		//			$record->brief			= $_REQUEST['brief'];
		$record->content		= $_REQUEST['content'];
		$record->start_date		= $_REQUEST['start_date'];
		$record->offer_date		= $_REQUEST['offer_date'];
					$record->linksrc 		= $_REQUEST['linksrc'];
					$record->linktype 		= $_REQUEST['linktype'];
		$record->adults		    = $_REQUEST['adults'];
		//			$record->children		= $_REQUEST['children'];
		$record->type 		= $_REQUEST['type'];
		$record->offerpopup 		= $_REQUEST['offerpopup'];
		$record->status			= $_REQUEST['status'];
		$record->homepage 		= $_REQUEST['homepage'];
		
		$record->sortorder		= Offers::find_maximum();
		$record->added_date 	= registered();
		
		$checkDupliName = Offers::checkDupliName($record->title);
		if ($checkDupliName):
			echo json_encode(array("action" => "warning", "message" => "Articles Title Already Exists."));
			exit;
		endif;
		
		/*if(empty($_REQUEST['imageArrayname'])):
			echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
			exit;
		endif;*/
		
		$db->begin();
		if ($record->save()): $db->commit();
		
		//$offer_id = $db->getLastInsertId();
		$offer_id = $record->id;
		
		//pr($offer_id);
		foreach ($_POST as $kk => $vv) {
			$$kk = $vv;
		}
		$pEdit = false;
		if (!empty($offer_pax) and $type == 0) {
			$i = 1;
			foreach ($offer_pax as $k => $row) {
				$csql = "INSERT INTO tbl_offer_child SET offer_id='" . $offer_id . "', offer_pax='" . $offer_pax[$k] . "', offer_usd='" . $offer_usd[$k] . "', offer_no='" . $i . "' ";
				$db->query($csql);
				$i++;
			}
			
			$pEdit = true;
		} elseif (!empty($multi_offer_title) and $type == 2) {
			$i = 1;
			// pr( $offer_id );
			foreach ($multi_offer_title as $k => $row) {
				// if((!empty($multi_offer_title))
				$msql = "INSERT INTO tbl_offer_child SET offer_id='" . $offer_id . "', multi_offer_title='" . $multi_offer_title[$k] . "', multi_offer_npr='" . $multi_offer_npr[$k] . "', offer_no='" . $i . "' ";
				$db->query($msql);
				$i++;
			}
			$pEdit = true;
		}
		
		
		$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Offers '" . $record->title . "'");
		echo json_encode(array("action" => "success", "message" => $message));
		log_action("Offers [" . $record->title . "]" . $GLOBALS['basic']['addedSuccess'], 1, 3);
	else: $db->rollback();
	echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
endif;
break;

case "edit":
	$record = Offers::find_by_id($_REQUEST['idValue']);
	
	if ($record->title != $_REQUEST['title']) {
		$checkDupliName = Offers::checkDupliName($_REQUEST['title']);
		if ($checkDupliName):
			echo json_encode(array("action" => "warning", "message" => "Articles title is already exist."));
			exit;
		endif;
	}
	
	$record->slug 			= create_slug($_REQUEST['title']);
	$record->title 			= $_REQUEST['title'];
	$record->tag 			= $_REQUEST['tag'];
	$record->rate			= $_REQUEST['rate'];
	$record->discount		= $_REQUEST['discount'];
		//			$record->brief			= $_REQUEST['brief'];
		$record->content		= $_REQUEST['content'];
					$record->linksrc 		= $_REQUEST['linksrc'];
					$record->linktype 		= $_REQUEST['linktype'];
					$record->start_date		= $_REQUEST['start_date'];
					$record->offer_date		= $_REQUEST['offer_date'];
					$record->adults		    = $_REQUEST['adults'];
					//            $record->children		= $_REQUEST['children'];
					$record->type 		= $_REQUEST['type'];
					$record->status			= $_REQUEST['status'];
					$record->homepage = $_REQUEST['homepage'];
					$record->list_image 		    = !empty($_REQUEST['imageArrayname3'])?$_REQUEST['imageArrayname3']:'';
					$record->image 		    = !empty($_REQUEST['imageArrayname'])?$_REQUEST['imageArrayname']:'';
					$record->offerpopup 		= $_REQUEST['offerpopup'];
		// if (!empty($_REQUEST['imageArrayname3'])):
		// 	$record->list_image	= $_REQUEST['imageArrayname3'];
		// endif;

		// if (!empty($_REQUEST['imageArrayname'])):
		// 	$record->image		= $_REQUEST['imageArrayname'];
		// endif;

		foreach ($_POST as $kk => $vv) {
			$$kk = $vv;
		}
		// pr($_REQUEST,1);
		$pEdit = false;
		if (!empty($offer_pax) and $type == 0) {
			$i = 1;
			$db->query("DELETE FROM tbl_offer_child WHERE offer_id = $record->id ");
			foreach ($offer_pax as $k => $row) {
				$csql = "INSERT INTO tbl_offer_child SET offer_id='" . $record->id . "', offer_pax='" . $offer_pax[$k] . "', offer_usd='" . $offer_usd[$k] . "', offer_no='" . $i . "' ";
				$db->query($csql);
				$i++;
			}
			$pEdit = true;
		} elseif (!empty($multi_offer_title) and $type == 2) {
			$i = 1;
			$db->query("DELETE FROM tbl_offer_child WHERE offer_id = $record->id ");
			foreach ($multi_offer_title as $k => $row) {
				$msql = "INSERT INTO tbl_offer_child SET offer_id='" . $record->id . "', multi_offer_title='" . $multi_offer_title[$k] . "', multi_offer_npr='" . $multi_offer_npr[$k] . "', offer_no='" . $i . "' ";
				$db->query($msql);
				$i++;
			}
			$pEdit = true;
		} else {
			$db->query("DELETE FROM tbl_offer_child WHERE offer_id = $record->id ");
			$pEdit = true;
		}

		$db->begin();
		if ($record->save() or $pEdit): $db->commit();
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Offers '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Offers [" . $record->title . "] Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
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
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Offers Meta Data saved successfully");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Offers Meta Data Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;
			
			
	
		break;

	case "delete":
		$id = $_REQUEST['id'];
		$record = Offers::find_by_id($id);
		log_action("Offerss  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		$db->query("DELETE FROM tbl_offers WHERE id='{$id}'");
		$db->query("DELETE FROM tbl_offer_child WHERE offer_id = '{$id}' ");

		reOrder("tbl_offers", "sortorder");

		$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Offers '" . $record->title . "'");
		echo json_encode(array("action" => "success", "message" => $message));
		log_action("Offers  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

		// Module Setting Sections  >> <<
	case "toggleStatus":
		$id = $_REQUEST['id'];
		$record = Offers::find_by_id($id);
		$record->status = ($record->status == 1) ? 0 : 1;
		$record->save();
		echo "";
		break;

	case "bulkToggleStatus":
		$id = $_REQUEST['idArray'];
		$allid = explode("|", $id);
		$return = "0";
		for ($i = 1; $i < count($allid); $i++) {
			$record = Offers::find_by_id($allid[$i]);
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
			$record = Offers::find_by_id($allid[$i]);
			log_action("Offers  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
			$res = $db->query("DELETE FROM tbl_offers WHERE id='" . $allid[$i] . "'");
			$db->query("DELETE FROM tbl_offer_child WHERE offer_id = '" . $allid[$i] . "' ");
			$return = 1;
		}
		if ($res) $db->commit();
		else $db->rollback();
		reOrder("tbl_offers", "sortorder");

		if ($return == 1):
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Offers");
			echo json_encode(array("action" => "success", "message" => $message));
		else:
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
		endif;
		break;

	case "sort":
		$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
		$sortIds = $_REQUEST['sortIds'];
		$posId   = Offers::field_by_id($id, 'type');
		datatableReordering('tbl_offers', $sortIds, "sortorder", '', '', 1);
		$message  = sprintf($GLOBALS['basic']['sorted_'], "Offers");
		echo json_encode(array("action" => "success", "message" => $message));
		break;
}
