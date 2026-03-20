<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_testimonial"; // Database table name
$moduleId = 17;             // module id >>>>> tbl_modules
$moduleFoldername = "";     // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "testimonial" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, "testimonial");
    clearImages($moduleTablename, "testimonial/thumbnails");
    ?>
    <h3>
        List Testimonial
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewtestimonial();">
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
                    <!-- <th class="text-center">Via Type</th> -->
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $records = Testimonial::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
                foreach ($records as $key => $record): ?>
                    <tr id="<?php echo $record->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>"/></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);"
                                   class="loadingbar-demo"
                                   title="<?php echo $record->name; ?>"><?php echo $record->name; ?></a>
                            </div>
                        </td>
                        <!-- <td><?php echo $record->via_type; ?></td> -->
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
            <span class="button-content"> Submit </span>
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

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $testimonialId = addslashes($_REQUEST['id']);
        $testimonialInfo = Testimonial::find_by_id($testimonialId);
        $status = ($testimonialInfo->status == 1) ? "checked" : " ";
        $unstatus = ($testimonialInfo->status == 0) ? "checked" : " ";
    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit testimonial' : 'Add New testimonial'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewtestimoniallist();">
    <span class="glyph-icon icon-separator">
        <i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="testimonial_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Name :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Name" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="name" id="name"
                               value="<?php echo !empty($testimonialInfo->name) ? $testimonialInfo->name : ""; ?>">
                    </div>
                </div>
                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Country :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Country" class="col-md-6 " type="text" name="country" id="country"
                               value="<?php echo !empty($testimonialInfo->country) ? $testimonialInfo->country : "";
                ?>">

                    </div>
                </div> -->

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Sub Title :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Sub Title" class="col-md-6 " type="text" name="via_type" id="via_type"
                               value="<?php echo !empty($testimonialInfo->via_type) ? $testimonialInfo->via_type : "";
                               ?>">

                    </div>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">   
                        <label for="">
                            Link :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Link" class="col-md-6" type="text" name="linksrc" id="linksrc"
                               value="<?php echo !empty($testimonialInfo->linksrc) ? $testimonialInfo->linksrc : "";
                               ?>">
                    </div>
                </div> -->

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Rating :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <select class="col-md-6" name="rating" id="rating">
                            <option value="<?php echo !empty($testimonialInfo->rating) ? $testimonialInfo->rating : "0"; ?>"
                                    selected="selected">
                                <?php echo !empty($testimonialInfo->rating) ? $testimonialInfo->rating : "Rating"; ?></option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div> -->

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>

                    <?php if (!empty($testimonialInfo->image)): ?>
                        <div class="col-md-3" id="removeSavedimg<?php echo $testimonialInfo->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/testimonial/" . $testimonialInfo->image)):
                                    $filesize = filesize(SITE_ROOT . "images/testimonial/" . $testimonialInfo->image);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteSavetestimonialimage(<?php echo $testimonialInfo->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'testimonial/thumbnails/' . $testimonialInfo->image; ?>"
                                     style="width:100%"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader <?php echo !empty($testimonialInfo->image) ? "hide" : ""; ?>">
                        <input type="file" name="image" id="image_upload" class="transparent no-shadow">
                        <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                        </small>
                    </label>
                    </div>
                    <div id="preview_Image"><input type="hidden" name="imageArrayname"
                                                   value="<?php echo !empty($testimonialInfo->image) ? $testimonialInfo->image : ""; ?>"
                                                   class=""/></div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Content :
                        </label>
                    </div>
                    <div class="form-label col-md-10">
                        <textarea name="content" id="content"
                                  class="large-textarea validate[required]"><?php echo !empty($testimonialInfo->content) ? $testimonialInfo->content : ""; ?></textarea>
                        <!--<a class="btn medium bg-orange mrg5T" title="Read More" id="readMore" href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>-->
                    </div>
                </div>


                <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label>Display at</label>
                </div>
                <div class="form-input col-md-4">
                    <select name="type" class="">
                        <option value="">All</option>

                        
                        <?php // $sql = "SELECT id, title FROM tbl_package WHERE status='1' ORDER BY sortorder ASC";
                // $pages = Package::find_by_sql($sql);
                // if(!empty($pages)) {

                //     $result.='<optgroup label="Package">';
                //     foreach($pages as $pageRow) {
                //         $chkChild  = Subpackage::getTotalSub($pageRow->type);
                //         $pakgLink  = !empty($chkChild)?'package':'package';

                //         $sel = (!empty($testimonialInfo->type) and $testimonialInfo->type==$pageRow->id) ?'selected':'';
                //         $result.='<option value="'.$pageRow->id.'" '.$sel.'>'.$pageRow->title.'</option>';

                //         // Sub package list
                //         $subRec = Subpackage::getPackage_limit($pageRow->id);
                //         if($subRec){
                //             foreach($subRec as $Nrow){
                //                 $sel = (!empty($testimonialInfo->type) and $testimonialInfo->type==$Nrow->id) ?'selected':'';
                //                 $result.='<option value="'.$Nrow->id.'" '.$sel.'>&nbsp;&nbsp;&nbsp;-&nbsp;'.$Nrow->title.'</option>';
                //             }
                //         }

                //     }
                //     $result.='</optgroup>';
                // }
                // echo $result;
                ?>

                    </select>
                </div>
            </div> -->

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
                       value="<?php echo !empty($testimonialInfo->id) ? $testimonialInfo->id : 0; ?>"/>
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
            $('#image_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/testimonial/',
                    thumb_width: 265,
                    thumb_height: 170
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
                    $.post('<?php echo BASE_URL;?>apanel/testimonial/uploaded_image.php', {imagefile: filename}, function (msg) {
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