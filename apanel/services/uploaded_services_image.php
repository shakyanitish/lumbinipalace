<?php
include_once('../../includes/initialize.php');
if(!isset($_SESSION['servicesImageNameArr'])){ $_SESSION['servicesImageNameArr']=array(); }
$_SESSION['servicesImageNameArr'][] = $_POST['imagefile']; 

// Get only the newly uploaded image
$newImage = $_POST['imagefile'];
$deleteid = rand(0,99999);

if(!empty($newImage)):?>
<div class="col-md-3" id="previewUserimage<?php echo $deleteid;?>">
    <div class="infobox info-bg">
        <img src="<?php echo IMAGE_PATH.'services/servicesimages/thumbnails/'.$newImage;?>" style="width:100%"/>
        <a href="javascript:void(0);" onclick="deleteTempServicesimage(<?php echo $deleteid;?>);">
            <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                <i class="glyph-icon icon-clock-os"></i>
            </span>
        </a>
        <input type="hidden" name="imageArrayname[]" value="<?php echo $newImage;?>" class="validate[required,length[0,250]]" />
        <input type="text" id="title" name="title[]" placeholder="Title" class="validate[required,length[0,250]]" style="padding: 0;">
    </div> 
</div>
<?php endif; ?>
