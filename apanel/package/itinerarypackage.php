<?php
$ItineraryPackageTablename  = "tbl_packageitineary"; // Database table name
if(isset($_GET['page']) && $_GET['page'] == "package" && isset($_GET['mode']) && $_GET['mode']=="itinerarylistpackage"):
$id = intval(addslashes($_GET['id']));  
$packagedetail = Package::find_by_id($id);
?>
<h3>
List Itinerary ["<?php echo Package::field_by_id($id, 'title');?>"]
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewItineraryPackage(<?php echo $id;?>);">
    <span class="glyph-icon icon-separator">
        <i class="glyph-icon icon-plus-square"></i>
    </span>
    <span class="button-content"> Add New </span>
</a>
<a class="loadingbar-demo btn medium bg-blue-alt float-right mrg5R" href="javascript:void(0);" onClick="viewPackagelist();">
    <span class="glyph-icon icon-separator">
        <i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
    <span class="button-content"> Back </span>
</a>
</h3>
<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">    
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="subexample2">
        <thead>
            <tr>
               <th style="display:none;"></th>
               <th class="text-center"><input class="check-all" type="checkbox" /></th>
               <th class="text-center">Order</th>
               <th class="text-center"><?php echo $GLOBALS['basic']['action'];?></th>
            </tr>
        </thead> 
            
        <tbody>
            <?php $records = PackageItinerary::find_by_sql("SELECT * FROM ".$ItineraryPackageTablename." WHERE package_id=".$id." ORDER BY sortorder ASC "); 
                  foreach($records as $key=>$record): ?>    
            <tr id="<?php echo $record->id;?>">
                <td style="display:none;"><?php echo $key+1;?></td>
                <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id;?>" /></td>
                <td class="text-center"><?php echo $record->sortorder?></td>
                <td class="text-center">
                    <?php   
                        $statusImage = ($record->status == 1) ? "bg-green" : "bg-red" ; 
                        $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'] ; 
                    ?>                                            
                    <a href="javascript:void(0);" class="btn small <?php echo $statusImage;?> tooltip-button statusItineraryPackage" data-placement="top" title="<?php echo $statusText;?>" status="<?php echo $record->status;?>" id="imgHolder_<?php echo $record->id;?>" moduleId="<?php echo $record->id;?>">
                        <i class="glyph-icon icon-flag"></i>
                    </a>
                    <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button" data-placement="top" title="Edit" onclick="editItineraryPackage(<?php echo $record->package_id;?>,<?php echo $record->id;?>);">
                        <i class="glyph-icon icon-edit"></i>
                    </a>
                    <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top" title="Remove" onclick="packageItinDelete(<?php echo $record->id;?>);">
                        <i class="glyph-icon icon-remove"></i>
                    </a>
                    <input name="sortId" type="hidden" value="<?php echo $record->id;?>">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<div class="pad0L col-md-2">
<select name="dropdown" id="groupTaskField2" class="custom-select">
    <option value="0"><?php echo $GLOBALS['basic']['choseAction'];?></option>
    <option value="packageitdelete"><?php echo $GLOBALS['basic']['delete'];?></option>
    <option value="packageitoggleStatus"><?php echo $GLOBALS['basic']['toggleStatus'];?></option>
</select>
</div>
<a class="btn medium primary-bg" href="javascript:void(0);" id="applySelected_btn2">
    <span class="glyph-icon icon-separator float-right">
      <i class="glyph-icon icon-cog"></i>
    </span>
    <span class="button-content"> Submit </span>
</a>
</div>

<?php elseif(isset($_GET['mode']) && $_GET['mode'] == "addEditItineraryPackage"): 
$pid   = addslashes($_REQUEST['id']);
$itineraryInfo = null;

if(isset($_GET['subid']) and !empty($_GET['subid'])):
    $subId          = addslashes($_REQUEST['subid']);
    $itineraryInfo  = PackageItinerary::find_by_id($subId);
endif;  

$status     = (!empty($itineraryInfo) && $itineraryInfo->status==1) || empty($itineraryInfo) ? "checked" : "";
$unstatus   = (!empty($itineraryInfo) && $itineraryInfo->status==0) ? "checked" : "";
?>
<h3>
<?php echo (isset($_GET['subid']))?'Edit Itinerary':'Add Itinerary';?>
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewItinerarylistPackage(<?php echo $pid;?>);">
    <span class="glyph-icon icon-separator">
        <i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
    <span class="button-content"> Back </span>
</a>
</h3>

<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
        <form action="" class="col-md-12 center-margin" id="itinerarypackage_frm">
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Title:
                    </label>
                </div>                
                <div class="form-input col-md-20">
                    <input placeholder="Title" class="col-md-6 validate[required]" type="text" name="title" id="title" value="<?php echo (!empty($itineraryInfo) && !empty($itineraryInfo->title))?$itineraryInfo->title:"";?>">
                </div>                
            </div> 
          
             <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Content :
                    </label>
                </div> 
                <div class="form-input col-md-8">          
                   <textarea name="content" id="content" class="large-textarea"><?php echo !empty($itineraryInfo->content)?$itineraryInfo->content:"";?></textarea>
                </div>
            </div>
                                    
            <div class="form-row">   
                <div class="form-label col-md-2">
                    <label for="">
                        Published :
                    </label>
                </div>             
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio" name="status" id="check1" value="1" <?php echo $status;?>>
                    <label for="">Published</label>
                    <input type="radio" class="custom-radio" name="status" id="check0" value="0" <?php echo $unstatus;?>>
                    <label for="">Un-Published</label>
                </div>                
            </div> 
                                             
            <button btn-action='0' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save
                </span>
            </button>
            <button btn-action='1' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save & More
                </span>
            </button>
            <button btn-action='2' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save & quit
                </span>
            </button>
            <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($itineraryInfo->id)?$itineraryInfo->id:0;?>" />
            <input type="hidden" name="package_id" id="package_id" value="<?php echo !empty($itineraryInfo->package_id)?$itineraryInfo->package_id:$pid;?>" />
         </form>    
    </div>
</div>  
<script>
var base_url =  "<?php echo ASSETS_PATH; ?>";
var editor_arr = ["content"];
create_editor(base_url,editor_arr);
</script> 
<?php endif; ?>
