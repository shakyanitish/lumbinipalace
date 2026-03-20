<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename  = "tbl_download"; // Database table name
$moduleId         = 29;             // module id >>>>> tbl_modules
$moduleFoldername = "";     // Image folder name
// Define download categories (similar to service types)
$download_categories = array(
    1 => 'Research',
    2 => 'Case Study',
    3 => 'Medical Study'
);

if (isset($_GET['page']) && $_GET['page'] == "download" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, "download/docs");
    clearImages($moduleTablename, "download/docs/thumbnails");

    // Set the category type ID similar to services
    if (isset($_GET['category']) and !empty($_GET['category'])) {
        $session->set('category_id_download', $_GET['category']);
    }
    $categoryid = ($session->get('category_id_download')) ? $session->get('category_id_download') : 'all';
    $pagename = strtolower($_GET['page']);
?>
    <h3>
        List Download
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewdownload();">
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
                        <!-- <th class="text-center">Category</th> -->
                        <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $where_clause = ($categoryid == 'all') ? '' : "WHERE category={$categoryid}";
                    $records = Download::find_by_sql("SELECT * FROM " . $moduleTablename . " {$where_clause} ORDER BY sortorder DESC");
                    foreach ($records as $key => $record):
                    ?>
                        <tr id="<?php echo $record->id; ?>">
                            <td style="display:none;"><?php echo $key + 1; ?></td>
                            <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>" /></td>
                            <td>
                                <div class="col-md-7">
                                    <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);" class="loadingbar-demo" title="<?php echo $record->title; ?>"><?php echo $record->title; ?></a>
                                </div>
                            </td>

                            <!-- <td>
                                <?php echo $download_categories[$record->category] ?? 'N/A'; ?>
                            </td> -->


                            <td class="text-center">
                                <?php
                                $statusImage = ($record->status == 1) ? "bg-green" : "bg-red";
                                $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                                ?>
                                <a href="javascript:void(0);" class="btn small <?php echo $statusImage; ?> tooltip-button statusToggler" data-placement="top" title="<?php echo $statusText; ?>" status="<?php echo $record->status; ?>" id="imgHolder_<?php echo $record->id; ?>" moduleId="<?php echo $record->id; ?>">
                                    <i class="glyph-icon icon-flag"></i>
                                </a>
                                <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button" data-placement="top" title="Edit" onclick="editRecord(<?php echo $record->id; ?>);">
                                    <i class="glyph-icon icon-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top" title="Remove" onclick="recordDelete(<?php echo $record->id; ?>);">
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
            <span class="button-content"> Submit </span>
        </a>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $downloadId  = addslashes($_REQUEST['id']);
        $downloadInfo   = Download::find_by_id($downloadId);
        $status     = ($downloadInfo->status == 1) ? "checked" : " ";
        $unstatus   = ($downloadInfo->status == 0) ? "checked" : " ";
    endif;

    // Set the category type ID - if editing, use the saved category; if adding, use session or default to 1 (Research)
    if (isset($downloadInfo) && !empty($downloadInfo->category)):
        $categoryid = $downloadInfo->category;
    else:
        $categoryid = (!empty($session->get('category_id_download'))) ? $session->get('category_id_download') : 1;
    endif;
?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit download' : 'Add New download'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewdownloadlist();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-arrow-circle-left"></i>
            </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="download_frm">
                <input type="hidden" name="category" value="<?php echo $categoryid; ?>" />

                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Category :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <?php foreach ($download_categories as $key => $category): ?>
                            <input id="cat_<?php echo $key; ?>" class="custom-radio" type="radio" name="category" value="<?php echo $key; ?>"
                                <?php echo ($key == $categoryid ? 'checked' : ''); ?>>
                            <label for="cat_<?php echo $key; ?>"><?php echo $category; ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Title" class="col-md-6 validate[required,length[0,200]]" type="text" name="title" id="title" value="<?php echo !empty($downloadInfo->title) ? $downloadInfo->title : ""; ?>">
                    </div>
                </div>

                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Date :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <input placeholder="Download Date" class="col-md-6 validate[required] datepicker" type="text"
                            name="case_date" id="case_date"
                            value="<?php echo !empty($downloadInfo->case_date) ? $downloadInfo->case_date : ""; ?>">
                    </div>
                </div>


                <div class="form-row add-image">
                    <div class="form-label col-md-2">
                        <label for="">
                            Upload :
                        </label>
                    </div>

                    <?php if (!empty($downloadInfo->image)): ?>
                        <div class="col-md-6" id="removeSavedimg22">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/download/docs/" . $downloadInfo->image)):
                                            $filesize = filesize(SITE_ROOT . "images/download/docs/" . $downloadInfo->image);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedDownloadimage('22');">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <input type="hidden" name="imageArrayname" value="<?php echo $downloadInfo->image;; ?>" class="validate[required,length[0,250]]" /><?php echo $downloadInfo->image; ?>
                            </div>
                            <small><?php echo BASE_URL . "images/download/docs/" . $downloadInfo->image;  ?></small>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploaderimg <?php echo !empty($downloadInfo->image) ? "hide" : ""; ?>">
                        <input type="file" name="download_icon" id="download_icon" class="transparent no-shadow">
                        <label><small>Upload files. (*.pdf, *.docx, *.zip, *.rar and image files)</small></label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image"></div>
                </div>

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

                <button btn-action='0' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                    <span class="button-content">
                        Save
                    </span>
                </button>
                <button btn-action='1' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                    <span class="button-content">
                        Save & More
                    </span>
                </button>
                <button btn-action='2' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                    <span class="button-content">
                        Save & quit
                    </span>
                </button>
                <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($downloadInfo->id) ? $downloadInfo->id : 0; ?>" />
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
        $(document).ready(function() {

            $('#background_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/download/',
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
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/download/uploaded_logo.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_logo').html(msg).show();
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
            $('#download_icon').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadfile.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/download/docs/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
                'auto': true,
                'multi': false,
                'hideButton': false,
                'buttonText': 'Upload',
                'width': 100,
                'height': 25,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG; *.pdf; *.docx',
                'buttonClass': 'button formButtons',
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/download/uploaded_image.php', {
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
        });
        // ]]>
    </script>

    <script>
        // Filter downloads by category - similar to services
        function filterDownloadCategory(categoryId) {
            // Store the selected category in session or pass via URL
            window.location.href = '<?php echo BASE_URL; ?>apanel/index.php?page=download&mode=list&category=' + categoryId;
        }
    </script>

<?php endif; ?>