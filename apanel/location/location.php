<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename = "tbl_configs"; // Database table name
$moduleId = 12;                // module id >>>>> tbl_modules
$moduleFoldername = "";        // Image folder name

?>
<h3>Office Location Management</h3>
<?php $locationRow = Config::find_by_id(1);
$status = ($locationRow->location_type == 1) ? "checked" : " ";
$unstatus = ($locationRow->location_type == 0) ? "checked" : " "; ?>
<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
        <form action="" class="col-md-12 center-margin" id="location_frm">

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Fiscal Address :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Fiscal Address" class="col-md-6" type="text" name="fiscal_address"
                        id="fiscal_address"
                        value="<?php echo !empty($locationRow->fiscal_address) ? $locationRow->fiscal_address : ""; ?>">
                </div>
            </div>
            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Ktm Address :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Mail Address" class="col-md-6" type="text" name="mail_address" id="mail_address"
                           value="<?php echo !empty($locationRow->mail_address) ? $locationRow->mail_address : ""; ?>">
                </div>
            </div> -->

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                    Ktm Contact Info :
                    </label>
                </div>                
                <div class="form-input col-md-20">
                    <input placeholder="Whatsapp" class="col-md-6" type="text" name="whatsapp" id="whatsapp" value="<?php echo !empty($locationRow->whatsapp) ? $locationRow->whatsapp : ""; ?>">
                </div>                
            </div> -->
            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                    KTM E-mail  :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Google Map Link" class="col-md-6" type="text" name="contact_info2" id="contact_info2"
                           value="<?php echo !empty($locationRow->contact_info2) ? $locationRow->contact_info2 : ""; ?>">
                </div>
            </div> -->
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Landline Number :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Landline Number"Info" class="col-md-6" type="text" name="contact_info" id="contact_info"
                        value="<?php echo !empty($locationRow->contact_info) ? $locationRow->contact_info : ""; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                    Mobile Number:
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Mobile Number" class="col-md-6" type="text" name="address" id="address"
                           value="<?php echo !empty($locationRow->address) ? $locationRow->address : ""; ?>">
                </div>
            </div> 
            <div class="form-row hide">
                <div class="form-label col-md-2">
                    <label for="">
                    Room Reservation Number:
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Room Reservation Number" class="col-md-6" type="text" name="room_reservation_number" id="room_reservation_number"
                           value="<?php echo !empty($locationRow->room_reservation_number) ? $locationRow->room_reservation_number : ""; ?>">
                </div>
            </div>
            <div class="form-row hide">
                <div class="form-label col-md-2">
                    <label for="">
                    Fax Number:
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Fax Number" class="col-md-6" type="text" name="pobox" id="pobox"
                           value="<?php echo !empty($locationRow->pobox) ? $locationRow->pobox : ""; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Email Address :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Email Address" class="col-md-6" type="text" name="email_address"
                        id="email_address"
                        value="<?php echo !empty($locationRow->email_address) ? $locationRow->email_address : ""; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Whatsapp :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Whatsapp" class="col-md-6" type="text" name="whatsapp_a"
                        id="whatsapp_a"
                        value="<?php echo !empty($locationRow->whatsapp_a) ? $locationRow->whatsapp_a : ""; ?>">
                </div>
            </div>


            <div class="form-row hide">
                <div class="form-label col-md-2">
                    <label for="">
                        Footer Map Link :
                    </label>
                </div>
                <div class="form-input col-md-20">
                    <input placeholder="Map Link" class="col-md-6" type="text" name="mapping"
                        id="mapping"
                        value="<?php echo !empty($locationRow->mapping) ? $locationRow->mapping : ""; ?>">
                </div>
            </div>
            <div class="form-row hide">
                <div class="form-label col-md-2">
                    <label for="">
                        Type :
                    </label>
                </div>
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio addtype" name="location_type" id="check1"
                        value="1" <?php echo !empty($status) ? $status : "checked"; ?>>
                    <label for="">Google Map</label>
                    <input type="radio" class="custom-radio addtype" name="location_type" id="check0"
                        value="0" <?php echo !empty($unstatus) ? $unstatus : ""; ?>>
                    <label for="">Image</label>
                </div>
            </div>

            <div class="form-row <?php echo ($locationRow->location_type == 0) ? 'hide' : ''; ?> google-link">
                <div class="form-label col-md-2">
                    <label for="">
                        Link :
                    </label>
                </div>
                <div class="form-input col-md-4">
                    <textarea name="location_map" id="location_map"
                        class="large-textarea validate[]"><?php echo !empty($locationRow->location_map) ? $locationRow->location_map : ""; ?></textarea>
                </div>
            </div>

            <div class="form-row <?php echo ($locationRow->location_type == 1) ? 'hide' : ''; ?> image-link">
                <div class="form-label col-md-2">
                    <label for="">
                        Image :
                    </label>
                </div>

                <?php if (!empty($locationRow->location_image)): ?>
                    <div class="col-md-4" id="removeSavedimg1">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/preference/locimage/" . $locationRow->location_image)):
                                        $filesize = filesize(SITE_ROOT . "images/preference/locimage/" . $locationRow->location_image);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                    onclick="deleteSavedPreferenceimage(1);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/locimage/thumbnails/' . $locationRow->location_image; ?>"
                                style="width:100%" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader1 <?php echo !empty($locationRow->location_image) ? "hide" : ""; ?>">
                    <input type="file" name="location_image" id="location_image" class="transparent no-shadow">
                </div>
                <!-- Upload user image preview -->
                <div id="preview_Image"><input type="hidden" name="imageArrayname" value="" class="" /></div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-12">
                    <label for="">
                        Brief :
                    </label>
                    <textarea name="breif" id="breif"
                        class="large-textarea"><?php echo !empty($locationRow->breif) ? $locationRow->breif : ""; ?></textarea>
                    <a class="btn medium bg-orange mrg5T hide" title="Read More" id="readMore" href="javascript:void(0);">
                        <span class="button-content">Read More</span>
                    </a>
                    <a class="btn medium bg-orange mrg5T " title="Read More" id="readMoreBrief" href="javascript:void(0);">
                        <span class="button-content">Read More</span>
                    </a>
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
            <?php
            $pagename = "Contact";

            // $pagename = strtolower($_GET['page']);
            $metasql = $db->query("SELECT * FROM tbl_metadata WHERE page_name='$pagename'");
            $metadata = $metasql->fetch_object();
            // $metaexist= !empty($metadata) ? array_shift($metadata) : false;
            // pr($metadata);

            ?>
            <div class="form-row show  <?php echo (!empty($metadata->meta_keywords) || !empty($metadata->meta_description) || !empty($metadata->meta_title)) ? '' : 'hide'; ?> metadata">
                <input type="hidden" name="page_name" value="Contact" />
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
            <!-- <button btn-action='2' type="submit"
                class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                onClick="toggleMetadata();" title="Save">
                <span class="button-content">
                    Cancel
                </span>
            </button> -->

            <!-- <button type="submit" name="submit"
                    class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit"
                    title="Save">
                <span class="button-content">
                    Save
                </span>
            </button> -->
            <input type="hidden" name="idValue" id="idValue"
                value="<?php echo !empty($locationRow->id) ? $locationRow->id : 0; ?>" />
        </form>
    </div>
</div>
<script>
    var base_url = "<?php echo ASSETS_PATH; ?>";
    var editor_arr = ["breif"];
    create_editor(base_url, editor_arr);
</script>
<script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function() {
        // For Icon Image Upload
        $('#location_image').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/locimage/',
                thumb_width: 300,
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
            'uploadLimit': 5,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/location/uploaded_image.php', {
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
</script>