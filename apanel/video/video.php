<?php
$moduleTablename = "tbl_video"; // Database table name
$moduleId = 11;                // module id >>>>> tbl_modules
$moduleFoldername = "";        // Image folder name
?>
    <style>
        .divContent a { position: relative; }
        .divContent a span { background-image: url('../../images/apanel/play.png'); background-repeat: no-repeat; width: 32px; height: 32px; position: absolute; left: 10px; bottom: 10px; }
    </style>
    <h3>AddEdit Video</h3>
    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-10 center-margin" id="video_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Video link :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <input placeholder="http://www.youtube.com/watch?v=fs2khSNtSu0" class="col-md-8 validate[custom[url]]" type="text" name="source" id="source">
                        <input type="hidden" name="url_type" id="url_type">
                        <button type="submit" name="submit" class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                        <span class="button-content">
                            Save
                        </span>
                        </button>
                        <small>
                            <br/>Ex. Soundcloud : http://soundcloud.com/balajipatturaj/chennais-super-kings-with-rj
                            <br/>Ex. Youtube : https://www.youtube.com/watch?v=dZpc936_Hgo
                            <br/>Ex. Vimeo : http://vimeo.com/65484727
                            <br/>Ex. Metacafe : http://www.metacafe.com/watch/10599819/are_katy_perry_and_john_mayer_back_together
                            <br/>Ex. Dailymotion : http://www.dailymotion.com/video/xzdijh_hire-data-entry-expert_news
                        </small>
                    </div>
                </div>
                <input type="hidden" name="idValue" id="idValue" value="0"/>
            </form>
        </div>
    </div>

    
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
       
        <form class="col-md-12 center-margin" id="videos_frm">
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
                    <!-- <button btn-action='2' type="submit"
                        class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                        onClick="toggleMetadata();" title="Save">
                        <span class="button-content">
                            Cancel
                        </span>
                    </button> -->
                    <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($metadata->id) ? $metadata->id : 0; ?>" />
                </div>
            </div>
        </form>
    </div>

    
<?php
$sql = "SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC";
$saveVideo = Video::find_by_sql($sql);
if ($saveVideo): ?>
    <h3>List Videos</h3>
    <div class="example-box">
        <div class="example-code">
            <div class="row">
                <div class="col-md-12 video-sort">
                    <?php foreach ($saveVideo as $videoRow): ?>
                        <div class="col-md-3 oldsort" id="<?php echo $videoRow->id; ?>" csort="<?php echo $videoRow->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="recordDelete(<?php echo $videoRow->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                    <?php
                                    $imageStatus = ($videoRow->status == 1) ? 'icon-check-circle-o' : 'icon-clock-os-circle-o';
                                    ?>
                                    <a class="btn small float-right videoStatusToggle" href="javascript:void(0);"
                                       rowId="<?php echo $videoRow->id; ?>" status="<?php echo $videoRow->status; ?>">
                                        <i class="glyph-icon <?php echo $imageStatus; ?>"
                                           id="toggleImg<?php echo $videoRow->id; ?>"></i>
                                    </a>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="editVideoTitle(<?php echo $videoRow->id; ?>);" title="Edit Title">
                                        <i class="glyph-icon icon-edit"></i>
                                    </a>
                                    <span><?php echo $videoRow->host; ?></span>
                                </div>
                                <div class="divContent">
                                    <a class="<?php echo $videoRow->class; ?>" href="<?php echo $videoRow->url; ?>"
                                       rel="media-gallery">
                                        <span></span>
                                        <img src="<?php echo $videoRow->thumb_image; ?>" width="100%" title="Play"
                                             alt="Play"/>
                                    </a>
                                </div>
                                <div class="button-group" data-toggle="buttons">
                                    <span class="up-title vidclicked<?php echo $videoRow->id; ?>" vid-id="<?php echo $videoRow->id; ?>"><?php echo $videoRow->title; ?></span>
                                </div>
                                <!--<span><?php echo $videoRow->content; ?></span>-->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

