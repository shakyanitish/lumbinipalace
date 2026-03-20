<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename = "tbl_configs"; // Database table name
$moduleId = 13;             // module id >>>>> tbl_modules
$moduleFoldername = "";     // Image folder name

clearImages($moduleTablename, "preference/contact", "contact_upload");
clearImages($moduleTablename, "preference/contact/thumbnails", "contact_upload");
clearImages($moduleTablename, "preference/gallery", "gallery_upload");
clearImages($moduleTablename, "preference/gallery/thumbnails", "gallery_upload");
clearImages($moduleTablename, "preference/other", "other_upload");
clearImages($moduleTablename, "preference/other/thumbnails", "other_upload");
clearImages($moduleTablename, "preference/facility", "facility_upload");
clearImages($moduleTablename, "preference/facility/thumbnails", "facility_upload");
clearImages($moduleTablename, "preference/offer", "offer_upload");
clearImages($moduleTablename, "preference/offer/thumbnails", "offer_upload");
?>
<h3>Preference Management</h3>
<?php $PrefeRow = Config::find_by_id(1);
$upcoming = ($PrefeRow->upcoming == 1) ? "checked" : " ";
$notupcoming = ($PrefeRow->upcoming == 0) ? "checked" : " ";
?>
<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
        <form action="" class="col-md-12 center-margin" id="Preference_frm">
            <div class="form-row hide">
                <div class="form-label col-md-2">
                    <label for="">
                        Site Under Construction:
                    </label>
                </div>
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio upcoming" name="upcoming" id="check1"
                        value="1" <?php echo !empty($upcoming) ? $upcoming : ""; ?>>
                    <label for="">Yes</label>
                    <input type="radio" class="custom-radio upcoming" name="upcoming" id="check0"
                        value="0" <?php echo !empty($notupcoming) ? $notupcoming : "checked"; ?>>
                    <label for="">No</label>
                </div>
            </div>
            <div class="form-row upcoming-val <?php echo ($PrefeRow->upcoming == 0) ? 'hide' : ''; ?>">
                <div class="form-label col-md-2">
                    <label for="">
                        Content :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea placeholder="upcoming content" class="col-md-12 validate[required,length[0,200]]" type="text"
                        name="upcomingcontent" id="upcomingcontent"
                        value="<?php echo !empty($PrefeRow->upcomingcontent) ? $PrefeRow->upcomingcontent : ""; ?>"><?php echo !empty($PrefeRow->upcomingcontent) ? $PrefeRow->upcomingcontent : ""; ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Site Title :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <input placeholder="Site Title" class="col-md-6 validate[required,length[0,200]]" type="text"
                        name="sitetitle" id="sitetitle"
                        value="<?php echo !empty($PrefeRow->sitetitle) ? $PrefeRow->sitetitle : ""; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Site Name :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <input placeholder="Site Name" class="col-md-6 validate[required,length[0,200]]" type="text"
                        name="sitename" id="sitename"
                        value="<?php echo !empty($PrefeRow->sitename) ? $PrefeRow->sitename : ""; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Copyright :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <input placeholder="&#0169; Copyright <?php echo date('Y'); ?> by Longtail-e-media - All Rights Reserved"
                        class="col-md-6" type="text" name="copyright" id="copyright"
                        value='<?php echo !empty($PrefeRow->copyright) ? $PrefeRow->copyright : "&#0169; Copyright " . date('Y') . " by Longtail-e-media - All Rights Reserved"; ?>'>
                    <br /><label>
                        <small>Copy this red code for copyright year dynamic <span style="color:red;">{year}</span>
                        </small>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Icon Image :
                    </label>
                </div>

                <?php if (!empty($PrefeRow->icon_upload)): ?>
                    <div class="col-md-1" id="removeSavedimg1">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/preference/" . $PrefeRow->icon_upload)):
                                        $filesize = filesize(SITE_ROOT . "images/preference/" . $PrefeRow->icon_upload);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                    onclick="deleteSavedPreferenceimage(1);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/thumbnails/' . $PrefeRow->icon_upload; ?>"
                                style="width:100%" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader1 <?php echo !empty($PrefeRow->icon_upload) ? "hide" : ""; ?>">
                    <input type="file" name="icon_upload" id="icon_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'imgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'imgheight'); ?> px)
                        </small>
                    </label>
                </div>
                <!-- Upload user image preview -->
                <div id="preview_Image"><input type="hidden" name="imageArrayname" value="" class="" /></div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Logo Image :
                    </label>
                </div>

                <?php if (!empty($PrefeRow->logo_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg2">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/preference/" . $PrefeRow->logo_upload)):
                                        $filesize = filesize(SITE_ROOT . "images/preference/" . $PrefeRow->logo_upload);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                    onclick="deleteSavedPreferenceimage(2);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/thumbnails/' . $PrefeRow->logo_upload; ?>"
                                style="width:100%" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader2 <?php echo !empty($PrefeRow->logo_upload) ? "hide" : ""; ?>">
                    <input type="file" name="logo_upload" id="logo_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'simgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'simgheight'); ?> px)
                        </small>z
                    </label>
                </div>
                <!-- Upload user image preview -->
                <div id="preview_Image2"><input type="hidden" name="imageArrayname2" value="" class="" /></div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Logo footer :
                    </label>
                </div>

                <?php if (!empty($PrefeRow->fb_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg3">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/preference/" . $PrefeRow->fb_upload)):
                                    $filesize = filesize(SITE_ROOT . "images/preference/" . $PrefeRow->fb_upload);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedPreferenceimage(3);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/thumbnails/' . $PrefeRow->fb_upload; ?>"
                                 style="width:100%"/>
                            <input type="hidden" name="imageArrayname3" value="<?php echo $PrefeRow->fb_upload; ?>"/>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader3 <?php echo !empty($PrefeRow->fb_upload) ? "hide" : ""; ?>">
                    <input type="file" name="fb_upload" id="fb_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'fbimgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'fbimgheight'); ?> px)
                        </small>
                    </label>
                </div>
                <div id="preview_Image3"></div>
            </div>

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Twitter Card Sharing Image :
                    </label>
                </div>

                <?php if (!empty($PrefeRow->twitter_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg4">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/preference/" . $PrefeRow->twitter_upload)):
                                    $filesize = filesize(SITE_ROOT . "images/preference/" . $PrefeRow->twitter_upload);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedPreferenceimage(4);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/thumbnails/' . $PrefeRow->twitter_upload; ?>"
                                 style="width:100%"/>
                            <input type="hidden" name="imageArrayname4"
                                   value="<?php echo $PrefeRow->twitter_upload; ?>"/>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader4 <?php echo !empty($PrefeRow->twitter_upload) ? "hide" : ""; ?>">
                    <input type="file" name="twitter_upload" id="twitter_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'timgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'timgheight'); ?> px)
                        </small>
                    </label>
                </div>
                <div id="preview_Image4"></div>
            </div> -->

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Gallery Image :
                    </label>
                </div> -->

            <!-- <?php if (!empty($PrefeRow->gallery_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg5">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/preference/gallery/" . $PrefeRow->gallery_upload)):
                                    $filesize = filesize(SITE_ROOT . "images/preference/gallery/" . $PrefeRow->gallery_upload);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedPreferenceimage(5);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/gallery/thumbnails/' . $PrefeRow->gallery_upload; ?>"
                                 style="width:100%"/>
                            <input type="hidden" name="imageArrayname5"
                                   value="<?php echo $PrefeRow->gallery_upload; ?>"/>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader5 <?php echo !empty($PrefeRow->gallery_upload) ? "hide" : ""; ?>">
                    <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'gimgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'gimgheight'); ?> px)
                        </small>
                    </label>
                </div> -->
            <!-- Upload user image preview -->
            <!-- <div id="preview_Image5"></div>
            </div> -->

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Contact Image :
                    </label>
                </div>

                <?php if (!empty($PrefeRow->contact_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg6">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/preference/contact/" . $PrefeRow->contact_upload)):
                                    $filesize = filesize(SITE_ROOT . "images/preference/contact/" . $PrefeRow->contact_upload);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedPreferenceimage(6);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/contact/thumbnails/' . $PrefeRow->contact_upload; ?>"
                                 style="width:100%"/>
                            <input type="hidden" name="imageArrayname6"
                                   value="<?php echo $PrefeRow->contact_upload; ?>"/>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader6 <?php echo !empty($PrefeRow->contact_upload) ? "hide" : ""; ?>">
                    <input type="file" name="contact_upload" id="contact_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'cimgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'cimgheight'); ?> px)
                        </small>
                    </label>
                </div>
                <div id="preview_Image6"></div>
            </div>

            <!-- <?php if ($PrefeRow->id == 1) { ?>
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Default Image :
                        </label>
                    </div>

                    <?php if (!empty($PrefeRow->other_upload)): ?>
                        <div class="col-md-2" id="removeSavedimg7">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/preference/other/" . $PrefeRow->other_upload)):
                                    $filesize = filesize(SITE_ROOT . "images/preference/other/" . $PrefeRow->other_upload);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                       onclick="deleteSavedPreferenceimage(7);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'preference/other/thumbnails/' . $PrefeRow->other_upload; ?>"
                                     style="width:100%"/>
                                <input type="hidden" name="imageArrayname7"
                                       value="<?php echo $PrefeRow->other_upload; ?>"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader7 <?php echo !empty($PrefeRow->other_upload) ? "hide" : ""; ?>">
                        <input type="file" name="other_upload" id="other_upload" class="transparent no-shadow">
                        <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'oimgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'oimgheight'); ?> px)
                        </small>
                    </label>
                    </div>
                    <div id="preview_Image7"></div>
                </div>
            <?php } ?> -->
            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Facility Image :
                    </label>
                </div> -->

            <!-- <?php if (!empty($PrefeRow->facility_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg8">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/preference/facility/" . $PrefeRow->facility_upload)):
                                    $filesize = filesize(SITE_ROOT . "images/preference/facility/" . $PrefeRow->facility_upload);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                <a class="btn small float-right" href="javascript:void(0);"
                                   onclick="deleteSavedPreferenceimage(8);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/facility/thumbnails/' . $PrefeRow->facility_upload; ?>"
                                 style="width:100%"/>
                            <input type="hidden" name="imageArrayname8"
                                   value="<?php echo $PrefeRow->facility_upload; ?>"/>
                        </div>
                    </div>
                <?php endif; ?> -->
            <!-- <div class="form-input col-md-10 uploader8 <?php echo !empty($PrefeRow->facility_upload) ? "hide" : ""; ?>">
                    <input type="file" name="facility_upload" id="facility_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'fimgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'fimgheight'); ?> px)
                        </small>
                    </label>
                </div> -->
            <!-- Upload user image preview -->
            <!-- <div id="preview_Image8"></div>
            </div> -->

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Logo2 :
                    </label>
                </div>

                <?php if (!empty($PrefeRow->offer_upload)): ?>
                    <div class="col-md-2" id="removeSavedimg9">
                        <div class="infobox info-bg">
                            <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/preference/offer/" . $PrefeRow->offer_upload)):
                                        $filesize = filesize(SITE_ROOT . "images/preference/offer/" . $PrefeRow->offer_upload);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedPreferenceimage(9);">
                                    <i class="glyph-icon icon-trash-o"></i>
                                </a>
                            </div>
                            <img src="<?php echo IMAGE_PATH . 'preference/offer/thumbnails/' . $PrefeRow->offer_upload; ?>" style="width:100%" />
                            <input type="hidden" name="imageArrayname9"
                                value="<?php echo $PrefeRow->offer_upload; ?>" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-input col-md-10 uploader9 <?php echo !empty($PrefeRow->offer_upload) ? "hide" : ""; ?>">
                    <input type="file" name="offer_upload" id="offer_upload" class="transparent no-shadow">
                    <label>
                        <small>Image Dimensions (<?php echo Module::get_properties($moduleId, 'ofimgwidth'); ?> px
                            X <?php echo Module::get_properties($moduleId, 'ofimgheight'); ?> px)
                        </small>
                    </label>
                </div>
                <div id="preview_Image9"></div>
            </div> -->

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Facebook Messenger Code :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea placeholder="Facebook Messenger Code" name="fb_messenger" id="fb_messenger" rows="5"
                              class=""><?php echo !empty($PrefeRow->fb_messenger) ? $PrefeRow->fb_messenger : ""; ?></textarea>
                </div>
            </div> -->

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Google Analytics Code :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea placeholder="Google Analytics Code" name="google_anlytics" id="google_anlytics"
                        class=""><?php echo !empty($PrefeRow->google_anlytics) ? $PrefeRow->google_anlytics : ""; ?></textarea>
                </div>
            </div>

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Facebook Pixel Code :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea placeholder="Facebook Pixel Code" name="pixel_code" id="pixel_code"
                              class=""><?php echo !empty($PrefeRow->pixel_code) ? $PrefeRow->pixel_code : ""; ?></textarea>
                </div>
            </div> -->

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Google Analytics Link :
                    </label>
                </div>
                <div class="form-input col-md-8">
                    <div class="col-md-4" style="padding-left:0px !important;">
                        <input placeholder="Google Analytics Link" class="validate[required,length[0,50]]" type="text"
                               name="linksrc" id="linksrc"
                               value="<?php echo !empty($PrefeRow->linksrc) ? $PrefeRow->linksrc : ""; ?>">
                    </div>
                </div>
            </div> -->

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Schema.org Code :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea placeholder="Schema.org Code" name="schema_code" id="schema_code"
                              class=""><?php echo !empty($PrefeRow->schema_code) ? $PrefeRow->schema_code : ""; ?></textarea>
                </div>
            </div> 
            
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Robots.txt Content:
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <textarea placeholder="Robot.txt Content Code" name="robot_txt" id="robot_txt"
                              class=""><?php echo !empty($PrefeRow->robot_txt) ? $PrefeRow->robot_txt : ""; ?></textarea>
                </div>
            </div> 
            <!-- <div class="form-row" style="">
                <div class="form-label col-md-2">
                    <label>Booking Type :</label>
                </div>
                <div class="form-input col-md-10">
                    <select class="col-md-2 book_type" name="book_type">
                        <option value="1" <?php echo (!empty($PrefeRow->book_type) and $PrefeRow->book_type == 1) ? 'selected' : ''; ?>>
                            Default
                        </option>
                        <option value="2" <?php echo (!empty($PrefeRow->book_type) and $PrefeRow->book_type == 2) ? 'selected' : ''; ?>>
                            Rojai
                        </option>
                        <option value="3" <?php echo (!empty($PrefeRow->book_type) and $PrefeRow->book_type == 3) ? 'selected' : ''; ?>>
                            Fast Booking
                        </option>
                        <option value="4" <?php echo (!empty($PrefeRow->book_type) and $PrefeRow->book_type == 4) ? 'selected' : ''; ?>>
                            Booking.com
                        </option>
                    </select>
                    <div class="col-md-12 booking-val <?php echo (!empty($PrefeRow->book_type) and $PrefeRow->book_type == 1) ? 'hide' : ''; ?>">
                        <div class="row">
                            <div class="form-label">
                                <label>Hotel Result page</label>
                            </div>
                            <div class="form-input">
                                <input type="text" name="hotel_page" class="col-md-4 validate[required]"
                                       value="<?php echo !empty($PrefeRow->hotel_page) ? $PrefeRow->hotel_page : ""; ?>">
                                <br/>
                                <small>eg: Nepal Hotel : result.php / Fast Booking : dispoprice.phtml / Booking.com :
                                    highlander-inn.en.html
                                </small>
                            </div>

                            <div class="form-label">
                                <label>Hotel Code</label>
                            </div>
                            <div class="form-input">
                                <input type="text" name="hotel_code" class="col-md-4 validate[required]"
                                       value="<?php echo !empty($PrefeRow->hotel_code) ? $PrefeRow->hotel_code : ""; ?>">
                                <br/>
                                <small>eg: Nepal Hotel : yfOuZl / Fast Booking : NPPOKHTLPokharaChoic / Booking.com :
                                    1885971
                                </small>
                            </div>
                            <div class="form-label">
                                <label>Online Booking Code</label>
                            </div>
                            <div class="form-input">
                                <input type="text" name="booking_code" class="col-md-4 validate[required]"
                                       value="<?php echo !empty($PrefeRow->booking_code) ? $PrefeRow->booking_code : ""; ?>">
                                <br/>
                                <small>eg: Nepal Hotel : yfOuZl / Fast Booking : NPPOKHTLPokharaChoic / Booking.com :
                                    1885971
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->


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
            <div class="form-row <?php echo (!empty($PrefeRow->site_keywords) || !empty($PrefeRow->site_description)) ? '' : 'hide'; ?> metadata">
                <div class="col-md-6 form-input">
                    <input type="text" placeholder="Meta Title" name="meta_title" id="meta_title" class=""
                        value="<?php echo !empty($PrefeRow->meta_title) ? $PrefeRow->meta_title : ""; ?>">
                </div>
                <div class="clear"></div>
                <br />
                <div class="col-md-6 form-input">
                    <textarea placeholder="Meta Keyword" name="site_keywords" id="site_keywords"
                        class="character-keyword validate[required]"><?php echo !empty($PrefeRow->site_keywords) ? $PrefeRow->site_keywords : ""; ?></textarea>
                    <div class="keyword-remaining clear input-description">250 characters left</div>
                </div>
                <div class="col-md-6 form-input">
                    <textarea placeholder="Meta Description" name="site_description" id="site_description"
                        class="character-description validate[required]"><?php echo !empty($PrefeRow->site_description) ? $PrefeRow->site_description : ""; ?></textarea>
                    <div class="description-remaining clear input-description">160 characters left</div>
                </div>
            </div>

            <button type="submit" name="submit"
                class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit"
                title="Save">
                <span class="button-content">
                    Save
                </span>
            </button>
            <input type="hidden" name="idValue" id="idValue"
                value="<?php echo !empty($PrefeRow->id) ? $PrefeRow->id : 0; ?>" />
        </form>
    </div>
</div>

<script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function() {
        // For Icon Image Upload
        $('#icon_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/',
                thumb_width: 60,
                thumb_height: 60
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
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_image.php', {
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

        // For Logo upload
        $('#logo_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_image2.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image2').html(msg).show();
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

        //For FB image upload
        $('#fb_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_fb_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image3').html(msg).show();
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

        //For Twitter image upload
        $('#twitter_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_twitter_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image4').html(msg).show();
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

        //For gallery banner upload
        $('#gallery_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/gallery/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_gallery_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image5').html(msg).show();
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

        //For contact banner upload
        $('#contact_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/contact/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_contact_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image6').html(msg).show();
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
        $('#other_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/other/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_other_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image7').html(msg).show();
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

        $('#facility_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/facility/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_facility_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image8').html(msg).show();
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

        $('#offer_upload').uploadify({
            'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
            'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
            'formData': {
                PROJECT: '<?php echo SITE_FOLDER; ?>',
                targetFolder: 'images/preference/offer/',
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
            'uploadLimit': 1,
            'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
            'buttonClass': 'button formButtons',
            /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess': function(file, data, response) {
                var filename = data;
                $.post('<?php echo BASE_URL; ?>apanel/preference/uploaded_offer_image.php', {
                    imagefile: filename
                }, function(msg) {
                    $('#preview_Image9').html(msg).show();
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