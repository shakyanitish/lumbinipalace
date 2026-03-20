<?php
include_once('../../includes/initialize.php');
if (!isset($_SESSION['imageArraynameCompanyDoc'])) {
    $_SESSION['imageArraynameCompanyDoc'] = array();
}
$_SESSION['imageArraynameCompanyDoc'][] = $_POST['imagefile'];
$imageArraynameCompanyDoc = $_SESSION['imageArraynameCompanyDoc'];
$deleteid = rand(0, 99999);

if (!empty($imageArraynameCompanyDoc)):
    foreach ($imageArraynameCompanyDoc as $key => $val):?>
        <div class="form-row">
            <div class="" id="previewUserCompanyDoc<?php echo $deleteid; ?>">
                <div class="infobox info-bg">
                <img src="<?php echo IMAGE_PATH . 'hotelusercompanydoc/' . $val; ?>"
                                     style="width:100%"/>
                    <a href="javascript:void(0);" onclick="deleteTempimage(<?php echo $deleteid; ?>);">
                        <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                            <i class="glyph-icon icon-clock-os"></i>
                        </span>
                    </a>
                    <input type="hidden" name="imageArraynameCompanyDoc" value="<?php echo $val; ?>" class="validate[required,length[0,250]]"/>
                </div>
            </div>
        </div>
    <?php endforeach;
endif;

//uplodify
if (isset($_SESSION['imageArraynameCompanyDoc'])) {
    if (count($_SESSION['imageArraynameCompanyDoc']) > 0) {
        foreach ($_SESSION['imageArraynameCompanyDoc'] as $key => $val) {
            @unlink(IMAGE_PATH . 'hotelusercompanydoc/thumbnails/' . $val);
            @unlink(IMAGE_PATH . 'hotelusercompanydoc/' . $val);
        }
        unset($_SESSION['imageArraynameCompanyDoc']);
    }
}
//uplodify
?>
