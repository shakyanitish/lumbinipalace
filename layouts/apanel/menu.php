
<!-- Sider menu Header-->
<div id="header-logo">
    <?php Config::getField('sitename'); ?>
    <a href="javascript:;" class="tooltip-button" data-placement="bottom" title="Close sidebar" id="close-sidebar">
        <i class="glyph-icon icon-align-justify"></i>
    </a>
    <a href="javascript:;" class="tooltip-button hidden" data-placement="bottom" title="Open sidebar" id="rm-close-sidebar">
        <i class="glyph-icon icon-align-justify"></i>
    </a>
    <a href="javascript:;" class="tooltip-button hidden" title="Navigation Menu" id="responsive-open-menu">
        <i class="glyph-icon icon-align-justify"></i>
    </a>
</div>
<!-- Menu search Section -->
<div id="sidebar-search">
    <input type="text" placeholder="Menu category search..." class="autocomplete-input tooltip-button" data-placement="right" title="Type menu category name " id=""
           name="">
    <i class="glyph-icon icon-search"></i>
</div>
<!-- Sider menu from Database -->
<div id="sidebar-menu" class="scrollable-content">
    <ul>
        <li <?php echo !isset($_REQUEST['page']) ? 'class="current-page"' : ''; ?>>
            <a href="<?php echo ADMIN_URL; ?>dashboard" title="Dashboard">
                <i class="glyph-icon icon-dashboard"></i>
                Dashboard
            </a>
        </li>

        <?php

        $usid = isset($_SESSION['u_id']) ? $_SESSION['u_id'] : 0;
        $grpid = isset($_SESSION['u_group']) ? $_SESSION['u_group'] : 0;
//        $urow = User::field_by_id($usid, 'permission');
//        $mod_chk = !empty($urow) ? unserialize($urow) : array();
// pr($_SESSION);
        $userGroupInfo = Usergrouptype::find_by_id($grpid);
        $mod_chk = !empty($userGroupInfo->permission) ? unserialize($userGroupInfo->permission) : array();
        
        
        $slg = !empty($_REQUEST['page']) ? $_REQUEST['page'] : '';
        $mm = Module::get_id_by($slg);
        // pr($mm,1);
        if (!empty($slg) and !empty($mm) and !in_array($mm->id, $mod_chk)) {
            redirect_to(ADMIN_URL . 'dashboard');
            exit;
        }
        ?>

        <?php 
        $config= Config::find_by_id(1);
        if($config->upcoming==1){

            $parentmenu = Module::find_all_parent_upcoming();
        }
        else{

            $parentmenu = Module::find_all_parent();
        }
        //if($loginUser->type == 'hotel' AND $loginUser->package_status==0) { unset($parentmenu[2]); }
        //if($loginUser->type == 'hotel' AND $loginUser->vehicle_status==0) { unset($parentmenu[3]); }
        $page = (!empty($_REQUEST['page']) and isset($_REQUEST['page'])) ? $_REQUEST['page'] : '';
        foreach ($parentmenu as $key => $val) {
            if (in_array($val->id, $mod_chk)) {
                if (!empty($page)):
                    $currpage = ($page == $val->mode) ? 'current-page' : '';
                else:
                    $currpage = '';
                endif;
                $actvpage = '';
                $pid = Module::check_parent($page);
                if ($pid) {
                    $actvpage = ($pid->parent_id == $val->id) ? ' active' : '';
                }

                $childmenu = Module::find_child_by($val->id);
                $pagelink = !empty($childmenu) ? 'javascript:void(0);' : ADMIN_URL . $val->link;
                echo '<li class="' . $currpage . $actvpage . '">
                <a href="' . $pagelink . '" title="' . $val->name . '">
                    <i class="glyph-icon ' . $val->icon_link . '"></i>
                    ' . $val->name . '
                </a>';
                if (!empty($childmenu)) {
                    echo '<ul>';
                    foreach ($childmenu as $k => $v) {
                        if (in_array($v->id, $mod_chk)) {
                            $subcurrent = ($page == $v->mode) ? 'current-page' : '';
                            echo '<li class="' . $subcurrent . '">
                                <a href="' . ADMIN_URL . $v->link . '" title="' . $v->name . '">
                                    <i class="glyph-icon ' . $v->icon_link . '"></i>
                                    ' . $v->name . '
                                </a>
                              </li>';
                        }
                    }
                    echo '</ul>';
                }
                echo '</li>';
            }
        } ?>
    </ul>
    <div class="divider mrg5T mobile-hidden"></div>
</div>