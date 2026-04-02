<?php
$result = '';

$menuRec = Menu::getMenuByParent(0, 1);

$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);

if ($menuRec):
    $result .= '<ul class="mad-navigation mad-navigation--vertical-sm">';
    foreach ($menuRec as $menuRow):
        $linkActive = $PlinkActive = '';
        $tot = strlen(SITE_FOLDER) + 2;
        $data = substr($_SERVER['REQUEST_URI'], $tot);

        if (!empty($data)):
            $linkActive = ($menuRow->linksrc == $data) ? " current-menu-item" : "";
            $parentInfo = Menu::find_by_linksrc($data);
            if ($parentInfo):
                $PlinkActive = ($menuRow->id == $parentInfo->parentOf) ? " " : "";
            endif;
        endif;

        $menusubRec = Menu::getMenuByParent($menuRow->id, 1);
        $subclass = ($menusubRec) ? 'nav-item dropdown' : 'nav-item';
        $classLink = !empty($menusubRec) ? 'nav-link dropdown-toggle' : 'nav-link';
        $chkchild = !empty($menusubRec) ? ' data-bs-toggle="dropdown" role="button" aria-expanded="false" ' : ' role="button" aria-haspopup="true" aria-expanded="false" ';
        $drop1 = !empty($menusubRec) ? ' <i class=icon-down-open-mini></i>' : '';
        $result .=  '<li class="' . $subclass . ' ">';
        $result .= getMenuList($menuRow->name, $menuRow->linksrc, $menuRow->linktype, $linkActive . $PlinkActive . $classLink, $chkchild);
        /* Second Level Menu */
        if ($menusubRec):
            $result .= '<ul class="sub-menu">';
            foreach ($menusubRec as $menusubRow):
                $menusub2Rec = Menu::getMenuByParent($menusubRow->id, 1);
                $chkparent2 = (!empty($menusub2Rec)) ? 1 : 0;
                $drop2 = !empty($menusub2Rec) ? 'menu-item' : 'menu-item';
                $result .= '<li class="' . $drop2 . '">';
                $result .= getMenuList($menusubRow->name, $menusubRow->linksrc, $menusubRow->linktype, '', $chkparent2);
                /* Third Level Menu */
                if ($menusub2Rec):
                    $result .= '<ul class="sub-menu">';
                    foreach ($menusub2Rec as $menusub2Row):
                        $menusub3Rec = Menu::getMenuByParent($menusub2Row->id, 1);
                        $chkparent3 = (!empty($menusub3Rec)) ? 1 : 0;
                        $drop3 = !empty($menusub3Rec) ? 'class="dropdown"' : '';
                        $result .= '<li id="menu-item-' . $menusub2Row->id . '" ' . $drop3 . '>';
                        $result .= getMenuList($menusub2Row->name, $menusub2Row->linksrc, $menusub2Row->linktype, '', $chkparent3);
                        /* Fourth Level Menu */
                        if ($menusub3Rec):
                            $result .= '<ul class="dropdown-menu">';
                            foreach ($menusub3Rec as $menusub3Row):
                                $menusub4Rec = Menu::getMenuByParent($menusub3Row->id, 1);
                                $chkparent4 = (!empty($menusub4Rec)) ? 1 : 0;
                                $result .= '<li id="menu-item-' . $menusub2Row->id . '">';
                                $result .= getMenuList($menusub3Row->name, $menusub3Row->linksrc, $menusub3Row->linktype, '', $chkparent4);
                                /* Fifth Level Menu */
                                if ($menusub4Rec):
                                    $result .= '<ul>';
                                    foreach ($menusub4Rec as $menusub4Row):
                                        $menusub5Rec = Menu::getMenuByParent($menusub4Row->id, 1);
                                        $chkparent5 = (!empty($menusub4Rec)) ? 1 : 0;
                                        $result .= '<li>' . getMenuList($menusub4Row->name, $menusub4Row->linksrc, $menusub4Row->linktype, $chkparent5) . '</li>';
                                    endforeach;
                                    $result .= '</ul>';
                                endif;
                                $result .= '</li>';
                            endforeach;
                            $result .= '</ul>';
                        endif;
                        $result .= '</li>';
                    endforeach;
                    $result .= '</ul>';
                endif;
                $result .= '</li>';
            endforeach;
            $result .= '</ul>';
        endif;
        $result .= '</li>';
    endforeach;
    $result .= '</ul>';
endif;

$jVars['module:res-menu'] = $result;



// Desktop Header Navigation
$desktopNav = '';
$menuRec = Menu::getMenuByParent(0, 1);

// Get current path for active class detection
$tot = strlen(SITE_FOLDER) + 2;
$currentPath = substr($_SERVER['REQUEST_URI'], $tot);
$isHomePage = (empty($currentPath) || $currentPath == '/' || $currentPath == 'index.php');

if ($menuRec) {
    foreach ($menuRec as $menuRow) {
        $linkActive = '';
        $isHomeMenu = ($menuRow->linksrc == 'home' || $menuRow->linksrc == '' || $menuRow->linksrc == '/');
        
        // Check if homepage and menu is home link
        if ($isHomePage && $isHomeMenu) {
            $linkActive = ' class="active"';
        } elseif (!empty($currentPath)) {
            // Check if current path matches menu link
            if ($menuRow->linksrc == $currentPath) {
                $linkActive = ' class="active"';
            } else {
                // Check if parent is active (when a submenu item is selected)
                $parentInfo = Menu::find_by_linksrc($currentPath);
                if ($parentInfo && $menuRow->id == $parentInfo->parentOf) {
                    $linkActive = ' class="active"';
                }
            }
        }
        
        $menuLink = ($menuRow->linktype == 'external') ? $menuRow->linksrc : (($isHomeMenu) ? BASE_URL : BASE_URL . $menuRow->linksrc);
        $desktopNav .= '<a href="' . $menuLink . '"' . $linkActive . '>' . $menuRow->name . '</a>';
    }
}

$jVars['module:desktop-nav'] = $desktopNav;


// Mobile Sidebar Navigation
$mobileNav = '';
$menuRec = Menu::getMenuByParent(0, 1);

if ($menuRec) {
    foreach ($menuRec as $menuRow) {
        $linkActive = '';
        $isHomeMenu = ($menuRow->linksrc == 'home' || $menuRow->linksrc == '' || $menuRow->linksrc == '/');
        
        // Check if homepage and menu is home link
        if ($isHomePage && $isHomeMenu) {
            $linkActive = ' class="active"';
        } elseif (!empty($currentPath)) {
            // Check if current path matches menu link
            if ($menuRow->linksrc == $currentPath) {
                $linkActive = ' class="active"';
            } else {
                // Check if parent is active (when a submenu item is selected)
                $parentInfo = Menu::find_by_linksrc($currentPath);
                if ($parentInfo && $menuRow->id == $parentInfo->parentOf) {
                    $linkActive = ' class="active"';
                }
            }
        }
        
        $menuLink = ($menuRow->linktype == 'external') ? $menuRow->linksrc : (($isHomeMenu) ? BASE_URL : BASE_URL . $menuRow->linksrc);
        $mobileNav .= '
        <a href="' . $menuLink . '">' . $menuRow->name . ' <i class="fa-solid fa-chevron-right' . $linkActive . '"></i></a>';
    }
}

$jVars['module:mobile-nav'] = $mobileNav;


// Footer Menu List
$resfooter = '';
$FmenuRec = Menu::getMenuByParent(0, 1);
if ($FmenuRec):
    // $resfooter .= '<h3>Quick Link</h3><ul>';

    foreach ($FmenuRec as $FmenuRow):
        $resfooter .= '<li>';
        $resfooter .= getMenuList($FmenuRow->name, $FmenuRow->linksrc, $FmenuRow->linktype, 'mad-text-link');
        $resfooter .= '</li>';
    endforeach;
    // $resfooter .= '</ul>';
endif;




$result = '';

$menuRec = Menu::getMenuByParent(0, 2);

if ($menuRec):
    $links = [];

    foreach ($menuRec as $menuRow):
        $linkActive = '';
        $tot = strlen(SITE_FOLDER) + 2;
        $data = substr($_SERVER['REQUEST_URI'], $tot);

        if (!empty($data)):
            $linkActive = ($menuRow->linksrc == $data) ? " active" : "";
        endif;

        // Build inline <a> tag
        $links[] = '<a href="' . $menuRow->linksrc . '" class="mad-text-link' . $linkActive . '">' 
                    . strtoupper($menuRow->name) . '</a>';
    endforeach;

    // Join with pipe
    $result = implode('  ', $links);
endif;

$jVars['module:footer-menu'] = $result;

$resfooter = '';
$FmenuRec = Menu::getMenuByParent(0, 2);

if ($FmenuRec) {
    foreach ($FmenuRec as $FmenuRow) {

        // Check if it's a modal link
        if ($FmenuRow->linksrc == '#virtualTourModal') {
            $resfooter .= '
            <li>
                <a href="' . $FmenuRow->linksrc . '" 
                   data-bs-toggle="modal"
                   class="text-decoration-underline text-dark fw-normal small">
                   ' . $FmenuRow->name . '
                </a>
            </li>';
        } else {
            $resfooter .= '
            <li>
                <a href="' . BASE_URL . $FmenuRow->linksrc . '" 
                   class="text-decoration-underline text-dark fw-normal small">
                   ' . $FmenuRow->name . '
                </a>
            </li>';
        }
    }
}

$jVars['module:footer-menu-list'] = $resfooter;

$resfooter = '';
$FmenuRec1 = Menu::getMenuByParent(0, 3);
if ($FmenuRec1) {
    foreach ($FmenuRec1 as $FmenuRow) {
        $resfooter .= '

        <li><a href="' . BASE_URL . $FmenuRow->linksrc . '" class="text-decoration-underline text-dark fw-normal small">' . $FmenuRow->name . '</a></li>';
    }
}
$jVars['module:footer-menu-list1'] = $resfooter;

// Footer two-column nav (built from menutype=1 top-level items)



//menu for uc
$result_uc = '';

$menuRec_uc = Menu::getMenuByParent(0, 1, 1);

$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);

if ($menuRec_uc):
    $result_uc .= '<ul>';
    foreach ($menuRec_uc as $key => $menuRec_uc):
        $linkActive = $PlinkActive = '';
        $tot = strlen(SITE_FOLDER) + 2;
        $data = substr($_SERVER['REQUEST_URI'], $tot);

        if (!empty($data)):
            $linkActive = ($menuRec_uc->linksrc == $data) ? " " : "";
            $parentInfo = Menu::find_by_linksrc($data);
            if ($parentInfo):
                $PlinkActive = ($menuRec_uc->id == $parentInfo->parentOf) ? " " : "";
            endif;
        endif;

        $hrefId = '#mod-about';

        if ($menuRec_uc->name == 'Our Location'):
            $hrefId = '#mod-map';
        elseif ($menuRec_uc->name == 'Career'):
            $hrefId = '#mod-career';
        endif;

        $locationStyle = '';
        if ($menuRec_uc->name == 'Our Location'):
            $locationStyle = ' style="border-right: 1px solid #dfc175; color: white;"';
        endif;


        $menusubRec = Menu::getMenuByParent($menuRec_uc->id, 1);
        $subclass = ($menusubRec) ? 'menu-item menu-item-has-children' : ' ';
        $classLink = !empty($menusubRec) ? '' : '';
        $chkchild = !empty($menusubRec) ? ' ' : '';
        $drop1 = !empty($menusubRec) ? ' <i class=icon-down-open-mini></i>' : '';
        $result_uc .=  '<li class="' . $subclass . $linkActive . $PlinkActive . ' ' . $classLink . ' imgclass' . $key . '"' . $locationStyle . '">
        <style>
            .imgclass' . $key . ' a::before {
                width: 28px;
                height: 28px;
                line-height: 28px;
                background-image: url(' . IMAGE_PATH . 'menu/' . $menuRec_uc->image . ') !important;
                left: 24px;
                background-size: contain;
            }
        </style>
        ';
        $result_uc .= getMenuList($menuRec_uc->name, $menuRec_uc->linksrc, $menuRec_uc->linksrc, $menuRec_uc->linktype, $linkActive . $PlinkActive . $classLink, $chkchild);
        /* Second Level Menu */
        if ($menusubRec):
            $result_uc .= '<ul class="sub-menu">';
            foreach ($menusubRec as $menusubRow):
                $menusub2Rec = Menu::getMenuByParent($menusubRow->id, 1);
                $chkparent2 = (!empty($menusub2Rec)) ? 1 : 0;
                $drop2 = !empty($menusub2Rec) ? 'menu-item' : 'menu-item';
                $result_uc .= '<li class="' . $drop2 . '">';
                $result_uc .= getMenuList($menusubRow->name, $menusubRow->linksrc, $menusubRow->linktype, '', $chkparent2);
                /* Third Level Menu */
                if ($menusub2Rec):
                    $result_uc .= '<ul class="sub-menu">';
                    foreach ($menusub2Rec as $menusub2Row):
                        $menusub3Rec = Menu::getMenuByParent($menusub2Row->id, 1);
                        $chkparent3 = (!empty($menusub3Rec)) ? 1 : 0;
                        $drop3 = !empty($menusub3Rec) ? 'class="dropdown"' : '';
                        $result_uc .= '<li id="menu-item-' . $menusub2Row->id . '" ' . $drop3 . '>';
                        $result_uc .= getMenuList($menusub2Row->name, $menusub2Row->linksrc, $menusub2Row->linktype, '', $chkparent3);
                        /* Fourth Level Menu */
                        if ($menusub3Rec):
                            $result_uc .= '<ul class="dropdown-menu">';
                            foreach ($menusub3Rec as $menusub3Row):
                                $menusub4Rec = Menu::getMenuByParent($menusub3Row->id, 1);
                                $chkparent4 = (!empty($menusub4Rec)) ? 1 : 0;
                                $result_uc .= '<li id="menu-item-' . $menusub2Row->id . '">';
                                $result_uc .= getMenuList($menusub3Row->name, $menusub3Row->linksrc, $menusub3Row->linktype, '', $chkparent4);
                                /* Fifth Level Menu */
                                if ($menusub4Rec):
                                    $result_uc .= '<ul>';
                                    foreach ($menusub4Rec as $menusub4Row):
                                        $menusub5Rec = Menu::getMenuByParent($menusub4Row->id, 1);
                                        $chkparent5 = (!empty($menusub4Rec)) ? 1 : 0;
                                        $result_uc .= '<li>' . getMenuList($menusub4Row->name, $menusub4Row->linksrc, $menusub4Row->linktype, $chkparent5) . '</li>';
                                    endforeach;
                                    $result_uc .= '</ul>';
                                endif;
                                $result_uc .= '</li>';
                            endforeach;
                            $result_uc .= '</ul>';
                        endif;
                        $result_uc .= '</li>';
                    endforeach;
                    $result_uc .= '</ul>';
                endif;
                $result_uc .= '</li>';
            endforeach;
            $result_uc .= '</ul>';
        endif;


        $result_uc .= '</li>';
    endforeach;
    $result_uc .= '</ul>';
endif;

$jVars['module:res-menu-uc'] = $result_uc;


$result = '';
$menuRec = Menu::getMenuByParent(0, 1);

$tot = strlen(SITE_FOLDER) + 2;
$currentPath = substr($_SERVER['REQUEST_URI'], $tot);

if ($menuRec) {
    $result .= '<ul class="nav navbar-nav" id="responsive-menu">';
    foreach ($menuRec as $menuRow) {
        $linkActive = '';
        // Check if we're on homepage - account for .htaccess rewrites
        $isHomePage = (empty($currentPath) || $currentPath == '/' || $currentPath == 'index.php');
        // Also check if current URL is the home domain (handles .htaccess rewrites)
        if (!$isHomePage && strpos($_SERVER['REQUEST_URI'], BASE_URL) === 0) {
            $pathAfterBase = substr($_SERVER['REQUEST_URI'], strlen(BASE_URL));
            $isHomePage = (empty($pathAfterBase) || $pathAfterBase == '/' || $pathAfterBase == 'index.php');
        }
        $isHomeMenu = ($menuRow->linksrc == 'home' || $menuRow->linksrc == '' || $menuRow->linksrc == '/');

        if ($isHomePage && $isHomeMenu) {
            $linkActive = "active";
        } elseif (!empty($currentPath)) {
            $linkActive = ($menuRow->linksrc == $currentPath) ? "active" : "";
            
            // Check if parent is active (when a submenu item is selected)
            if (empty($linkActive)) {
                $parentInfo = Menu::find_by_linksrc($currentPath);
                if ($parentInfo) {
                    $linkActive = ($menuRow->id == $parentInfo->parentOf) ? "active" : "";
                }
            }
            
            // Also check if any child menu is active (to highlight parent)
            if (empty($linkActive)) {
                $childMenus = Menu::getMenuByParent($menuRow->id, 1);
                if ($childMenus) {
                    foreach ($childMenus as $child) {
                        if ($child->linksrc == $currentPath || (!empty($child->linksrc) && strpos($currentPath, $child->linksrc) === 0)) {
                            $linkActive = "active";
                            break;
                        }
                    }
                }
            }
        }

        $menusubRec = Menu::getMenuByParent($menuRow->id, 1);
        if ($menusubRec) {
            $result .= '<li class="dropdown submenu ' . $linkActive . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $menuRow->name . ' <i class="fas fa-chevron-down"></i></a>';
            $result .= '<ul class="dropdown-menu">';
            foreach ($menusubRec as $subRow) {
                // Determine if it's an external link or a local link
                $subLink = ($subRow->linktype == 'external') ? $subRow->linksrc : (($subRow->linksrc == 'home' || $subRow->linksrc == '' || $subRow->linksrc == '/') ? BASE_URL : BASE_URL . $subRow->linksrc);
                $result .= '<li><a href="' . $subLink . '">' . $subRow->name . '</a></li>';
            }
            $result .= '</ul>';
            $result .= '</li>';
        } else {
            $menuLink = ($menuRow->linktype == 'external') ? $menuRow->linksrc : (($menuRow->linksrc == 'home' || $menuRow->linksrc == '' || $menuRow->linksrc == '/') ? BASE_URL : BASE_URL . $menuRow->linksrc);
            $result .= '<li class="' . $linkActive . '"><a href="' . $menuLink . '">' . $menuRow->name . '</a></li>';
        }
    }
    $result .= '</ul>';
}

$jVars['module:main-menu'] = $result;


// Mobile menu toggle script
?>