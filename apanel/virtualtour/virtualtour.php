<?php
$virtualtourTablename = "tbl_vt_virtual_tour";
if (isset($_GET['page']) && $_GET['page'] == "virtualtour" && isset($_GET['mode']) && $_GET['mode'] == "list"): ?>
    <h3>
        List Virtual Tour
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewVirtualTour();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-plus-square"></i></span>
            <span class="button-content"> Add New </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <div>Get sample copy of Virtual Tour shortcode. Example: "&lt;jcms:module:virtualtour-1/&gt;" where "1" is id of the item.</div>
    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                <thead>
                <tr>
                    <th style="display:none;"></th>
                    <th class="text-center"><input class="check-all" type="checkbox"/></th>
                    <th>Title</th>
                    <th>Short Code</th>
                    <th class="text-center">360 Images</th>
                    <th>Hotspots</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $virtual_tours = VirtualTour::find_by_sql("SELECT * FROM tbl_vt_virtual_tour ORDER BY sortorder DESC ");
                foreach ($virtual_tours as $key => $virtual_tour): ?>

                    <tr id="<?php echo $virtual_tour->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $virtual_tour->id; ?>"/></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);" class="loadingbar-demo"
                                   onClick="editVirtualTour(<?php echo $virtual_tour->id; ?>);"
                                   title="<?php echo $virtual_tour->title; ?>"><?php echo $virtual_tour->title; ?></a>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="col-md-12">
                                <?php echo $virtual_tour->id; ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="col-md-12">
                                <a class="primary-bg medium btn loadingbar-demo" title="" href="javascript:void(0);"
                                   onClick="viewthreeImageList(<?php echo $virtual_tour->id; ?>);">
                                    <span class="button-content">
                                        <span class="badge bg-orange radius-all-4 mrg5R" title="" data-original-title="Badge with tooltip">
                                            <?php echo $countImagesI = Image360::getTotalImages($virtual_tour->id); ?>
                                        </span>
                                        <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                    </span>
                                </a>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="col-md-12">
                                <a class="primary-bg medium btn loadingbar-demo" title="" href="javascript:void(0);"
                                   onClick="<?php echo ($countImagesI > 0) ? 'viewhotspotList('.$virtual_tour->id.');' : '' ?>">
                                    <span class="button-content">
                                        <span class="badge bg-orange radius-all-4 mrg5R" title="" data-original-title="Badge with tooltip">
                                            <?php echo $countImages = Hotspots::getTotalImages($virtual_tour->id); ?>
                                        </span>
                                        <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                    </span>
                                </a>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php
                            $statusImage = ($virtual_tour->status == 1) ? "bg-green" : "bg-red";
                            $statusText = ($virtual_tour->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                            ?>
                            <a href="javascript:void(0);"
                               class="btn small <?php echo $statusImage; ?> tooltip-button statusToggler"
                               data-placement="top" title="<?php echo $statusText; ?>"
                               status="<?php echo $virtual_tour->status; ?>"
                               id="imgHolder_<?php echo $virtual_tour->id; ?>"
                               moduleId="<?php echo $virtual_tour->id; ?>">
                                <i class="glyph-icon icon-flag"></i>
                            </a>
                            <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button"
                               data-placement="top" title="Edit"
                               onclick="editVirtualTour(<?php echo $virtual_tour->id; ?>);">
                                <i class="glyph-icon icon-edit"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn small bg-red tooltip-button"
                               title="Remove" data-placement="top"
                               onclick="virtualDelete(<?php echo $virtual_tour->id; ?>);">
                                <i class="glyph-icon icon-remove"></i>
                            </a>
                            <input name="sortId" type="hidden" value="<?php echo $virtual_tour->id; ?>">
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
            <span class="glyph-icon icon-separator float-right"><i class="glyph-icon icon-cog"></i></span>
            <span class="button-content"> Submit </span>
        </a>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEditVirtualTour"):
    if (isset($_GET['id']) and !empty($_GET['id'])):
        $imgId      = addslashes($_REQUEST['id']);
        $imgRec     = VirtualTour::find_by_id($imgId);
        $status     = ($imgRec->status == 1) ? "checked" : " ";
        $unstatus   = ($imgRec->status == 0) ? "checked" : " ";
    endif;
    ?>

    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Virtual Tour' : 'Add Vitual Tour'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewVirtualTourList();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="virtual_frm">
                <div class="form-row hidden">
                    <div class="form-label col-md-2">
                        <label for="">
                            Select option :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="toogle" value="1" checked>
                        <label for="">Virtual Tour</label>
                        <input type="radio" class="custom-radio" name="toogle" value="0">
                        <label for="">360 Video</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="" class="col-md-6 validate[required,length[0,50]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($imgRec->title) ? $imgRec->title : ""; ?>">
                        <div><label><small>Enter virtual tour name</small></label></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Scene Fade Duration :
                        </label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="Fade Duration for 360 Image"
                               class="col-md-6 validate[required,length[0,50]]" type="number"
                               name="scene_fade_duration" id="scene_fade_duration" step="1" min="500"
                               value="<?php echo !empty($imgRec->scene_fade_duration) ? $imgRec->scene_fade_duration : "500"; ?>">
                        <div><label><small> Enter numeric whole number value for Scene fade in and out duration in
                                    milisecond</small></label></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image Width :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Image Width Px."
                               class="col-md-4 validate[required,length[0,50]] noSpaces" type="text"
                               name="image_width" id="image_width"
                               value="<?php echo !empty($imgRec->image_width) ? $imgRec->image_width : "1200"; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image Height :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Image Hight Px."
                               class="col-md-4 validate[required,length[0,100]] noSpaces" type="text"
                               name="image_height" id="image_height"
                               value="<?php echo !empty($imgRec->image_height) ? $imgRec->image_height : "600"; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Hotspot Icon :
                        </label>
                    </div>
                    <?php if (!empty($imgRec->hotspot_icon)): ?>
                        <div class="col-md-3" id="removeHotspoticon<?php echo $imgRec->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/hotspot/" . $imgRec->hotspot_icon)):
                                            $filesize = filesize(SITE_ROOT . "images/hotspot/" . $imgRec->hotspot_icon);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteHotspoticon(<?php echo $imgRec->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'hotspot/thumbnails/' . $imgRec->hotspot_icon; ?>" style="width:100%"/>
                                <input type="hidden" name="imageArraynameIcon" value="<?php echo $imgRec->hotspot_icon; ?>"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader <?php echo !empty($imgRec->hotspot_icon) ? "hide" : ""; ?>">
                        <input type="file" name="hotspot_icon" id="hotspot_icon" class="transparent no-shadow">
                        <label><small>Image Dimensions (50 px X 50 px)</small></label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_icon"></div>
                </div>
                <!-- end -->

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Published :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="status" id="check1" value="1" <?php echo !empty($status) ? $status : "checked"; ?>>
                        <label for="">Published</label>
                        <input type="radio" class="custom-radio" name="status" id="check0" value="0" <?php echo !empty($unstatus) ? $unstatus : ""; ?>>
                        <label for="">Un-Published</label>
                    </div>
                </div>
                <button btn-action='0' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save</span>
                </button>
                <button btn-action='1' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save & More</span>
                </button>
                <button btn-action='2' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save & quit</span>
                </button>

                <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($imgRec->id) ? $imgRec->id : 0; ?>"/>

            </form>
        </div>
    </div>
 <link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>

    <script type="text/javascript">
        $('#hotspot_icon').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/upload_hotspot_icon.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/hotspot/',
                thumb_width: 380,
                thumb_height: 478
            },
            'method': 'post',
            'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
            'auto': true,
            'multi': false,
            'hideButton': false,
            'buttonText': 'Upload Image',
            'width': 125,
            'height': 21,
            'removeCompleted': true,
            'progressData': 'speed',
            'uploadLimit': 100,
            'fileTypeExts' : '*.jpg;*.jpeg;*.webp;*.png', // Allowed formats
            'fileTypeDesc' : 'Image Files', // Description for file types
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function (file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/virtualtour/upload_hotspot_icon.php', {
                    imagefile: filename
                }, function (msg) {
                    $('#preview_icon').html(msg).show();
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
    </script>
       
<?php endif;
include("three60.php");
include("hotspots.php");
?>

