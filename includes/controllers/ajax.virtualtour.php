<?php 
// LOAD SYSTEM FILES 
require_once('../initialize.php');
//ACTIONS AFTER AJAX SUBMISSION - ADD EDIT AND DELETE TO SAVE DATA IN DATABASE
$action = $_REQUEST['action'];
				
	switch($action) 
	
	{	//ADDING VIRTUAL TOUR SAVING REQUESTS FROM FORM TO DATABASE TABLE FIELDS	
		case "addVirtual":

			$virtual = new VirtualTour();
        
            $virtual->title    			   	= $_REQUEST['title'];
			$virtual->scene_fade_duration 	= $_REQUEST['scene_fade_duration'];
			$virtual->status				= $_REQUEST['status'];
            $virtual->image_width           = $_REQUEST['image_width'];
            $virtual->image_height          = $_REQUEST['image_height'];
            $virtual->hotspot_icon          = (isset($_REQUEST['imageArraynameIcon'])) ? $_REQUEST['imageArraynameIcon'] : '';
			$virtual->sortorder			    = VirtualTour::find_maximum();
			$virtual->added_date 			= registered();

            //HOTSPOT ICON MESSAGE, IF ICON IS NOT UPLOADED
            /*
            if(empty($_REQUEST['imageArraynameIcon'])):
				echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
				exit;					
			endif;
            */

			//DATABASE SQL QUERIES TO SAVE AND PASS MESSAGE
			$db->begin();
            if ($virtual->save()): $db->commit();
                $message = sprintf($GLOBALS['basic']['addedSuccess_'], "Virtual Tour '" . $virtual->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 3);
            else: $db->rollback();
                echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
			endif;	
		break;    

        //EDIT VIRTUAL TOUR 
        case "editVirtual":

			$newArr = array();

            $virtual = VirtualTour::find_by_id($_REQUEST['idValue']);
             //HOTSPOT ICONS MESSAGE, IF ICON MISSING OR EMPTY
             /*
             if(empty($_REQUEST['imageArraynameIcon'])):
				echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
				exit;					
			endif;
            */

			$virtual->title    				= $_REQUEST['title'];
			$virtual->scene_fade_duration 	= $_REQUEST['scene_fade_duration'];
            $virtual->image_width           = $_REQUEST['image_width'];
            $virtual->image_height          = $_REQUEST['image_height'];
            $virtual->hotspot_icon          = (isset($_REQUEST['imageArraynameIcon'])) ? $_REQUEST['imageArraynameIcon'] : '';
			$virtual->status				= $_REQUEST['status'];

			//DATABASE SQL QUERIES TO SAVE AND PASS MESSSAGE 
           $db->begin();

            if ($virtual->save()): $db->commit();
                $message = sprintf($GLOBALS['basic']['changesSaved_'], "Virtual Tour '" . $virtual->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 4);
            else: $db->rollback();
                echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
			endif;	
		break;

        //DELETE VIRTUAL TOUR
        case "deleteVirtual":
            $id         = $_REQUEST['id'];
            $virtual    = VirtualTour::find_by_id($id);
            $db->query("DELETE FROM tbl_vt_virtual_tour WHERE id='{$id}'");
            //REORDERING THE REMAINING LIST OF TABLE
            reOrder("tbl_vt_virtual_tour", "sortorder");
            //DATABASE SQL QUERIES TO SAVE AND PASS MESSAGE
            $message = sprintf($GLOBALS['basic']['deletedSuccess_'], "Virtual Tour '" . $virtual->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("Virtual Tour [" . $virtual->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

        //SORTING DATA INORDER OF IDS
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
            datatableReordering('tbl_vt_virtual_tour', $sortIds, "sortorder", '', '', 1);
            $message = sprintf($GLOBALS['basic']['sorted_'], "Virtual Tour");
            echo json_encode(array("action" => "success", "message" => $message));
		break;	

		//FOR STATUS TOOGLER TO PUBLISH OR UNPUBLISH
		case "toggleStatus":
			$id         = $_REQUEST['id'];
			$record     = VirtualTour::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
            $db->begin();
            $res        = $record->save();
            if ($res): $db->commit(); else: $db->rollback(); endif;
            echo "";
		break;

        //FOR BULK STATUS TOOGLER TO PUBLISH OR UNPUBLISH ON TABLE
        case "bulkToggleStatus":
            $id     = $_REQUEST['idArray'];
            $allid  = explode("|", $id);
            $return = "0";
            for ($i = 1; $i < count($allid); $i++) {
                $record = VirtualTour::find_by_id($allid[$i]);
                $record->status = ($record->status == 1) ? 0 : 1;
                $record->save();
            }
            echo "";
        break;

        //FOR BULK DELETE FROM TABLE
        case "bulkDelete":
            $id     = $_REQUEST['idArray'];
            $allid  = explode("|", $id);
            $return = "0";
            $db->begin();
            for ($i = 1; $i < count($allid); $i++) {
                $res = $db->query("DELETE FROM tbl_vt_virtual_tour WHERE id='" . $allid[$i] . "'");
                $return = 1;
            }
            if ($res) $db->commit(); else $db->rollback();
            reOrder("tbl_vt_virtual_tour", "sortorder");

            if ($return == 1):
                $message = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Virtual Tour");
                echo json_encode(array("action" => "success", "message" => $message));
            else:
                echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
            endif;
        break;

    }


?>