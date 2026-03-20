<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_pages"; // Database table name
$moduleId = 3;                // module id >>>>> tbl_modules
$moduleFoldername = "pages";  // module folder name  >>>>>  images/pages/

//CHeck URL contains Parameters(apanel/pages/list)
if (isset($_GET['page']) && $_GET['page'] == "pages" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    //search and clear images from gallery and thumbnail folder
    SerclearImages($moduleTablename, $moduleFoldername."/gallery","gallery_images");
    SerclearImages($moduleTablename, $moduleFoldername."/gallery/thumbnails","gallery_images");
    //clear images from main folder and thumbnail folder
    clearImages($moduleTablename, $moduleFoldername);
    clearImages($moduleTablename, $moduleFoldername . "/thumbnails");
    ?>
    <h3>
        List Homepage
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewPages();">
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
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php 
                $maintenance =  Config::find_by_field('upcoming');
                $records = Page::find_by_sql("SELECT * FROM " . $moduleTablename . " WHERE upcoming=$maintenance ORDER BY sortorder DESC ");
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
                            <!-- <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top"
                               title="Remove" onclick="recordDelete(<?php echo $record->id; ?>);">
                                <i class="glyph-icon icon-remove"></i>
                            </a> -->
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
                <!-- <option value="delete"><?php echo $GLOBALS['basic']['delete']; ?></option> -->
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
        $pagesId = addslashes($_REQUEST['id']);
        $pagesInfo = Page::find_by_id($pagesId);
        $status = ($pagesInfo->status == 1) ? "checked" : " ";
        $unstatus = ($pagesInfo->status == 0) ? "checked" : " ";
        $homepage = ($pagesInfo->homepage == 1) ? "checked" : " ";
        $nothomepage = ($pagesInfo->homepage == 0) ? "checked" : " ";

    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Homepage' : 'Add Homepage'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewPageslist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="pages_frm">
            <input type="hidden" value="<?php  $maintenance =  Config::find_by_field('upcoming'); echo $maintenance ?>" name="upcoming"/>
            <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($pagesInfo->title) ? $pagesInfo->title : ""; ?>">
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
                                                      value="<?php echo !empty($pagesInfo->slug) ? $pagesInfo->slug : ""; ?>">
                        <span id="error"></span>
                    </div>
                </div>


                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Date :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <input placeholder="Date" class="col-md-6 validate[required] datepicker" type="text"
                            name="date" id="date"
                            value="<?php echo !empty($pagesInfo->date) ? $pagesInfo->date : ""; ?>">
                    </div>
                </div> 



                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Banner Image :
                        </label>
                    </div>

                    <?php if (!empty($pagesInfo->image)): ?>
                        <div class="col-md-3" id="removeSavedimg1">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/pages/" . $pagesInfo->image)):
                                    $filesize = filesize(SITE_ROOT . "images/pages/" . $pagesInfo->image);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteSavedimage(1);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'pages/thumbnails/' . $pagesInfo->image; ?>"
                                     style="width:100%"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader1 <?php echo !empty($pagesInfo->image) ? "hide" : ""; ?>">
                        <input type="file" name="banner_upload" id="banner_upload"
                               class="transparent no-shadow">
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_image"><input type="hidden" name="imageArrayname" value="<?php echo (!empty($pagesInfo->image))?$pagesInfo->image :'';  ?>" class=""/></div>
                </div> 



                <!-- *****************************************************************************************
                Image multi
                    ***************************************************************************************** -->
                    <div class="form-row add-image hide">
                        <div class="form-label col-md-2">
                            <label for="">
                                Gallery Image :
                            </label>
                        </div>
                        <div class="form-input col-md-10 uploader">
                            <input type="file" name="gallerys_upload" id="gallerys_upload" class="transparent no-shadow">
                                <label>
                                <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                    X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                                </small>
                            </label>
                            
                        </div>

                        <!-- Upload user image preview -->
                        <div id="preview_gallery"><input type="hidden" name="galleryArrayname[]"/></div>
                        
                        <?php
                        if (!empty($pagesInfo->gallery_images)) {
                            $galleryData = (string)$pagesInfo->gallery_images; 
                            $imgRec = @unserialize($galleryData);
                            if ($imgRec === false && $galleryData !== 'b:0;') {
                                $imgRec = [];
                            }
                            if (is_array($imgRec)) {
                                foreach ($imgRec as $key => $recimg) {
                                    $deleteid = rand(0, 99999);
                                    $imagePath = SITE_ROOT . 'images/pages/gallery/' . $recimg;
                                    if (file_exists($imagePath)) { ?>
                                        <div class="col-md-3" id="removesaveimg<?php echo $deleteid; ?>">
                                            <div class="infobox info-bg">
                                                <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/pages/gallery/" . $recimg)):
                                        $filesize = filesize(SITE_ROOT . "images/pages/gallery/" . $recimg);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                                    <a class="btn small float-right" href="javascript:void(0);"
                                                    onclick="deletesavepageimage(<?php echo $deleteid; ?>);">
                                                        <i class="glyph-icon icon-trash-o"></i>
                                                    </a>
                                                </div>
                                                <img src="<?php echo IMAGE_PATH . 'pages/gallery/thumbnails/' . $recimg; ?>"
                                                    style="width:100%"/>
                                                <input type="hidden" name="galleryArrayname[]" value="<?php echo $recimg; ?>"
                                                    class="validate[required,length[0,250]]"/>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            }
                        } ?>
                    </div>
                    
                <!-- *********************************************** -->
                <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                            Content 1 :
                        </label>
                        <textarea name="content" id="content"
                                  class="large-textarea validate[required]"><?php echo !empty($pagesInfo->content) ? $pagesInfo->content : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                           href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                            Content 2 :
                        </label>
                        <textarea name="content2" id="content2"
                                  class="large-textarea validate[required]"><?php echo !empty($pagesInfo->content2) ? $pagesInfo->content2 : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                           href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                            Content 3 :
                        </label>
                        <textarea name="content3" id="content3"
                                  class="large-textarea validate[required]"><?php echo !empty($pagesInfo->content3) ? $pagesInfo->content3 : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                           href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>



                
                <div class="form-row hide">
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="homepage" id="homepage1"
                               value="1" <?php echo !empty($homepage) ? $homepage : "checked"; ?>>
                        <label for="">Homepage</label>
                        <input type="radio" class="custom-radio" name="homepage" id="homepage0"
                               value="0" <?php echo !empty($nothomepage) ? $nothomepage : ""; ?>>
                        <label for="">Not at Homepage</label>
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="status" id="check1"
                               value="1" <?php echo !empty($status) ? $status : "checked"; ?>>
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

                <div class="form-row <?php echo (!empty($pagesInfo->meta_keywords) || !empty($pagesInfo->meta_description) || !empty($pagesInfo->meta_title)) ? '' : 'hide'; ?> metadata">
                    
                    <div class="col-md-12">
                        <div class="form-input col-md-12">
                            <input placeholder="Meta Title" class="col-md-6 validate[required]" type="text"
                                   name="meta_title" id="meta_title"
                                   value="<?php echo !empty($pagesInfo->meta_title) ? $pagesInfo->meta_title : ""; ?>">
                        </div>
                        <br/>
                        <div class="form-input col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords"
                                              class="character-keyword validate[required]"><?php echo !empty($pagesInfo->meta_keywords) ? $pagesInfo->meta_keywords : ""; ?></textarea>
                                    <div class="keyword-remaining clear input-description">250 characters left</div>
                                </div>
                                <div class="col-md-6">
                                    <textarea placeholder="Meta Description" name="meta_description"
                                              id="meta_description"
                                              class="character-description validate[required]"><?php echo !empty($pagesInfo->meta_description) ? $pagesInfo->meta_description : ""; ?></textarea>
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
                       value="<?php echo !empty($pagesInfo->id) ? $pagesInfo->id : 0; ?>"/>
            </form>
        </div>
    </div>
    
    <script>
        var base_url = "<?php echo ASSETS_PATH; ?>";
        var editor_arr = ["content", "content2", "content3"];
        create_editor(base_url, editor_arr);
    </script>

<script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#banner_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {       //formData tells where to upload the image
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/pages/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL;?>uploadify/cancel.png',
                'auto': true,
                'multi': false,
                'hideButton': false,
                'buttonText': 'Upload Image',
                'width': 125,
                'height': 21,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                /* Shows a preview after upload*/
                'onUploadSuccess': function (file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    //send the uploaded image to the server and then shows the prevgiew of the page
                    $.post('<?php echo BASE_URL;?>apanel/pages/uploaded_image.php', {imagefile: filename}, function (msg) {
                        $('#preview_image').html(msg).show();
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

<!-- Create image upload button that lets u to pick the images and upload it to target folder (images/pages/gallery/) -->
<script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#gallerys_upload').uploadify({
        'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
        'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
        'formData': {
            PROJECT: '<?php echo SITE_FOLDER;?>',
            targetFolder: 'images/pages/gallery/',
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
        'fileTypeExts': '*.gif; *.jpg; *.jpeg; *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
        'buttonClass': 'button formButtons',
        'onUploadSuccess': function (file, data, response) {
            var filename = data;
            // Append only new image block
            $.post('<?php echo BASE_URL;?>apanel/pages/gallery_image.php', {imagefile: filename}, function (msg) {
                $('#preview_gallery').append(msg);
            });
        },
        'onUploadError': function (file, errorCode, errorMsg, errorString) {
            alert('Upload Error: ' + errorMsg);
        }
    });
});
</script>

<?php endif; ?>


