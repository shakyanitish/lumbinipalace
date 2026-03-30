<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php
$moduleTablename = "tbl_blog"; // Database table name
$moduleId = 27;                // module id >>>>> tbl_modules
$moduleFoldername = "blog";        // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "blog" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, $moduleFoldername);
    clearImages($moduleTablename, $moduleFoldername . "/thumbnails");
    ?>
    <h3>
        List Blog
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewBlog();"> 
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
                    <th>Title</th>
                    <!-- <th class="text-center">Images</th> -->
                    <th class="text-center">Blog Date</th>
                    <!-- <th class="text-center">Author</th> -->
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $records = Blog::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
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
                        <!-- <td class="text-center">
                            <a class="primary-bg medium btn loadingbar-demo" title=""
                               onClick="viewBlogImagelist(<?php echo $record->id; ?>);" href="javascript:void(0);">
                                <span class="button-content">
                                    <span class="badge bg-orange radius-all-4 mrg5R" title=""
                                          data-original-title="Badge with tooltip"><?php echo BlogImage::getTotalImages($record->id); ?></span>
                                    <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                                </span>
                            </a>
                        </td> -->
                        <td><?php echo date('F d, Y', strtotime($record->blog_date)); ?></td>
                        <!-- <td><?php echo $record->author; ?></td> -->
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

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $blogId = addslashes($_REQUEST['id']);
        $blogInfo = Blog::find_by_id($blogId);
        $status = ($blogInfo->status == 1) ? "checked" : " ";
        $unstatus = ($blogInfo->status == 0) ? "checked" : " ";

        $addtype = ($blogInfo->type == 1) ? "checked" : " ";
        $unaddtype = ($blogInfo->type == 0) ? "checked" : " ";


    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Blog' : 'Add Blog'; 
        if(isset($_GET['id']) && !empty($_GET['id'])):
	$blogId = addslashes($_REQUEST['id']);
	$blog = Blog::find_by_id($blogId);
	$status    = ($blog->status==1)?"checked":"";
	$unstatus  = ($blog->status==0)?"checked":"";
	
	$external = ($blog->linktype==1)?"checked":"";
	$internal = ($blog->linktype==0)?"checked":"";
endif;	
?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewbloglist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="blog_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Blog Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($blogInfo->title) ? $blogInfo->title : ""; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Reading Time :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Reading Time" class="col-md-6 validate[required,length[0,200]]" type="text"
                               name="category" id="category"
                               value="<?php echo !empty($blogInfo->category) ? $blogInfo->category : ""; ?>">
                    </div>
                </div>
                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Author :
                        </label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="Blog Author Name" class="col-md-6 validate[required,length[0,200]]"
                               type="text" name="author" id="author"
                               value="<?php $adminName = User::get_user_shotInfo_byId(1);
                               echo !empty($blogInfo->author) ? $blogInfo->author : $adminName->first_name; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Date :
                        </label>
                    </div>
                    <div class="form-input col-md-4">
                        <input placeholder="Blog Date" class="col-md-6 validate[required] datepicker" type="text"
                               name="blog_date" id="blog_date"
                               value="<?php echo !empty($blogInfo->blog_date) ? $blogInfo->blog_date : ""; ?>">
                    </div>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Add Type :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype1"
                               value="1" <?php echo !empty($addtype) ? $addtype : "checked"; ?>>
                        <label for="">Media</label>
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype0"
                               value="0" <?php echo !empty($unaddtype) ? $unaddtype : ""; ?>>
                        <label for="">Blog</label>
                    </div>
                </div> -->

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>

                    <?php if (!empty($blogInfo->image)): ?>
                        <div class="col-md-3" id="removeSavedimg<?php echo $blogInfo->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/blog/" . $blogInfo->image)):
                                    $filesize = filesize(SITE_ROOT . "images/blog/" . $blogInfo->image);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteSavedBlogimage(<?php echo $blogInfo->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'blog/thumbnails/' . $blogInfo->image; ?>"
                                     style="width:100%"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader" <?php echo !empty($blogInfo->image) ? 'style="display:none;"' : ''; ?>>
                        <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                                X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                            </small>
                        </label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image"><input type="hidden" name="imageArrayname"
                                                   value="<?php echo !empty($blogInfo->image) ? $blogInfo->image : ""; ?>"
                                                   class=""/></div>
                </div>

                <!-- <div class="form-row ">
            	<div class="form-label col-md-2">
                    <label for="">
                        Link Type :
                    </label>
                </div>
                <div class="form-checkbox-radio col-md-9 ">
                    <input id="" class="custom-radio" type="radio" name="linktype" value="0" onClick="linkTypeSelect(0);" <?php echo !empty($internal)?$internal:"checked";?>>
                    <label for="">Internal Link</label>
                    <input id="" class="custom-radio" type="radio" name="linktype" value="1" onClick="linkTypeSelect(1);" <?php echo !empty($external)?$external:"";?>>
                    <label for="">External Link</label>
                </div>
            </div> -->
            
            <!-- <div class="form-row <?php echo (!empty($blogInfo->linksrc)) ?  '' : 'hide'; ?> " id="linkPage_chosen">
            	<div class="form-label col-md-2">
                    <label for="">
                        Link :
                    </label>
                </div>
                <div class="form-input col-md-8">
                	<div class="col-md-4" style="padding-left:0px !important;">
                    	<input  placeholder="Menu Link" class="validate[required,length[0,50]]"  type="text" name="linksrc" id="linksrc" value="<?php echo !empty($blogInfo->linksrc)?$blogInfo->linksrc:"";?>">                  
                    </div>
                	                    
                </div>
            </div> -->
            <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Brief :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <textarea name="brief" id="brief"
                                  class="medium-textarea character-brief validate[required]"><?php echo !empty($blogInfo->brief) ? $blogInfo->brief : ""; ?></textarea>
                        <div class="brief-remaining clear input-description">250 characters left</div>
                    </div>
                </div> 
                <div class="form-row">
                    <div class="form-label col-md-12">
                        <label for="">
                            Content :
                        </label>
                        <textarea name="content" id="content"
                                  class="large-textarea validate[required]"><?php echo !empty($blogInfo->content) ? $blogInfo->content : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore"
                           href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>
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
                <div class="form-row <?php echo (!empty($blogInfo->meta_keywords) || !empty($blogInfo->meta_description)) ?  '' : 'hide'; ?> metadata">
                    <div class="col-md-6">
                        <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords"
                                  class="character-keyword validate[required]"><?php echo !empty($blogInfo->meta_keywords) ? $blogInfo->meta_keywords : ""; ?></textarea>
                        <div class="keyword-remaining clear input-description">250 characters left</div>
                    </div>
                    <div class="col-md-6">
                        <textarea placeholder="Meta Description" name="meta_description" id="meta_description"
                                  class="character-description validate[required]"><?php echo !empty($blogInfo->meta_description) ? $blogInfo->meta_description : ""; ?></textarea>
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
                       value="<?php echo !empty($blogInfo->id) ? $blogInfo->id : 0; ?>"/>
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
            $('#gallery_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/blog/',
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
                    $.post('<?php echo BASE_URL;?>apanel/blog/uploaded_image.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image').html(msg).show();
                        $('.uploader').hide();
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
// include("blog_images.php"); 
?>