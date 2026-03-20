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
    case "addNewUser":
        $record = new Usergrouptype();

        $record->group_name     = $_REQUEST['group_name'];
//        $record->group_type     = $_REQUEST['group_type'];
        $record->description    = (!empty($_REQUEST['description'])) ? $_REQUEST['description'] : '';
        $record->status         = $_REQUEST['status'];
        
        $module_id = !empty($_REQUEST['module_id']) ? $_REQUEST['module_id'] : array();
        $record->permission = serialize($module_id);

        $checkDupliUname = Usergrouptype::checkDupliUname($record->group_name);
        if ($checkDupliUname):
            echo json_encode(array("action" => "warning", "message" => "Group Name Already Exists."));
            exit;
        endif;

        $db->begin();
        if ($record->save()): $db->commit();
            $message = sprintf($GLOBALS['basic']['addedSuccess_'], "User Group '" . $record->group_name . " '");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("User Group [" . $record->group_name . "] login Created " . $GLOBALS['basic']['addedSuccess'], 1, 3);
        else: $db->rollback();
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        endif;
    break;

    case "editNewUser":
        $record = Usergrouptype::find_by_id($_REQUEST['idValue']);

        $record->group_name     = $_REQUEST['group_name'];
//        $record->group_type     = $_REQUEST['group_type'];
        $record->description    = (!empty($_REQUEST['description'])) ? $_REQUEST['description'] : '';
        $record->status         = $_REQUEST['status'];

        $module_id = !empty($_REQUEST['module_id']) ? $_REQUEST['module_id'] : array();
        $record->permission = serialize($module_id);

        if ($record->group_name != $_REQUEST['group_name']) {
            $checkDupliUname = Usergrouptype::checkDupliUname($_REQUEST['group_name']);
            if ($checkDupliUname):
                echo json_encode(array("action" => "warning", "message" => "Group Name Already Exists."));
                exit;
            endif;
        }

        $db->begin();
        if ($record->save()):
            $db->commit();
            $message = sprintf($GLOBALS['basic']['changesSaved_'], "User Group '" . $record->group_name. "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("User Group [" . $record->group_name . "] Edit Successfully", 1, 4);
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
    break;

    case "userPermission":
        $record = Usergrouptype::find_by_id($_REQUEST['idValue']);

        $module_id = !empty($_REQUEST['module_id']) ? $_REQUEST['module_id'] : array();
        $record->permission = serialize($module_id);

        $db->begin();
        if ($record->save()): $db->commit();
            $message = sprintf($GLOBALS['basic']['changesSaved_'], "User Group '" . $record->group_name . " Permissions'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("User Group [" . $record->group_name . "] Permissions Edit Successfully", 1, 4);
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
    break;

    case "delete":
        $id         = $_REQUEST['id'];
        $record     = Usergrouptype::find_by_id($id);
        $db->begin();
        $res        = $db->query("DELETE FROM tbl_group_type WHERE id='{$id}'");
        if ($res): $db->commit();
        else: $db->rollback(); endif;
//        reOrder("tbl_group_type", "sortorder");

        $message    = sprintf($GLOBALS['basic']['deletedSuccess_'], "User Group '" . $record->group_name . "'");
        echo json_encode(array("action" => "success", "message" => $message));
        log_action("User Group [" . $record->group_name . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
    break;

    // Module Setting Sections  >> <<
    case "toggleStatus":
        $id             = $_REQUEST['id'];
        $record         = Usergrouptype::find_by_id($id);
        $record->status = ($record->status == 1) ? 0 : 1;
        $db->begin();
        $res            = $record->save();
        if ($res): $db->commit();
        else: $db->rollback(); endif;
        echo "";
    break;
}
?>