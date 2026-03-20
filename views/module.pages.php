<?php
/*
 * Homepage Page Section
 * Renders the admission/services section from tbl_pages where homepage=1
 */

$homePageSection = '';
$homePageSection1 = '';

if (defined('HOME_PAGE')) {
    $pageRec = Page::homepagePage(1); // fetch first homepage page
    if (!empty($pageRec)) {
        $page = $pageRec[0];
        $imgSrc = !empty($page->image)
            ? IMAGE_PATH . 'pages/' . $page->image
            : BASE_URL . 'images/inner/img1.jpg';
        $subTitle  = !empty($page->sub_title) ? htmlspecialchars($page->sub_title) : '';
        $title     = !empty($page->title)     ? htmlspecialchars($page->title)     : '';
        $content   = !empty($page->content)   ? $page->content                     : '';
        $content2  = !empty($page->content2)  ? $page->content2                    : '';

        $homePageSection = '

        <section class="m-overview wow animate__fadeInUp">
            <div class="m-overview-center">
                <p class="m-overview-label">Welcome to Lumbini Palace Resort</p>
                <div class="m-overview-divider"></div>
                ' . $content . '   
            </div>
        </section>





    
';
    }
}

$jVars['module:home-pages'] = $homePageSection;



if (defined('HOME_PAGE')) {
    $pageRec = Page::homepagePage(1); // fetch first homepage page
    if (!empty($pageRec)) {
        $page = $pageRec[0];
        $imgSrc = !empty($page->image)
            ? IMAGE_PATH . 'pages/' . $page->image
            : BASE_URL . 'images/inner/img1.jpg';
        $subTitle  = !empty($page->sub_title) ? htmlspecialchars($page->sub_title) : '';
        $title     = !empty($page->title)     ? htmlspecialchars($page->title)     : '';
        $content3  = !empty($page->content3)  ? $page->content3                    : '';

        $homePageSection1 = '

    <section class="join-now">
        <div class="container">
        ' . $content3 . '
        </div>
    </section>

';
    }
}

$jVars['module:home-content'] = $homePageSection1;


