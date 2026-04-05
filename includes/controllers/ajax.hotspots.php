<?php 
// LOAD SYSTEM FILES 
require_once('../initialize.php');

//ACTIONS AFTER AJAX SUBMISSION - ADD EDIT AND DELETE TO SAVE DATA IN DATABASE
$action = $_REQUEST['action'];
	switch($action) 
	{		
		//IF ADDING HOTSPOT SAVING REQUESTS FROM FORM TO DATABASE TABLE FIELDS	
		case "addHotspot":
			$hotspot = new Hotspots();
            $hotspot->title    			= $_REQUEST['title'];
			$hotspot->hotspot_pitch 	= $_REQUEST['hotspot_pitch'];
			$hotspot->hotspot_yaw 		= $_REQUEST['hotspot_yaw']; 
			$hotspot->hotspot_text 		= (!empty($_REQUEST['hotspot_text'])) ? $_REQUEST['hotspot_text'] : '';
			$hotspot->hotspot_type 		= $_REQUEST['hotspot_type'];
			$hotspot->scene_id 			= $_REQUEST['scene_id'];
			$hotspot->target_yaw		= $_REQUEST['target_yaw'];
			$hotspot->target_pitch 		= $_REQUEST['target_pitch'];
			$hotspot->three60_id 		= $_REQUEST['threeId'];
			$hotspot->status			= $_REQUEST['status'];
			$hotspot->sortorder			= Hotspots::find_maximum();
			$hotspot->added_date 		= registered();

			//DATABASE SQL QUERY TO RUN
            $db->begin();
            if ($hotspot->save()): $db->commit();
                $message = sprintf($GLOBALS['basic']['addedSuccess_'], "Hotspot '" . $hotspot->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 3);
            else: $db->rollback();
                echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
            endif;
		break;    

		//FOR EDIT A HOTSPOT 
        case "editHotspot":
			$newArr = array();
			//FINDING PREVIOUS DATA FROM TABLE FOR EDIT AND OVERWRITING
            $hotspot = Hotspots::find_by_id($_REQUEST['idValue']);

            $hotspot->title    			= $_REQUEST['title'];
			$hotspot->hotspot_pitch 	= $_REQUEST['hotspot_pitch'];
			$hotspot->hotspot_yaw 		= $_REQUEST['hotspot_yaw']; 
			$hotspot->hotspot_text 		= (!empty($_REQUEST['hotspot_text'])) ? $_REQUEST['hotspot_text'] : '';
			$hotspot->hotspot_type 		= $_REQUEST['hotspot_type'];
			$hotspot->scene_id 			= $_REQUEST['scene_id'];
			$hotspot->target_yaw		= $_REQUEST['target_yaw'];
			$hotspot->target_pitch 		= $_REQUEST['target_pitch'];
			$hotspot->three60_id 		= $_REQUEST['threeId'];
			$hotspot->status		    = $_REQUEST['status'];

			//DATABASE SQL QUERY TO RUN
            $db->begin();
            if ($hotspot->save()):
                $db->commit();
                $message = sprintf($GLOBALS['basic']['changesSaved_'], "Hotspot '" . $hotspot->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 4);
            else:
                $db->rollback();
                echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
            endif;
		break;
		
		//FOR DELETE A HOSPOT	
        case "deleteHotspot":
            //FINDING RELATED DATA BY ID USING QUERY ON DATABASE AND DELETE
            $id         = $_REQUEST['id'];
            $hotspot    = Hotspots::find_by_id($id);
            $db->query("DELETE FROM tbl_vt_hotspots WHERE id='{$id}'");

            //REORDING THE REMAINING LIST OF TABLE
            reOrder("tbl_vt_hotspots", "sortorder");

            //SUCCESS MESSAGE
            $message = sprintf($GLOBALS['basic']['deletedSuccess_'], "Hotspot '" . $hotspot->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("Image  [" . $hotspot->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

		//FOR SORTING THE DATA INORDER OF IDS
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Hotspots::field_by_id($id,'three60_id');
            datatableReordering('tbl_vt_hotspots', $sortIds, "sortorder", "three60_id", $posId, 1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "360Image"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;	
		
		//SELECT OPTION FOR 360 IMAGE
		case "filter360Image":
            $virtualId  = addslashes($_REQUEST['virtualId']);
            $threeId    = addslashes($_REQUEST['selct']);
            $hotspot    = Image360::get_all_scene_data($virtualId, '', $threeId);
            echo json_encode(array("action" => "success", "result" => $hotspot));
        break;

		//FOR STATUS TOOGLER TO PUBLISH OR UNPUBLISH
		case "hotspotToggleStatus":
            $id             = $_REQUEST['id'];
            $record         = Hotspots::find_by_id($id);
            $record->status = ($record->status == 1) ? 0 : 1;
            $db->begin();
            $res = $record->save();
            if ($res): $db->commit(); else: $db->rollback(); endif;
            echo "";
		break;

        case "bulkToggleStatus":
            $id         = $_REQUEST['idArray'];
            $allid      = explode("|", $id);
            $return     = "0";
            for ($i = 1; $i < count($allid); $i++) {
                $record = Hotspots::find_by_id($allid[$i]);
                $record->status = ($record->status == 1) ? 0 : 1;
                $record->save();
            }
            echo "";
        break;

        case "bulkDelete":
            $id     = $_REQUEST['idArray'];
            $allid  = explode("|", $id);
            $return = "0";
            $db->begin();
            for ($i = 1; $i < count($allid); $i++) {
                $record = Hotspots::find_by_id($allid[$i]);
                $res    = $db->query("DELETE FROM tbl_vt_hotspots WHERE id='" . $allid[$i] . "'");
                reOrderSub("tbl_vt_hotspots", "sortorder", "three60_id", $record->three60_id);
                $return = 1;
            }
            if ($res) $db->commit(); else $db->rollback();

            if ($return == 1):
                $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Hotspot");
                echo json_encode(array("action"=>"success","message"=>$message));
            else:
                echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
            endif;
        break;

    }
?>