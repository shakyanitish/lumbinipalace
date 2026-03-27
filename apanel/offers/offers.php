<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename = "tbl_offers"; // Database table name
$moduleId = 29;                // module id >>>>> tbl_modules

if (isset($_GET['page']) && $_GET['page'] == "offers" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    clearImages($moduleTablename, "offers");
    clearImages($moduleTablename, "offers/thumbnails");

    clearImages($moduleTablename, "offers/listimage", "list_image");
    clearImages($moduleTablename, "offers/listimage/thumbnails", "list_image");

    $pagename = strtolower($_GET['page']);

?>
    <h3>
        List Offers
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewOffers();">
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
                        <th>Title</th>
                        <!-- <th>Link</th> -->
                        <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php $records = Offers::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY sortorder DESC ");
                    foreach ($records as $key => $record):
                        // pr($record);
                    ?>
                        <tr id="<?php echo $record->id; ?>">
                            <td style="display:none;"><?php echo $key + 1; ?></td>
                            <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id; ?>" /></td>
                            <td>
                                <div class="col-md-7">
                                    <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);" class="loadingbar-demo"
                                        title="<?php echo $record->title; ?>"><?php echo $record->title; ?></a>
                                </div>
                            </td>
                            <!-- <td><?php echo set_na($record->linksrc); ?></td> -->
                            <td class="text-center">
                                <?php
                                $statusImage = ($record->status == 1) ? "bg-green" : "bg-red";
                                $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                                ?>
                                <a href="javascript:void(0);" class="btn small <?php echo $statusImage; ?> tooltip-button statusToggler"
                                    data-placement="top" title="<?php echo $statusText; ?>" status="<?php echo $record->status; ?>"
                                    id="imgHolder_<?php echo $record->id; ?>" moduleId="<?php echo $record->id; ?>">
                                    <i class="glyph-icon icon-flag"></i>
                                </a>
                                <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button" data-placement="top"
                                    title="Edit" onclick="editRecord(<?php echo $record->id; ?>);">
                                    <i class="glyph-icon icon-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top" title="Remove"
                                    onclick="recordDelete(<?php echo $record->id; ?>);">
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

    $metasql = $db->query("SELECT * FROM tbl_metadata WHERE page_name='$pagename'");
    $metadata = $metasql->fetch_object();
    // $metaexist= !empty($metadata) ? array_shift($metadata) : false;
    // pr($metadata);

    ?>
    <div class="form-row show  <?php echo (!empty($metadata->meta_keywords) || !empty($metadata->meta_description) || !empty($metadata->meta_title)) ? '' : 'hide'; ?> metadata">
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
        $advId = addslashes($_REQUEST['id']);
        $advInfo = Offers::find_by_id($advId);
        $status = ($advInfo->status == 1) ? "checked" : " ";
        $unstatus = ($advInfo->status == 0) ? "checked" : " ";
        $homepage = ($advInfo->homepage == 1) ? "checked" : " ";
        $nothomepage = ($advInfo->homepage == 0) ? "checked" : " ";

        $offerpopup = ($advInfo->offerpopup == 1) ? "checked" : " ";
        $notofferpopup = ($advInfo->offerpopup == 0) ? "checked" : " ";

        $external = ($advInfo->linktype == 1) ? "checked" : " ";
        $internal = ($advInfo->linktype == 0) ? "checked" : " ";

        $addtype = ($advInfo->type == 1) ? "checked" : " ";
        $unaddtype = ($advInfo->type == 0) ? "checked" : " ";
        $multiaddtype = ($advInfo->type == 2) ? "checked" : " ";
        $noneaddtype = ($advInfo->type == 3) ? "checked" : " ";
        $sdiscount = '';
        $ddiscount = '';
        $mdiscount = '';
        $ndiscount = '';
        $distype = $advInfo->type;

        switch ($distype) {
            case 0:
                $sdiscount = 'hide';
                $mdiscount = 'hide';
                $ndiscount = 'hide';
                break;
            case 1:
                $mdiscount = 'hide';
                $ddiscount = 'hide';
                $ndiscount = 'hide';
                break;
            case 2:
                $sdiscount = 'hide';
                $ddiscount = 'hide';
                $ndiscount = 'hide';
                break;

            case 3:
                $ndiscount = 'hide';
                $sdiscount = 'hide';
                $ddiscount = 'hide';
                $mdiscount = 'hide';
                break;
        }
    // pr($advInfo);
    // $sdiscount = ($advInfo->type == 0) ? 'hide' : '';
    // $ddiscount = ($advInfo->type == 1) ? 'hide' : '';
    // $mdiscount = ($advInfo->type == 2) ? '' : 'hide';
    // $ndiscount = ($advInfo->type == 3) ? '' : 'hide';
    endif;
?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Offers' : 'Add Offers'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewOfferslist();">
            <span class="glyph-icon icon-separator">
                <i class="glyph-icon icon-arrow-circle-left"></i>
            </span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="offers_frm">
                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Title :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Offers Title" class="col-md-6 validate[required,length[0,50]]" type="text" name="title" id="title"
                            value="<?php echo !empty($advInfo->title) ? $advInfo->title : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Tag :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <input placeholder="Offers Tag" class="col-md-6 validate[length[0,50]]" type="text" name="tag" id="tag"
                            value="<?php echo !empty($advInfo->tag) ? $advInfo->tag : ""; ?>">
                    </div>
                </div>

                <!-- <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Banner Image :
                        </label>
                    </div>

                    <?php if (!empty($advInfo->image)): ?>
                        <div class="col-md-3" id="removeSavedimg1">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php
                                if (file_exists(SITE_ROOT . "images/offers/" . $advInfo->image)):
                                    $filesize = filesize(SITE_ROOT . "images/offers/" . $advInfo->image);
                                    echo 'Size : ' . getFileFormattedSize($filesize);
                                endif;
                                ?>
                            </span>
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedOffersimage(1);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'offers/thumbnails/' . $advInfo->image; ?>" style="width:100%"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-input col-md-10 uploader1 <?php echo !empty($advInfo->image) ? "hide" : ""; ?>">
                        <input type="file" name="background_upload" id="background_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (2000 px X 1667 px)</small>
                        </label>
                    </div>
                  Upload user image preview -->
                <!--<div id="preview_Image"><input type="hidden" name="imageArrayname" value="" class=""/></div>
                </div> -->



                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Type :</label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="deadline_type" id="deadline_type1" value="deadline" <?php echo (empty($advInfo->deadline_type) || $advInfo->deadline_type == 'deadline') ? 'checked' : ''; ?>>
                        <label for="deadline_type1">Deadline</label>
                        <input type="radio" class="custom-radio" name="deadline_type" id="deadline_type0" value="alltime" <?php echo (!empty($advInfo->deadline_type) && $advInfo->deadline_type == 'alltime') ? 'checked' : ''; ?>>
                        <label for="deadline_type0">All Time</label>
                    </div>
                </div>

                <div id="date_fields_wrapper" class="deadline-fields">
                    <div class="form-row">
                        <div class="form-label col-md-2">
                            <label for="">
                                Start Date :
                            </label>
                        </div>
                        <div class="form-input col-md-4">
                            <input placeholder="Start Date" class="col-md-6 validate[required] datepicker" type="text"
                                name="start_date" id="start_date"
                                value="<?php echo !empty($advInfo->start_date) ? $advInfo->start_date : ""; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label col-md-2">
                            <label for="">
                                Deadline :
                            </label>
                        </div>
                        <div class="form-input col-md-4">
                            <input placeholder="End Date" class="col-md-6 validate[required] datepicker" type="text"
                                name="offer_date" id="offer_date"
                                value="<?php echo !empty($advInfo->offer_date) ? $advInfo->offer_date : ""; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader2 <?php echo !empty($advInfo->list_image) ? "hide" : ""; ?>">
                        <input type="file" name="list_image" id="offers_list_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (270 px X 300 px)</small>
                        </label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image3"></div>

                    <?php if (!empty($advInfo->list_image)): ?>
                        <div class="col-md-3" id="removeSavedimg1<?php echo $advInfo->id; ?>">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php
                                        if (file_exists(SITE_ROOT . "images/offers/listimage/" . $advInfo->list_image)):
                                            $filesize = filesize(SITE_ROOT . "images/offers/listimage/" . $advInfo->list_image);
                                            echo 'Size : ' . getFileFormattedSize($filesize);
                                        endif;
                                        ?>
                                    </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                        onclick="deleteSavedOfferLimage(<?php echo $advInfo->id; ?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'offers/listimage/thumbnails/' . $advInfo->list_image; ?>" style="width:100%" />
                                <input type="hidden" name="imageArrayname3" value="<?php echo $advInfo->list_image; ?>" class="" />
                            </div>
                        </div>
                    <?php endif; ?>

                </div>


                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Discount Type :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype1"
                            value="1" <?php echo !empty($addtype) ? $addtype : "checked"; ?>>
                        <label for="">Fixed</label>
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype0"
                            value="0" <?php echo !empty($unaddtype) ? $unaddtype : ""; ?>>
                        <label for="">Dynamic</label>
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype2"
                            value="2" <?php echo !empty($multiaddtype) ? $multiaddtype : ""; ?>>
                        <label for="">multi</label>
                        <input type="radio" class="custom-radio addtype" name="type" id="adtype3"
                            value="3" <?php echo !empty($noneaddtype) ? $noneaddtype : ""; ?>>
                        <label for="">none</label>
                    </div>
                </div>

                <div class="form-row static-discount hide<?php echo !empty($sdiscount) ? $sdiscount : ''; ?>">
                    <div class="form-label col-md-2">
                        <label for="">
                            Discount (%) :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <select class="col-md-1 validate[required]" name="discount">
                            <?php foreach (range(0, 100) as $v):
                                $sel = ($advInfo->discount == $v) ? "selected" : ""; ?>
                                <option value="<?= $v ?>" <?= $sel ?>><?= $v ?> %</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row static-discount hide <?php echo  !empty($sdiscount) ? $sdiscount : ''; ?>">
                    <div class="form-label col-md-2">
                        <label for="">
                            Rate (USD) :
                        </label>
                    </div>
                    <div class="form-input col-md-2">
                        <input placeholder="Rate (USD)" class="col-md-6 validate[required,length[0,10]]" type="text" name="rate" id="rate"
                            value="<?php echo !empty($advInfo->rate) ? $advInfo->rate : ""; ?>">
                    </div>
                </div>

                <div class="form-row static-discount hide <?php echo !empty($sdiscount) ? $sdiscount : ''; ?>">
                    <div class="form-label col-md-2">
                        <label for="">
                            People :
                        </label>
                    </div>
                    <div class="form-input col-md-2">
                        <input placeholder="People" class="col-md-6 validate[required,length[0,50]]" type="number" name="adults" id="adults"
                            value="<?php echo !empty($advInfo->adults) ? $advInfo->adults : ""; ?>">
                    </div>
                </div>

                <div class="form-row dynamic-discount hsasdas <?php echo !empty($ddiscount) ? $ddiscount : ''; ?>  <?php
                                                                                                                    echo isset($_GET['id']) ? '' : 'hide'; ?>">
                    <div class="form-label col-md-2">
                        <label for="">Information :</label>
                    </div>
                    <div class="form-input col-md-4">
                        <table class="table tbl-result">
                            <tr>
                                <th>No. of Pax</th>
                                <th>Rate (USD)</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php
                            if (empty($advInfo->id)) {
                                $tid = 0;
                            } else {
                                $tid = $advInfo->id;
                            }
                            $csql = "SELECT offer_pax, offer_usd, offer_inr, offer_npr FROM tbl_offer_child WHERE offer_id = $tid AND offer_pax<>''";
                            $query = $db->query($csql);
                            if ($db->num_rows($query) > 0) {
                                $i = 1;
                                while ($row = $db->fetch_object($query)) { ?>
                                    <tr class="dp<?php echo $i; ?>">
                                        <td>
                                            <input class="validate[required]" type="text" name="offer_pax[]" value="<?php echo $row->offer_pax; ?>">
                                        </td>
                                        <td width="90">
                                            <input class="validate[required,custom[number]]" type="text" name="offer_usd[]"
                                                value="<?php echo $row->offer_usd; ?>">
                                        </td>
                                        <td width="40">
                                            <a href="javascript:;" class="btn bg-blue btn-add">&nbsp;+&nbsp;</a>
                                            <?php if ($i > 1) { ?><a href="javascript:;" class="btn bg-red btn-remove"
                                                    data-id="dp<?php echo $i; ?>">
                                                    &nbsp;x&nbsp;</a><?php } ?>
                                        </td>
                                    </tr>
                                <?php $i++;
                                }
                            } else { ?>
                                <tr>
                                    <td><input class="validate[required]" type="text" name="offer_pax[]"></td>
                                    <td width="90">
                                        <input class="validate[required,custom[number]]" type="text" name="offer_usd[]">
                                    </td>
                                    <td width="40">
                                        <a href="javascript:;" class="btn bg-blue btn-add">&nbsp;+&nbsp;</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="form-row multi-discount <?php echo !empty($mdiscount) ? $mdiscount : '';
                                                    echo isset($_GET['id']) ? '' : 'hide'; ?>">
                    <div class="form-label col-md-2">
                        <label for="">multi :</label>
                    </div>
                    <div class="form-input col-md-4">
                        <table class="table tbl-result">
                            <tr>
                                <th>Items</th>
                                <th>Rate (USD)</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php
                            if (empty($advInfo->id)) {
                                $tid = 0;
                            } else {
                                $tid = $advInfo->id;
                            }
                            $csql = "SELECT multi_offer_title, multi_offer_npr FROM tbl_offer_child WHERE offer_id = $tid AND multi_offer_title<>''";
                            $query = $db->query($csql);
                            if ($db->num_rows($query) > 0) {
                                $i = 1;
                                while ($row = $db->fetch_object($query)) { ?>
                                    <tr class="dp<?php echo $i; ?>">
                                        <td>
                                            <input class="validate[required]" type="text" name="multi_offer_title[]" value="<?php echo $row->multi_offer_title; ?>">
                                        </td>
                                        <td width="90">
                                            <input class="validate[required,custom[number]]" type="text" name="multi_offer_npr[]"
                                                value="<?php echo $row->multi_offer_npr; ?>">
                                        </td>
                                        <td width="40">
                                            <a href="javascript:;" class="btn bg-blue multi-btn-add">&nbsp;+&nbsp;</a>
                                            <?php if ($i > 1) { ?><a href="javascript:;" class="btn bg-red multi-btn-remove"
                                                    multi-data-id="dp<?php echo $i; ?>">
                                                    &nbsp;x&nbsp;</a><?php } ?>
                                        </td>
                                    </tr>
                                <?php $i++;
                                }
                            } else { ?>
                                <tr>
                                    <td><input class="validate[required]" type="text" name="multi_offer_title[]"></td>
                                    <td width="90">
                                        <input class="validate[required,custom[number]]" type="text" name="multi_offer_npr[]">
                                    </td>
                                    <td width="40">
                                        <a href="javascript:;" class="btn bg-blue multi-btn-add">&nbsp;+&nbsp;</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="form-row none-discount <?php echo !empty($ndiscount) ? $ndiscount : '';
                                                    echo isset($_GET['id']) ? '' : 'hide'; ?>">

                </div>


                <!--<div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Children :
                        </label>
                    </div>
                    <div class="form-input col-md-2">
                        <input placeholder="Children" class="col-md-6 validate[length[0,50]]" type="number" name="children" id="children"
                               value="<?php echo !empty($advInfo->children) ? $advInfo->children : ""; ?>">
                    </div>
                </div>-->

                <!--<div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Brief :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <textarea name="brief"><?php echo !empty($advInfo->brief) ? $advInfo->brief : ""; ?></textarea>
                    </div>
                </div>-->

                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Link Type :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input id="" class="custom-radio" type="radio" name="linktype" value="0"
                            onClick="linkTypeSelect(0);" <?php echo !empty($internal) ? $internal : "checked"; ?>>
                        <label for="">Internal Link</label>
                        <input id="" class="custom-radio" type="radio" name="linktype" value="1"
                            onClick="linkTypeSelect(1);" <?php echo !empty($external) ? $external : ""; ?>>
                        <label for="">External Link</label>
                    </div>
                </div>

                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Link :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <div class="col-md-4" style="padding-left:0px !important;">
                            <input placeholder="Slideshow Link" class="" type="text" name="linksrc" id="linksrc"
                                value="<?php echo !empty($advInfo->linksrc) ? $advInfo->linksrc : ""; ?>">
                        </div>
                        <div class="col-md-6" style="padding-left:0px !important;">
                            <?php $Lpageview = !empty($advInfo->linksrc) ? $advInfo->linksrc : "";
                            $lnktype = !empty($advInfo->linktype) ? $advInfo->linktype : ""; ?>
                            <select data-placeholder="Select Link Page"
                                class="col-md-4 chosen-select <?php echo ($lnktype == 1) ? 'hide' : ''; ?>"
                                id="linkPage">
                                <option value=""></option>
                                <?php echo Article::get_internal_link($Lpageview, $lnktype); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Content :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <textarea name="content" id="content"
                            class="large-textarea"><?php echo !empty($advInfo->content) ? $advInfo->content : ""; ?></textarea>
                        <a class="btn medium bg-orange mrg5T hide" title="Read More" id="readMore"
                            href="javascript:void(0);">
                            <span class="button-content">Read More</span>
                        </a>
                    </div>
                </div>

                <div class="form-row hide">
                    <div class="form-label col-md-2">
                        <label for="">
                            Display Style :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="offerpopup" id="offerpopup1"
                            value="1" <?php echo !empty($offerpopup) ? $offerpopup : "checked"; ?>>
                        <label for="">Popup Slider</label>
                        <input type="radio" class="custom-radio" name="offerpopup" id="offerpopup0"
                            value="0" <?php echo !empty($notofferpopup) ? $notofferpopup : ""; ?>>
                        <label for="">Normal</label>
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Status :
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
                <div class="form-row hide">
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="homepage" id="homepage1"
                            value="1" <?php echo !empty($homepage) ? $homepage : ""; ?>>
                        <label for="">Homepage</label>
                        <input type="radio" class="custom-radio" name="homepage" id="homepage0"
                            value="0" <?php echo !empty($nothomepage) ? $nothomepage : "checked"; ?>>
                        <label for="">Not at Homepage</label>
                    </div>
                </div>
                <button btn-action='0' type="submit" name="submit"
                    class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                    <span class="button-content">
                        Save
                    </span>
                </button>
                <button btn-action='1' type="submit" name="submit"
                    class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                    <span class="button-content">
                        Save & More
                    </span>
                </button>
                <button btn-action='2' type="submit" name="submit"
                    class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                    <span class="button-content">
                        Save & quit
                    </span>
                </button>
                <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($advInfo->id) ? $advInfo->id : 0; ?>" />
            </form>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo ASSETS_PATH; ?>uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        // <![CDATA[
        $(document).ready(function() {
            $('#background_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/offers/',
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
                    $.post('<?php echo BASE_URL; ?>apanel/offers/uploaded_image.php', {
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
            $('#offers_list_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH; ?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER; ?>',
                    targetFolder: 'images/offers/listimage/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL; ?>uploadify/cancel.png',
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
                'onUploadSuccess': function(file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL; ?>apanel/offers/list_image.php', {
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
        });
        // ]]>
    </script>
    <script>
        $(document).ready(function() {
            /************************************ Editor for message *****************************************/
            var base_url = "<?php echo ASSETS_PATH; ?>";
            var editors = ["content"];
            create_editor(base_url, editors);
            /*CKEDITOR.replace('content', {
                toolbar:
                    [
                        {name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates']},
                        {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']}, '/',
                        {name: 'colors', items: ['TextColor', 'BGColor']},
                        {name: 'tools', items: ['Maximize', 'ShowBlocks', '-', 'About']}
                    ]
            });*/
            
            // Handle deadline type toggle
            function toggleDeadlineFields() {
                var deadlineType = $('input[name="deadline_type"]:checked').val();
                if (deadlineType === 'alltime') {
                    $('#date_fields_wrapper').hide();
                    $('#start_date').val('');
                    $('#offer_date').val('');
                } else {
                    $('#date_fields_wrapper').show();
                }
            }
            
            // Initialize on page load
            toggleDeadlineFields();
            
            // Handle radio button change
            $('input[name="deadline_type"]').on('change', function() {
                toggleDeadlineFields();
            });
        });
    </script>
<?php endif; ?>