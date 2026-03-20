<?php
include_once('../../includes/initialize.php');
if(!isset($_SESSION['bannerNameArr'])){ $_SESSION['bannerNameArr']=array(); }
$_SESSION['bannerNameArr'][] = $_POST['imagefile']; 
$bannerNameArr =  $_SESSION['bannerNameArr'];
$deleteid = rand(0,99999);
if(!empty($bannerNameArr)):
foreach($bannerNameArr as $key=>$val):?>
<div class="col-md-3" id="previewUserbanner<?php echo $deleteid;?>">
    <div class="infobox info-bg">
        <img src="<?php echo IMAGE_PATH.'services/banner/thumbnails/'.$val;?>"  style="width:100%"/>
        <a href="javascript:void(0);" onclick="deleteTempbanner(<?php echo $deleteid;?>);">
            <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                <i class="glyph-icon icon-clock-os"></i>
            </span>
        </a>
        <input type="hidden" name="bannerArrayname[]" value="<?php echo $val;?>" class="validate[required,length[0,250]]" />        
    </div> 
</div>
<?php endforeach; endif;  
//uplodify
  if(isset($_SESSION['bannerNameArr'])){
   if(count($_SESSION['bannerNameArr'])>0){
      foreach($_SESSION['bannerNameArr'] as $key=>$val)
      { 
         @unlink(IMAGE_PATH.'services/banner/thumbnails/'.$val);
         @unlink(IMAGE_PATH.'services/banner/'.$val);
      }  
      unset($_SESSION['bannerNameArr']);
       }
  }
//uplodify
?>
