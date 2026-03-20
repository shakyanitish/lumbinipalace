<?php
include_once('../../includes/initialize.php');
if(!isset($_SESSION['iconNameArr'])){ $_SESSION['iconNameArr']=array(); }
$_SESSION['iconNameArr'][] = $_POST['imagefile']; 
$iconNameArr =  $_SESSION['iconNameArr'];
$deleteid = rand(0,99999);
if(!empty($iconNameArr)):
foreach($iconNameArr as $key=>$val):?>
<div class="col-md-3" id="previewUsericon<?php echo $deleteid;?>">
    <div class="infobox info-bg">
        <img src="<?php echo IMAGE_PATH.'services/icon/thumbnails/'.$val;?>"  style="width:100%"/>
        <a href="javascript:void(0);" onclick="deleteTempicon(<?php echo $deleteid;?>);">
            <span class="badge badge-absolute float-right bg-red" style="right: -10px !important;">
                <i class="glyph-icon icon-clock-os"></i>
            </span>
        </a>
        <input type="hidden" name="iconArrayname[]" value="<?php echo $val;?>" class="validate[required,length[0,250]]" />        
    </div> 
</div>
<?php endforeach; endif;  
//uplodify
  if(isset($_SESSION['iconNameArr'])){
   if(count($_SESSION['iconNameArr'])>0){
      foreach($_SESSION['iconNameArr'] as $key=>$val)
      { 
         @unlink(IMAGE_PATH.'services/icon/thumbnails/'.$val);
         @unlink(IMAGE_PATH.'services/icon/'.$val);
      }  
      unset($_SESSION['iconNameArr']);
       }
  }
//uplodify
?>
