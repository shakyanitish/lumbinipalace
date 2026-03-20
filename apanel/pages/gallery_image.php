<?php
include_once('../../includes/initialize.php');
// Checks if a session variable galleryNameArr exists and is an array.
// If not, it creates an empty array.
// This array will temporarily store the names of uploaded gallery images.
if(!isset($_SESSION['galleryNameArr']) || !is_array($_SESSION['galleryNameArr'])) {
    $_SESSION['galleryNameArr'] = [];
}
$_SESSION['galleryNameArr'][] = $_POST['imagefile'];

$galleryNameArr =  $_SESSION['galleryNameArr'];
$deleteid = rand(0,99999);

// display the uploaded gallery images and show delete btn.
if(!empty($galleryNameArr)):
foreach($galleryNameArr as $key=>$val):?>
<div class="col-md-3" id="previewUserimage<?php echo $deleteid;?>">
    <div class="infobox info-bg">
        <img src="<?php echo IMAGE_PATH.'pages/gallery/thumbnails/'.$val;?>"  style="width:100%"/>
        <a href="javascript:void(0);" onclick="deleteTempimage(<?php echo $deleteid;?>);">
            <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                <i class="glyph-icon icon-clock-os"></i>
            </span>
        </a>
        <input type="hidden" name="galleryArrayname[]" value="<?php echo $val;?>" class="validate[required,length[0,250]]" />        
    </div> 
</div>
<!-- Deletes the temp images after displaying and uses unlink to delete file from server i.e, images. -->
<?php endforeach; endif;  
//uplodify
  if(isset($_SESSION['galleryNameArr'])){
   if(count($_SESSION['galleryNameArr'])>0){
      foreach($_SESSION['galleryNameArr'] as $key=>$val)
      { 
         @unlink(IMAGE_PATH.'pages/gallery/thumbnails/'.$val);
      	 @unlink(IMAGE_PATH.'pages/gallery/'.$val);
      }	 
    	unset($_SESSION['galleryNameArr']);
       }
  }
//uplodify
?>
