<?php

$threeTablename = "tbl_vt_hotspots"; // Database table name
$type_arr = array('info' => 'Info', 'scene' => 'Scene');  //Select option containing two type for hotspot. 
// Retrieve the selected scene value
$selected_scene = isset($_POST['scene_id']) ? $_POST['scene_id'] : 'none'; // Or use GET if the form is submitted via GET

if (isset($_GET['page']) && $_GET['page'] == "virtualtour" && isset($_GET['mode']) && $_GET['mode'] == "viewhotspotList"):
    $pid = addslashes($_REQUEST['id']);
    ?>

    <h3>
        List Hotspot
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="AddNewhotspot(<?= $pid ?>);">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-plus-square"></i></span>
            <span class="button-content"> Add New </span>
        </a>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right mrg5R" href="javascript:void(0);"
           onClick="viewVirtualTourList();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="my-msg"></div>

    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="subexampleh">
                <thead>
                <tr>
                    <th style="display:none;"></th>
                    <th class="text-center"><input class="check-all" type="checkbox"/></th>
                    <th>Title</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $hotspots = Hotspots::find_by_sql("SELECT h.* FROM tbl_vt_hotspots as h INNER JOIN tbl_vt_360_images as ts ON h.three60_id = ts.id WHERE virtual_tour_id ='$pid' ORDER BY sortorder DESC ");
                foreach ($hotspots as $key => $hotspot): ?>
                    <tr id="<?php echo $hotspot->id; ?>">
                        <td style="display:none;"><?php echo $key + 1; ?></td>
                        <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $hotspot->id; ?>"/></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);"
                                   onClick="edithotspot(<?php echo $pid; ?>,<?php echo $hotspot->id; ?>);"
                                   class="loadingbar-demo"
                                   title="<?php echo $hotspot->title; ?>"><?php echo $hotspot->title; ?></a>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php
                            $statusImage = ($hotspot->status == 1) ? "bg-green" : "bg-red";
                            $statusText = ($hotspot->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                            ?>
                            <a href="javascript:void(0);"
                               class="btn small <?php echo $statusImage; ?> tooltip-button hotspotStatusToggler"
                               data-placement="top" title="<?php echo $statusText; ?>"
                               status="<?php echo $hotspot->status; ?>" id="toggleImg<?php echo $hotspot->id; ?>"
                               moduleId="<?php echo $hotspot->id; ?>">
                                <i class="glyph-icon icon-flag"></i>
                            </a>
                            <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button"
                               data-placement="top" title="Edit"
                               onclick="edithotspot(<?php echo $pid; ?>,<?php echo $hotspot->id; ?>);">
                                <i class="glyph-icon icon-edit"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top"
                               title="Remove" onclick="hotspotDelete(<?php echo $hotspot->id; ?>);">
                                <i class="glyph-icon icon-remove"></i>
                            </a>
                            <input name="sortId" type="hidden" value="<?php echo $hotspot->id; ?>">
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
        <a class="btn medium primary-bg" href="javascript:void(0);" id="applySelected_btn_hotspot">
            <span class="glyph-icon icon-separator float-right"><i class="glyph-icon icon-cog"></i></span>
            <span class="button-content"> Submit </span>
        </a>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdithotspot"):
    $pid = addslashes($_REQUEST['id']);
    if (isset($_GET['subid']) and !empty($_GET['subid'])):
        $imgId      = addslashes($_REQUEST['subid']);
        $imgRec     = Hotspots::find_by_id($imgId);
        $status     = ($imgRec->status == 1) ? "checked" : " ";
        $unstatus   = ($imgRec->status == 0) ? "checked" : " ";
    endif;
    ?>

    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit Hotspot' : 'Add Hotspot'; ?>
        <a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);"
           onClick="viewhotspotList(<?= $pid ?>);">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>

    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <form action="" class="col-md-12 center-margin" id="hotspot_frm">
                <input type="hidden" value="<?php echo $pid ?>" id="parentid"/>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">360 Images</label>
                    </div>
                    <div class="form-input col-md-7">
                        <?php $threeid = !empty($imgRec->three60_id) ? $imgRec->three60_id : 0; ?>
                        <?php $virtualid = !empty($pid) ? $pid : 0; ?>
                        <select name="threeId" class="col-md-4 validate[required] parent_360"
                                vId="<?php echo $virtualid; ?>">
                            <?php echo Image360::get_all_images($virtualid, $threeid); ?>
                        </select>
                        <div>Select the uploaded image to get hotspot applied</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Text :
                        </label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="Text" class="col-md-6 validate[required,length[0,50]]" type="text"
                               name="title" id="title"
                               value="<?php echo !empty($imgRec->title) ? $imgRec->title : ""; ?>">
                        <div>Enter hotspot name</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Tilt Position :
                        </label>
                    </div>
                    <div class="form-input col-md-5">
                        <input placeholder="" class="col-md-6 validate[required,length[0,50]]" type="number" step="1"
                               name="hotspot_pitch" id="hotspot_pitch"
                               value="<?php echo isset($imgRec->hotspot_pitch) ? $imgRec->hotspot_pitch : "0"; ?>">
                        <div>Sets the Hotspot button starting tilt position in degrees. Defaults to 0.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Rotate :</label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder=""
                               class="col-md-5 validate[required,length[0,200]]" type="number" step="1"
                               name="hotspot_yaw" id="hotspot_yaw"
                               value="<?php echo isset($imgRec->hotspot_yaw) ? $imgRec->hotspot_yaw : "0"; ?>">
                        <span id="error"></span>
                        <div>Sets the Hotspot button starting rotate position in degrees. Defaults to 0.</div>
                    </div>
                </div>

                <!--<div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">Text:</label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="" class="col-md-5" type="text" name="hotspot_text" id="hotspot_text"
                               value="<?php echo !empty($imgRec->hotspot_text) ? $imgRec->hotspot_text : ''; ?>">
                    </div>
                </div>
                -->

                <div class="form-row menu-position">
                    <div class="form-label col-md-2">
                        <label for="">
                            Type :
                        </label>
                    </div>
                    <div class="form-input col-md-5">
                        <select data-placeholder="None" class="chosen-selec validate[required] col-md-6"
                                id="hotspot_type" name="hotspot_type">
                                <!--$key will hold the array key ('scene' or 'info'). $val will hold the value ('Scene' or 'Info')-->
                            <?php 
                            $virtualid = !empty($pid) ? $pid : 0;
                            $sql = "SELECT id FROM tbl_vt_360_images WHERE virtual_tour_id='{$virtualid}' ORDER BY sortorder DESC";
                            $imgs = Image360::find_by_sql($sql);
                            $count = count($imgs);
                            if($count == 1){
                                $type_arr = array('info' => 'Info');
                            }
                            foreach ($type_arr as $key => $val) {
                                //$sel variable is set to 'selected'.'selected' attribute added if $imgRec->hotspot_type matches $key.
                                $sel = (!empty($imgRec->hotspot_type) and $imgRec->hotspot_type == $key) ? 'selected' : '';
                                //select option will display text of the option set to $val (either 'Scene' or 'Info').
                                echo '<option value="' . $key . '" ' . $sel . '>' . $val . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                
                <?php 
                    $hide = 'hide'; 
                    if(!empty($imgRec->hotspot_type) && ($imgRec->hotspot_type != 'info')) {
                        $hide = '';
                    }
                ?>
                <div class="form-row linkTo <?= $hide; ?>">
                    <div class="form-label col-md-2">
                        <label for="">Link To:</label>
                    </div>
                    <div class="form-input col-md-5">
                        <?php $scene = !empty($imgRec->scene_id) ? $imgRec->scene_id : 0; ?>
                        <?php $virtualid = !empty($pid) ? $pid : 0; ?>
                        <select name="scene_id" class="col-md-6 scene_id" vId="<?php echo $scene; ?>">
                            <?php $threeid = !empty($imgRec->three60_id) ? $imgRec->three60_id : 0;
                            echo Image360::get_all_scene_data($virtualid, $scene, $threeid);
                            ?>
                        </select>
                        <div>Link to: Selected the 360 image to navigate</div>
                    </div>
                </div>
                
                <!-- if statement that checks if $selected_scene is not 'none'. If it's not 'none', the "Targeted Rotate" & "Targeted Tilt" row is rendered.-->
                <?php // if( $selected_scene != 'none') :
                    
                    ?>
                <?php 
                    $hide = 'hide'; 
                    if(!empty($imgRec->scene_id) && ($imgRec->scene_id != '') && ($imgRec->hotspot_type != 'info')) {
                        $hide = '';
                    }
                ?>
                <div class="form-row linkTo2 <?= $hide; ?>">
                    <div class="form-label col-md-2">
                        <label for="">Targeted Rotate:</label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="" class="col-md-5" type="number" name="target_yaw" id="target_yaw" step="1"
                               value="<?php echo isset($imgRec->target_yaw) ? $imgRec->target_yaw : '0'; ?>">
                        <div> Sets the targeted image rotate position in degrees. Defaults is 0</div>
                    </div>
                </div>

                <div class="form-row linkTo2 <?= $hide; ?>">
                    <div class="form-label col-md-2">
                        <label for="">Targeted Tilt:</label>
                    </div>
                    <div class="form-input col-md-6">
                        <input placeholder="" class="col-md-5" type="number" name="target_pitch" id="target_pitch"
                               step="1"
                               value="<?php echo isset($imgRec->target_pitch) ? $imgRec->target_pitch : '0'; ?>">
                        <div>Sets the targeted image tilt position in degrees. Defaults is 0</div>
                    </div>
                </div>
                <?php // endif; ?>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Published :
                        </label>
                    </div>
                    <div class="form-checkbox-radio col-md-9">
                        <input type="radio" class="custom-radio" name="status" id="check1" value="1" <?php echo !empty($status) ? $status : "checked"; ?>>
                        <label for="">Published</label>
                        <input type="radio" class="custom-radio" name="status" id="check0" value="0" <?php echo !empty($unstatus) ? $unstatus : ""; ?>>
                        <label for="">Un-Published</label>
                    </div>
                </div>

                <button btn-action='0' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save</span>
                </button>
                <button btn-action='1' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save & More</span>
                </button>
                <button btn-action='2' type="submit" name="submit" id="btn-submit" title="Save"
                        class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4">
                    <span class="button-content">Save & quit</span>
                </button>

                <input type="hidden" name="parentid" id="parentid" value="<?php echo $pid ?>"/>
                <input myaction='0' type="hidden" name="idValue" id="idValue"
                       value="<?php echo !empty($imgRec->id) ? $imgRec->id : 0; ?>"/>

            </form>
        </div>
    </div>

<?php endif; ?>