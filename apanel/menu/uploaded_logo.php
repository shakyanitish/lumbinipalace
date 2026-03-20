<?php
include_once('../../includes/initialize.php');
if(!isset($_SESSION['logoNameArr']) || !is_array($_SESSION['logoNameArr'])) {
    $_SESSION['logoNameArr'] = [];
}
$_SESSION['logoNameArr'][] = $_POST['imagefile'];

$logoNameArr =  $_SESSION['logoNameArr'];
$deleteid = rand(0,99999);
if(!empty($logoNameArr)):
foreach($logoNameArr as $key=>$val):?>
<div class="col-md-3" id="previewUserlogo<?php echo $deleteid;?>">
    <div class="infobox info-bg">
        <img src="<?php echo IMAGE_PATH.'menu/thumbnails/'.$val;?>"  style="width:100%"/>
        <a href="javascript:void(0);" onclick="deleteTemplogo(<?php echo $deleteid;?>);">
            <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                <i class="glyph-icon icon-clock-os"></i>
            </span>
        </a>
        <!-- here we avoid array[] and use string as we upload a single image -->
        <input type="hidden" name="logoArrayname" value="<?php echo $val;?>" class="validate[required,length[0,250]]" />        
    </div> 
</div>

<!-- check the array of uploaded images and delete all the images from server,remove session variable for next upload -->

<?php endforeach; endif;  
//uplodify
  if(isset($_SESSION['logoNameArr'])){
   if(count($_SESSION['logoNameArr'])>0){
      foreach($_SESSION['logoNameArr'] as $key=>$val)
      { 
         @unlink(IMAGE_PATH.'menu/thumbnails/'.$val);// delete thumbnail
      	 @unlink(IMAGE_PATH.'menu/'.$val);// delete original
      }	 
    	unset($_SESSION['logoNameArr']);//unset removes the session variable completerly
       }
  }
//uplodify
?>
