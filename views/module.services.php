    <?php
/*
 * Service home list
 */

$rescont = $res = '';


$subpkgRec = Services::getservice_list(1000, 2);

if (!empty($subpkgRec)) {

    foreach ($subpkgRec as $k => $v) {


        // Main image
        $imglink = '';
        if ($v->image != "a:0:{}") {
            $imageList = unserialize($v->image);
            $file_path = SITE_ROOT . 'images/services/' . $imageList[0];
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'services/' . $imageList[0];
            }
        }

        // Icon image
        $iconlink = '';
        if (!empty($v->iconimage) && $v->iconimage != "a:0:{}") {
            $iconList = unserialize($v->iconimage);
            $file_path_icon = SITE_ROOT . 'images/services/icon/' . $iconList[0];
            if (file_exists($file_path_icon)) {
                $iconlink = IMAGE_PATH . 'services/icon/' . $iconList[0];
            }
        }
        // Only create link if linksrc exists in database
        if (!empty($v->linksrc)) {
            $linkTarget = ($v->linktype == 1) ? ' target="_blank" ' : '';
            $linksrc = ($v->linktype == 1) ? $v->linksrc : BASE_URL . $v->linksrc;
            $titleHtml = '<a href="' . $linksrc . '"' . $linkTarget . '>
                            <h3 class="ul-feature-title">' . $v->title . '</h3>
                            </a>';
        }
        else {
            // No link - just display the title without anchor
            $titleHtml = '
                    <h3 class="ul-feature-title">' . $v->title . '</h3>';
        }

        $isLeft    = ($k % 2 === 0);
        $sideClass = $isLeft ? 'style1-left'  : 'style1-right';
        $wowClass  = $isLeft ? 'wow fadeInLeftBig'  : 'wow fadeInRightBig';
        $pbClass   = $isLeft ? 'pb-170 ' : '';

        // Build the h4 link from linksrc or fall back to slug-based URL
        $itemLink  = BASE_URL . '' . $v->slug;

        $res .= '
                    <div class="col-lg-6 col-md-6 ' . $pbClass . 'customize-wrap ' . $wowClass . '">
                        <div class="customize-item ' . $sideClass . '">
                            <div class="sv-image">
                            <a href="' . $itemLink . '"' . $linkTarget . '>
                            <img src="' . $iconlink . '" alt="' . htmlspecialchars($v->title) . '" />
                            </a>     
                            </div>
                            <div class="customize-ct">
                                <h4><a href="' . $itemLink . '"' . $linkTarget . '>' . $v->title . '</a></h4>
                                <p>' . $v->sub_title . '</p>
                            </div>
                        </div>
                    </div>
                ';
    }
}

// Wrap the features in the section structure
$rescont = '

    <section class="courses programs34">
        <div class="container">
            <div class="section-title sc-center justify-content-center text-center borderline">
                <div class="title-top">
                    <div class="title-quote">
                        <span>Find More Courses</span>
                    </div>
                    <h2>ACADEMICS OF IMS</h2>
                </div>
            </div>

            <div class="wrap-customize">
                <div class="row">
                ' . $res . '
                </div>
            </div>
        </div>
    </section>

    ';

$jVars['module:home-service-list'] = $rescont;



$restscont = '';

$servpkgRec = Services::find_all();
// var_dump($subpkgRec); die();
if (isset($_REQUEST['slug']) and !empty($_REQUEST['slug'])) {
    $slug = $_REQUEST['slug'];
}
else {
    $slug = 'health-club';
}
if (!empty($subpkgRec)) {
    $i = 0;
    $j = 0;
    $restscont .= '<div class="tab-section bg-gray body-room-5">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <h2 class="mb-0">Services</h2>
                                <ul class="pages-link">
                                    <li><a href="' . BASE_URL . 'home">Home</a></li>
                                    <li>/</li>
                                    <li>Services</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="dining-tabs">
                            <ul class="nav nav-tabs">';
    foreach ($servpkgRec as $key => $serRec) {
        if ($slug == $serRec->slug) {
            $class = "active";
        }
        else {
            $class = "";
        }
        $actv = ($i == 0) ? 'active' : '';
        $restscont .= '<li class="' . $class . '">
                                    <a href="#Sauna' . $serRec->id . '" id="' . $serRec->slug . '" role="tab" data-toggle="tab">' . $serRec->title . '<small class="d-block">' . $serRec->sub_title . '</small></a>
                                </li>';
        $i++;
    }
    $restscont .= '  </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block small-padding both-padding page">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tab-content">';
    foreach ($servpkgRec as $key => $serRec) {
        $imageList = '';
        if ($serRec->image != "a:0:{}") {
            $imageList = unserialize($serRec->image);
        }
        if ($slug == $serRec->slug) {
            $class1 = "active";
        }
        else {
            $class1 = "";
        }
        $actv = ($j == 0) ? 'active' : '';
        $restscont .= '<div role="tabpanel" class="tab-pane fade in ' . $class1 . '" id="Sauna' . $serRec->id . '">
                                    <div class="dining-detail">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="dining-detail-carousel">';
        // var_dump($imageList); die();
        if ($serRec->image != "a:0:{}") {
            foreach ($imageList as $key => $imgServ) {
                $restscont .= ' <div class="item">
                                                <img src="' . IMAGE_PATH . 'services/' . $imgServ . '" alt="' . $serRec->title . '" />
                                            </div>';
            }
        }
        $restscont .= ' </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="service-content">
                                                ' . substr(strip_tags($serRec->content), 0, 30000) . '
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        $j++;
    }
    $restscont .= '</div>
                        </div>
                    </div>
                </div><!-- container -->
            </div><!-- block -->';
}

$jVars['module:service-detail-list'] = $restscont;

$facility_bread = '';
if (defined('FACILITY_PAGE')) {
    $siteRegulars = Config::find_by_id(1);
    $imglink = $siteRegulars->facility_upload;
    // pr($imglink);
    if (!empty($imglink)) {
        $img = IMAGE_PATH . 'preference/facility/' . $siteRegulars->facility_upload;
    }
    else {
        $img = '';
    }

    $facility_bread = '<div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="' . $img . '">
        <div class="container wide">
            <h1 class="mad-page-title">Hotel Amenities</h1>
            <nav class="mad-breadcrumb-path">
                <span><a href="index.html" class="mad-link">Home</a></span> /
                <span>Facilities</span>
            </nav>
        </div>
    </div>';
}
$jVars['module:facilitybread'] = $facility_bread;

$facility = "";
if (defined('FACILITY_PAGE')) {

    $record = Services::getservice_list(1000, 2);
    if (!empty($record)) {
        $count = $countsec = 0;
        foreach ($record as $recRow) {
            if (!empty($recRow->icon)) {
                $facility .= ' 
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mad-col">
                            <!--================ Icon Box ================-->
                            <article class="mad-icon-box">
                                <span class="' . $recRow->icon . '"></span>
                                <div class="mad-icon-box-content">
                                    <h6 class="mad-icon-box-title">' . $recRow->title . '</h6>
                                </div>
                            </article>
                            <!--================ End of Icon Box ================-->
                        </div>
                    </div>
                    ';
            }
            else {
                $img = unserialize($recRow->image);
                if (!empty($img) && isset($img[0])) {
                    $file_path = SITE_ROOT . 'images/services/' . $img[0];
                    if (file_exists($file_path)) {
                        $imglink = IMAGE_PATH . 'services/' . $img[0];
                        $facility .= ' 
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="ins-main-list">
                        <img src="' . $imglink . '" alt="' . $recRow->title . '">
                        <div class="ins-names">
                            <h4><a href="facility-details.html">' . $recRow->title . '</a></h4>
                        </div>  
                    </div>
                </div>';
                    }
                }
            }
        }
    }
}

$jVars['module:facility-list'] = $facility;



/*
 * Service Page
 */
$rescont = '';


$rescont .= '';


$subpkgRec = services::find_8();

if (!empty($subpkgRec)) {
    $rescont .= '';
    foreach ($subpkgRec as $k => $v) {
        $img_nm = unserialize($v->image);
        $rescont .= '
                
                            
                            ';
    }
    $rescont .= '';
}

// pr($rescont_left);
$rescont_final = '
                        <!-- detail features starts -->
                        <div class="mad-section mad-section.no-pb">
                        <div class="row justify-content-center">
                            <div class="col-xxl-10">
                                <div class="mad-title-wrap align-center">
                                    <div class="mad-pre-title">The Advantages</div>
                                    <h2 class="mad-section-title">Amenities and Facilities</h2>
                                </div>
                                <!--================ Icon Boxes ================-->
                                <div class="mad-icon-boxes align-center small-size item-col-5">
                                        ' . $rescont . '
                                        </div>
                                        <!--================ End of Icon Boxes ================-->
                                    </div>
                                </div>
                            </div>';
$jVars['module:service-homepage'] = $rescont_final;



$facilityhome = "";

if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = trim($_GET['slug']);
    $recRow = Services::find_by_slugs($slug);

    if ($recRow) {
        $service_slider = '';
        // Fetch services images
        $servicesImages = ServicesImage::find_by_sql("SELECT * FROM tbl_services_images WHERE servicesid='{$recRow->id}' AND status=1 ORDER BY sortorder ASC");

        if (!empty($servicesImages)) {
            foreach ($servicesImages as $serviceImg) {
                $serviceImgPath = SITE_ROOT . 'images/services/servicesimages/' . $serviceImg->image;
                if (file_exists($serviceImgPath)) {
                    $service_slider .= '
                        <div class="col-md-12">
                            <div class="feedback-inner">
                                <img src="' . IMAGE_PATH . 'services/servicesimages/' . $serviceImg->image . '" alt="' . htmlspecialchars($serviceImg->title) . '" />
                            </div>
                        </div>';
                }
            }
        }
        else {
            // Fallback to main image if no gallery images
            if (!empty($recRow->image) && $recRow->image != "a:0:{}") {
                $img = unserialize($recRow->image);
                $file_path = SITE_ROOT . 'images/services/' . $img[0];
                if (file_exists($file_path)) {
                    $service_slider .= '
                        <div class="col-md-12">
                            <div class="feedback-inner">
                                <img src="' . IMAGE_PATH . 'services/' . $img[0] . '" alt="' . htmlspecialchars($recRow->title) . '" />
                            </div>
                        </div>';
                }
            }
        }

        $facilityhome .= '
            <section class="course-detail shape_big2">
                <div class="container">
                    <div class="row pb-5">
                        <div class="col-lg-6">
                            <div class="home-2 testimonial p-0 cs-detail-im">
                                <div class="row review-slider2 wow fadeInUp">
                                    ' . $service_slider . '
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="cs-detail-info d-flex flex-column justify-content-center align-items-start h-100">
                                <h3>' . htmlspecialchars($recRow->title) . '</h3>
                                <div class="customize-bottom">
                                    <ul class="d-flex justify-content-start">
                                        <li class="mr-3">' . htmlspecialchars($recRow->sub_title) . '</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="course-content">
                            ' .$recRow->content. '

                            </div>
                        </div>
                    </div>
                </div>
            </section>';
    }
} else {
    // No slug - show all services list
    $records = Services::find_by_sql("SELECT * FROM tbl_services WHERE status=1 ORDER BY sortorder ASC");
    $service_items = '';
    
    if (!empty($records)) {
        foreach ($records as $rec) {
            $imglink = '';
            if (!empty($rec->image) && $rec->image != "a:0:{}") {
                $img = unserialize($rec->image);
                if (!empty($img[0])) {
                    $file_path = SITE_ROOT . 'images/services/' . $img[0];
                    if (file_exists($file_path)) {
                        $imglink = IMAGE_PATH . 'services/' . $img[0];
                    }
                }
            }
            
            // Fallback to icon image if main image is missing
            if (empty($imglink) && !empty($rec->iconimage) && $rec->iconimage != "a:0:{}") {
                $iconList = unserialize($rec->iconimage);
                if (!empty($iconList[0])) {
                    $file_path = SITE_ROOT . 'images/services/icon/' . $iconList[0];
                    if (file_exists($file_path)) {
                        $imglink = IMAGE_PATH . 'services/icon/' . $iconList[0];
                    }
                }
            }
            
            // Use placeholder if no image found
            if (empty($imglink)) {
                $imglink = IMAGE_PATH . 'placeholder.jpg';
            }
            
            $service_items .= '
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="course-item">
                    <div class="course-img">
                        <a href="' . BASE_URL . 'service_list.php?slug=' . htmlspecialchars($rec->slug) . '">
                            <img src="' . $imglink . '" alt="' . htmlspecialchars($rec->title) . '" class="img-fluid" style="height:250px; width:100%; object-fit:cover;">
                        </a>
                    </div>
                    <div class="course-content p-3 border">
                        <h4><a href="' . BASE_URL . 'service_list.php?slug=' . htmlspecialchars($rec->slug) . '">' . htmlspecialchars($rec->title) . '</a></h4>
                        <p>' . htmlspecialchars($rec->sub_title) . '</p>
                        <a href="' . BASE_URL . 'service_list.php?slug=' . htmlspecialchars($rec->slug) . '" class="btn btn-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>';
        }
    }
    
    $facilityhome .= '
    <section class="services-list py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2>Our Services & Programs</h2>
                </div>
                ' . $service_items . '
            </div>
        </div>
    </section>';
}





$jVars['module:facility-list-home'] = $facilityhome;





/*
* Service Level Detail Page (slug-driven)
*/
$serviceLevelDetail = "";

if (defined('SCHOOL_PAGE') and isset($_REQUEST['slug'])) {
    $slug = addslashes($_REQUEST['slug']);
    $serviceLevel = Services::find_by_sql("SELECT * FROM tbl_services WHERE slug='{$slug}' LIMIT 1");
    if (!empty($serviceLevel)) {
        $service = $serviceLevel[0];

        // Build image slider
        $service_level_slider = '';

        // Fetch services images
        $servicesImages = ServicesImage::find_by_sql("SELECT * FROM tbl_services_images WHERE servicesid='{$service->id}' AND status=1 ORDER BY sortorder ASC");

        if (!empty($servicesImages)) {
            foreach ($servicesImages as $serviceImg) {
                $serviceImgPath = SITE_ROOT . 'images/services/servicesimages/' . $serviceImg->image;
                if (file_exists($serviceImgPath)) {
                    $service_level_slider .= '
                        <div class="col-md-12">
                            <div class="feedback-inner">
                                <img src="' . IMAGE_PATH . 'services/servicesimages/' . $serviceImg->image . '" alt="' . htmlspecialchars($serviceImg->title) . '" />
                            </div>
                        </div>';
                }
            }
        } else {
            // Fallback to main image if no gallery images
            if (!empty($service->image) && $service->image != "a:0:{}") {
                $img = unserialize($service->image);
                $file_path = SITE_ROOT . 'images/services/' . $img[0];
                if (file_exists($file_path)) {
                    $service_level_slider .= '
                        <div class="col-md-12">
                            <div class="feedback-inner">
                                <img src="' . IMAGE_PATH . 'services/' . $img[0] . '" alt="' . htmlspecialchars($service->title) . '" />
                            </div>
                        </div>';
                }
            }
        }

        $serviceLevelDetail .= '

    <section class="breadcrumb-main">
        <div class="container">
            <div class="breadcrumb-inner">
                <h2>' . htmlspecialchars($service->title) . '</h2>
            </div>
        </div>
    </section>
    <section class="course-detail shape_big2">
        <div class="container">
            <div class="row pb-5">
                <div class="col-lg-6">
                    <div class="home-2 testimonial p-0 cs-detail-im">
                        <div class="row review-slider2 wow fadeInUp">
                            ' . $service_level_slider . '
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cs-detail-info d-flex flex-column justify-content-center align-items-start h-100">
                        <h3>' . htmlspecialchars($service->sub_title) . '</h3>
                        <div class="customize-bottom">
                            <ul class="d-flex justify-content-start">
                                <li class="mr-3">' . htmlspecialchars($service->brief) . '</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="course-content">
                            ' . $service->content . '
                    </div>
                </div>
            </div>
        </div>
    </section>';
    }
}

$jVars['module:primary-level'] = $serviceLevelDetail;




