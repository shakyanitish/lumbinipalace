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

        $Package = new Package();

        $Package->slug             = $_REQUEST['slug'];
        $Package->title            = $_REQUEST['title'];
        $Package->sub_title        = $_REQUEST['sub_title'];
        // $Package->program_date = !empty($_REQUEST['program_date'])
            // ? date('Y-m-d', strtotime($_REQUEST['program_date']))
            // : NULL;
        $Package->events_room       = !empty($_REQUEST['events_room']) ? $_REQUEST['events_room'] : '';
        $Package->total_event_space = !empty($_REQUEST['total_event_space']) ? $_REQUEST['total_event_space'] : '';
        $Package->capacity_largest_space = !empty($_REQUEST['capacity_largest_space']) ? $_REQUEST['capacity_largest_space'] : '';
        $Package->breakout_rooms = !empty($_REQUEST['breakout_rooms']) ? $_REQUEST['breakout_rooms'] : '';
        $Package->content       = $_REQUEST['content'];
        $Package->content1      = !empty($_REQUEST['content1']) ? $_REQUEST['content1'] : '';
        $Package->content2      = !empty($_REQUEST['content2']) ? $_REQUEST['content2'] : '';
        $Package->content3      = !empty($_REQUEST['content3']) ? $_REQUEST['content3'] : '';
        $Package->content4      = !empty($_REQUEST['content4']) ? $_REQUEST['content4'] : '';
        $Package->content5      = !empty($_REQUEST['content5']) ? $_REQUEST['content5'] : '';
        $Package->type         = $_REQUEST['type'];
        $Package->meta_title        = $_REQUEST['meta_title'];
        $Package->meta_keywords        = $_REQUEST['meta_keywords'];
        $Package->meta_description    = $_REQUEST['meta_description'];

        $Package->banner_image    = serialize(array_values(array_filter($_REQUEST['imageArrayname2'])));
        // $Package->flag_image	= serialize(array_values(array_filter($_REQUEST['flagArrayname'])));
        
        // Combine text, URL, and link type into array of objects
        $incexc_items = array();
        if (!empty($_REQUEST['incexc_text'])) {
            $text_array = $_REQUEST['incexc_text'];
            $url_array = !empty($_REQUEST['incexc_url']) ? $_REQUEST['incexc_url'] : array();
            $linktype_array = !empty($_REQUEST['incexc_linktype']) ? $_REQUEST['incexc_linktype'] : array();
            
            foreach ($text_array as $key => $text) {
                if (!empty($text)) {
                    $incexc_items[] = array(
                        'text' => trim($text),
                        'url' => !empty($url_array[$key]) ? trim($url_array[$key]) : '',
                        'linktype' => isset($linktype_array[$key]) ? $linktype_array[$key] : 1
                    );
                }
            }
        }
        $Package->incexc = !empty($incexc_items) ? serialize($incexc_items) : 'a:0:{}';

        // Combine text, URL, and link type into array of objects for incexc1
        $incexc_items1 = array();
        if (!empty($_REQUEST['incexc_text1'])) {
            $text_array1 = $_REQUEST['incexc_text1'];
            $url_array1 = !empty($_REQUEST['incexc_url1']) ? $_REQUEST['incexc_url1'] : array();
            $linktype_array1 = !empty($_REQUEST['incexc_linktype1']) ? $_REQUEST['incexc_linktype1'] : array();
            $subtitle_array1 = !empty($_REQUEST['incexc_subtitle1']) ? $_REQUEST['incexc_subtitle1'] : array();
            
            foreach ($text_array1 as $key => $text) {
                if (!empty($text)) {
                    $incexc_items1[] = array(
                        'text' => trim($text),
                        'url' => !empty($url_array1[$key]) ? trim($url_array1[$key]) : '',
                        'linktype' => isset($linktype_array1[$key]) ? $linktype_array1[$key] : 1,
                        'subtitle' => !empty($subtitle_array1[$key]) ? trim($subtitle_array1[$key]) : ''
                    );
                }
            }
        }
        $Package->incexc1 = !empty($incexc_items1) ? serialize($incexc_items1) : 'a:0:{}';

        $Package->status        = $_REQUEST['status'];
        $Package->sortorder        = Package::find_maximum();
        $Package->added_date     = registered();
        $Package->modified_date     = registered();
        // $Package->linksrc 	= !empty($_REQUEST['linksrc']) ? $_REQUEST['linksrc'] : '';
        // $Package->linktype 	= !empty($_REQUEST['linktype']) ? $_REQUEST['linktype'] : '';


        $checkDupliTitle = Package::checkDupliTitle($Package->title);
        if ($checkDupliTitle):
            echo json_encode(array("action" => "warning", "message" => "Package Title Already Exists."));
            exit;
        endif;

        $db->begin();
        if ($Package->save()):  $db->commit();
            // Global slug table storeSlug(class name, main slug, store id);
            // $act_id = $db->insert_id();
            $qry = $db->query("SELECT LAST_INSERT_ID() as lastId");
            $row = $db->fetch_object($qry);
            $act_id = $row->lastId;
            storeSlug('Package', $_REQUEST['slug'], $act_id);
            // End function
            $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Package Image '" . $Package->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("Package [" . $Package->title . "]" . $GLOBALS['basic']['addedSuccess'], 1, 3);
        else: $db->rollback();
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        endif;
        break;

    case "edit":
        $Package = Package::find_by_id($_REQUEST['idValue']);

        if ($Package->title != $_REQUEST['title']) {
            $checkDupliTitle = Package::checkDupliTitle($_REQUEST['title']);
            if ($checkDupliTitle):
                echo json_encode(array("action" => "warning", "message" => "Package Title is already exist."));
                exit;
            endif;
        }

        $Package->banner_image    = serialize(array_values(array_filter($_REQUEST['imageArrayname2'])));
        // $Package->flag_image	= serialize(array_values(array_filter($_REQUEST['flagArrayname'])));

        $Package->slug        = $_REQUEST['slug'];  
        $Package->title    = $_REQUEST['title'];
        $Package->sub_title = $_REQUEST['sub_title'];
        
        // Combine text, URL, and link type into array of objects
        $incexc_items = array();
        if (!empty($_REQUEST['incexc_text'])) {
            $text_array = $_REQUEST['incexc_text'];
            $url_array = !empty($_REQUEST['incexc_url']) ? $_REQUEST['incexc_url'] : array();
            $linktype_array = !empty($_REQUEST['incexc_linktype']) ? $_REQUEST['incexc_linktype'] : array();
            
            foreach ($text_array as $key => $text) {
                if (!empty($text)) {
                    $incexc_items[] = array(
                        'text' => trim($text),
                        'url' => !empty($url_array[$key]) ? trim($url_array[$key]) : '',
                        'linktype' => isset($linktype_array[$key]) ? $linktype_array[$key] : 1
                    );
                }
            }
        }
        $Package->incexc = !empty($incexc_items) ? serialize($incexc_items) : 'a:0:{}';

        // Combine text, URL, and link type into array of objects for incexc1
        $incexc_items1 = array();
        if (!empty($_REQUEST['incexc_text1'])) {
            $text_array1 = $_REQUEST['incexc_text1'];
            $url_array1 = !empty($_REQUEST['incexc_url1']) ? $_REQUEST['incexc_url1'] : array();
            $linktype_array1 = !empty($_REQUEST['incexc_linktype1']) ? $_REQUEST['incexc_linktype1'] : array();
            $subtitle_array1 = !empty($_REQUEST['incexc_subtitle1']) ? $_REQUEST['incexc_subtitle1'] : array();
            
            foreach ($text_array1 as $key => $text) {
                if (!empty($text)) {
                    $incexc_items1[] = array(
                        'text' => trim($text),
                        'url' => !empty($url_array1[$key]) ? trim($url_array1[$key]) : '',
                        'linktype' => isset($linktype_array1[$key]) ? $linktype_array1[$key] : 1,
                        'subtitle' => !empty($subtitle_array1[$key]) ? trim($subtitle_array1[$key]) : ''
                    );
                }
            }
        }
        $Package->incexc1 = !empty($incexc_items1) ? serialize($incexc_items1) : 'a:0:{}';
        // $Package->program_date = !empty($_REQUEST['program_date'])
        //     ? date('Y-m-d', strtotime($_REQUEST['program_date']))
        //     : NULL;
        $Package->events_room       = !empty($_REQUEST['events_room']) ? $_REQUEST['events_room'] : '';
        $Package->total_event_space = !empty($_REQUEST['total_event_space']) ? $_REQUEST['total_event_space'] : '';
        $Package->capacity_largest_space = !empty($_REQUEST['capacity_largest_space']) ? $_REQUEST['capacity_largest_space'] : '';
        $Package->breakout_rooms = !empty($_REQUEST['breakout_rooms']) ? $_REQUEST['breakout_rooms'] : '';
        $Package->content  = $_REQUEST['content'];
        $Package->content1 = !empty($_REQUEST['content1']) ? $_REQUEST['content1'] : '';
        $Package->content2 = !empty($_REQUEST['content2']) ? $_REQUEST['content2'] : '';
        $Package->content3 = !empty($_REQUEST['content3']) ? $_REQUEST['content3'] : '';
        $Package->content4 = !empty($_REQUEST['content4']) ? $_REQUEST['content4'] : '';
        $Package->content5 = !empty($_REQUEST['content5']) ? $_REQUEST['content5'] : '';
        $Package->status   = $_REQUEST['status'];
        $Package->type         = $_REQUEST['type'];
        $Package->meta_title        = $_REQUEST['meta_title'];
        $Package->meta_keywords        = $_REQUEST['meta_keywords'];
        $Package->meta_description    = $_REQUEST['meta_description'];
        $Package->modified_date        = registered();
        // $Package->linksrc 	= !empty($_REQUEST['linksrc']) ? $_REQUEST['linksrc'] : '';
        // $Package->linktype 	= !empty($_REQUEST['linktype']) ? $_REQUEST['linktype'] : '';


        $db->begin();
        if ($Package->save()): $db->commit();
            // Global slug table storeSlug(class name, main slug, store id);
            $act_id = $_REQUEST['idValue'];
            storeSlug('Package', $_REQUEST['slug'], $act_id);
            // End function
            $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Package '" . $Package->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action("Package " . $Package->title . " Edit Successfully", 1, 4);
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
        break;

    case "delete":
        $id = $_REQUEST['id'];
        $record = Package::find_by_id($id);
        // Global slug table deleteSlug(class name, store id);
        deleteSlug('Package', $id);
        // End function
        log_action("Package [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
        $db->begin();
        $res = $db->query("DELETE FROM tbl_package WHERE id='{$id}'");
        if ($res):
            $subpkgids = $db->fetch_array($db->query("SELECT id from tbl_package_sub WHERE type='{$id}'"));
            if (!empty($subpkgids)) {
                foreach ($subpkgids as $k => $v) {
                    $db->query("DELETE FROM tbl_subpackage_images WHERE subpackageid='{$v}'");
                    $db->query("DELETE FROM tbl_itinerary WHERE package_id='{$v}'");

                    $db->query("DELETE FROM tbl_mlink WHERE act_id='" . $v . "' AND mod_class='Subpackage'");
                }
            }
            $db->query("DELETE FROM tbl_package_sub WHERE type='{$id}'");
            $db->commit();
        else: $db->rollback();
        endif;
        reOrder("tbl_package", "sortorder");
        reOrder("tbl_package_sub", "sortorder");
        reOrder("tbl_itinerary", "sortorder");

        reOrder('tbl_subpackage_images', "sortorder");
        echo json_encode(array("action" => "success", "message" => "Package  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess']));
        break;

    case "toggleStatus":
        $id = $_REQUEST['id'];
        $record = Package::find_by_id($id);
        $record->status = ($record->status == 1) ? 0 : 1;
        $db->begin();
        $res   =  $record->save();
        if ($res): $db->commit();
        else: $db->rollback();
        endif;
        echo "";
        break;

    case "bulkToggleStatus":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        for ($i = 1; $i < count($allid); $i++) {
            $record = Package::find_by_id($allid[$i]);
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
            $subpkgids = $db->fetch_array($db->query("SELECT id from tbl_package_sub WHERE type='{$allid[$i]}'"));
            if (!empty($subpkgids)) {
                foreach ($subpkgids as $k => $v) {
                    $db->query("DELETE FROM tbl_subpackage_images WHERE subpackageid='{$v}'");
                    $db->query("DELETE FROM tbl_itinerary WHERE package_id='{$v}'");

                    $db->query("DELETE FROM tbl_mlink WHERE act_id='" . $v . "' AND mod_class='Subpackage'");
                }
            }
            $db->query("DELETE FROM tbl_package_sub WHERE type='" . $allid[$i] . "'");


            $db->query("DELETE FROM tbl_mlink WHERE act_id='" . $allid[$i] . "' AND mod_class='Package'");
            $res = $db->query("DELETE FROM tbl_package WHERE id='" . $allid[$i] . "'");
            $return = 1;
        }
        if ($res) $db->commit();
        else $db->rollback();
        reOrder("tbl_package", "sortorder");
        reOrder("tbl_package_sub", "sortorder");
        reOrder('tbl_subpackage_images', "sortorder");
        reOrder('tbl_itinerary', "sortorder");

        if ($return == 1):
            $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Package");
            echo json_encode(array("action" => "success", "message" => $message));
        else:
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
        endif;
        break;

    case "sort":
        $id      = $_REQUEST['id'];     // IS a line containing ids starting with : sortIds
        $sortIds = $_REQUEST['sortIds'];
        datatableReordering('tbl_package', $sortIds, "sortorder", '', '', 1);
        $message  = sprintf($GLOBALS['basic']['sorted_'], "Package ");
        echo json_encode(array("action" => "success", "message" => $message));
        break;

    /*********************** Sub Package Transaction Section *************************/
    case "addSubpackage":
        $record    = new Subpackage();

        $newArr = array();
        $fparent = (isset($_REQUEST['fparent']) and !empty($_REQUEST['fparent'])) ? $_REQUEST['fparent'] : '';
        $feature = (isset($_REQUEST['feature']) and !empty($_REQUEST['feature'])) ? $_REQUEST['feature'] : '';
        if (!empty($fparent) and !empty($feature)) {
            foreach ($fparent as $kk => $vv) {
                $final_fpt = !empty($fparent[$kk]) ? $fparent[$kk] : '';
                $final_ft  = !empty($feature[$kk]) ? $feature[$kk] : '';
                $newArr[$kk] = array($final_fpt, $final_ft);
            }
        }

        $record->type             = $_REQUEST['type'];
        $record->slug             = $_REQUEST['slug'];
        $record->title             = $_REQUEST['title'];

        $record->detail         = !empty($_REQUEST['detail']) ? $_REQUEST['detail'] : '';
        $record->image2            = !empty($_REQUEST['imageArrayname2']) ? $_REQUEST['imageArrayname2'] : '';
        $record->image4            = !empty($_REQUEST['imageArraynameCompanyDoc']) ? $_REQUEST['imageArraynameCompanyDoc'] : '';
        // $record->source_vid     = !empty($_REQUEST['videoArrayname']) ? $_REQUEST['videoArrayname'] : '';
        // $record->three60_image  = !empty($_REQUEST['imageArrayname360']) ? $_REQUEST['imageArrayname360'] : '';
        $record->image             = !empty($_REQUEST['imageArrayname']) ? serialize(array_values(array_filter($_REQUEST['imageArrayname']))) : '';
        $record->feature        = serialize($newArr);
        $record->content         = $_REQUEST['content'];
        $record->content2        = !empty($_REQUEST['content2']) ? $_REQUEST['content2'] : '';
        $record->included        = !empty($_REQUEST['included']) ? $_REQUEST['included'] : 1;
        $record->accessible_rooms        = !empty($_REQUEST['accessible_rooms']) ? $_REQUEST['accessible_rooms'] : 1;
        $record->status            = $_REQUEST['status'];
        $record->number_room    = !empty($_REQUEST['number_room']) ? $_REQUEST['number_room'] : '';
        $record->currency         = !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : '';
        $record->people_qnty     = !empty($_REQUEST['people_qnty']) ? $_REQUEST['people_qnty'] : '';
        $record->onep_price     = !empty($_REQUEST['onep_price']) ? $_REQUEST['onep_price'] : '';
        $record->twop_price     = !empty($_REQUEST['twop_price']) ? $_REQUEST['twop_price'] : '';
        $record->threep_price     = !empty($_REQUEST['threep_price']) ? $_REQUEST['threep_price'] : '';

        $record->oneb_price     = !empty($_REQUEST['oneb_price']) ? $_REQUEST['oneb_price'] : '';
        $record->twob_price     = !empty($_REQUEST['twob_price']) ? $_REQUEST['twob_price'] : '';
        $record->threeb_price     = !empty($_REQUEST['threeb_price']) ? $_REQUEST['threeb_price'] : '';

        $record->theatre_style     = !empty($_REQUEST['theatre_style']) ? $_REQUEST['theatre_style'] : '';
        $record->class_room_style     = !empty($_REQUEST['class_room_style']) ? $_REQUEST['class_room_style'] : '';
        $record->shape     = !empty($_REQUEST['shape']) ? $_REQUEST['shape'] : '';
        $record->round_table     = !empty($_REQUEST['round_table']) ? $_REQUEST['round_table'] : '';
        $record->clusture     = !empty($_REQUEST['clusture']) ? $_REQUEST['clusture'] : '';
        $record->cocktail     = !empty($_REQUEST['cocktail']) ? $_REQUEST['cocktail'] : '';
        $record->seats     = !empty($_REQUEST['seats']) ? $_REQUEST['seats'] : '';

        $record->short_title    = !empty($_REQUEST['short_title']) ? $_REQUEST['short_title'] : '';
        $record->sub_title    = !empty($_REQUEST['sub_title']) ? $_REQUEST['sub_title'] : '';

        $record->dress             = !empty($_REQUEST['dress']) ? $_REQUEST['dress'] : '';
        $record->capacity          = !empty($_REQUEST['capacity']) ? $_REQUEST['capacity'] : '';
        $record->room_size         = !empty($_REQUEST['room_size']) ? $_REQUEST['room_size'] : '';

        $record->phone             = !empty($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $record->time     = !empty($_REQUEST['time']) ? $_REQUEST['time'] : '';
        $record->location     = !empty($_REQUEST['location']) ? $_REQUEST['location'] : '';
        $record->serve     = !empty($_REQUEST['serve']) ? $_REQUEST['serve'] : '';
        // $record->rojai_room_id 	= !empty($_REQUEST['rojai_room_id'])?$_REQUEST['rojai_room_id']:'';
        //$record->extra_bed 		= !empty($_REQUEST['extra_bed'])?$_REQUEST['extra_bed']:'';
        $record->meta_title        = $_REQUEST['meta_title'];
        $record->meta_keywords        = $_REQUEST['meta_keywords'];
        $record->meta_description    = $_REQUEST['meta_description'];
        $record->sortorder        = Subpackage::find_maximum_byparent("sortorder", $_REQUEST['type']);
        $record->added_date     = registered();
        $record->modified_date     = registered();

        $record->below_content         = !empty($_REQUEST['below_content']) ? $_REQUEST['below_content'] : '';



        $record->seminar     = !empty($_REQUEST['seminar']) ? $_REQUEST['seminar'] : '';
        $record->meeting     = !empty($_REQUEST['meeting']) ? $_REQUEST['meeting'] : '';
        $record->events     = !empty($_REQUEST['events']) ? $_REQUEST['events'] : '';
        $record->conference     = !empty($_REQUEST['conference']) ? $_REQUEST['conference'] : '';
        $record->catering     = !empty($_REQUEST['catering']) ? $_REQUEST['catering'] : '';
        $record->celebration     = !empty($_REQUEST['celebration']) ? $_REQUEST['celebration'] : '';
        $record->organic_food     = !empty($_REQUEST['organic_food']) ? $_REQUEST['organic_food'] : '';
        $record->occupancy     = !empty($_REQUEST['occupancy']) ? $_REQUEST['occupancy'] : '';
        $record->view     = !empty($_REQUEST['view']) ? $_REQUEST['view'] : '';
        $record->size     = !empty($_REQUEST['size']) ? $_REQUEST['size'] : '';
        $record->service     = !empty($_REQUEST['service']) ? $_REQUEST['service'] : '';
        // $record->live_music 	= !empty($_REQUEST['live_music'])?$_REQUEST['live_music']:'';

        // $record->bed 	= !empty($_REQUEST['bed'])?$_REQUEST['bed']:'';
        $record->room_size     = !empty($_REQUEST['room_size']) ? $_REQUEST['room_size'] : '';
        // $record->room_service 	= !empty($_REQUEST['room_service'])?$_REQUEST['room_service']:'';
        // $record->airport_pickup 	= !empty($_REQUEST['airport_pickup'])?$_REQUEST['airport_pickup']:'';
        // $record->private_balcony 	= !empty($_REQUEST['private_balcony'])?$_REQUEST['private_balcony']:'';
        // $record->checkinout 	= !empty($_REQUEST['checkinout'])?$_REQUEST['checkinout']:'';
        // $record->google_embeded	= $_REQUEST['google_embeded'];
        $record->homepage    = $_REQUEST['homepage'];
        $record->link_a    = !empty($_REQUEST['link_a']) ? $_REQUEST['link_a'] : '';
        $record->link_b    = !empty($_REQUEST['link_b']) ? $_REQUEST['link_b'] : '';
        $record->explorelinksrc     = !empty($_REQUEST['explorelinksrc']) ? $_REQUEST['explorelinksrc'] : '';
        $record->explorelinktype     = !empty($_REQUEST['explorelinktype']) ? $_REQUEST['explorelinktype'] : '';




        $db->begin();
        if ($record->save()): $db->commit();
            // Global slug table storeSlug(class name, main slug, store id);
            // $act_id = $db->insert_id();
            $qry = $db->query("SELECT LAST_INSERT_ID() as lastId");
            $row = $db->fetch_object($qry);
            $act_id = $row->lastId;
            storeSlug('Subpackage', $_REQUEST['slug'], $act_id);
            // End function
            $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Sub Package '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action($message, 1, 3);
        else: $db->rollback();
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        endif;
        break;

    case "editSubpackage":


        $record = Subpackage::find_by_id($_REQUEST['idValue']);

        $newArr = array();
        $fparent = (isset($_REQUEST['fparent']) and !empty($_REQUEST['fparent'])) ? $_REQUEST['fparent'] : '';
        $feature = (isset($_REQUEST['feature']) and !empty($_REQUEST['feature'])) ? $_REQUEST['feature'] : '';
        if (!empty($fparent) and !empty($feature)) {
            foreach ($fparent as $kk => $vv) {
                $final_fpt = !empty($fparent[$kk]) ? $fparent[$kk] : '';
                $final_ft  = !empty($feature[$kk]) ? $feature[$kk] : '';
                $newArr[$kk] = array($final_fpt, $final_ft);
            }
        }

        $record->type             = $_REQUEST['type'];
        $record->slug             = $_REQUEST['slug'];
        $record->title             = $_REQUEST['title'];
        $record->dress             = !empty($_REQUEST['dress']) ? $_REQUEST['dress'] : '';
        $record->capacity          = !empty($_REQUEST['capacity']) ? $_REQUEST['capacity'] : '';
        $record->room_size         = !empty($_REQUEST['room_size']) ? $_REQUEST['room_size'] : '';
        $record->phone             = !empty($_REQUEST['phone']) ? $_REQUEST['phone'] : '';

        $record->detail         = !empty($_REQUEST['detail']) ? $_REQUEST['detail'] : '';
        $record->image2            = !empty($_REQUEST['imageArrayname2']) ? $_REQUEST['imageArrayname2'] : '';
        $record->image4            = !empty($_REQUEST['imageArraynameCompanyDoc']) ? $_REQUEST['imageArraynameCompanyDoc'] : '';
        // $record->source_vid     = !empty($_REQUEST['videoArrayname']) ? $_REQUEST['videoArrayname'] : '';
        // $record->three60_image  = !empty($_REQUEST['imageArrayname360']) ? $_REQUEST['imageArrayname360'] : '';
        $record->image             = !empty($_REQUEST['imageArrayname']) ? serialize(array_values(array_filter($_REQUEST['imageArrayname']))) : '';
        $record->feature        = serialize($newArr);
        $record->content         = $_REQUEST['content'];
        $record->content2         = !empty($_REQUEST['content2']) ? $_REQUEST['content2'] : '';
        $record->included         = !empty($_REQUEST['included']) ? $_REQUEST['included'] : 1;
        $record->accessible_rooms         = !empty($_REQUEST['accessible_rooms']) ? $_REQUEST['accessible_rooms'] : 1;
        $record->status            = $_REQUEST['status'];
        $record->number_room    = !empty($_REQUEST['number_room']) ? $_REQUEST['number_room'] : '';
        $record->currency         = !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : '';
        $record->people_qnty     = !empty($_REQUEST['people_qnty']) ? $_REQUEST['people_qnty'] : '';
        $record->onep_price     = !empty($_REQUEST['onep_price']) ? $_REQUEST['onep_price'] : '';
        $record->twop_price     = !empty($_REQUEST['twop_price']) ? $_REQUEST['twop_price'] : '';
        $record->threep_price     = !empty($_REQUEST['threep_price']) ? $_REQUEST['threep_price'] : '';

        $record->theatre_style     = !empty($_REQUEST['theatre_style']) ? $_REQUEST['theatre_style'] : '';
        $record->class_room_style     = !empty($_REQUEST['class_room_style']) ? $_REQUEST['class_room_style'] : '';
        $record->shape     = !empty($_REQUEST['shape']) ? $_REQUEST['shape'] : '';
        $record->round_table     = !empty($_REQUEST['round_table']) ? $_REQUEST['round_table'] : '';
        $record->clusture     = !empty($_REQUEST['clusture']) ? $_REQUEST['clusture'] : '';
        $record->cocktail     = !empty($_REQUEST['cocktail']) ? $_REQUEST['cocktail'] : '';
        $record->seats     = !empty($_REQUEST['seats']) ? $_REQUEST['seats'] : '';

        $record->oneb_price     = !empty($_REQUEST['oneb_price']) ? $_REQUEST['oneb_price'] : '';
        $record->twob_price     = !empty($_REQUEST['twob_price']) ? $_REQUEST['twob_price'] : '';
        $record->threeb_price     = !empty($_REQUEST['threeb_price']) ? $_REQUEST['threeb_price'] : '';

        $record->short_title    = !empty($_REQUEST['short_title']) ? $_REQUEST['short_title'] : '';
        $record->sub_title    = !empty($_REQUEST['sub_title']) ? $_REQUEST['sub_title'] : '';
        $record->time     = !empty($_REQUEST['time']) ? $_REQUEST['time'] : '';
        $record->location     = !empty($_REQUEST['location']) ? $_REQUEST['location'] : '';
        $record->serve     = !empty($_REQUEST['serve']) ? $_REQUEST['serve'] : '';
        // $record->rojai_room_id 	= !empty($_REQUEST['rojai_room_id'])?$_REQUEST['rojai_room_id']:'';
        //$record->extra_bed 		= !empty($_REQUEST['extra_bed'])?$_REQUEST['extra_bed']:'';
        $record->meta_title        = $_REQUEST['meta_title'];
        $record->meta_keywords        = $_REQUEST['meta_keywords'];
        $record->meta_description    = $_REQUEST['meta_description'];
        $record->modified_date    = registered();

        // $record->below_content 		= $_REQUEST['below_content'];
        $record->below_content         = !empty($_REQUEST['below_content']) ? $_REQUEST['below_content'] : '';


        $record->seminar     = !empty($_REQUEST['seminar']) ? $_REQUEST['seminar'] : '';
        $record->meeting     = !empty($_REQUEST['meeting']) ? $_REQUEST['meeting'] : '';
        $record->events     = !empty($_REQUEST['events']) ? $_REQUEST['events'] : '';
        $record->conference     = !empty($_REQUEST['conference']) ? $_REQUEST['conference'] : '';
        $record->catering     = !empty($_REQUEST['catering']) ? $_REQUEST['catering'] : '';
        $record->celebration     = !empty($_REQUEST['celebration']) ? $_REQUEST['celebration'] : '';
        $record->organic_food     = !empty($_REQUEST['organic_food']) ? $_REQUEST['organic_food'] : '';
        $record->occupancy     = !empty($_REQUEST['occupancy']) ? $_REQUEST['occupancy'] : '';
        $record->view     = !empty($_REQUEST['view']) ? $_REQUEST['view'] : '';
        $record->size     = !empty($_REQUEST['size']) ? $_REQUEST['size'] : '';
        $record->service     = !empty($_REQUEST['service']) ? $_REQUEST['service'] : '';
        // $record->live_music 	= !empty($_REQUEST['live_music'])?$_REQUEST['live_music']:'';

        // $record->bed 	= !empty($_REQUEST['bed'])?$_REQUEST['bed']:'';
        $record->room_size     = !empty($_REQUEST['room_size']) ? $_REQUEST['room_size'] : '';
        // $record->room_service 	= !empty($_REQUEST['room_service'])?$_REQUEST['room_service']:'';
        // $record->airport_pickup 	= !empty($_REQUEST['airport_pickup'])?$_REQUEST['airport_pickup']:'';
        // $record->private_balcony 	= !empty($_REQUEST['private_balcony'])?$_REQUEST['private_balcony']:'';
        // $record->checkinout 	= !empty($_REQUEST['checkinout'])?$_REQUEST['checkinout']:'';
        // $record->google_embeded	= $_REQUEST['google_embeded'];
        $record->homepage    = $_REQUEST['homepage'];
        $record->link_a    = !empty($_REQUEST['link_a']) ? $_REQUEST['link_a'] : '';
        $record->link_b    = !empty($_REQUEST['link_b']) ? $_REQUEST['link_b'] : '';
        $record->explorelinksrc     = !empty($_REQUEST['explorelinksrc']) ? $_REQUEST['explorelinksrc'] : '';
        $record->explorelinktype     = !empty($_REQUEST['explorelinktype']) ? $_REQUEST['explorelinktype'] : '';




        $db->begin();

        if ($record->save()): $db->commit();
            // Global slug table storeSlug(class name, main slug, store id);
            $act_id = $_REQUEST['idValue'];
            storeSlug('Subpackage', $_REQUEST['slug'], $act_id);
            // End function
            $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Sub Package '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
            log_action($message, 1, 4);
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
        break;

    case "deletesubpackage":
        $id = $_REQUEST['id'];
        $record = Subpackage::find_by_id($id);
        // Global slug table deleteSlug(class name, store id);
        deleteSlug('Subpackage', $id);
        // End function
        log_action("Sub Package [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
        $db->begin();

        $res = $db->query("DELETE FROM tbl_package_sub WHERE id='{$id}'");
        if ($res):
            $db->query("DELETE FROM tbl_subpackage_images WHERE subpackageid='{$id}'");
            $db->query("DELETE FROM tbl_itinerary WHERE package_id='{$id}'");

            $db->commit();
        else: $db->rollback();
        endif;
        reOrder("tbl_package_sub", "sortorder");
        reOrder('tbl_subpackage_images', "sortorder");
        reOrder('tbl_itinerary', "sortorder");

        echo json_encode(array("action" => "success", "message" => "Sub Package [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess']));
        break;

    case "SubtoggleStatus":
        $id = $_REQUEST['id'];
        $record = Subpackage::find_by_id($id);
        $record->status = ($record->status == 1) ? 0 : 1;
        $db->begin();
        $res   =  $record->save();
        if ($res): $db->commit();
        else: $db->rollback();
        endif;
        echo "";
        break;

    case "subbulkToggleStatus":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        for ($i = 1; $i < count($allid); $i++) {
            $record = Subpackage::find_by_id($allid[$i]);
            $record->status = ($record->status == 1) ? 0 : 1;
            $record->save();
        }
        echo "";
        break;

    case "subbulkDelete":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        $db->begin();
        $affected_type_ids = array();
        for ($i = 1; $i < count($allid); $i++) {
            $record = Subpackage::find_by_id($allid[$i]);
            if ($record) {
                $affected_type_ids[] = $record->type;
            }
            $res  = $db->query("DELETE FROM tbl_package_sub WHERE id='" . $allid[$i] . "'");
            $db->query("DELETE FROM tbl_mlink WHERE act_id='" . $allid[$i] . "' AND mod_class='Subpackage'");
            $db->query("DELETE FROM tbl_subpackage_images WHERE subpackageid='" . $allid[$i] . "'");
            $db->query("DELETE FROM tbl_itinerary WHERE package_id='" . $allid[$i] . "'");

            $return = 1;
        }

        if (!empty($affected_type_ids)) {
            foreach (array_unique($affected_type_ids) as $type_id) {
                reOrderSub("tbl_package_sub", "sortorder", "type", $type_id);
            }
        }
        reOrder('tbl_subpackage_images', "sortorder");
        reOrder('tbl_itinerary', "sortorder");

        if ($res) $db->commit();
        else $db->rollback();

        if ($return == 1):
            $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Package");
            echo json_encode(array("action" => "success", "message" => $message));
        else:
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
        endif;
        break;

    case "subSort":
        $id      = $_REQUEST['id'];     // IS a line containing ids starting with : sortIds
        $sortIds = $_REQUEST['sortIds'];
        $posId   = Subpackage::field_by_id($id, 'type');
        datatableReordering('tbl_package_sub', $sortIds, "sortorder", "type", $posId, 1);
        $message  = sprintf($GLOBALS['basic']['sorted_'], "Sub Package");
        echo json_encode(array("action" => "success", "message" => $message));
        break;

    case "addSubPackageImage":

        $subpackageid  = $_REQUEST['subpackageid'];

        $imageName  = !empty($_REQUEST['imageArrayname']) ? $_REQUEST['imageArrayname'] : '';
        $title      = !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';

        if (!empty($imageName)):
            foreach ($imageName as $key => $val):
                $FimageName        = $imageName[$key];
                $Ftitle            = $title[$key];
                //Save Record
                if (!empty($FimageName)):
                    $Gallery     = new SubPackageImage();

                    $Gallery->image            = $FimageName;
                    $Gallery->title         = $Ftitle;
                    $Gallery->status        = 1;
                    $Gallery->subpackageid        = $subpackageid;
                    $Gallery->sortorder        = SubPackageImage::find_maximum_byparent("sortorder", $subpackageid);
                    $Gallery->registered    = registered();
                    $db->begin();
                    $res   =  $Gallery->save();
                    if ($res): $db->commit();
                    else: $db->rollback();
                    endif;
                    log_action("Sub Package Image [" . $Gallery->title . "]" . $GLOBALS['basic']['addedSuccess'], 1, 3);
                endif;
            endforeach;
            echo json_encode(array("action" => "success", "message" => $GLOBALS['basic']['changesSaved'], "subpackageid" => $subpackageid));
        else:
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        endif;

        break;
    case "SubitoggleStatus":
        $id = $_REQUEST['id'];
        $record = SubPackageImage::find_by_id($id);
        $record->status = ($record->status == 1) ? 0 : 1;
        $db->begin();
        $res   =  $record->save();
        if ($res): $db->commit();
        else: $db->rollback();
        endif;
        echo "";
        break;
    case "deleteSubimage":
        $id = $_REQUEST['id'];
        $record = SubPackageImage::find_by_id($id);
        log_action("SubPackage Gallery Image [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
        $db->begin();
        $res =  $db->query("DELETE FROM tbl_subpackage_images WHERE id='{$id}'");
        if ($res): $db->commit();
        else: $db->rollback();
        endif;
        reOrderSub("tbl_subpackage_images", "sortorder", "subpackageid", $record->subpackageid);
        echo json_encode(array("action" => "success"));
        break;
    case "sortSubGalley":
        $id     = $_REQUEST['id'];     // IS a line containing ids starting with : sortIds
        $record = SubPackageImage::find_by_id($id);
        $sortIds = $_REQUEST['sortIds'];

        datatableReordering('tbl_subpackage_images', $sortIds, "sortorder", 'subpackageid', $record->subpackageid, 0);
        reOrder('tbl_subpackage_images', "sortorder");
        echo json_encode(array("action" => "success", "message" => $GLOBALS['basic']['sorted']));
        break;

    case "editSubGalleryImageText":

        $GalleryImage = SubPackageImage::find_by_id($_REQUEST['id']);
        if (!empty($GalleryImage)) {
            $GalleryImage->title = $_REQUEST['title'];
            $db->begin();
            if ($GalleryImage->save()): $db->commit();
                $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Sub Gallery Image '" . $GalleryImage->title . "'");
                echo json_encode(array("action" => "success", "message" => $message));
                log_action("Gallery Image [" . $GalleryImage->title . "] Edit Successfully", 1, 4);
            else: $db->rollback();
                echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
            endif;
        } else {
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        }

        break;

    case "getRoomsdetails":
        $result = '';
        $getdate = addslashes($_REQUEST['getdate']);
        $roomCat  = Subpackage::getPackage_limit(1);
        if ($roomCat):
            foreach ($roomCat as $roomRow) {
                $rec = Subpackage::find_by_id($roomRow->id);
                $nos = json_decode($rec->image, true);
                global $db;
                $sql = "SELECT ss.season,ss.date_from, ss.date_to, rp.one_person, rp.two_person, rp.three_person
	    					FROM 
	    					tbl_seasion AS ss
	    					INNER JOIN tbl_room_price AS rp
	    					ON ss.id = rp.season_id
	    					WHERE ss.date_to>='$getdate' LIMIT 1";
                $dtResult = $db->query($sql);

                $sql2 = "SELECT rp.one_person, rp.two_person, rp.three_person
	    			 		FROM 
	    			 		tbl_room_price AS rp
	    			 		WHERE rp.season_id='0' AND rp.room_id= $rec->id LIMIT 1";
                $dfltResult = $db->query($sql2);

                $myArr = '';
                if ($db->num_rows($dtResult) > 0) {
                    $myArr = $dtResult;
                } else {
                    $myArr = $dfltResult;
                }

                $romprice = array();
                while ($row = $db->fetch_array($myArr)) {
                    foreach ($row as $key => $val) {
                        $$key = $val;
                    }
                    $romprice = array(1 => $one_person, 2 => $two_person, 3 => $three_person);
                }
                $result .= '<div class="main_imgdiv">
	    					<img alt="' . $rec->title . '" src="' . IMAGE_PATH . 'subpackage/' . $nos[0] . '">
	    				</div>
	    				<div class="main_listing">';
                for ($i = 1; $i <= $rec->people_qnty; $i++) {
                    $result .= '<ul>
							 	<li>' . $i . '</li>
							 	<li>' . $rec->currency . ' ' . $romprice[$i] . '</li>
							 	<li>
								 	<select name="" id="" class="select-room" data-person="' . $i . '" data-currency="' . $rec->currency . '" data-price="' . $romprice[$i] . '"
                                    data-room="' . $rec->title . '">
								 		<option value="0">0</option>';
                    for ($j = 1; $j <= $rec->no_rooms; $j++) {
                        $result .= '<option value="' . $j . '">' . $j . '</option>';
                    }
                    $result .= '</select>
							 	</li>
							 	<li><span class="ind-total">0</span></li>
							</ul>
							<div class="clear"></div>';
                }
                $result .= '</div>
						<div class="clear"></div>';
            }
        endif;

        echo json_encode(array("roomresult" => $result));
        break;


    case "additinerary":
        $record = new Itinerary();

        $record->package_id     = $_REQUEST['package_id'];
        $record->day             = $_REQUEST['day'];
        $record->title             = $_REQUEST['title'];
        $record->slug             = create_slug($_REQUEST['title']);
        // $record->image			= $_REQUEST['imageArrayname'];
        $record->content             = $_REQUEST['content'];
        $record->status            = $_REQUEST['status'];
        $record->sortorder        = Itinerary::find_maximum_byparent("sortorder", $_REQUEST['package_id']);

        /*if(empty($_REQUEST['imageArrayname'])):
				echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
				exit;
			endif;*/

        $db->begin();
        if ($record->save()): $db->commit();
            $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Itinerary '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
        else: $db->rollback();
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        endif;
        break;

    case "edititinerary":
        $record = Itinerary::find_by_id($_REQUEST['idValue']);

        $record->package_id     = $_REQUEST['package_id'];
        $record->day             = $_REQUEST['day'];
        $record->title             = $_REQUEST['title'];
        $record->slug             = create_slug($_REQUEST['title']);
        // $record->image			= $_REQUEST['imageArrayname'];
        $record->content             = $_REQUEST['content'];
        $record->status            = $_REQUEST['status'];

        if (!empty($_REQUEST['imageArrayname'])):
            $record->image        = $_REQUEST['imageArrayname'];
        endif;

        $db->begin();
        if ($record->save()): $db->commit();
            $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Itinerary '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
        break;

    case "deleteitinerary":
        $id = $_REQUEST['id'];
        $record = Itinerary::find_by_id($id);
        log_action("Itinerary [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
        $db->begin();

        $res   = $db->query("DELETE FROM tbl_itinerary WHERE id='{$id}'");
        if ($res): $db->commit();
        else: $db->rollback();
        endif;
        reOrder("tbl_itinerary", "sortorder");
        echo json_encode(array("action" => "success", "message" => "Itinerary [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess']));
        break;

    case "subiSort":
        $id      = $_REQUEST['id'];     // IS a line containing ids starting with : sortIds
        $sortIds = $_REQUEST['sortIds'];
        $posId   = Itinerary::field_by_id($id, 'package_id');
        datatableReordering('tbl_itinerary', $sortIds, "sortorder", "package_id", $posId, 0);
        $message  = sprintf($GLOBALS['basic']['sorted_'], "Itinerary");
        echo json_encode(array("action" => "success", "message" => $message));
        break;

    case "subibulkDelete":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        $db->begin();
        for ($i = 1; $i < count($allid); $i++) {
            $record = Itinerary::find_by_id($allid[$i]);
            $res  = $db->query("DELETE FROM tbl_itinerary WHERE id='" . $allid[$i] . "'");
            // reOrderSub("tbl_itinerary", "sortorder", "package_id",$record->package_id);

            $return = 1;
        }
        if ($res) $db->commit();
        else $db->rollback();

        if ($return == 1):
            $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Itinerary");
            echo json_encode(array("action" => "success", "message" => $message));
        else:
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
        endif;
        break;


    case "subibulkToggleStatus":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        for ($i = 1; $i < count($allid); $i++) {
            $record = Itinerary::find_by_id($allid[$i]);
            $record->status = ($record->status == 1) ? 0 : 1;
            $record->save();
        }
        echo json_encode(array("action" => "success"));
        break;



    // --- NEW CASE FOR SINGLE TOGGLE ---
    case "subisingleToggleStatus":
        // Get the single ID from the request (sent as 'id' not 'idArray')
        $itemId = (int)$_REQUEST['id'];

        // Find the record and toggle status
        $record = Itinerary::find_by_id($itemId);

        if ($record) {
            $record->status = ($record->status == 1) ? 0 : 1;
            $record->save();
        }

        // Return success. The JavaScript will handle the UI update.
        echo json_encode(array("action" => "success"));
        break;

    case "additinerarypackage":
        $record = new PackageItinerary();

        $record->package_id     = $_REQUEST['package_id'];
        $record->title             = $_REQUEST['title'];
        $record->slug             = create_slug($_REQUEST['title']);
        $record->content             = $_REQUEST['content'];
        $record->status            = isset($_REQUEST['status']) ? $_REQUEST['status'] : 1;
        $record->sortorder        = PackageItinerary::find_maximum_byparent("sortorder", $_REQUEST['package_id']);

        $db->begin();
        if ($record->save()): $db->commit();
            $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Package Itinerary '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
        else: $db->rollback();
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
        endif;
        break;

    case "edititinerarypackage":
        $record = PackageItinerary::find_by_id($_REQUEST['idValue']);

        $record->package_id     = $_REQUEST['package_id'];
        $record->title             = $_REQUEST['title'];
        $record->slug             = create_slug($_REQUEST['title']);
        $record->content             = $_REQUEST['content'];
        $record->status            = isset($_REQUEST['status']) ? $_REQUEST['status'] : 1;

        $db->begin();
        if ($record->save()): $db->commit();
            $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Package Itinerary '" . $record->title . "'");
            echo json_encode(array("action" => "success", "message" => $message));
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
        break;

    case "deletepackageitinerary":
        $id = $_REQUEST['id'];
        $record = PackageItinerary::find_by_id($id);
        log_action("Package Itinerary [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
        $db->begin();

        $res   = $db->query("DELETE FROM tbl_packageitineary WHERE id='{$id}'");
        if ($res): $db->commit();
        else: $db->rollback();
        endif;
        reOrder("tbl_packageitineary", "sortorder");
        echo json_encode(array("action" => "success", "message" => "Package Itinerary [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess']));
        break;

    case "packageitSort":
        $id      = $_REQUEST['id'];
        $sortIds = $_REQUEST['sortIds'];
        $posId   = PackageItinerary::field_by_id($id, 'package_id');
        datatableReordering('tbl_packageitineary', $sortIds, "sortorder", "package_id", $posId, 0);
        $message  = sprintf($GLOBALS['basic']['sorted_'], "Package Itinerary");
        echo json_encode(array("action" => "success", "message" => $message));
        break;

    case "packageitbulkDelete":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        $db->begin();
        for ($i = 1; $i < count($allid); $i++) {
            $record = PackageItinerary::find_by_id($allid[$i]);
            $res  = $db->query("DELETE FROM tbl_packageitineary WHERE id='" . $allid[$i] . "'");
            $return = 1;
        }
        if ($res) $db->commit();
        else $db->rollback();

        if ($return == 1):
            $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Package Itinerary");
            echo json_encode(array("action" => "success", "message" => $message));
        else:
            echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
        endif;
        break;

    case "packageitbulkToggleStatus":
        $id = $_REQUEST['idArray'];
        $allid = explode("|", $id);
        $return = "0";
        for ($i = 1; $i < count($allid); $i++) {
            $record = PackageItinerary::find_by_id($allid[$i]);
            $record->status = ($record->status == 1) ? 0 : 1;
            $record->save();
        }
        echo json_encode(array("action" => "success"));
        break;

    case "statusItineraryPackage":
        $itemId = (int)$_REQUEST['id'];
        $record = PackageItinerary::find_by_id($itemId);

        if ($record) {
            $record->status = ($record->status == 1) ? 0 : 1;
            $record->save();
        }

        echo json_encode(array("action" => "success"));
        break;

}
