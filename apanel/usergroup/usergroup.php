<?php
$moduleTablename = "tbl_group_type"; // Database table name
$moduleId = 1;                // module id >>>>> tbl_modules
$moduleFoldername = "";        // Image folder name

if (isset($_GET['page']) && $_GET['page'] == "usergroup" && isset($_GET['mode']) && $_GET['mode'] == "list"):
    ?>
    <h3>
        List User Groups
        <a class="loadingbar-demo btn btn-primary btn-sm medium bg-blue-alt float-right" href="javascript:void(0);" onClick="addNewGroup();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-plus-square"></i></span>
            <span class="button-content"> Add User Group </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <div class="example-box">
        <div class="example-code">
            <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                <thead>
                <tr>
                    <th class="text-center">S.No.</th>
                    <th class="text-left">Group Name</th>
                    <th class="text-center"><?php echo $GLOBALS['basic']['action']; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $records = Usergrouptype::find_by_sql("SELECT * FROM " . $moduleTablename . " ORDER BY id ASC ");
                foreach ($records as $record): ?>
                    <tr id="<?php echo $record->id; ?>">
                        <td class="text-center"><?php echo $record->id; ?></td>
                        <td>
                            <div class="col-md-7">
                                <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id; ?>);" class="loadingbar-demo"
                                   title="<?php echo $record->group_name; ?>"><?php echo $record->group_name; ?></a>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php
                            $statusImage = ($record->status == 1) ? "bg-green" : "bg-red";
                            $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'];
                            ?>                            
                            <!--<a href="javascript:void(0);" class="btn small <?php echo $statusImage; ?> tooltip-button statusToggler"
                               data-placement="top" title="<?php echo $statusText; ?>" status="<?php echo $record->status; ?>"
                               id="imgHolder_<?php echo $record->id; ?>" moduleId="<?php echo $record->id; ?>">
                                <i class="glyph-icon icon-flag"></i>
                            </a>-->
                            <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button" data-placement="top"
                               title="Edit" onclick="editRecord(<?php echo $record->id; ?>);">
                                <i class="glyph-icon icon-edit"></i>
                            </a>
                            <!--<a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top" title="Remove"
                               onclick="recordDelete(<?php echo $record->id; ?>);">
                                <i class="glyph-icon icon-remove"></i>
                            </a>-->
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif (isset($_GET['mode']) && $_GET['mode'] == "addEdit"):
    if (isset($_GET['id']) && !empty($_GET['id'])):
        $userGroupId = addslashes($_REQUEST['id']);
        $userGroupInfo = Usergrouptype::find_by_id($userGroupId);

        $published = ($userGroupInfo->status == 1) ? "checked" : "";
        $unpublished = ($userGroupInfo->status == 0) ? "checked" : "";
    endif;
    ?>
    <h3>
        <?php echo (isset($_GET['id'])) ? 'Edit User Group' : 'Add User Group'; ?>
        <a class="loadingbar-demo btn btn-primary btn-sm medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewList();">
            <span class="glyph-icon icon-separator"><i class="glyph-icon icon-arrow-circle-left"></i></span>
            <span class="button-content"> Back </span>
        </a>
    </h3>
    <div class="my-msg"></div>
    <div class="example-box ">
        <div class="example-code detail">
            <form action="" class="col-md-10 center-margin" id="adminusersetting_frm">

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Name :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <input placeholder="Name" class="col-md-4 validate[required,length[0,50]]" type="text" name="group_name" id="group_name"
                               value="<?php echo !empty($userGroupInfo->group_name) ? $userGroupInfo->group_name : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Description :
                        </label>
                    </div>
                    <div class="form-input col-md-10">
                        <input placeholder="Name" class="col-md-4 validate[length[0,250]]" type="text" name="description" id="description"
                               value="<?php echo !empty($userGroupInfo->description) ? $userGroupInfo->description : ""; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            Permission :
                        </label>
                    </div>
                    <div class="form-input col-md-8">
                        <a href="javascript:;" class="check-all">Check All</a> | <a href="javascript:;" class="uncheck-all">Un-check All</a>
                        <ul class="">
                            <?php $res = '';
                            $uType = 'admin';
                            $parentmenu = Module::find_all_parent('admin');
                           if(!empty($userGroupInfo) and $userGroupInfo->id=='2'){
                            $parentmenu = Module::find_all_parent('hotel');
                           }
                            $mod_chk = !empty($userGroupInfo->permission) ? unserialize($userGroupInfo->permission) : array();
                            foreach ($parentmenu as $key => $val) {
                                $childmenu = Module::find_all_byparnt($val->id);
                                $parent_checked = (in_array($val->id, $mod_chk)) ? "checked" : "";
                                $res .= '<li class="">
                                <div class="form-checkbox-radio py-3">
                                    <strong><input type="checkbox" class="mcheck parent" name="module_id[]" value="' . $val->id . '" ' . $parent_checked . '/> ' . $val->name . '</strong>
                                </div>';
                                if (!empty($childmenu)) {
                                    $res .= '<ul  style="margin-left:40px;">';
                                    foreach ($childmenu as $k => $v) {
                                        $child_checked = (in_array($v->id, $mod_chk)) ? "checked" : "";
                                        $res .= '<li class="">
                                            <div class="form-checkbox-radio">
                                                <input type="checkbox" class="mcheck child-' . $v->parent_id . '" data-parent="' . $v->parent_id . '" name="module_id[]" value="' . $v->id . '" ' . $child_checked . '/> ' . $v->name . '
                                            </div>
                                        </li>';
                                    }
                                    $res .= '</ul>';
                                }
                                $res .= '</li>';
                            }
                            echo $res; ?>
                        </ul>
                    </div>
                </div>

                <div class="form-row">
                  
                    <div class="form-checkbox-radio col-md-10">
                        <input type="radio" class="custom-radio" name="status" id="check1"
                               value="1" <?php echo !empty($published) ? $published : "checked"; ?>>
                        <label for="">Published</label>
                        <input type="radio" class="custom-radio" name="status" id="check0"
                               value="0" <?php echo !empty($unpublished) ? $unpublished : ""; ?>>
                        <label for="">Un-published</label>
                    </div>
                </div>

                
                <button type="submit" name="submit" class="btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4"
                        id="btn-submit" title="Save">
                    <span class="button-content">Save</span>
                </button>
                <input type="hidden" name="idValue" id="idValue" value="<?php echo !empty($userGroupId) ? $userGroupId : 0; ?>"/>
            </form>
        </div>
    </div>

<?php endif; ?>