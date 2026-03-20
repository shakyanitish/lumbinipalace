<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_galleries"; // Database table name
$moduleId = 5;                // module id >>>>> tbl_modules
$moduleFoldername = "";        // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "gallery" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, "gallery");
    clearImages($moduleTablename, "gallery/thumbnails");
    ?>
    <h3>
        List Galleries
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewGallery();">
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
                    <th class="text-center">Sub Images</th>
                    <!-- <th class="text-center">Display</th> -->
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $records = Gallery::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
                foreach ($records as $k => $record): ?>
                    <tr id="<?php echo $record->id; ?>">
                        <td style="display:none;"><?php echo $k + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>"/></td>
                        <td>
                            <div class="top-icon-bar dropdown">
                                <a href="javascript:void(0);" title="" class="user-ico clearfix" data-toggle="dropdown">
                                    <span><?php echo $record->title; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="infobox info-bg mrg0B">
                                            <img src="<?php echo IMAGE_PATH . 'gallery/thumbnails/' . $record->image; ?>"
                                                 style="width:100%"/>
                                            <span><?php echo $record->title; ?></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td class="text-center">
                            <a class="primary-bg medium btn loadingbar-demo" title=""
                               onClick="viewsubimagelist(<?php echo $record->id; ?>);" href="javascript:void(0);">
                        <span class="button-content">
                            <span class="badge bg-orange radius-all-4 mrg5R" title=""
                                  data-original-title="Badge with tooltip"><?php echo $countImages = GalleryImage::getTotalImages($record->id); ?></span>
                            <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                        </span>
                            </a>
                        </td>
                        <!-- <td class="text-center"><?php echo ($record->type == '1') ? 'Inner Page' : 'Home Page'; ?></td> -->
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
$pagename = strtolower($_GET['page']);
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

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"): ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Gallery' : 'Add Gallery'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewGallerylist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <?php
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $galleryId = addslashes($_REQUEST['id']);
        $galleryInfo = Gallery::find_by_id($galleryId);
    endif;
    ?>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" method="post" id="gallery_frm">
                <!-- <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>
                    <?php if (!empty($galleryInfo->image)): ?>
                        <div class="col-md-3" id="removeSavedimg<?php echo $galleryInfo->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
								<?php
                                if (file_exists(SITE_ROOT . "images/gallery/" . $galleryInfo->image)):
                                    $filesize = filesize(SITE_ROOT . "images/gallery/" . $galleryInfo->image);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteSavedGalleryimage(<?php echo $galleryInfo->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'gallery/thumbnails/' . $galleryInfo->image; ?>"
                                     style="width:100%"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader <?php echo !empty($galleryInfo->image) ? "hide" : ""; ?>">
                        <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                            </small>
                        </label>
                    </div>
                     Upload user image preview
                    <div id="preview_Image"><input type="hidden" name="imageArrayname"
                                                   value="<?php echo !empty($galleryInfo->image) ? $galleryInfo->image : ""; ?>"
                                                   class=""/></div>
                </div> -->
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <input type="text" name="title" id="title"
                               value="<?php echo !empty($galleryInfo->title) ? $galleryInfo->title : ""; ?>"
                               class="col-md-6">
                    </div>
                </div>
                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Display :
                        </label>
                    </div>
                    <div class="form-input col-md-2">
                        <select id="type" name="type">
                            <option value="1" <?php echo (!empty($galleryInfo->type) and $galleryInfo->type == '1') ? 'selected' : ""; ?>>
                                Inner Page
                            </option>
                            <option value="2" <?php echo (!empty($galleryInfo->type) and $galleryInfo->type == '2') ? 'selected' : ""; ?>>
                                Home Page
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-input col-md-10 mrg10T">
                        <button type="submit" name="submit"
                                class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4 btn-submit"
                                id="btn-submit" title="Save">
                            <span class="button-content">
                                Save
                            </span>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="idValue" id="idValue"
                       value="<?php echo !empty($galleryInfo) ? $galleryInfo->id : 0; ?>"/>
        </div>
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
                    targetFolder: 'images/gallery/',
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
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function (file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/gallery/uploaded_image_edit.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image').html(msg).show();
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
<?php endif;
include("gallery_images.php"); ?>