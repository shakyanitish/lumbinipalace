<?php
if (isset($_GET['page']) && $_GET['page'] == "services" && isset($_GET['mode']) && $_GET['mode'] == "servicesImageList"):
    $id = intval(addslashes($_GET['id']));
    $moduleId = 20; // module id >>>>> tbl_modules
    $servicesInfo = Services::find_by_id($id);
    ?>
    <h3>
        List Services Images ["<?php echo $servicesInfo->title; ?>"]
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewServiceslist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="divider"></div>
    <div class="my-msg"></div>

    <link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
    <form action="" class="col-md-12 center-margin" method="post" id="subservices_frm">
        <div class="row">
            <div class="form-row col-md-12">
                <div class="form-input col-md-10">
                    <input type="file" name="services_images_upload" id="services_images_upload" class="transparent no-shadow">
                    <label>
                            <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                            </small>
                        </label>
                </div>
                <div class="form-input float-right">
                    <button type="submit" name="submit"
                            class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4 btn-submit"
                            id="btn-submit" title="Save">
                    <span class="button-content">
                        Save
                    </span>
                    </button>
                </div>
            </div>
            <input type="hidden" name="servicesid" value="<?php echo $id; ?>" class="validate[required]">
            <!-- Upload user image preview -->
            <div id="previewUser_Image"></div>
        </div>
    </form>

    <script type="text/javascript" src="<?php echo ASSETS_PATH;?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#services_images_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/services/servicesimages/',
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
                    $.post('<?php echo BASE_URL;?>apanel/services/uploaded_services_image.php', {imagefile: filename}, function (msg) {
                        $('#previewUser_Image').append(msg).show();
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

    <?php
    clearImages("tbl_services_images", "services/servicesimages");
    clearImages("tbl_services_images", "services/servicesimages/thumbnails");
    $saveServices = ServicesImage::find_by_sql("SELECT * FROM tbl_services_images WHERE servicesid='{$id}' ORDER BY sortorder ASC");
    if ($saveServices):
        ?>
        <div class="row">
            <div class="col-md-12 subImageservices-sort">
                <?php
                $ic = 1;
                foreach ($saveServices as $serviceRow):
                    ?>
                    <div class="col-md-3 removeSavedimg<?php echo $serviceRow->id; ?> oldsort"
                         id="<?php echo $serviceRow->id; ?>" csort="<?php echo $serviceRow->id; ?>">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                                <span class="float-left"><?php
                                    if (file_exists(SITE_ROOT . "images/services/servicesimages/" . $serviceRow->image)):
                                        $filesize = filesize(SITE_ROOT . "images/services/servicesimages/" . $serviceRow->image);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedServicesSubimage(<?php echo $serviceRow->id; ?>);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                                <?php
                                $imageStatus = ($serviceRow->status == 1) ? 'icon-check-circle-o' : 'icon-clock-os-circle-o';
                                ?>
                                <a class="btn small float-right servicesImageStatusToggle" href="javascript:void(0);"
                                   rowId="<?php echo $serviceRow->id; ?>" status="<?php echo $serviceRow->status; ?>">
                                    <i class="glyph-icon <?php echo $imageStatus; ?>"
                                       id="toggleImg<?php echo $serviceRow->id; ?>"></i>
                                </a>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="editServicesImageTitle(<?php echo $serviceRow->id; ?>);" title="Edit Title">
                                    <i class="glyph-icon icon-edit"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'services/servicesimages/thumbnails/' . $serviceRow->image; ?>"
                                 style="width:100%"/>

                            <div class="button-group" data-toggle="buttons">
                                <span class="up-title clicked<?php echo $serviceRow->id; ?>"
                                      img-id="<?php echo $serviceRow->id; ?>"><?php echo $serviceRow->title; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; endif; ?>
