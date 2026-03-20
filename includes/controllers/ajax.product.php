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
            $record = new Product();

            $record->name       = $_REQUEST['name'];
            $record->title      = $_REQUEST['title'];
            $record->img_thumb  = (!empty($_REQUEST['imageThumb'])) ? $_REQUEST['imageThumb'] : '';
            $record->img_jpg    = (!empty($_REQUEST['imageJpg'])) ? $_REQUEST['imageJpg'] : '';
            $record->img_png    = (!empty($_REQUEST['imagePng'])) ? $_REQUEST['imagePng'] : '';
            $record->img_svg    = (!empty($_REQUEST['imageSvg'])) ? $_REQUEST['imageSvg'] : '';
            $record->content    = $_REQUEST['content'];
            $record->status     = $_REQUEST['status'];

            $record->sortorder  = Product::find_maximum();
            $record->added_date = registered();
            $record->modified_date = registered();

            $db->begin();
            if ($record->save()): $db->commit();
                $message = sprintf($GLOBALS['basic']['addedSuccess_'], "Product '" . $record->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 3);
            else:
                $db->rollback();
                echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
            endif;

        break;

        case "edit":
            $record = Product::find_by_id($_REQUEST['idValue']);

            $record->name       = $_REQUEST['name'];
            $record->title      = $_REQUEST['title'];
            $record->img_thumb  = (!empty($_REQUEST['imageThumb'])) ? $_REQUEST['imageThumb'] : '';
            $record->img_jpg    = (!empty($_REQUEST['imageJpg'])) ? $_REQUEST['imageJpg'] : '';
            $record->img_png    = (!empty($_REQUEST['imagePng'])) ? $_REQUEST['imagePng'] : '';
            $record->img_svg    = (!empty($_REQUEST['imageSvg'])) ? $_REQUEST['imageSvg'] : '';
            $record->content    = $_REQUEST['content'];
            $record->status     = $_REQUEST['status'];

            $record->modified_date = registered();

            $db->begin();
            if ($record->save()):$db->commit();
                $message = sprintf($GLOBALS['basic']['changesSaved_'], "Product '" . $record->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action($message, 1, 4);
            else: $db->rollback();
                echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
            endif;

        break;

        case "delete":
            $id         = $_REQUEST['id'];
            $record     = Product::find_by_id($id);

            $db->begin();
            $res        = $db->query("DELETE FROM tbl_product WHERE id='{$id}'");
            if ($res): $db->commit();
            else: $db->rollback(); endif;

            reOrder("tbl_product", "sortorder");
            $message = sprintf($GLOBALS['basic']['deletedSuccess_'], "Product '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("Product [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
        break;

        // Module Setting Sections  >> <<
        case "toggleStatus":
            $id         = $_REQUEST['id'];
            $record     = Product::find_by_id($id);
            $record->status = ($record->status == 1) ? 0 : 1;
            $record->save();
            echo "";
        break;

        case "bulkToggleStatus":
            $id         = $_REQUEST['idArray'];
            $allid      = explode("|", $id);
            $return     = "0";
            for ($i = 1; $i < count($allid); $i++) {
                $record = Product::find_by_id($allid[$i]);
                $record->status = ($record->status == 1) ? 0 : 1;
                $record->save();
            }
            echo "";
        break;

        case "bulkDelete":
            $id         = $_REQUEST['idArray'];
            $allid      = explode("|", $id);
            $return     = "0";
            $db->begin();
            for ($i = 1; $i < count($allid); $i++) {
                $res = $db->query("DELETE FROM tbl_product WHERE id='" . $allid[$i] . "'");
                $return = 1;
            }
            if ($res) $db->commit(); else $db->rollback();
            reOrder("tbl_product", "sortorder");

            if ($return == 1):
                $message = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Products");
                echo json_encode(array("action" => "success", "message" => $message));
            else:
                echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
            endif;
        break;

        case "sort":
            $id         = $_REQUEST['id'];    // IS a line containing ids starting with : sortIds
            $sortIds    = $_REQUEST['sortIds'];
            datatableReordering('tbl_product', $sortIds, "sortorder", '', '', 1);
            $message    = sprintf($GLOBALS['basic']['sorted_'], "Product");
            echo json_encode(array("action" => "success", "message" => $message));
        break;
	}
?>