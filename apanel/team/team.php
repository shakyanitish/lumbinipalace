<?php
$moduleTablename = "tbl_team"; // Database table name
$moduleId = 312;                // module id >>>>> tbl_modules
$moduleFoldername = "team";        // Image folder name
$role = ['4' => 'Past Chairperson','5' => 'Life Time Members','3' => 'Chairperson','1' => 'Member', '2' => 'Staff'];


if (isset($_GET['page']) && $_GET['page'] == "team" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, "team");
    clearImages($moduleTablename, "team/thumbnails");
    ?>
    <h3>
        List teams
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="addNewteam();">
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
                    <th class="text-center">Name</th>
                    <th class="text-center">Position</th>
                    <th class="text-center">Role</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $records = team::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
                foreach ($records as $key => $record): ?>
                    <tr id="<?php echo $record->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>"/></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);"
                                   class="loadingbar-demo"
                                   title='<?php echo $record->name; ?>'><?php echo $record->name; ?></a>
                            </div>
                        </td>
                        <td class="text-center"><?php echo $record->title; ?></td>
                        <td class="text-center"><?php echo ($record->role) ? $role[$record->role] : 'N/A'; ?></td>
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
    <div class="form-row hide">
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
            <input type="hidden" name="page_name" value="<?php echo $pagename ?>"/>
            <input type="hidden" name="module_id" value="<?php echo $moduleId ?>"/>
            <div class="col-md-12">
                <div class="form-input col-md-12">
                    <input placeholder="Meta Title" class="col-md-6 validate[required]" type="text"
                           name="meta_title" id="meta_title"
                           value="<?php echo !empty($metadata->meta_title) ? $metadata->meta_title : ""; ?>">
                </div>
                <br/>
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
                    <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($metadata->id) ? $metadata->id : 0; ?>"/>
        </form>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $teamId = addslashes($_REQUEST['id']);
        $teamInfo = team::find_by_id($teamId);
        $status = ($teamInfo->status == 1) ? "checked" : " ";
        $unstatus = ($teamInfo->status == 0) ? "checked" : " ";
    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit team' : 'Add team'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewteamList();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="team_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Name :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Name" class="col-md-6" type="text"
                               name="name" id="name"
                               value="<?php echo !empty($teamInfo->name) ? $teamInfo->name : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Position :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Position" class="col-md-6" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($teamInfo->title) ? $teamInfo->title : ''; ?>">
                    </div>
                </div>
                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Social Media :
                        </label>
                    </div>
                    <div class="form-input col-md-7">
                        <div class="col-sm-4" style="padding-left:0;">
                            <input placeholder="Facebook link" ype="text"
                                   name="facebook" id="facebook"
                                   value="<?php echo !empty($teamInfo->facebook) ? $teamInfo->facebook : ''; ?>">
                        </div>
                        <div class="col-sm-6" style="padding-left:0;">
                            <input placeholder="Instagram link" class="col-sm-6" type="text"
                                   name="instagram" id="instagram"
                                   value="<?php echo !empty($teamInfo->instagram) ? $teamInfo->instagram : ''; ?>">
                        </div>
                    </div>
                </div> -->

                <div class="form-row menu-position">
                    <div class="form-label col-md-2">
                        <label for="">
                            Role :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <select data-placeholder="None" class="chosen-selec validate[required]" id="role" name="role">
                            <option value="">Choose Role</option>
                            <?php
                            foreach ($role as $key => $posi) {
                                $selected = ($teamInfo->role == $key) ? 'selected' : '';
                                echo '<option value="' . $key . '" ' . $selected . '>' . $posi . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>

                    <?php if (!empty($teamInfo->image)): ?>
                        <div class="col-md-3" id="removeSavedimg1">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/team/" . $teamInfo->image)):
                                    $filesize = filesize(SITE_ROOT . "images/team/" . $teamInfo->image);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteSavedSlideshowimage(1);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'team/thumbnails/' . $teamInfo->image; ?>"
                                     style="width:100%"/>
                                <input type="hidden" name="imageArrayname" value="<?php echo (!empty($teamInfo->image)) ? $teamInfo->image : ''; ?>" class=""/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader1 <?php echo !empty($teamInfo->image) ? "hide" : ""; ?>">
                        <input type="file" name="background_upload" id="background_upload"
                               class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (240 px X 240 px)</small>
                        </label>
                    </div>
                    <div id="preview_Image"></div>
                </div> -->

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            content
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                     <textarea name="content" id="content"
                               class="large-textarea validate[required]"><?php echo !empty($teamInfo->content) ? $teamInfo->content : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                           href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                             content
                        </label>
                  <textarea name="content_gr" id="content_gr"
                                  class="large-textarea validate[required]"><?php echo !empty($teamInfo->content) ? $teamInfo->content : ""; ?></textarea>
                    </div>
                </div> -->
                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Email :
                        </label>
                    </div>
                    <div class="form-input col-md-20">
                        <input placeholder="Email" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="email" id="email"
                               value="<?php echo !empty($teamInfo->email) ? $teamInfo->email : ''; ?>">
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
                       value="<?php echo !empty($teamInfo->id) ? $teamInfo->id : 0; ?>"/>
            </form>
        </div>
    </div>
    <script>
        var base_url = "<?php echo ASSETS_PATH; ?>";
        var editor_arr = ["content", "content"];
        create_editor(base_url, editor_arr);
    </script>
    <link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function () {
            $('#background_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/team/',
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
                    $.post('<?php echo BASE_URL;?>apanel/team/uploaded_image.php', {imagefile: filename}, function (msg) {
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

<?php endif; ?>
