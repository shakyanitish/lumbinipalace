<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename = "tbl_package"; // Database table name
$moduleId = 23;                // module id >>>>> tbl_modules
$moduleFoldername = "";        // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "package" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, "package");
    clearImages($moduleTablename, "package/thumbnails");

    SerclearImages($moduleTablename, "package/banner", "banner_image");
    SerclearImages($moduleTablename, "package/banner/thumbnails", "banner_image");
    SerclearImages($moduleTablename, "package/flag", "flag_image");
    SerclearImages($moduleTablename, "package/flag/thumbnails", "flag_image");

?>

    <h3>
        List Packages
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
            onClick="AddNewPackage();">
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
                        <th class="text-center">Title</th>
                        <th>Itinerary</th>
                        <!-- <th class="text-center">Images</th>  -->

                        <th>Sub Package</th>
                        <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php $records = Package::find_all();
                    foreach ($records as $key => $record): ?>
                        <tr id="<?php echo $record->id; ?>">
                            <td style="display:none;"><?php echo $key + 1; ?></td>
                            <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>" /></td>
                            <td>
                                <a href="javascript:void(0);" title="" class="user-ico clearfix"
                                    onclick="editRecord(<?php echo $record->id; ?>);">
                                    <span><?php echo $record->title; ?></span>
                                </a>
                            </td>
                            <td>
                                <a class="primary-bg medium btn loadingbar-demo" title=""
                                    onClick="viewItinerarylistPackage(<?php echo $record->id; ?>);" href="javascript:void(0);">
                                    <span class="button-content">
                                        <span class="badge bg-orange radius-all-4 mrg5R" title=""
                                            data-original-title="Badge with tooltip"><?php echo $countItinerary = PackageItinerary::getTotalSub($record->id); ?></span>
                                        <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                    </span>
                                </a>
                            </td> <!-- <td>
                                <a class="primary-bg medium btn loadingbar-demo" title=""
                                    onClick="viewsubimagelist(<?php echo $record->id; ?>);" href="javascript:void(0);">
                                    <span class="button-content">
                                        <span class="badge bg-orange radius-all-4 mrg5R" title=""
                                            data-original-title="Badge with tooltip"><?php echo $countImages = SubPackageImage::getTotalImages($record->id);
                                                                                        //var_dump($countImages);die();
                                                                                        ?></span>

                                        <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                    </span>
                                </a>
                            </td>  -->
                            <td>
                                <a class="primary-bg medium btn loadingbar-demo" title=""
                                    onClick="viewSubpackagelist(<?php echo $record->id; ?>);" href="javascript:void(0);">
                                    <span class="button-content">
                                        <span class="badge bg-orange radius-all-4 mrg5R" title=""
                                            data-original-title="Badge with tooltip"><?php echo $countImages = Subpackage::getTotalSub($record->id); ?></span>
                                        <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                    </span>
                                </a>
                                <?php $makasroom = ($record->type == 1) ? "icon-circle" : "icon-circle-o"; ?>
                                <a href="javascript:void(0);" class="btn small tooltip-button" data-placement="top"
                                    title="">
                                    <i class="glyph-icon <?php echo $makasroom; ?>"></i>
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

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $packageId = addslashes($_REQUEST['id']);
        $packageInfo = Package::find_by_id($packageId);
        $status = ($packageInfo->status == 1) ? "checked" : " ";
        $unstatus = ($packageInfo->status == 0) ? "checked" : " ";
        $masrom = ($packageInfo->type == 1) ? "checked" : " ";
        $unmasrom = ($packageInfo->type == 0) ? "checked" : " ";
        $masexp = ($packageInfo->type == 2) ? "checked" : " ";
        $maswed = ($packageInfo->type == 3) ? "checked" : " ";

    endif;
?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Package' : 'Add Package'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
            onClick="viewPackagelist();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-arrow-circle-left"></i>
            </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="package_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Package Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                            name="title" id="title"
                            value="<?php echo !empty($packageInfo->title) ? $packageInfo->title : ""; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Slug :</label>
                    </div>
                    <div class="form-input col-md-20">
                        <?php echo BASE_URL; ?><input placeholder="Slug"
                            class="col-md-3 validate[required,length[0,200]]" type="text"
                            name="slug" id="slug"
                            value="<?php echo !empty($packageInfo->slug) ? $packageInfo->slug : ""; ?>">
                        <span id="error"></span>
                    </div>
                </div>
                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Sub Title :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Package Sub" class="col-md-6 validate[length[0,200]]" type="text"
                            name="sub_title" id="sub_title"
                            value="<?php echo !empty($packageInfo->sub_title) ? $packageInfo->sub_title : ""; ?>">
                    </div>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Date :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <input placeholder="Date" class="col-md-6 validate[required] datepicker" type="text"
                            name="program_date" id="program_date"
                            value="<?php echo !empty($packageInfo->program_date) ? $packageInfo->program_date : ""; ?>">
                    </div>
                </div> -->

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
                    <!-- Upload user image preview -->
                    <div id="preview_banner"><input type="hidden" name="imageArrayname2[]" /></div>
                </div>

                <div class="form-row">
                    <?php
                    if (!empty($packageInfo->banner_image)):
                        $imageRec = unserialize($packageInfo->banner_image);
                        if ($imageRec):
                            foreach ($imageRec as $k => $imageRow): ?>
                                <div class="col-md-3" id="removeSavedimg<?php echo $k; ?>">
                                    <div class="infobox info-bg">
                                        <div class="button-group" data-toggle="buttons">
                                            <span class="float-left">
                                                <?php
                                                if (file_exists(SITE_ROOT . "images/package/banner/" . $imageRow)):
                                                    $filesize = filesize(SITE_ROOT . "images/package/banner/" . $imageRow);
                                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                                endif;
                                                ?>
                                            </span>
                                            <a class="btn small float-right" href="javascript:void(0);"
                                                onclick="deleteSavedPackageimage(<?php echo $k; ?>);">
                                                <i class="glyph-icon icon-trash-o"></i>
                                            </a>
                                        </div>
                                        <img src="<?php echo IMAGE_PATH . 'package/banner/thumbnails/' . $imageRow; ?>"
                                            style="width:100%" />
                                        <input type="hidden" name="imageArrayname2[]" value="<?php echo $imageRow; ?>" />

                                    </div>
                                </div>
                    <?php endforeach;
                        endif;
                    endif; ?>
                </div>




                <!-- flag image -->
                <!-- 
                <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Flag Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader">
                        <input type="file" name="flag_upload" id="flag_upload" class="transparent no-shadow">
                        <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                        </small>
                    </label>
                    </div>
                    <div id="preview_flag"><input type="hidden" name="flagArrayname[]"/></div>
                </div> -->

                <!-- <div class="form-row">
                    <?php
                    if (!empty($packageInfo->flag_image)):
                        $imageRec = unserialize($packageInfo->flag_image);
                        if ($imageRec):
                            foreach ($imageRec as $k => $imageRow): ?>
                                <div class="col-md-3" id="removeSavedflag<?php echo $k; ?>">
                                    <div class="infobox info-bg">
                                        <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/package/flag/" . $imageRow)):
                                            $filesize = filesize(SITE_ROOT . "images/package/flag/" . $imageRow);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                            <a class="btn small float-right" href="javascript:void(0);"
                                               onclick="deleteSavedPackageflag(<?php echo $k; ?>);">
                                                <i class="glyph-icon icon-trash-o"></i>
                                            </a>
                                        </div>
                                        <img src="<?php echo IMAGE_PATH . 'package/flag/thumbnails/' . $imageRow; ?>"
                                             style="width:100%"/>
                                        <input type="hidden" name="flagArrayname[]" value="<?php echo $imageRow; ?>"/>

                                    </div>
                                </div>
                            <?php endforeach;
                        endif;
                    endif; ?>
                </div> -->




                <!-- Explore Link -->


                <!-- <div class="form-row">
            	<div class="form-label col-md-2">
                    <label for="">
                        Link Type :
                    </label>
                </div>
                <div class="form-checkbox-radio col-md-9">
                    <input id="" class="custom-radio" type="radio" name="linktype" value="0" onClick="linkTypeSelect(0);" <?php echo !empty($internal) ? $internal : "checked"; ?>>
                    <label for="">Internal Link</label>
                    <input id="" class="custom-radio" type="radio" name="linktype" value="1" onClick="linkTypeSelect(1);" <?php echo !empty($external) ? $external : ""; ?>>
                    <label for="">External Link</label>
                </div>
            </div> -->
                <!-- <div class="form-row">
            	<div class="form-label col-md-2">
                    <label for="">
                        Link :
                    </label>
                </div>
                <div class="form-input col-md-8">
                	<div class="col-md-4" style="padding-left:0px !important;">
                    	<input  placeholder="Menu Link" class="validate[required,length[0,50]]" type="text" name="linksrc" id="linksrc" value="<?php echo !empty($packageInfo->linksrc) ? $packageInfo->linksrc : ""; ?>">                    
                    </div>
                	<div class="col-md-6" style="padding-left:0px !important;">
						<select data-placeholder="Select Link Page" class="col-md-4 chosen-select" id="linkPage">
                            <option value=""></option>
                            <?php
                            $Lpageview = !empty($packageInfo->linksrc) ? $packageInfo->linksrc : "";

                            $LinkTypeview = !empty($packageInfo->linktype) ? $packageInfo->linktype : "";
                            // Article Page Link
                            echo Article::get_internal_link($Lpageview, $LinkTypeview);
                            // Package Page Link
                            echo Package::get_internal_link($Lpageview, $LinkTypeview);
                            // Download Page Link
                            // echo Download::get_internal_link($Lpageview,$LinkTypeview);


                            echo Services::get_internal_link($Lpageview, $LinkTypeview);
                            ?>
                        </select>  
                    </div>                    
                </div>
            </div> -->
                <!--<div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Brief :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <textarea placeholder="Brief" class="col-md-6 validate[length[0,200]]" type="text"
                                  name="sub_title"
                                  id="sub_title"><?php //echo !empty($packageInfo->sub_title) ? $packageInfo->sub_title : ""; 
                                                    ?></textarea>
                    </div>
                </div>

                
-->
                <div class="events-only-fields" style="display: none;">
                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">
                                Content 1:
                            </label>
                            <textarea name="content1" id="content1"
                                class="large-textarea validate[]"><?php echo !empty($packageInfo->content1) ? $packageInfo->content1 : ""; ?></textarea>
                            <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore1" href="javascript:void(0);">
                                <span class="button-content">Read More</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="form-row multi-discount">
                    <div class="form-label col-md-2">
                        <label for="">Includes :</label>
                    </div>
                    <div class="form-input col-md-10">
                        <div class="" id="includes_sortable">
                            <?php
                            $savedIncludes = !empty($packageInfo->incexc) ? unserialize($packageInfo->incexc) : array();
                            if (sizeof($savedIncludes) > 0 && is_array($savedIncludes)) {
                                foreach ($savedIncludes as $childKey => $childRow) {
                                    // Handle both old format (string) and new format (array)
                                    $incText = is_array($childRow) ? $childRow['text'] : $childRow;
                                    $incUrl  = is_array($childRow) ? (!empty($childRow['url']) ? $childRow['url'] : '') : '';
                            ?>
                                    <div class="mrg10B">
                                        <span class="drag-handle cp"><i class="glyph-icon icon-arrows"></i></span>
                                        <input type="text" placeholder="Includes Text" class="col-md-6 validate[]"
                                            name="incexc_text[]" value="<?php echo $incText; ?>">

                                        <input type="text" placeholder="Learn More URL (optional)" class="col-md-4 validate[]"
                                            name="incexc_url[]" value="<?php echo $incUrl; ?>">

                                        <span class="cp remove_includes_row" onclick="$(this).parent().remove();"><i class="glyph-icon icon-minus-square"></i></span><br>
                                    </div>
                            <?php
                                }
                            } ?>

                            <div id="add_includes_div"></div>
                            <a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" title="Add" onclick="addIncludesRow();">
                                <i class="glyph-icon icon-plus-square"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                            Content :
                        </label>
                        <textarea name="content" id="content"
                            class="large-textarea validate[required]"><?php echo !empty($packageInfo->content) ? $packageInfo->content : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore" href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>

                <div class="events-only-fields" style="display: none;">
                    <!-- //Seapare includes with url set_exception_handler with content -->
                    <div class="form-row multi-discount">
                        <div class="form-label col-md-2">
                            <label for="">Includes :</label>
                        </div>
                        <div class="form-input col-md-10">
                            <div class="" id="includes_sortable_2">
                                <?php
                                $savedIncludes = !empty($packageInfo->incexc1) ? unserialize($packageInfo->incexc1) : array();
                                if (sizeof($savedIncludes) > 0 && is_array($savedIncludes)) {
                                    foreach ($savedIncludes as $childKey => $childRow) {
                                        // Handle both old format (string) and new format (array)
                                        $incText = is_array($childRow) ? $childRow['text'] : $childRow;
                                        $incUrl  = is_array($childRow) ? (!empty($childRow['url']) ? $childRow['url'] : '') : '';
                                ?>
                                        <div class="mrg10B">
                                            <span class="drag-handle cp"><i class="glyph-icon icon-arrows"></i></span>
                                            <input type="text" placeholder="Includes Text" class="col-md-6 validate[]"
                                                name="incexc_text1[]" value="<?php echo $incText; ?>">

                                            <input type="text" placeholder="Learn More URL (optional)" class="col-md-4 validate[]"
                                                name="incexc_url1[]" value="<?php echo $incUrl; ?>">

                                            <span class="cp remove_includes_row" onclick="$(this).parent().remove();"><i class="glyph-icon icon-minus-square"></i></span><br>
                                        </div>
                                <?php
                                    }
                                } ?>

                                <div id="add_includes_div_2"></div>
                                <a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" title="Add" onclick="addIncludesRow2();">
                                    <i class="glyph-icon icon-plus-square"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">
                                Content 2:
                            </label>
                            <textarea name="content2" id="content2"
                                class="large-textarea validate[]"><?php echo !empty($packageInfo->content2) ? $packageInfo->content2 : ""; ?></textarea>
                            <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore2" href="javascript:void(0);">
                                <span class="button-content">Read More</span>
                            </a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">
                                Content 3:
                            </label>
                            <textarea name="content3" id="content3"
                                class="large-textarea validate[]"><?php echo !empty($packageInfo->content3) ? $packageInfo->content3 : ""; ?></textarea>
                            <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore3" href="javascript:void(0);">
                                <span class="button-content">Read More</span>
                            </a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">
                                Content 4:
                            </label>
                            <textarea name="content4" id="content4"
                                class="large-textarea validate[]"><?php echo !empty($packageInfo->content4) ? $packageInfo->content4 : ""; ?></textarea>
                            <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore4" href="javascript:void(0);">
                                <span class="button-content">Read More</span>
                            </a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">
                                Content 5:
                            </label>
                            <textarea name="content5" id="content5"
                                class="large-textarea validate[]"><?php echo !empty($packageInfo->content5) ? $packageInfo->content5 : ""; ?></textarea>
                            <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore5" href="javascript:void(0);">
                                <span class="button-content">Read More</span>
                            </a>
                        </div>
                    </div>
                </div>







                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Type :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="type" id="check1"
                            value="1" <?php echo !empty($masrom) ? $masrom : ""; ?>>
                        <label for="">Room</label>
                        <input type="radio" class="custom-radio" name="type" id="check0"
                            value="0" <?php echo !empty($unmasrom) ? $unmasrom : "checked"; ?>>
                        <label for="">Dine</label>
                        <input type="radio" class="custom-radio" name="type" id="check2"
                            value="2" <?php echo !empty($masexp) ? $masexp : ""; ?>>
                        <label for="">Experience</label>
                        <input type="radio" class="custom-radio" name="type" id="check3"
                            value="3" <?php echo !empty($maswed) ? $maswed : ""; ?>>
                        <label for="">Events</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Published :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="status" id="check1"
                            value="1" <?php echo !empty($status) ? $status : " checked"; ?>>
                        <label for="">Published</label>
                        <input type="radio" class="custom-radio" name="status" id="check0"
                            value="0" <?php echo !empty($unstatus) ? $unstatus : ""; ?>>
                        <label for="">Un-Published</label>
                    </div>
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
                <div class="form-row <?php echo (!empty($packageInfo->meta_keywords) || !empty($packageInfo->meta_description) || !empty($packageInfo->meta_title)) ? '' : 'hide'; ?> metadata">
                    <div class="col-md-12">
                        <div class="form-input col-md-12">
                            <input placeholder="Meta Title" class="col-md-6 validate[required]" type="text"
                                name="meta_title" id="meta_title"
                                value="<?php echo !empty($packageInfo->meta_title) ? $packageInfo->meta_title : ""; ?>">
                        </div>
                        <br />
                        <div class="form-input col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords"
                                        class="character-keyword validate[required]"><?php echo !empty($packageInfo->meta_keywords) ? $packageInfo->meta_keywords : ""; ?></textarea>
                                    <div class="keyword-remaining clear input-description">250 characters left</div>
                                </div>
                                <div class="col-md-6">
                                    <textarea placeholder="Meta Description" name="meta_description"
                                        id="meta_description"
                                        class="character-description validate[required]"><?php echo !empty($packageInfo->meta_description) ? $packageInfo->meta_description : ""; ?></textarea>
                                    <div class="description-remaining clear input-description">160 characters left</div>
                                </div>
                            </div>
                        </div>
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
                    value="<?php echo !empty($packageInfo->id) ? $packageInfo->id : 0; ?>" />
            </form>
        </div>
    </div>

    <script>
        var base_url = "<?php echo ASSETS_PATH; ?>";
        var editor_arr = ["content"];
        create_editor(base_url, editor_arr);
    </script>
    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function() {
            $('#package_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/package/',
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
                    $.post('<?php echo BASE_URL; ?>apanel/package/uploaded_image.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_Image').html(msg).show();
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

            $('#banner_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/package/banner/',
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
                    $.post('<?php echo BASE_URL; ?>apanel/package/banner_image.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_banner').append(msg).show();
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




            $('#flag_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/package/flag/',
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
                    $.post('<?php echo BASE_URL; ?>apanel/package/flag_image.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_flag').append(msg).show();
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

            $('#header_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/package/imgheader/',
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
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/package/header_image.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_himage').html(msg).show();
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

<?php endif;
include("sub_package.php");
include("itinerarypackage.php");
// include("subpackage_images.php");

?>