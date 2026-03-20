<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_product"; // Database table name
$moduleId = 3;                // module id >>>>> tbl_modules
$moduleFoldername = "product";        // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "product" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, $moduleFoldername . "/thumb", "img_thumb");
    clearImages($moduleTablename, $moduleFoldername . "/thumb/thumbnails", "img_thumb");
    clearImages($moduleTablename, $moduleFoldername . "/jpg", "img_jpg");
    clearImages($moduleTablename, $moduleFoldername . "/jpg/thumbnails", "img_jpg");
    clearImages($moduleTablename, $moduleFoldername . "/png", "img_png");
    clearImages($moduleTablename, $moduleFoldername . "/png/thumbnails", "img_png");
    clearImages($moduleTablename, $moduleFoldername . "/svg", "img_svg");
    ?>
    <h3>
        List Products
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="addNewProduct();">
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
                <?php $records = Product::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
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
        $productId = addslashes($_REQUEST['id']);
        $productInfo = Product::find_by_id($productId);

        $status = ($productInfo->status == 1) ? "checked" : " ";
        $unstatus = ($productInfo->status == 0) ? "checked" : " ";
    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Prodcut' : 'Add Prodcut'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewProductList();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-arrow-circle-left"></i>
            </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="product_frm">

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Name :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Name" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="name" id="name"
                               value="<?php echo !empty($productInfo->name) ? $productInfo->name : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($productInfo->title) ? $productInfo->title : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image (Thumb) :
                        </label>
                    </div>

                    <?php $delId = rand(0, 99999);
                    if (!empty($productInfo->img_thumb)): ?>
                        <div class="col-md-3" id="removeSavedimg<?= $delId ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/product/thumb/" . $productInfo->img_thumb)):
                                            $filesize = filesize(SITE_ROOT . "images/product/thumb/" . $productInfo->img_thumb);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedImage(<?= $delId ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'product/thumb/thumbnails/' . $productInfo->img_thumb; ?>" style="width:100%"/>
                                <input type="hidden" name="imageThumb" value="<?= $productInfo->img_thumb ?>" class=""/>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-input col-md-10 uploader<?= $delId ?> <?php echo !empty($productInfo->img_thumb) ? "hide" : ""; ?>">
                        <input type="file" name="thumb_upload" id="thumb_upload" class="transparent no-shadow">
                        <!--<label>
                            <small>Image Dimensions (1920 px X 860 px)</small>
                        </label>-->
                    </div>
                    <!-- Upload image preview -->
                    <div id="preview_Image_Thumb"></div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image (JPG) :
                        </label>
                    </div>

                    <?php $delId = rand(0, 99999);
                    if (!empty($productInfo->img_jpg)): ?>
                        <div class="col-md-3" id="removeSavedimg<?= $delId ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/product/jpg/" . $productInfo->img_jpg)):
                                            $filesize = filesize(SITE_ROOT . "images/product/jpg/" . $productInfo->img_jpg);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedImage(<?= $delId ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'product/jpg/thumbnails/' . $productInfo->img_jpg; ?>" style="width:100%"/>
                                <input type="hidden" name="imageJpg" value="<?= $productInfo->img_jpg ?>" class=""/>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-input col-md-10 uploader<?= $delId ?> <?php echo !empty($productInfo->img_jpg) ? "hide" : ""; ?>">
                        <input type="file" name="jpg_upload" id="jpg_upload" class="transparent no-shadow">
                        <!--<label>
                            <small>Image Dimensions (1920 px X 860 px)</small>
                        </label>-->
                    </div>
                    <!-- Upload image preview -->
                    <div id="preview_Image_Jpg"></div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image (PNG) :
                        </label>
                    </div>

                    <?php $delId = rand(0, 99999);
                    if (!empty($productInfo->img_png)): ?>
                        <div class="col-md-3" id="removeSavedimg<?= $delId ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/product/png/" . $productInfo->img_png)):
                                            $filesize = filesize(SITE_ROOT . "images/product/png/" . $productInfo->img_png);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedImage(<?= $delId ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'product/png/thumbnails/' . $productInfo->img_png; ?>" style="width:100%"/>
                                <input type="hidden" name="imageJpg" value="<?= $productInfo->img_png ?>" class=""/>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-input col-md-10 uploader<?= $delId ?> <?php echo !empty($productInfo->img_png) ? "hide" : ""; ?>">
                        <input type="file" name="png_upload" id="png_upload" class="transparent no-shadow">
                        <!--<label>
                            <small>Image Dimensions (1920 px X 860 px)</small>
                        </label>-->
                    </div>
                    <!-- Upload image preview -->
                    <div id="preview_Image_Png"></div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image (SVG) :
                        </label>
                    </div>

                    <?php $delId = rand(0, 99999);
                    if (!empty($productInfo->img_svg)): ?>
                        <div class="col-md-3" id="removeSavedimg<?= $delId ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/product/svg/" . $productInfo->img_svg)):
                                            $filesize = filesize(SITE_ROOT . "images/product/svg/" . $productInfo->img_svg);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedImage(<?= $delId ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'product/svg/' . $productInfo->img_svg; ?>" style="width:100%"/>
                                <input type="hidden" name="imageSvg" value="<?= $productInfo->img_svg ?>" class=""/>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-input col-md-10 uploader<?= $delId ?> <?php echo !empty($productInfo->img_svg) ? "hide" : ""; ?>">
                        <input type="file" name="svg_upload" id="svg_upload" class="transparent no-shadow">
                        <!--<label>
                            <small>Image Dimensions (1920 px X 860 px)</small>
                        </label>-->
                    </div>
                    <!-- Upload image preview -->
                    <div id="preview_Image_Svg"></div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                            Content :
                        </label>
                        <textarea name="content" id="content"
                                  class="large-textarea validate[required]"><?php echo !empty($productInfo->content) ? $productInfo->content : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                           href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
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
                       value="<?php echo !empty($productInfo->id) ? $productInfo->id : 0; ?>"/>
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
        $(document).ready(function () {
            $('#thumb_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify_svg.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/product/thumb/',
                    thumb_width: 200,
                    thumb_height: 200
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
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                'onUploadSuccess': function (file, data, response) {
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/product/uploaded_thumb.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image_Thumb').append(msg).show();
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

            $('#jpg_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify_svg.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/product/jpg/',
                    thumb_width: 200,
                    thumb_height: 200
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
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                'onUploadSuccess': function (file, data, response) {
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/product/uploaded_jpg.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image_Jpg').append(msg).show();
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

            $('#png_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify_svg.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/product/png/',
                    thumb_width: 200,
                    thumb_height: 200
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
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                'onUploadSuccess': function (file, data, response) {
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/product/uploaded_png.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image_Png').append(msg).show();
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

            $('#svg_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify_svg.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/product/svg/',
                    thumb_width: 200,
                    thumb_height: 200
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
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.svg; *.GIF; *.JPG; *.JPEG; *.PNG; *.SVG;',
                'buttonClass': 'button formButtons',
                'onUploadSuccess': function (file, data, response) {
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/product/uploaded_svg.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image_Svg').append(msg).show();
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