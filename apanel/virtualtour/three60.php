<?php

$threeTablename = "tbl_vt_360_images"; // Database table name

if (isset($_GET['page']) && $_GET['page'] == "virtualtour" && isset($_GET['mode']) && $_GET['mode'] == "threeImageList"):
    $pid = addslashes($_REQUEST['id']);
    clearImages($threeTablename, "360", "panorama");
    clearImages($threeTablename, "360/thumbnails", "panorama");
    ?>

    <h3>
        List 360 Images
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewthreeImage(<?= $pid ?>);">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-plus-square"></i></span>
            <span class="button-content"> Add New </span>
        </a>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right mrg5R" href="javascript:void(0);"
           onClick="viewVirtualTourList();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="my-msg"></div>

    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="subexample">
                <thead>
                <tr>
                    <th style="display:none;"></th>
                    <th class="text-center"><input class="check-all" type="checkbox"/></th>
                    <th>Title</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $three60s = Image360::find_by_sql("SELECT * FROM tbl_vt_360_images WHERE virtual_tour_id='$pid' ORDER BY sortorder DESC ");
                foreach ($three60s as $key => $three60): ?>
                    <tr id="<?php echo $three60->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $three60->id; ?>"/></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);"
                                   onClick="editthreeImage(<?php echo $pid; ?>,<?php echo $three60->id; ?>);"
                                   class="loadingbar-demo"
                                   title="<?php echo $three60->title; ?>"><?php echo $three60->title; ?></a>
                            </div>
                        </td>

                        <td class="text-center">
                            <?php
                            $statusImage = ($three60->status == 1) ? "bg-green" : "bg-red";
                            $statusText = ($three60->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                            ?>
                            <a href="javascript:void(0);"
                               class="btn small <?php echo $statusImage; ?> tooltip-button image360StatusToggler"
                               data-placement="top" title="<?php echo $statusText; ?>"
                               status="<?php echo $three60->status; ?>" id="toggleImg<?php echo $three60->id; ?>"
                               moduleId="<?php echo $three60->id; ?>">
                                <i class="glyph-icon icon-flag"></i>
                            </a>
                            <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button"
                               data-placement="top" title="Edit"
                               onclick="editthreeImage(<?php echo $pid; ?>,<?php echo $three60->id; ?>);">
                                <i class="glyph-icon icon-edit"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top"
                               title="Remove" onclick="three60Delete(<?php echo $three60->id; ?>);">
                                <i class="glyph-icon icon-remove"></i>
                            </a>
                            <input name="sortId" type="hidden" value="<?php echo $three60->id; ?>">
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
        <a class="btn medium primary-bg" href="javascript:void(0);" id="applySelected_btn_360">
            <span class="glyph-icon icon-separator float-right"><i class="glyph-icon icon-cog"></i></span>
            <span class="button-content"> Submit </span>
        </a>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEditthreeImage"):
    $pid = addslashes($_REQUEST['id']);
    if (isset($_GET['subid']) and !empty($_GET['subid'])):
        $imgId      = addslashes($_REQUEST['subid']);
        $imgRec     = Image360::find_by_id($imgId);
        $status     = ($imgRec->status == 1) ? "checked" : " ";
        $unstatus   = ($imgRec->status == 0) ? "checked" : " ";
    endif;
    ?>

    <h3>
        <?php echo (isset($_GET['subid'])) ? 'Edit 360 Image' : 'Add 360 Image'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewthreeImageList(<?= $pid ?>);">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="three60_frm">
                <input type="hidden" value="<?php echo $pid ?>" id="parentid"/>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="" class="col-md-5 validate[required,length[0,50]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($imgRec->title) ? $imgRec->title : ""; ?>">
                        <div>Enter the Name of 360 images. Make short as possible. Example: Restaurant Area</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Zoom Image :</label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder=""
                               class="col-md-5 validate[required,length[0,200]]" type="number"
                               name="hfov" id="hfov" step="0.1"
                               value="<?php echo !empty($imgRec->hfov) ? $imgRec->hfov : "100"; ?>">
                        <span id="error"></span>
                        <div>Sets the panorama’s starting zoom of view in degrees. Defaults to 100.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Tilt:</label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="" class="col-md-5" type="number" step="0.1" name="pitch" id="pitch"
                               value="<?php echo isset($imgRec->pitch) ? $imgRec->pitch : '0'; ?>">
                        <div>Sets the panorama’s starting tilt image position in degrees. Defaults to 0.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for=""> Rotate:</label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="" class="col-md-5" type="number" step="0.1" name="yaw" id="yaw"
                               value="<?php echo isset($imgRec->yaw) ? $imgRec->yaw : '0'; ?>">
                        <div>Sets the panorama’s starting rotate image position in degrees. Defaults to 0.</div>
                    </div>
                </div>

                <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>
                    <?php if (!empty($imgRec->panorama)): ?>
                        <div class="col-md-3" id="remove360img<?php echo $imgRec->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/360/" . $imgRec->panorama)):
                                            $filesize = filesize(SITE_ROOT . "images/360/" . $imgRec->panorama);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="delete360img(<?php echo $imgRec->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . '360/thumbnails/' . $imgRec->panorama; ?>" style="width:100%"/>
                                <input type="hidden" name="imageArrayname360"
                                       value="<?php echo !empty($imgRec->panorama) ? $imgRec->panorama : ""; ?>"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader <?php echo !empty($imgRec->panorama) ? "hide" : ""; ?>">
                        <input type="file" name="panorama" id="panorama" class="transparent no-shadow">
                        <label><small>Image Dimensions (1400 px X 1500 px)</small></label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image_360"></div>
                </div>
                <!-- end -->

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

                <input type="hidden" name="virtual_tour_id" id="virtual_tour_id" value="<?php echo $pid ?>"/>
                <input myaction='0' type="hidden" name="idValue" id="idValue"
                       value="<?php echo !empty($imgRec->id) ? $imgRec->id : 0; ?>"/>

            </form>
        </div>
    </div>

    <link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>

    <script type="text/javascript">
        $('#panorama').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/360/',
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
            'fileTypeExts' : '*.jpg;*.jpeg;*.webp', // Allowed formats
            'fileTypeDesc' : 'Image Files', // Description for file types
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function (file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/virtualtour/uploaded_image_360.php', {
                    imagefile: filename
                }, function (msg) {
                    $('#preview_Image_360').html(msg).show();
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

<?php endif; ?>