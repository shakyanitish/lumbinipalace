<?php
include_once('../../includes/initialize.php');
if(!isset($_SESSION['flagNameArr'])){ $_SESSION['flagNameArr']=array(); }
$_SESSION['flagNameArr'][] = $_POST['imagefile']; 
$flagNameArr =  $_SESSION['flagNameArr'];
$deleteid = rand(0,99999);
if(!empty($flagNameArr)):
foreach($flagNameArr as $key=>$val):?>
<div class="col-md-3" id="previewflag<?php echo $deleteid;?>">
    <div class="infobox info-bg">
        <img src="<?php echo IMAGE_PATH.'package/flag/thumbnails/'.$val;?>"  style="width:100%"/>
        <a href="javascript:void(0);" onclick="deleteTempflag(<?php echo $deleteid;?>);">
            <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                <i class="glyph-icon icon-clock-os"></i>
            </span>
        </a>
        <input type="hidden" name="flagArrayname[]" value="<?php echo $val;?>" class="validate[required,length[0,250]]" />        
    </div> 
</div>
<?php endforeach; endif;  
//uplodify
  if(isset($_SESSION['flagNameArr'])){
   if(count($_SESSION['flagNameArr'])>0){
      foreach($_SESSION['flagNameArr'] as $key=>$val)
      { 
         @unlink(IMAGE_PATH.'package/flag/thumbnails/'.$val);
         @unlink(IMAGE_PATH.'package/flag/'.$val);
      }  
      unset($_SESSION['flagNameArr']);
       }
  }
//uplodify
?>
