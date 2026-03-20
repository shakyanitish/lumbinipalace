<?php
$resinndetail = $imageList = $innerbred = $t = '';
$homearticle = Article::find_by_id(22);

// -----------------------------------------------------------------
// -----------------------------------------------------------------

if (!empty($homearticle)) {


    $img1 = BASE_URL . 'assets/img/about/01.jpg'; // default image 1
    $img2 = BASE_URL . 'assets/img/about/02.jpg'; // default image 2

    if ($homearticle->image != "a:0:{}") {
        $imageList = unserialize($homearticle->image);

        if (!empty($imageList[0])) {
            $file_path1 = SITE_ROOT . 'images/articles/' . $imageList[0];
            $img1 = (file_exists($file_path1))
                ? IMAGE_PATH . 'articles/' . $imageList[0]
                : $img1;
        }

        if (!empty($imageList[1])) {
            $file_path2 = SITE_ROOT . 'images/articles/' . $imageList[1];
            $img2 = (file_exists($file_path2))
                ? IMAGE_PATH . 'articles/' . $imageList[1]
                : $img2;
        }
    }

    // -----------------------------
    // TITLE + CONTENT
    // -----------------------------
    $title = !empty($homearticle->title) ? $homearticle->title : '';
    $content = !empty($homearticle->content) ? $homearticle->content : '';

    // -----------------------------
    // BUILD DYNAMIC HTML
    // -----------------------------
    $t .= '
    <div class="about-area py-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                        <div class="about-img">
                            <img class="about-img-1" src="' . $img1 . '" alt="">
                            <img class="about-img-2" src="' . $img2 . '" alt="">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <h2 class="site-title">' . $title . '</h2>
                        </div>

                        <div class="about-text">
                            ' . $content . '
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>';
}



$jVars['module:aboutarticle'] = $t;

// -----------------------------------------------------------------
// -----------------------------------------------------------------
$resinnh = '';


if (defined('HOME_PAGE')) {
    $recInn = Article::homepageArticle();
    // pr($recInn);
    if (!empty($recInn)) {
        foreach ($recInn as $innRow) {
            $image = unserialize($innRow->image);
            $content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($innRow->content));
            $readmore = '';
            if (!empty($innRow->linksrc)) {
                $linkTarget = ($innRow->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($innRow->linktype == 1) ? $innRow->linksrc : BASE_URL . $innRow->linksrc;
                $readmore = '<a href="' . $linksrc . '" title="">see more</a>';
            } else {
                $readmore = (count($content) > 1) ? '<a href="' . BASE_URL . 'page/' . $innRow->slug . '" title="">Read more...</a>' : '';
            }
            $resinnh .= '

                    ' . $content[0] . '
            
            ';
        }
    }
}

$jVars['module:home-article'] = $resinnh;


// -----------------------------------------------------------------

$aboutdetail = $imageList = $aboutbred = '';
$abouttbred = '';
// pr($_REQUEST);  // this will show all request parameters


if (defined('INNER_PAGE') and isset($_REQUEST['slug'])) {
    // pr('here');
    $slug = addslashes($_REQUEST['slug']);
    $recRow = Article::find_by_slug($slug);
    // pr($slug);
    if (!empty($recRow)) {
        $title = !empty($recRow->title) ? $recRow->title : '';

        // Default article banner
        if (!empty($siteRegulars->article_upload)) {
            $defaultImg = IMAGE_PATH . 'preference/articles/' . $siteRegulars->article_upload;
        } else {
            $defaultImg = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
        }

        // Start with default banner
        $imglink = $defaultImg;
        $hasImage = false;

        // If the article has images
        if (!empty($recRow->image) && $recRow->image != "a:0:{}") {

            $imageList = unserialize($recRow->image);
            $imgno = array_rand($imageList);

            $file_path = SITE_ROOT . 'images/articles/' . $imageList[$imgno];

            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'articles/' . $imageList[$imgno];
                $hasImage = true;
            }
        }
        $rescontent = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($recRow->content));
        $content = !empty($rescontent[1]) ? $rescontent[1] : $rescontent[0];

        

        $aboutdetail .=  '       
        <section class="breadcrumb-main">
            <div class="container">
                <div class="breadcrumb-inner">
                    <h2>' . $recRow->title . '</h2>
                </div>
            </div>
        </section>

        <section class="about-company inner-about">
            <div class="container">
                <div class="row">
                    ' . ($hasImage ? '
                    <div class="col-lg-5 col-md-12 wow fadeInLeftBig">
                        <div class="about-wrap-img">
                            <img src="' . $imglink . '" alt="' . $recRow->title . '" />
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 wow fadeInRightBig">' : '
                    <div class="col-lg-12 col-md-12 wow fadeInRightBig">') . '
                        <div class="about-us-wrap">
                            <div class="about-title">
                                <h4 class="top-title mb-3">' . $recRow->sub_title . '</h4>
                                <h3 class="mb-3 pb-3">' . $recRow->brief . '</h3>
                            </div>
                            <div class="about-content">
                                ' . $recRow->content . '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';




    }
}



$jVars['module:inner-about-detail'] = $aboutdetail;
$jVars['module:inner-about-bread'] = $abouttbred;






$restyp = '';

$typRow = Article::get_by_type();
if (!empty($typRow)) {
    $content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($typRow->content));
    $readmore = '';
    if (!empty($typRow->linksrc)) {
        $linkTarget = ($typRow->linktype == 1) ? ' target="_blank" ' : '';
        $linksrc = ($typRow->linktype == 1) ? $typRow->linksrc : BASE_URL . $typRow->linksrc;
        $readmore = '<a class="text-link link-direct" href="' . $linksrc . '">see more</a>';
    } else {
        $readmore = (count($content) > 1) ? '<a href="' . BASE_URL . $typRow->slug . '">Read more...</a>' : '';
    }
    $restyp .= '<h3 class="h3 header-sidebar">' . $typRow->title . '</h3>
    <div class="home-content">
        ' . $content[0] . ' ' . $readmore . '
    </div>';
}

$jVars['module:article_by_type'] = $restyp;



/*
    Why Choose Us
*/
$resinnh1 = '';

if (defined('HOME_PAGE')) {

    $resinnh1 .= '';

    // pr($resinnh1);
    $recInn1 = Article::find_by_id(2);
    if (!empty($recInn1)) {
        $resinnh1 .= $recInn1->content;
    }
}

$jVars['module:home_article'] = $resinnh1;


/*
    HomePage Facilities
*/
$resinnh1 = '';

if (defined('HOME_PAGE')) {

    $resinnh1 .= '';


    $recInn1 = Article::find_by_id(3);

    if (!empty($recInn1)) {

        $resinnh1 .= $recInn1->content;
    }
}

$jVars['module:home_facilities'] = $resinnh1;

