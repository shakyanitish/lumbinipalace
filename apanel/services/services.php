<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename = "tbl_services"; // Database table name
$moduleId = 20;                // module id >>>>> tbl_modules
$service_types = array(1 => 'Service\'s', 2 => 'Main Service\'s', 3 => 'Extra Service\'s'); //1 => 'Service\'s', 2 => 'Main Service\'s', 3 => 'Extra Service\'s'
if (isset($_GET['page']) && $_GET['page'] == "services" && isset($_GET['mode']) && $_GET['mode'] == "list"):


    SerclearImages($moduleTablename, "services");
    SerclearImages($moduleTablename, "services/thumbnails");
    SerclearImages($moduleTablename, "services/icon", "iconimage");
    SerclearImages($moduleTablename, "services/icon/thumbnails", "iconimage");
    SerclearImages($moduleTablename, "services/banner", "bannerimage");
    SerclearImages($moduleTablename, "services/banner/thumbnails", "bannerimage");

    foreach ($service_types as $key => $service_type) {
        $u_type_id = $key;
    }
    $typeid = (!empty($session->get('type_id_service'))) ? $session->get('type_id_service') : 2;
    $pagename = strtolower($_GET['page']);
?>
    <h3>
        List Services
        <?php
        if (!empty($service_types)) {
            $select_html = '<div class="pad0L col-md-3"><select class="user-hotel-select">';
            foreach ($service_types as $key => $service_type) {
                $select_html .= '<option value="' . $key . '" ' . ($key == $typeid ? ' selected' : '') . '>' . $service_type . '</option>';
            }
            $select_html .= '</select></div>';
            echo $select_html;
        }
        ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
            onClick="AddNewServices();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-plus-square"></i>
            </span>
            <span class="button-content"> Add New </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                <thead>
                    <tr>
                        <th style="display:none;"></th>
                        <th class="text-center"><input class="check-all" type="checkbox" /></th>
                        <th>Title</th>
                        <th class="text-center">Images</th>
                        <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php $records = Services::find_by_sql("SELECT * FROM " . $moduleTablename . " WHERE service_type=$typeid ORDER BY sortorder DESC ");
                    foreach ($records as $key => $record): ?>
                        <tr id="<?php echo $record->id; ?>">
                            <td style="display:none;"><?php echo $key + 1; ?></td>
                            <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>" /></td>
                            <td>
                                <div class="col-md-7">
                                    <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);"
                                        class="loadingbar-demo"
                                        title="<?php echo $record->title; ?>"><?php echo $record->title; ?></a>
                                </div>
                            </td>

                        <td class="text-center">
                            <a class="primary-bg medium btn loadingbar-demo" title=""
                               onClick="viewServiceImages(<?php echo $record->id; ?>);" href="javascript:void(0);">
                                <span class="button-content">
                                    <span class="badge bg-orange radius-all-4 mrg5R" title=""
                                          data-original-title="Badge with tooltip"><?php echo ServicesImage::getTotalImages($record->id); ?></span>
                                    <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                </span>
                            </a>
                        </td>


                            <td class="text-center">
                                <?php
                                $statusImage = ($record->status == 1) ? "bg-green" : "bg-red";
                                $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                                ?>
                                <a href="javascript:void(0);"
                                    class="btn small <?php echo $statusImage; ?> tooltip-button statusToggler"
                                    data-placement="top" title="<?php echo $statusText; ?>"
                                    status="<?php echo $record->status; ?>" id="imgHolder_<?php echo $record->id; ?>"
                                    moduleId="<?php echo $record->id; ?>">
                                    <i class="glyph-icon icon-flag"></i>
                                </a>
                                <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button"
                                    data-placement="top" title="Edit" onclick="editRecord(<?php echo $record->id; ?>);">
                                    <i class="glyph-icon icon-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top"
                                    title="Remove" onclick="recordDelete(<?php echo $record->id; ?>);">
                                    <i class="glyph-icon icon-remove"></i>
                                </a>
                                <input name="sortId" type="hidden" value="<?php echo $record->id; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="pad0L col-md-2">
            <select name="dropdown" id="groupTaskField" class="custom-select">
                <option value="0"><?php echo $GLOBALS['basic']['choseAction']; ?></option>
                <option value="delete"><?php echo $GLOBALS['basic']['delete']; ?></option>
                <option value="toggleStatus"><?php echo $GLOBALS['basic']['toggleStatus']; ?></option>
            </select>
        </div>
        <a class="btn medium primary-bg" href="javascript:void(0);" id="applySelected_btn">
            <span class="glyph-icon icon-separator float-right">
                <i class="glyph-icon icon-cog"></i>
            </span>
            <span class="button-content"> Click </span>
        </a>
    </div>
    <!-- Meta Tags-->
    <div class="form-row">
        <div class="form-checkbox-radio col-md-9">
            <a class="btn medium bg-blue" href="javascript:void(0);" onClick="toggleMetadata();">
                <span class="glyph-icon icon-separator float-right">
                    <i class="glyph-icon icon-caret-down"></i>
                </span>
                <span class="button-content"> Metadata Info </span>
            </a>
        </div>
    </div>
    <?php

    $metasql = $db->query("SELECT * FROM tbl_metadata WHERE page_name='$pagename'");
    $metadata = $metasql->fetch_object();
    // $metaexist= !empty($metadata) ? array_shift($metadata) : false;
    // pr($metadata);

    ?>
    <div class="form-row show <?php echo (!empty($metadata->meta_keywords) || !empty($metadata->meta_description) || !empty($metadata->meta_title)) ? '' : 'hide'; ?>  metadata">

        <form class="col-md-12 center-margin" id="offers_meta_frm">
            <input type="hidden" name="page_name" value="<?php echo $pagename ?>" />
            <input type="hidden" name="module_id" value="<?php echo $moduleId ?>" />
            <div class="col-md-12">
                <div class="form-input col-md-12">
                    <input placeholder="Meta Title" class="col-md-6 validate[required]" type="text"
                        name="meta_title" id="meta_title"
                        value="<?php echo !empty($metadata->meta_title) ? $metadata->meta_title : ""; ?>">
                </div>
                <br />
                <div class="form-input col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords"
                                class="character-keyword validate[required]"><?php echo !empty($metadata->meta_keywords) ? $metadata->meta_keywords : ""; ?></textarea>
                            <div class="keyword-remaining clear input-description">250 characters left</div>
                        </div>
                        <div class="col-md-6">
                            <textarea placeholder="Meta Description" name="meta_description"
                                id="meta_description"
                                class="character-description validate[required]"><?php echo !empty($metadata->meta_description) ? $metadata->meta_description : ""; ?></textarea>
                            <div class="description-remaining clear input-description">160 characters left</div>
                        </div>
                    </div>
                    <button btn-action='0' type="submit" name="submit"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                        id="btn-submit" title="Save">
                        <span class="button-content">
                            Save
                        </span>
                    </button>
                    <button btn-action='2' type="submit"
                        class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                        onClick="toggleMetadata();" title="Save">
                        <span class="button-content">
                            Cancel
                        </span>
                    </button>
                    <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($metadata->id) ? $metadata->id : 0; ?>" />
        </form>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $advId = addslashes($_REQUEST['id']);
        $advInfo = Services::find_by_id($advId);
        $status = ($advInfo->status == 1) ? "checked" : " ";
        $unstatus = ($advInfo->status == 0) ? "checked" : " ";
        $external = ($advInfo->linktype == 1) ? "checked" : " ";
        $internal = ($advInfo->linktype == 0) ? "checked" : " ";
    endif;
    // $servicetype= $_SESSION['type_id_service'];
    $typeid = (!empty($session->get('type_id_service'))) ? $session->get('type_id_service') : 2;
?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Service' : 'Add Service'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
            onClick="viewServiceslist();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-arrow-circle-left"></i>
            </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="services_frm">
                <input type="hidden" name="type" value="<?php echo $typeid; ?>" />
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Services Title" class="col-md-6 validate[required,length[0,50]]" type="text"
                            name="title" id="title"
                            value="<?php echo !empty($advInfo->title) ? $advInfo->title : ""; ?>">
                    </div>
                </div>



                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Slug :</label>
                    </div>
                    <div class="form-input col-md-20">
                        <?php echo BASE_URL; ?><input placeholder="Slug"
                            class="col-md-3 validate[length[0,200]]" type="text"
                            name="slug" id="slug"
                            value="<?php echo !empty($advInfo->slug) ? $advInfo->slug : ""; ?>">
                        <span id="error"></span>
                    </div>
                </div>




                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Heading :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Heading" class="col-md-6 validate" type="text" name="heading"
                            id="heading"
                            value="<?php echo !empty($advInfo->heading) ? $advInfo->heading : ""; ?>">
                    </div>
                </div> 



              <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Sub Title :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Sub Title" class="col-md-6 validate" type="text" name="sub_title"
                            id="sub_title"
                            value="<?php echo !empty($advInfo->sub_title) ? $advInfo->sub_title : ""; ?>">
                    </div>
                </div> 





<!-- 
                <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Banner Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader">
                        <input type="file" name="banner_upload" id="banner_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                            </small>
                        </label>
                    </div>
                    <div id="preview_Banner"><input type="hidden" name="bannerArrayname[]" /></div>
                    <?php
                    if (!empty($advInfo->bannerimage)) {
                        $imgRec = unserialize($advInfo->bannerimage);
                        if (is_array($imgRec)) {
                            foreach ($imgRec as $key => $recimg) {
                                $deleteid = rand(0, 99999);
                                $imagePath = SITE_ROOT . 'images/services/banner/' . $recimg;
                                if (file_exists($imagePath)) { ?>
                                    <div class="col-md-3" id="removeSavedimg2<?php echo $deleteid; ?>">
                                        <div class="infobox info-bg">
                                            <div class="button-group" data-toggle="buttons">
                                                <span class="float-left">
                                                    <?php
                                                    if (file_exists(SITE_ROOT . "images/services/banner/" . $recimg)):
                                                        $filesize = filesize(SITE_ROOT . "images/services/banner/" . $recimg);
                                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                                    endif;
                                                    ?>
                                                </span>
                                                <a class="btn small float-right" href="javascript:void(0);"
                                                    onclick="deleteSavedServicesBanner(<?php echo $deleteid; ?>);">
                                                    <i class="glyph-icon icon-trash-o"></i>
                                                </a>
                                            </div>
                                            <img src="<?php echo IMAGE_PATH . 'services/banner/' . $recimg; ?>"
                                                style="width:100%" />
                                            <input type="hidden" name="bannerArrayname[]" value="<?php echo $recimg; ?>"
                                                class="validate[required,length[0,250]]" />
                                        </div>
                                    </div>
                    <?php }
                            }
                        }
                    } ?>
                </div> -->

                <!-- // image upload -->

                <!-- <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader">
                        <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                            </small>
                        </label>
                    </div>
                    <div id="preview_Image"><input type="hidden" name="imageArrayname[]" /></div>
                    <?php
                    if (!empty($advInfo->image)) {
                        $imgRec = unserialize($advInfo->image);
                        if (is_array($imgRec)) {
                            foreach ($imgRec as $key => $recimg) {
                                $deleteid = rand(0, 99999);
                                $imagePath = SITE_ROOT . 'images/services/' . $recimg;
                                if (file_exists($imagePath)) { ?>
                                    <div class="col-md-3" id="removeSavedimg<?php echo $deleteid; ?>">
                                        <div class="infobox info-bg">
                                            <div class="button-group" data-toggle="buttons">
                                                <span class="float-left">
                                                    <?php
                                                    if (file_exists(SITE_ROOT . "images/services/" . $recimg)):
                                                        $filesize = filesize(SITE_ROOT . "images/services/" . $recimg);
                                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                                    endif;
                                                    ?>
                                                </span>
                                                <a class="btn small float-right" href="javascript:void(0);"
                                                    onclick="deleteSavedServicesimage(<?php echo $deleteid; ?>);">
                                                    <i class="glyph-icon icon-trash-o"></i>
                                                </a>
                                            </div>
                                            <img src="<?php echo IMAGE_PATH . 'services/thumbnails/' . $recimg; ?>"
                                                style="width:100%" />
                                            <input type="hidden" name="imageArrayname[]" value="<?php echo $recimg; ?>"
                                                class="validate[required,length[0,250]]" />
                                        </div>
                                    </div>
                    <?php }
                            }
                        }
                    } ?>
                </div> -->








                <!-- //  icon image upload -->

                <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader">
                        <input type="file" name="icon_upload" id="icon_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                            </small>
                        </label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Icon"><input type="hidden" name="iconArrayname[]" /></div>
                    <?php
                    if (!empty($advInfo->iconimage)) {
                        $imgRec = unserialize($advInfo->iconimage);
                        if (is_array($imgRec)) {
                            foreach ($imgRec as $key => $recimg) {
                                $deleteid = rand(0, 99999);
                                $imagePath = SITE_ROOT . 'images/services/icon/' . $recimg;
                                if (file_exists($imagePath)) { ?>
                                    <div class="col-md-3" id="removeSavedimg1<?php echo $deleteid; ?>">
                                        <div class="infobox info-bg">
                                            <div class="button-group" data-toggle="buttons">
                                                <span class="float-left">
                                                    <?php
                                                    if (file_exists(SITE_ROOT . "images/services/icon/" . $recimg)):
                                                        $filesize = filesize(SITE_ROOT . "images/services/icon/" . $recimg);
                                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                                    endif;
                                                    ?>
                                                </span>
                                                <a class="btn small float-right" href="javascript:void(0);"
                                                    onclick="deleteSavedServicesicon(<?php echo $deleteid; ?>);">
                                                    <i class="glyph-icon icon-trash-o"></i>
                                                </a>
                                            </div>
                                            <img src="<?php echo IMAGE_PATH . 'services/icon/thumbnails/' . $recimg; ?>"
                                                style="width:100%" />
                                            <input type="hidden" name="iconArrayname[]" value="<?php echo $recimg; ?>"
                                                class="validate[required,length[0,250]]" />
                                        </div>
                                    </div>
                    <?php }
                            }
                        }
                    } ?>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Icon :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="e.g. fas fa-wifi" class="col-md-6" type="text" name="icon" id="icon"
                               value="<?php echo !empty($advInfo->icon) ? $advInfo->icon : ""; ?>"><br/><a href="https://fontawesome.com/v4/icons/" target="_blank">fa Icon</a>
                    </div>
                </div> -->





                <!-- //brief -->
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Brief :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <textarea name="brief" id="brief"
                                  class="medium-textarea character-brief validate[]"><?php echo !empty($advInfo->brief) ? $advInfo->brief : ""; ?></textarea>
                        <div class="brief-remaining clear input-description">250 characters left</div>
                    </div>
                </div> 

                <!-- brief end -->
                <?php if (($typeid == 2) || ($typeid == 3)) { ?>
                    <div class="form-row hide">
                        <div class="form-label col-md-2">
                            <label for="">
                                Link Type :
                            </label>
                        </div>
                        <div class="form-checkbox-radio col-md-9">
                            <input id="" class="custom-radio" type="radio" name="linktype" value="0"
                                onClick="linkTypeSelect(0);" <?php echo !empty($internal) ? $internal : "checked"; ?>>
                            <label for="">Internal Link</label>
                            <input id="" class="custom-radio" type="radio" name="linktype" value="1"
                                onClick="linkTypeSelect(1);" <?php echo !empty($external) ? $external : ""; ?>>
                            <label for="">External Link</label>
                        </div>
                    </div>

                    <div class="form-row hide">
                        <div class="form-label col-md-2">
                            <label for="">
                                Link :
                            </label>
                        </div>
                        <div class="form-input col-md-8">
                            <div class="col-md-4" style="padding-left:0px !important;">
                                <input placeholder="Link" class="" type="text" name="linksrc" id="linksrc"
                                    value="<?php echo !empty($advInfo->linksrc) ? $advInfo->linksrc : ""; ?>">
                            </div>
                            <div class="col-md-6" style="padding-left:0px !important;">
                                <?php
                                $Lpageview = !empty($advInfo->linksrc) ? $advInfo->linksrc : "";
                                $LinkTypeview = !empty($advInfo->linktype) ? $advInfo->linktype : "";
                                ?>
                                <select data-placeholder="Select Link Page" class="col-md-4 chosen-select" <?php echo ($LinkTypeview == 1) ? 'hide' : ''; ?> id="linkPage">
                                    <option value=""></option>
                                    <?php
                                    // Article Page Link
                                    echo Article::get_internal_link($Lpageview, $LinkTypeview);
                                    echo Services::get_internal_link($Lpageview, $LinkTypeview);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>


                
                <?php if (($typeid == 3) || ($typeid == 2) || ($typeid == 1)) { ?>
                    <div class="form-row">
                        <div class="form-label col-md-8">
                            <label for="">
                                Content :
                            </label>
                            <textarea name="content" id="content"
                                class="large-textarea"><?php echo !empty($advInfo->content) ? $advInfo->content : ""; ?></textarea>
                            <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                                href="javascript:void(0);">
                                <span class="button-content">Read More</span>
                            </a>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Status :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="status" id="check1"
                            value="1" <?php echo !empty($status) ? $status : "checked"; ?>>
                        <label for="">Published</label>
                        <input type="radio" class="custom-radio" name="status" id="check0"
                            value="0" <?php echo !empty($unstatus) ? $unstatus : ""; ?>>
                        <label for="">Un-Published</label>
                    </div>
                </div>
                <button btn-action='0' type="submit" name="submit"
                    class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                    id="btn-submit" title="Save">
                    <span class="button-content">
                        Save
                    </span>
                </button>
                <button btn-action='1' type="submit" name="submit"
                    class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                    id="btn-submit" title="Save">
                    <span class="button-content">
                        Save & More
                    </span>
                </button>
                <button btn-action='2' type="submit" name="submit"
                    class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                    id="btn-submit" title="Save">
                    <span class="button-content">
                        Save & quit
                    </span>
                </button>
                <input myaction='0' type="hidden" name="idValue" id="idValue"
                    value="<?php echo !empty($advInfo->id) ? $advInfo->id : 0; ?>" />

            </form>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script>
        var base_url = "<?php echo ASSETS_PATH; ?>";
        var editor_arr = ["content"];
        create_editor(base_url, editor_arr);
    </script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function() {
            $('#gallery_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/services/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
                'auto': true,
                'multi': true,
                'hideButton': false,
                'buttonText': 'Upload Image',
                'width': 125,
                'height': 21,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/services/uploaded_image.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_Image').append(msg).show();
                    });

                },
                'onDialogOpen': function(event, ID, fileObj) {},
                'onUploadError': function(file, errorCode, errorMsg, errorString) {
                    alert(errorMsg);
                },
                'onUploadComplete': function(file) {
                    //alert('The file ' + file.name + ' was successfully uploaded');
                }
            });
        });



        //icon image upload
        $(document).ready(function() {
            $('#icon_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/services/icon/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
                'auto': true,
                'multi': true,
                'hideButton': false,
                'buttonText': 'Upload Image',
                'width': 125,
                'height': 21,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/services/uploaded_icon.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_Icon').append(msg).show();
                    });

                },
                'onDialogOpen': function(event, ID, fileObj) {},
                'onUploadError': function(file, errorCode, errorMsg, errorString) {
                    alert(errorMsg);
                },
                'onUploadComplete': function(file) {
                    //alert('The file ' + file.name + ' was successfully uploaded');
                }
            });
        });

        //banner image upload
        $(document).ready(function() {
            $('#banner_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/services/banner/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
                'auto': true,
                'multi': true,
                'hideButton': false,
                'buttonText': 'Upload Image',
                'width': 125,
                'height': 21,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/services/uploaded_banner.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_Banner').append(msg).show();
                    });

                },
                'onDialogOpen': function(event, ID, fileObj) {},
                'onUploadError': function(file, errorCode, errorMsg, errorString) {
                    alert(errorMsg);
                },
                'onUploadComplete': function(file) {
                    //alert('The file ' + file.name + ' was successfully uploaded');
                }
            });
        });
        // ]]>
    </script>
    <!-- <script>
        $(document).ready(function () {
            /************************************ Editor for message *****************************************/
            var base_url = "<?php echo ASSETS_PATH; ?>";
            CKEDITOR.replace('content', {
                toolbar:
                    [
                        {
                            name: 'document',
                            items: ['Source', '-', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates']
                        },
                        {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']}, '/',
                        {name: 'colors', items: ['TextColor', 'BGColor']},
                        {name: 'tools', items: ['Maximize', 'ShowBlocks', '-', 'About']}
                    ]
            });
        });
    </script> -->


<script>
function toggleWebsiteFields(value) {
    var linkFields = document.getElementById('linkFields');
    var noFields   = document.getElementById('fieldsToHide');

    // Hide both first (STRICT)
    linkFields.style.display = 'none';
    noFields.style.display   = 'none';

    if (parseInt(value) === 1) {
        linkFields.style.display = 'block';
    } else {
        noFields.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var checked = document.querySelector('input[name="has_website"]:checked');
    if (checked) {
        toggleWebsiteFields(checked.value);
    }
});

document.getElementById('services_frm').addEventListener('submit', function () {
    var selected = document.querySelector('input[name="has_website"]:checked').value;

    if (selected == '1') {
        // YES → disable NO section
        document.querySelectorAll('#fieldsToHide input, #fieldsToHide select, #fieldsToHide textarea')
            .forEach(el => el.disabled = true);
    } else {
        // NO → disable YES section
        document.querySelectorAll('#linkFields input, #linkFields select, #linkFields textarea')
            .forEach(el => el.disabled = true);
    }
});
</script>



<?php endif;
include("services_images.php"); ?>