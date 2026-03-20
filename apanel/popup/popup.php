<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_popup"; // Database table name
$moduleId = 32;             // module id >>>>> tbl_modules
$moduleFoldername = "popup";        // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "popup" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    SerclearImages($moduleTablename, $moduleFoldername);
    SerclearImages($moduleTablename, $moduleFoldername . "/thumbnails");
    ?>
    <h3>
        List Popup
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewPopup();">
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
                    <th class="text-center"><input class="check-all" type="checkbox"/></th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Deadline</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $records = Popup::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
                foreach ($records as $key => $record): ?>
                    <tr id="<?php echo $record->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>"/></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);"
                                   class="loadingbar-demo"
                                   title="<?php echo $record->title; ?>"><?php echo $record->title; ?></a>
                            </div>
                        </td>
                        <td class="text-center"><?php if ($record->type == 0) {
                                echo "Video";
                            } else {
                                echo "Image";
                            } ?></td>
                        <td class="text-center"><?php echo $record->date2; ?></td>
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
        $popupId = addslashes($_REQUEST['id']);
        $popupInfo = Popup::find_by_id($popupId);
        $status = ($popupInfo->status == 1) ? "checked" : " ";
        $unstatus = ($popupInfo->status == 0) ? "checked" : " ";

        $addtype = ($popupInfo->type == 1) ? "checked" : " ";
        $unaddtype = ($popupInfo->type == 0) ? "checked" : " ";

        $vertical = ($popupInfo->position == 1) ? "checked" : " ";
        $vertical_dimensions = ($popupInfo->position == 1) ? "Image Dimensions ( 450 px X 650 px)" : "";
        $horizontal = ($popupInfo->position == 2) ? "checked" : " ";
        $horizontal_dimensions = ($popupInfo->position == 2) ? "Image Dimensions ( 900 px X 600 px)" : " ";
        $square = ($popupInfo->position == 3) ? "checked" : " ";
        $square_dimensions = ($popupInfo->position == 3) ? "Image Dimensions ( 600 px X 600 px)" : " ";

        $external = ($popupInfo->linktype == 1) ? "checked" : "";
        $internal = ($popupInfo->linktype == 0) ? "checked" : "";

        $imghide = ($popupInfo->type == 0) ? 'hide' : '';
        $videohide = ($popupInfo->type == 1) ? 'hide' : '';

    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Popup' : 'Add Popup'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewpopuplist();">
    <span class="glyph-icon icon-separator">
        <i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="popup_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($popupInfo->title) ? $popupInfo->title : ""; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Start Date :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Start Date" class="col-md-2 validate[required,length[0,200]]" type="text"
                               name="date1" id="date1"
                               value="<?php echo !empty($popupInfo->date1) ? $popupInfo->date1 : ""; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Deadline :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Deadline" class="col-md-2 validate[required,length[0,200]]" type="text"
                               name="date2" id="date2"
                               value="<?php echo !empty($popupInfo->date2) ? $popupInfo->date2 : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Add Type :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype1"
                               value="1" <?php echo !empty($addtype) ? $addtype : "checked"; ?>>
                        <label for="">Image</label>
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype0"
                               value="0" <?php echo !empty($unaddtype) ? $unaddtype : ""; ?>>
                        <label for="">Video</label>
                    </div>
                </div>


                <div class="form-row add-image <?php echo !empty($imghide) ? $imghide : ''; ?>">
                    <div class="form-row">
                        <div class="form-label col-md-2">
                            <label for="">
                                Orientation :
                            </label>
                        </div>
                        <div class="form-checkbox-radio form-input col-md-10">
                            <input id="orientation-vertical" class="custom-radio" type="radio" name="orientation"
                                   value="1"
                                   onClick="orientationSelect(1);" <?php echo !empty($vertical) ? $vertical : "checked"; ?>>
                            <label for="orientation-vertical">Vertical</label>
                            <input id="orientation-horizontal" class="custom-radio" type="radio" name="orientation"
                                   value="2"
                                   onClick="orientationSelect(2);" <?php echo !empty($horizontal) ? $horizontal : ""; ?>>
                            <label for="orientation-horizontal">Horizontal</label>
                            <input id="orientation-square" class="custom-radio" type="radio" name="orientation"
                                   value="3"
                                   onClick="orientationSelect(3);" <?php echo !empty($square) ? $square : ""; ?>>
                            <label for="orientation-square">Square</label>
                        </div>
                    </div>

                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader">
                        <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                        <label id="image-dimensions">
                            <small>
                                <?php if (!empty($vertical_dimensions) || !empty($horizontal_dimensions) || !empty($square_dimensions)) {
                                    echo $vertical_dimensions;
                                    echo $horizontal_dimensions;
                                    echo $square_dimensions;
                                }else{
                                    echo "Image Dimensions ( 450 px X 650 px)";
                                } ?></small>
                        </label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image"><input type="hidden" name="imageArrayname[]"/></div>
                    <?php
                    if (!empty($popupInfo->image)) {
                        $imgRec = unserialize($popupInfo->image);
                        if (is_array($imgRec)) {
                            foreach ($imgRec as $key => $recimg) {
                                $deleteid = rand(0, 99999);
                                $imagePath = SITE_ROOT . 'images/popup/' . $recimg;
                                if (file_exists($imagePath)) { ?>
                                    <div class="col-md-3" id="removeSavedimg<?php echo $deleteid; ?>">
                                        <div class="infobox info-bg">
                                            <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/popup/" . $recimg)):
                                    $filesize = filesize(SITE_ROOT . "images/popup/" . $recimg);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                                <a class="btn small float-right" href="javascript:void(0);"
                                                   onclick="deleteSavedPopupimage(<?php echo $deleteid; ?>);">
                                                    <i class="glyph-icon icon-trash-o"></i>
                                                </a>
                                            </div>
                                            <img src="<?php echo IMAGE_PATH . 'popup/thumbnails/' . $recimg; ?>"
                                                 style="width:100%"/>
                                            <input type="hidden" name="imageArrayname[]" value="<?php echo $recimg; ?>"
                                                   class="validate[required,length[0,250]]"/>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }
                    } ?>
                </div>
                <div class="form-row add-image <?php echo !empty($imghide) ? $imghide : ''; ?>">
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
                <div class="form-row add-image <?php echo !empty($imghide) ? $imghide : ''; ?>">
                    <div class="form-label col-md-2">
                        <label for="">
                            Link :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <div class="col-md-4" style="padding-left:0px !important;">
                            <input placeholder="Link" class="" type="text" name="linksrc"
                                   id="linksrc"
                                   value="<?php echo !empty($popupInfo->linksrc) ? $popupInfo->linksrc : ""; ?>">
                        </div>
                        <div class="col-md-6" style="padding-left:0px !important;">
                            <select data-placeholder="Select Link Page" class="col-md-4 chosen-select" id="linkPage">
                                <option value=""></option>
                                <?php
                                $Lpageview = !empty($popupInfo->linksrc) ? $popupInfo->linksrc : "";
                                $LinkTypeview = !empty($popupInfo->linktype) ? $popupInfo->linktype : "";
                                // Article Page Link
                                echo Article::get_internal_link($Lpageview, $LinkTypeview);
                                // Offer Page Link
                                echo Offers::get_internal_link($Lpageview, $LinkTypeview);
                                ?>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="form-row <?php echo !empty($videohide) ? $videohide : '';
                echo isset($_GET['id']) ? '' : 'hide'; ?> videolink">
                    <div class="form-label col-md-2">
                        <label for="">
                            Video link :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <input placeholder="http://www.youtube.com/watch?v=fs2khSNtSu0 or https://www.facebook.com/reel/763482182422912"
                               class="col-md-8 validate[required,custom[url]]" type="text" name="source" id="source"
                               value="<?php echo !empty($popupInfo->source) ? $popupInfo->source : ""; ?>">
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
                       value="<?php echo !empty($popupInfo->id) ? $popupInfo->id : 0; ?>"/>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#gallery_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/popup/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL;?>uploadify/cancel.png',
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
                'onUploadSuccess': function (file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/popup/uploaded_image.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image').append(msg).show();
                    });

                },
                'onDialogOpen': function (event, ID, fileObj) {
                },
                'onUploadError': function (file, errorCode, errorMsg, errorString) {
                    alert(errorMsg);
                },
                'onUploadComplete': function (file) {
                    //alert('The file ' + file.name + ' was successfully uploaded');
                }
            });
        });
        // ]]>
    </script>
<?php endif; ?> 
