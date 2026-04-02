<?php 
// LOAD SYSTEM FILES 
require_once('../initialize.php');
//ACTIONS AFTER SUBMISSION OF AJAX SUBMISSON - ADD,EDIT AND DELETE TO SAVE IN DATABASE
$action = $_REQUEST['action'];

	switch($action)
	{		
		//IF ADDING NEW 360 IMAGE SAVING REQUESTS FROM FORM TO DATABASE TABLE FIELDS	
		case "addThree60":
			
			if(empty($_REQUEST['imageArrayname360'])):				
				echo json_encode(array("action"=>"warning","message"=>"Required 360 Image Upload Image !"));
				exit;					
			endif;

			$three60 = new Image360();
    
            $three60->title    				= $_REQUEST['title'];
            $three60->hfov     				= $_REQUEST['hfov'];
            $three60->pitch    				= $_REQUEST['pitch'];
            $three60->yaw     				= $_REQUEST['yaw'];
			$three60->panorama 				= $_REQUEST['imageArrayname360'];
			$three60->virtual_tour_id 		= $_REQUEST['virtual_tour_id'];
			$three60->status				= $_REQUEST['status'];
			$three60->sortorder				= Image360::find_maximum();
			$three60->added_date 			= registered();

			//DATABASE SQL QUERY TO SAVE AND PASS MESSAGE
            $db->begin();
            if ($three60->save()): $db->commit();
                $message = sprintf($GLOBALS['basic']['addedSuccess_'], "360 Image '" . $three60->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 3);
            else: $db->rollback();
                echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
			endif;	
		break;    

       //FOR EDIT 360 IMAGE
        case "editThree60":

			if(empty($_REQUEST['imageArrayname360'])):
				echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
				exit;
			endif;

			$newArr = array();
            //UPDATING EXISTING DATA FROM DATABASE
            $three60 = Image360::find_by_id($_REQUEST['idValue']);
            $three60->title    				= $_REQUEST['title'];
            $three60->hfov     				= $_REQUEST['hfov'];      
            $three60->pitch    				= $_REQUEST['pitch'];
            $three60->yaw      				= $_REQUEST['yaw'];        
			$three60->panorama 				= $_REQUEST['imageArrayname360'];
			$three60->virtual_tour_id 		= $_REQUEST['virtual_tour_id'];
			$three60->status				= $_REQUEST['status'];

			//DATABASE SQL QUERY TO SAVE AND PASS MESSAGE
			
			
           $db->begin();
			//DATABASE SQL QUERY TO SAVE AND PASS MESSAGE
            if ($three60->save()): $db->commit();
                $message = sprintf($GLOBALS['basic']['changesSaved_'], "360 Image '" . $three60->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 4);
            else: $db->rollback();
                echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
			endif;	
		break;

		//FOR DELETE AN IMAGE(360)
        case "deleteThree60":
            $id         = $_REQUEST['id'];
            $three60    = Image360::find_by_id($id);
            $db->query("DELETE FROM tbl_vt_360_images WHERE id='{$id}'");
            //REORDING THE REMAINING LIST
            reOrder("tbl_vt_360_images", "sortorder");
            //DATABASE SQL QUERY TO SAVE AND PASS MESSAGE
            $message = sprintf($GLOBALS['basic']['deletedSuccess_'], "360 Image '" . $three60->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("360 Image [" . $three60->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

		//FOR SORTING THE DATA INORDER OF IDS
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Image360::field_by_id($id,'virtual_tour_id');
            datatableReordering('tbl_vt_360_images', $sortIds, "sortorder", "virtual_tour_id", $posId, 1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "360Image"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;	

		//FOR STATUS TOOGLER TO PUBLISH OR UNPUBLISH
		case "imageToggleStatus":
            $id             = $_REQUEST['id'];
            $record         = Image360::find_by_id($id);
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
                $record = Image360::find_by_id($allid[$i]);
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
                $record = Image360::find_by_id($allid[$i]);
                $res    = $db->query("DELETE FROM tbl_vt_360_images WHERE id='" . $allid[$i] . "'");
                reOrderSub("tbl_vt_360_images", "sortorder", "virtual_tour_id", $record->virtual_tour_id);
                $return = 1;
            }
            if ($res) $db->commit(); else $db->rollback();

            if ($return == 1):
                $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "360Image");
                echo json_encode(array("action"=>"success","message"=>$message));
            else:
                echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
            endif;
        break;

    }

?>