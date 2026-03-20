<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />

<?php
$moduleTablename  = "tbl_menu"; // Database table name
$moduleId           = 2;                // module id >>>>> tbl_modules
$moduleFoldername = "";        // Image folder name
$menuLevel = Module::get_properties($moduleId, 'level');
$position = array(1 => 'Top Menu', 2 => 'Footer Menu');

if (isset($_GET['page']) && $_GET['page'] == "menu" && isset($_GET['mode']) && $_GET['mode'] == "list"):

    clearImages($moduleTablename, "menu");
    clearImages($moduleTablename, "menu/thumbnails");
    // 
?>
    <h3>
        List Menu
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewMenu();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-plus-square"></i>
            </span>
            <span class="button-content"> Add Menu </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th class="text-center">Link</th>
                        <th class="text-center">Position</th>
                        <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $maintenance =  Config::find_by_field('upcoming');
                    $records = Menu::find_by_sql("SELECT * FROM " . $moduleTablename . " WHERE parentOf='0' AND upcoming=$maintenance ORDER BY sortorder ASC ");
                    foreach ($records as $record): ?>
                        <tr id="<?php echo $record->id; ?>">
                            <td class="text-center"><?php echo $record->sortorder; ?></td>
                            <td>
                                <?php
                                $submenu = Menu::countSubMenu($record->id);
                                if ($submenu):
                                ?>
                                    <a href="javascript:void(0);" title="title" onClick="displaySubMenu(<?php echo $record->id; ?>,'<?php echo $record->name; ?>')" id="" name="<?php echo $record->name; ?>">
                                        <?php echo $record->name; ?> <i>[<?php echo $submenu; ?>]</i>
                                    </a>
                                <?php else:
                                    echo $record->name;
                                endif; ?>
                            </td>
                            <td><?php echo $record->linksrc; ?></td>
                            <td class="text-center"><?php echo ($record->type) ? $position[$record->type] : 'N/A'; ?></td>
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
                                <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top" title="Remove" onclick="recordDelete(<?php echo $record->id; ?>,<?php echo ($submenu) ? '1' : 0; ?>);">
                                    <i class="glyph-icon icon-remove"></i>
                                </a>
                                <input name="sortId" type="hidden" value="<?php echo $record->id; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- First Submenu -->
    <div class="submenu1"></div>
    <!-- Second Submenu -->
    <div class="submenu2"></div>
    <!-- Third Submenu -->
    <div class="submenu3"></div>
    <!-- Fourth Submenu -->
    <div class="submenu4"></div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $menuId = addslashes($_REQUEST['id']);
        $menu = Menu::find_by_id($menuId);
        $status    = ($menu->status == 1) ? "checked" : "";
        $unstatus  = ($menu->status == 0) ? "checked" : "";

        $external = ($menu->linktype == 1) ? "checked" : "";
        $internal = ($menu->linktype == 0) ? "checked" : "";
    endif;
?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Menu' : 'Add Menu'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewMenulist();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-arrow-circle-left"></i>
            </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-10 center-margin" id="menu_frm">
                <input type="hidden" value="<?php $maintenance =  Config::find_by_field('upcoming');
                                            echo $maintenance ?>" name="upcoming" />
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Menu Name :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <input placeholder="Menu Name" class="col-md-4 validate[required,length[0,50]]" type="text" name="name" id="name" value="<?php echo !empty($menu->name) ? $menu->name : ""; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Parent :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <?php
                        $Parentview = !empty($menu->parentOf) ? $menu->parentOf : 0;
                        echo Menu::get_parentList_bylevel($menuLevel, $Parentview);
                        ?>
                    </div>
                </div>
                <div class="form-row menu-position">
                    <div class="form-label col-md-2">
                        <label for="">
                            Menu Position :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <select data-placeholder="None" class="chosen-select validate[required]" id="type" name="type">
                            <option value="">Choose Position</option>
                            <?php
                            // $first_position = reset($position); // Get the first element of the array
                            // echo '<option value="' . key($position) . '" selected>' . $first_position . '</option>';

                            foreach ($position as $key => $value):
                                $selected = ($menu->type == $key) ? "selected" : "";
                                echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <?php if ($maintenance == 1) { ?>
                    <div class="form-row add-image">
                        <div class="form-label col-md-2">
                            <label for="">
                                Image :
                            </label>
                        </div>

                        <?php if (!empty($menu->image)): ?>
                            <div class="col-md-1" id="removeSavedimg<?php echo $menu->id; ?>">
                                <div class="infobox info-bg">
                                    <div class="button-group" data-toggle="buttons">
                                        <span class="float-left">
                                            <?php
                                            if (file_exists(SITE_ROOT . "images/menu/" . $menu->image)):
                                                $filesize = filesize(SITE_ROOT . "images/menu/" . $menu->image);
                                                echo 'Size : ' . getFileFormattedSize($filesize);
                                            endif;
                                            ?>
                                        </span>
                                        <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedMenuimage(<?php echo $menu->id; ?>);">
                                            <i class="glyph-icon icon-trash-o"></i>
                                        </a>
                                    </div>
                                    <img src="<?php echo IMAGE_PATH . 'menu/thumbnails/' . $menu->image; ?>" style="width:100%" />
                                    <input type="hidden" name="imageArrayname" value="<?php echo !empty($menu->image) ? $menu->image : ""; ?>" class="" />
                                </div>
                            </div>

                        <?php endif; ?>
                        <div class="form-input col-md-10 uploader <?php echo !empty($menu->image) ? "hide" : ""; ?>">
                            <input type="file" name="menu_icon" id="menu_icon" class="transparent no-shadow">
                            <label><small>Image Dimensions (50 px X 50 px)</small></label>

                        </div>
                        <!-- Upload user image preview -->
                        <div id="preview_Image"></div>
                    </div>
                <?php } ?>
                <div class="form-row">
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
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Link :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <div class="col-md-4" style="padding-left:0px !important;">
                            <input placeholder="Menu Link" class="validate[required,length[0,50]]" type="text" name="linksrc" id="linksrc" value="<?php echo !empty($menu->linksrc) ? $menu->linksrc : ""; ?>">
                        </div>
                        <div class="col-md-6" style="padding-left:0px !important;">
                            <select data-placeholder="Select Link Page" class="col-md-4 chosen-select" id="linkPage">
                                <option value=""></option>
                                <?php
                                $Lpageview = !empty($menu->linksrc) ? $menu->linksrc : "";
                                $LinkTypeview = !empty($menu->linktype) ? $menu->linktype : "";
                                // Article Page Link
                                echo Article::get_internal_link($Lpageview, $LinkTypeview);
                                // Package Page Link
                                //echo Package::get_internal_link($Lpageview, $LinkTypeview);
                                // Download Page Link
                                // echo Download::get_internal_link($Lpageview,$LinkTypeview);

                                echo Services::get_internal_link($Lpageview, $LinkTypeview);
                                //echo Blog::get_internal_link($Lpageview, $LinkTypeview);

                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- logo upload -->
                <!-- <div class="form-row logo-upload-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Logo :
                        </label>
                    </div>

                    <?php if (!empty($menu->logo)): ?>
                        <div class="col-md-3" id="removeSavedlogo<?php echo $menu->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/menu/" . $menu->logo)):
                                            $filesize = filesize(SITE_ROOT . "images/menu/" . $menu->logo);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedMenulogo(<?php echo $menu->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'menu/thumbnails/' . $menu->logo; ?>" style="width:100%; height:100px; object-fit:cover;" />
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-input col-md-10 uploader_logo <?php echo !empty($menu->logo) ? "hide" : ""; ?>">
                        <input type="file" name="menu_logo" id="menu_logo" class="transparent no-shadow">
                        <label><small>Logo Dimensions (100 px X 100 px)</small></label>
                    </div>

                    <div id="preview_logo">
                        <input type="hidden" name="logoArrayname" value="<?php echo (!empty($menu->logo)) ? $menu->logo : '';  ?>" class="" />
                    </div>
                </div> -->


                <!-- end here -->




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
                <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($menu->id) ? $menu->id : 0; ?>" />
            </form>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function() {
            $('#menu_icon').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/menu/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
                'auto': true,
                'multi': false,
                'hideButton': false,
                'buttonText': 'Upload Image',
                'width': 100,
                'height': 25,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/menu/uploaded_image.php', {
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


    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function() {
            $('#menu_logo').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/menu/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
                'auto': true,
                'multi': false,
                'hideButton': false,
                'buttonText': 'Upload Logo',
                'width': 125,
                'height': 25,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedLogoName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/menu/uploaded_logo.php', {
                        imagefile: filename
                    }, function(msg) {
                        $('#preview_logo').html(msg).show();
                    });
                },
                'onDialogOpen': function(event, ID, fileObj) {},
                'onUploadError': function(file, errorCode, errorMsg, errorString) {
                    alert(errorMsg);
                },
                'onUploadComplete': function(file) {}
            });
        });
        // ]]>
    </script>


    <script>
        $(document).ready(function() {
            // Function to toggle logo upload based on position
            function toggleLogoUpload() {
                var position = $('#type').val();
                if (position == '1') { // Top Menu
                    $('.logo-upload-row').show();
                } else { // Footer Menu or others
                    $('.logo-upload-row').hide();
                }
            }

            // Run on page load
            toggleLogoUpload();

            // Run on change of menu position
            $('#type').change(function() {
                toggleLogoUpload();
            });
        });
    </script>

<?php endif; ?>