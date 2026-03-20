<?php
if (isset($_GET['page']) && $_GET['page'] == "blog" && isset($_GET['mode']) && $_GET['mode'] == "blogImageList"):
    $id = intval(addslashes($_GET['id']));
    $moduleId = 27; // module id >>>>> tbl_modules
    $blogInfo = Blog::find_by_id($id);
    ?>
    <h3>
        List Blog Images ["<?php echo $blogInfo->title; ?>"]
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewbloglist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="divider"></div>
    <div class="my-msg"></div>

    <link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
    <form action="" class="col-md-12 center-margin" method="post" id="subblog_frm">
        <div class="row">
            <div class="form-row col-md-12">
                <div class="form-input col-md-10">
                    <input type="file" name="blog_images_upload" id="blog_images_upload" class="transparent no-shadow">
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
            <input type="hidden" name="blogid" value="<?php echo $id; ?>" class="validate[required]">
            <!-- Upload user image preview -->
            <div id="previewUser_Image"></div>
        </div>
    </form>

    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#blog_images_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/blog/blogimages/',
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
                    $.post('<?php echo BASE_URL;?>apanel/blog/uploaded_blog_image.php', {imagefile: filename}, function (msg) {
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
    clearImages("tbl_blog_images", "blog/blogimages");
    clearImages("tbl_blog_images", "blog/blogimages/thumbnails");
    $saveBlog = BlogImage::find_by_sql("SELECT * FROM tbl_blog_images WHERE blogid='{$id}' ORDER BY sortorder ASC");
    if ($saveBlog):
        ?>
        <div class="row">
            <div class="col-md-12 subImageblog-sort">
                <?php
                $ic = 1;
                foreach ($saveBlog as $blogRow):
                    ?>
                    <div class="col-md-3 removeSavedimg<?php echo $blogRow->id; ?> oldsort"
                         id="<?php echo $blogRow->id; ?>" csort="<?php echo $blogRow->id; ?>">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                                <span class="float-left"><?php
                                    if (file_exists(SITE_ROOT . "images/blog/blogimages/" . $blogRow->image)):
                                        $filesize = filesize(SITE_ROOT . "images/blog/blogimages/" . $blogRow->image);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedBlogSubimage(<?php echo $blogRow->id; ?>);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                                <?php
                                $imageStatus = ($blogRow->status == 1) ? 'icon-check-circle-o' : 'icon-clock-os-circle-o';
                                ?>
                                <a class="btn small float-right blogImageStatusToggle" href="javascript:void(0);"
                                   rowId="<?php echo $blogRow->id; ?>" status="<?php echo $blogRow->status; ?>">
                                    <i class="glyph-icon <?php echo $imageStatus; ?>"
                                       id="toggleImg<?php echo $blogRow->id; ?>"></i>
                                </a>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="editBlogImageTitle(<?php echo $blogRow->id; ?>);" title="Edit Title">
                                    <i class="glyph-icon icon-edit"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'blog/blogimages/thumbnails/' . $blogRow->image; ?>"
                                 style="width:100%"/>

                            <div class="button-group" data-toggle="buttons">
                                <span class="up-title clicked<?php echo $blogRow->id; ?>"
                                      img-id="<?php echo $blogRow->id; ?>"><?php echo $blogRow->title; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; endif; ?>
