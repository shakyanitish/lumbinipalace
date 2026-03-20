<?php
/* * Programs list - Dynamic carousel on homepage */

$programsContent = '';

// Get all active programs (packages with type = 0 for programs)
$programsRec = Package::getHomePackage();

if (!empty($programsRec)) {
    $programsHtml = '';

    foreach ($programsRec as $program) {
        // Get main image
        $imglink = '';
        if (!empty($program->banner_image) && $program->banner_image != "a:0:{}") {
            $imageList = unserialize($program->banner_image);
            if (!empty($imageList[0])) {
                $file_path = SITE_ROOT . 'images/package/banner/' . $imageList[0];
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'package/banner/' . $imageList[0];
                }
            }
        }

        // Fallback to default image if no banner image
        if (empty($imglink)) {
            $siteRegulars = Config::find_by_id(1);
            $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
        }

        // Build program link
        $programLink = BASE_URL . 'program/' . $program->slug;

        // Get title
        $title = !empty($program->title) ? $program->title : 'Program';

        // Get description from brief or sub_title
        $description = $program->sub_title ?? '';



        $programsHtml .= '
                        <!-- single slide -->
                        <div class="swiper-slide">
                            <div class="ul-service">
                                <div class="ul-service-img">
                                    <img src="' . $imglink . '" alt="' . $title . '">
                                </div>
                                <div class="ul-service-txt">
                                    <h3 class="ul-service-title"><a href="' . BASE_URL . 'program/' . $program->slug . '">' . $title . '</a></h3>
                                    <p class="ul-service-descr">' . $description . '</p>
                                    <a href="' . BASE_URL . 'program/' . $program->slug . '" class="ul-service-btn"><i class="flaticon-up-right-arrow"></i> View Details</a>
                                </div>
                            </div>
                        </div>';
    }

    $programsContent = '
        <section class="ul-section-spacing overflow-hidden">
            <div class="ul-container">
                <div class="ul-section-heading">
                    <div>
                        <span class="ul-section-sub-title">Together we can change lives forever</span>
                        <h2 class="ul-section-title">Our Programs</h2>
                    </div>

                    <div class="ul-services-slider-nav ul-slider-nav position-static">
                        <button class="prev"><i class="flaticon-back"></i></button>
                        <button class="next"><i class="flaticon-next"></i></button>
                    </div>
                </div>

                <div class="ul-services-slider swiper overflow-visible">
                    <div class="swiper-wrapper">
                        ' . $programsHtml . '
                    </div>
                </div>
            </div>
        </section>';
}

$jVars['module:programlist'] = $programsContent;


$booking_code = Config::getField('hotel_code', true);

$roomlist = $roombread = $singlepage = '';
$modalpopup = '';
$room_package = '';
$single_more = '';

/* * package listing page - LIST VIEW (no slug) */
if (defined('PACKAGE_PAGE') and !isset($_REQUEST['slug'])) {
    $pkgList = Package::find_all();
    if (!empty($pkgList)) {
        $counter = 0;
        $singlepage = '';
        $single_more = '';
        $single = '';

        foreach ($pkgList as $pkgRow) {
            $siteRegulars = Config::find_by_id(1);
            if ($pkgRow->type == 0 && $pkgRow->status == 1) {
                $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
                $pkgRowImg = $pkgRow->banner_image;

                if ($pkgRowImg != "a:0:{}") {
                    $pkgRowList = unserialize($pkgRowImg);
                    $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
                    if (file_exists($file_path) and !empty($pkgRowList[0])) {
                        $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
                    }
                }

                $single .= '

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="ins-main-list">
                        <img src="' . $imglink . '" alt="' . $pkgRow->title . '">
                        <div class="ins-names">
                            <h4><a href="' . BASE_URL . 'facilities/' . $pkgRow->slug . '">' . $pkgRow->title . '</a></h4>
                        </div>
                    </div>
                </div>

';

            }
        }

        $roombread .= '

    <section class="instructors">
        <div class="container">
            <div class="row instruct-main">
            '. $single .'
            </div>
        </div>
    </section>


';
    }
    $jVars['module:package'] = $roombread;
}


/* * Program Detail Page */
$program_detail = $program_detail_title = '';
if (defined("PACKAGE_DETAIL_PAGE") && isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? $_REQUEST['slug'] : '';
    $Package = Package::find_by_slug($slug);

    if (!empty($Package)) {
        // Breadcrumb title
        $program_detail_title = '
    <section class="breadcrumb-main">
        <div class="container">
            <div class="breadcrumb-inner">
                <h2>' . $Package->title . '</h2>
            </div>
        </div>
    </section>

        ';
        $jVars['module:program-detail-title'] = $program_detail_title;

        // Get all subpackages related to this package
        $subpackages = Subpackage::getPackage_limit($Package->id);

        // Generate tab buttons and tab content dynamically
        $tab_buttons = '';
        $tab_panels = '';
        $tab_count = 0;

        if (!empty($subpackages)) {
            foreach ($subpackages as $subpkg) {
                $tab_count++;
                $tab_id = 'tab' . $tab_count;
                $active_class = ($tab_count == 1) ? ' active' : '';

                // Tab button
                $tab_buttons .= '
                            <button class="tab-btn' . $active_class . '" data-tab="' . $tab_id . '">' . $subpkg->title . '</button>';

                // Get gallery images for this subpackage
                $subpkg_sliders = '';
                $subpkgGalleryImages = SubPackageImage::getImagelist_by($subpkg->id);

                if (!empty($subpkgGalleryImages)) {
                    foreach ($subpkgGalleryImages as $galleryImage) {
                        $file_path = SITE_ROOT . 'images/package/galleryimages/' . $galleryImage->image;
                        if (file_exists($file_path) && !empty($galleryImage->image)) {
                            $img_path = IMAGE_PATH . 'package/galleryimages/' . $galleryImage->image;
                            $subpkg_sliders .= '
                                    <!-- single slide -->
                                    <div class="swiper-slide">
                                        <div class="ul-event-details-img">
                                            <img src="' . $img_path . '" alt="' . $subpkg->title . '">
                                        </div>
                                    </div>';
                        }
                    }
                }

                // Fallback to subpackage main image
                if (empty($subpkg_sliders) && !empty($subpkg->image)) {
                    $file_path = SITE_ROOT . 'images/subpackage/' . $subpkg->image;
                    if (file_exists($file_path)) {
                        $img_path = IMAGE_PATH . 'subpackage/' . $subpkg->image;
                        $subpkg_sliders = '
                                    <!-- single slide -->
                                    <div class="swiper-slide">
                                        <div class="ul-event-details-img">
                                            <img src="' . $img_path . '" alt="' . $subpkg->title . '">
                                        </div>
                                    </div>';
                    }
                }

                // Tab panel content
                $tab_panels .= '
                        <div id="' . $tab_id . '" class="tab-panel' . $active_class . '">
                            ' . (!empty($subpkg_sliders) ? '
                            <div class="ul-testimonial-2-slider swiper">
                                <div class="swiper-wrapper">
                                    ' . $subpkg_sliders . '
                                </div>
                            </div>' : '') . '
                    
                            <h2 class="ul-event-details-title">' . $subpkg->title . '</h2>
                            ' . $subpkg->content . '
                        </div>';
            }
        }

        // Get package gallery images
        $packageImages = '';
        $packageGalleryImages = SubPackageImage::getImagelist_by($Package->id);
        
        if (!empty($packageGalleryImages)) {
            foreach ($packageGalleryImages as $galleryImage) {
                $file_path = SITE_ROOT . 'images/package/galleryimages/' . $galleryImage->image;
                if (file_exists($file_path) && !empty($galleryImage->image)) {
                    $img_path = IMAGE_PATH . 'package/galleryimages/' . $galleryImage->image;
                    $packageImages .= '
                        <div class="col-md-12">
                            <div class="feedback-inner">
                                <img src="' . $img_path . '" alt="' . $Package->title . '" />
                            </div>
                        </div>';
                }
            }
        }
        
        // Fallback to banner image if no gallery images
        if (empty($packageImages) && !empty($Package->banner_image) && $Package->banner_image != "a:0:{}") {
            $imageList = unserialize($Package->banner_image);
            if (!empty($imageList[0])) {
                $file_path = SITE_ROOT . 'images/package/banner/' . $imageList[0];
                if (file_exists($file_path)) {
                    $img_path = IMAGE_PATH . 'package/banner/' . $imageList[0];
                    $packageImages = '
                        <div class="col-md-12">
                            <div class="feedback-inner">
                                <img src="' . $img_path . '" alt="' . $Package->title . '" />
                            </div>
                        </div>';
                }
            }
        }

        $program_detail = '


    <section class="event-detail-cn">
        <div class="container">
            <div class="ev-detail-info d-flex flex-column justify-content-center align-items-center text-center h-100">
                <div class="home-2 testimonial p-0 ev-image">
                    <div class="row review-slider3 feedback-main wow fadeInUp">
                        ' . $packageImages . '
                    </div>
              </div>
            </div>
            <div class="ev-detail-content">
                <div class="evt__section">
                ' . $Package->content . '
                </div>
            </div>
        </div>
    </section>
';

        $jVars['module:program-detail'] = $program_detail;
    }
}
