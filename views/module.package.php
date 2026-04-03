<?php
$booking_code = Config::getField('hotel_code', true);

$roomlist = $roombread = $singlepage = '';
$modalpopup = '';
$room_package = '';
$single_more = '';
$roombreads = '';

/* * package listing page - LIST VIEW (no slug) */
if (defined('PACKAGE_PAGE') and !isset($_REQUEST['slug'])) {
    $pkgList = Package::find_all();
    if (!empty($pkgList)) {
        $counter = 0;
        $singlepage = '';
        $single_more = '';

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

                $single = '
                    <!-- single slide -->
                    <div class="col">
                        <div class="ul-service">
                            <div class="ul-service-img">
                                <img src="' . $imglink . '" alt="' . $pkgRow->title . '">
                            </div>
                            <div class="ul-service-txt">
                                <h3 class="ul-service-title"><a href="program-details.html">' . $pkgRow->title . '</a></h3>
                                <p class="ul-service-descr">
                                ' . $pkgRow->sub_title . '</p>
                                <a href="' . BASE_URL . 'program/' . $pkgRow->slug . '" class="ul-service-btn"><i class="flaticon-up-right-arrow"></i> View Details</a>
                            </div>
                        </div>
                    </div>';

                if ($counter < 6) {
                    $singlepage .= $single;
                } else {
                    $single_more .= $single;
                }

                $counter++;
            }
        }

        $roombread .= '
        <section class=" ul-section-spacing overflow-hidden">
            <div class="ul-container">
                <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                    ' . $singlepage . '

                </div>
                
                <span id="dots">...</span>
                <span id="more">
                    <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row mt-4">
                    ' . $single_more . '
                    </div>
                </span>

                <!-- pagination -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="btns-block btns-center">
                            <button onclick="myFunction()" id="myBtn1" class="ul-btn d-sm-inline-flex px-4 mt-4">Load More</button>
                        </div>
                    </div> 
                </div>
            </div>
        </section>

';
    }
    $jVars['module:packages'] = $roombread;
} else {
    $pkgRow = Package::find_by_slug($_REQUEST['slug']);
    $sql = "SELECT *  FROM tbl_package_sub WHERE status='1' AND type = '{$pkgRow->id}' ORDER BY sortorder DESC ";

    $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"])) ? $_REQUEST["pageno"] : 1;
    $limit = 200;
    $total = $db->num_rows($db->query($sql));
    $startpoint = ($page * $limit) - $limit;
    $sql .= " LIMIT " . $startpoint . "," . $limit;
    $query = $db->query($sql);
    $pkgRec = Subpackage::find_by_sql($sql);
    // pr($pkgRec);
    $image = '';

    if (!empty($pkgRec)) {

        foreach ($pkgRec as $key => $subpkgRow) {
            $imageList = '';
            $image1 = '';
            $image2 = '';

            if ($subpkgRow->image != "a:0:{}") {
                $imageList = unserialize($subpkgRow->image);

                if (!empty($imageList)) {
                    // Check for Image 1 (Primary Image - Index 0)
                    if (isset($imageList[0]) && !empty($imageList[0])) {
                        $image1 = IMAGE_PATH . 'subpackage/' . $imageList[0];
                    }

                    // Check for Image 2 (Hover Image - Index 1)
                    if (isset($imageList[1]) && !empty($imageList[1])) {
                        $image2 = IMAGE_PATH . 'subpackage/' . $imageList[1];
                    }
                }

                // default fallback for primary image when none uploaded
                if (empty($image1)) {
                    $image1 = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
                }
            }


            $roomlist .= '
                <div class="col-12 col-md-4">
                    <div class="de-room">
                        <div class="d-image">
                            <div class="d-label">' . $subpkgRow->short_title . '</div>
                            <div class="d-details">
                                <span class="d-meta-1">
                                    <img src="template/web/assets/images/ui/user.svg" alt="">' . $subpkgRow->occupancy . '
                                </span>
                            </div>
                            <a href="' . $subpkgRow->link_a . '">
                                <img src="' . $image1 . '" class="img-fluid" alt="" style="object-fit: cover;  aspect-ratio: 800/533;">
                                <img src="' . $image2 . '" class="d-img-hover img-fluid" alt=""style="object-fit: cover;  aspect-ratio: 800/533;" >
                            </a>
                        </div>
                        
                        <div class="d-text">
                            <h3>' . $subpkgRow->title . '</h3>
                            <p>' . $subpkgRow->content . '</p>
                            
                            <a href="' . $subpkgRow->link_b . '" class="btn-line"><span>Book Now</span></a>
                        </div>
                    </div>
                </div>





                            
       


                
                ';
        }

        $room_package = $roomlist;
    }
}

// Package detail view handling
if (isset($_REQUEST['slug'])) {
    $pkgRow = Package::find_by_slug($_REQUEST['slug']);
    $siteRegulars = Config::find_by_id(1);
    $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    $pkgRowImg = $pkgRow->banner_image;
    if (!empty($pkgRowImg) && $pkgRowImg != "a:0:{}") {
        $pkgRowList = unserialize($pkgRowImg);
        $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
        if (file_exists($file_path) and !empty($pkgRowList[0])) {
            $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
        } else {
            $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
        }
    }

    //         $roombread .= '
    //         <div class="banner-header section-padding valign bg-img bg-darkbrown1">
    //         <div class="container">
    //             <div class="row">
    //                 <div class="col-md-12 text-center caption mt-90">
    //                     <h1>' . $pkgRow->title . '</h1>
    //                 </div>
    //             </div>
    //         </div>
    //     </div>
    // ';

    $sql = "SELECT *  FROM tbl_package_sub WHERE status='1' AND type = '{$pkgRow->id}' ORDER BY sortorder DESC ";

    $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"])) ? $_REQUEST["pageno"] : 1;
    $limit = 200;
    $total = $db->num_rows($db->query($sql));
    $startpoint = ($page * $limit) - $limit;
    $sql .= " LIMIT " . $startpoint . "," . $limit;
    $query = $db->query($sql);
    $pkgRec = Subpackage::find_by_sql($sql);
    // pr($pkgRec);
    $image = '';

    if (!empty($pkgRec)) {

        foreach ($pkgRec as $key => $subpkgRow) {
            $imageList = '';
            $image = '';
            if ($subpkgRow->image != "a:0:{}") {
                $imageList = unserialize($subpkgRow->image);
                if (!empty($imageList)) {
                    $image .= '<img src="' . IMAGE_PATH . 'subpackage/' . $imageList[0] . '" alt="" >';
                }
            }
            // pr($subpkgRow);

            $roomlist .= '
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <div class="img"><a href="' . BASE_URL . '' . $subpkgRow->slug . '">' . $image . '</a></div>
                        <div class="desc">
                            <div class="name"><a href="' . BASE_URL . '' . $subpkgRow->slug . '">' . $subpkgRow->title . '</a></div>
';
            $itineraryInfos = Itinerary::get_itinerarylimit($subpkgRow->id);
            if (!empty($itineraryInfos)) {
                $roomlist .= '<ul class="list-unstyled list">';
                foreach ($itineraryInfos as $itineraryInfo) {
                    $roomlist .= '<li><i class="ti-check"></i>' . $itineraryInfo->title . '</li>';
                }
                $roomlist .= '</ul>';
            }



            $roomlist .= '
                              </div>
                    </div>
                </div>


                
                ';
        }

        $room_package = '
              <section class="pricing section-padding">
        <div class="container">
            <div class="row">
                    ' . $roomlist . '
                   </div>
        </div>
    </section>';
    }
} else {
    $siteRegulars = Config::find_by_id(1);
    $imglink = ($siteRegulars) ? IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload : '';
    $pkgRowImg = !empty($pkgRow) ? $pkgRow->banner_image : '';
    if (!empty($pkgRowImg) && $pkgRowImg != "a:0:{}") {
        $pkgRowList = unserialize($pkgRowImg);
        $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
        if (file_exists($file_path) and !empty($pkgRowList[0])) {
            $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
        } else {
            $imglink = ($siteRegulars) ? IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload : '';
        }
    }


    // <div id="background" data-bgimage="url(' . $imglink . ') fixed"></div>  


    $roombreads .= '
        <div id="background" data-bgimage="url(' . $imglink . ') fixed"></div>';

    $jVars['module:imagebanner'] = $roombreads;

    $roombread .= '


            <section id="subheader" class="no-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>' . (!empty($pkgRow) ? $pkgRow->title : '') . '</h1>
                </div>
            </div>
        </div>
    </section>
';

    $pkgRowId = !empty($pkgRow) ? $pkgRow->id : 0;
    $sql = "SELECT *  FROM tbl_package_sub WHERE status='1' AND type = '{$pkgRowId}' ORDER BY sortorder DESC ";

    $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"])) ? $_REQUEST["pageno"] : 1;
    $limit = 200;
    $total = $db->num_rows($db->query($sql));
    $startpoint = ($page * $limit) - $limit;
    $sql .= " LIMIT " . $startpoint . "," . $limit;
    $query = $db->query($sql);
    $pkgRec = Subpackage::find_by_sql($sql);

    // pr($pkgRec);

    if (!empty($pkgRec)) {

        $count = 1;


        $max_count = count($subpkgRec);

        foreach ($pkgRec as $key => $subpkgRow) {
            $gallRec = SubPackageImage::getImagelimit_by(100, $subpkgRow->id);
            $subpkg_caro = '';
            foreach ($gallRec as $row) {
                $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                if (file_exists($file_path) and !empty($row->image)):

                    // $active=($count==0)?'active':'';
                    $subpkg_caro .= '
                    <div class="mad-owl-item">
                                        <img src="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '" alt="' . $row->title . '" />
                                    </div>

                     
                            
                                ';


                endif;
            }

            $button = '';
            $modal = '';
            $imageList = '';
            $image1 = '';
            $image2 = '';

            if ($subpkgRow->image != "a:0:{}") {
                $imageList = unserialize($subpkgRow->image);

                if (!empty($imageList)) {
                    // Check for Image 1 (Primary Image - Index 0)
                    if (isset($imageList[0]) && !empty($imageList[0])) {
                        $image1 = IMAGE_PATH . 'subpackage/' . $imageList[0];
                    }

                    // Check for Image 2 (Hover Image - Index 1)
                    if (isset($imageList[1]) && !empty($imageList[1])) {
                        $image2 = IMAGE_PATH . 'subpackage/' . $imageList[1];
                    }
                }

                // default fallback for primary image when none uploaded
                if (empty($image1)) {
                    $image1 = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
                }
            }


            $st = '';
            if (!empty($subpkgRow->short_title)) {
                $st = '<div class="d-label">' . $subpkgRow->short_title . '</div>';
            }

            $roomlist .= '
                <div class="col-12 col-md-4">

                    <div class="de-room">
                        <div class="d-image">
                            ' . $st . '
                            <a href="' . $subpkgRow->explorelinksrc . '">
                                <img src="' . $image1 . '" style="aspect-ratio: 3/2;" class="img-fluid" alt="">
                                <img src="' . $image2 . '" style="aspect-ratio: 3/2;" class="d-img-hover img-fluid" alt="">
                            </a>
                        </div>
                        
                        <div class="d-text">
                            <h3> ' . $subpkgRow->title . '</h3>
                            <p>' . $subpkgRow->content . '</p>
                            <a href="' . $subpkgRow->explorelinksrc . '" class="btn-line"><span>Explore Now</span></a>
                        </div>
                    </div>
                </div>

                ';




            $room_package =

                '<div class="row">' . $roomlist . '</div>
';
        }
    }

    // Package Itinerary FAQ for package detail page
    $pkg_itinerary_faq = '';
    if (!empty($pkgRow)) {
        $packageItineraryInfos = Package::get_itinerary($pkgRow->id);
        if (!empty($packageItineraryInfos)) {
            $faqItems = '';
            foreach ($packageItineraryInfos as $i => $iti) {
                $collapseId = 'pkgItiFaq' . ($i + 1);
                $expandedAttr = '';
                $btnClass = ' collapsed';
                $faqItems .= '
        <div class="accordion-item border-top ' . ($i === count($packageItineraryInfos) - 1 ? 'border-bottom' : 'border-bottom-0') . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . htmlspecialchars($iti->title) .
                    '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse ' . $expandedAttr . '" data-bs-parent="#pkgFaqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $iti->content . '</div>
            </div>
        </div>';
            }

            $pkg_itinerary_faq = '
    <section class="m-property-details py-5 bg-white">
        <div class="container">
            <h2 class="h5 fw-bold mb-4 title">Itinerary</h2>
            <div class="accordion accordion-flush" id="pkgFaqAccordion">
                ' . $faqItems . '
            </div>
        </div>
    </section>';

            $roombread .= $pkg_itinerary_faq;
        }
    }

    $jVars['module:package-itinerary-faq'] = $pkg_itinerary_faq;
}

/* * package homepage listing
 ' . BASE_URL . 'result.php?hotel_code=' . $booking_code . ' */
$homeroomdetail = '';
if (defined('HOME_PAGE')) {
    $homeroomdetail = '';
    $roompkg = Package::find_by_sql("SELECT id FROM tbl_package WHERE status=1 AND type=1");
    if (!empty($roompkg)) {
        $pkgids = array();
        foreach ($roompkg as $rp) {
            $pkgids[] = $rp->id;
        }
        $idstr = implode(',', $pkgids);
        $sql = "SELECT * FROM tbl_package_sub WHERE status='1' AND homepage='1' AND type IN ($idstr) ORDER BY sortorder DESC ";

        $pkgRec = Subpackage::find_by_sql($sql);
        $room_list_html = '';
        if (!empty($pkgRec)) {
            foreach ($pkgRec as $subpkgRow) {
                $imgpath = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
                if (!empty($subpkgRow->image2)) {
                    $imgpath = IMAGE_PATH . 'subpackage/image/' . $subpkgRow->image2;
                } elseif ($subpkgRow->image != "a:0:{}") {
                    $imageList = unserialize($subpkgRow->image);
                    if (!empty($imageList[0])) {
                        $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                    }
                }

                $gallRec = SubPackageImage::getImagelist_by($subpkgRow->id);
                $gallery_images = array();
                if (!empty($gallRec)) {
                    foreach ($gallRec as $row) {
                        $gallery_images[] = IMAGE_PATH . 'package/galleryimages/' . $row->image;
                    }
                }
                if (empty($gallery_images)) {
                    $gallery_images[] = $imgpath;
                }
                $data_images_attr = htmlspecialchars(json_encode($gallery_images), ENT_QUOTES, 'UTF-8');

                $room_list_html .= '

                  <div class="swiper-slide">
                            <div class="m-room-slide-card">
                                <div class="m-room-slide-img">
                                    <img src="' . $imgpath . '" alt="' . $subpkgRow->title . '">
                                    <button class="m-room-gallery-btn m-room-zoom-btn"
                                        data-room-name="' . $subpkgRow->title . '"
                                        data-room-link="' . BASE_URL . 'room/' . $subpkgRow->slug . '"
                                        data-images="' . $data_images_attr . '">
                                        <i class="fa-solid fa-expand"></i>
                                    </button>
                                </div>
                                <div class="m-room-slide-body">
                                    <a href="' . BASE_URL . 'room/' . $subpkgRow->slug . '" class="m-room-slide-title">' . $subpkgRow->title . ' <i
                                            class="fa-solid fa-chevron-right"
                                            style="font-size: 13px; margin-left: 4px;"></i></a>
                                    <div class="m-room-slide-footer">
                                        <a href="' . BASE_URL . 'room/' . $subpkgRow->slug . '" class="m-view-more">View More</a>
                                        <a href="#" class="m-room-slide-btn">View Rates</a>
                                    </div>
                                </div>
                            </div>
                        </div>

';
            }
        }

        $pRow = $roompkg[0];
        $room_package = '

                <section class="m-rooms wow animate__fadeInUp">
            <div class="m-rooms-header">
                <h2 class="m-section-title">Rooms &amp; Suites</h2>
            </div>
            <div class="m-rooms-slider-container">
                <!-- Mobile Navigation (Arrows + Fraction) -->
                <div class="m-rooms-nav-mob d-flex d-lg-none justify-content-between align-items-center mb-4 px-2">
                    <div class="m-rooms-prev-mob cursor-pointer d-flex align-items-center justify-content-center">
                        <span class="m-rooms-line-mob m-rooms-line-prev-mob"></span>
                    </div>
                    <div class="m-rooms-pagination-mob" style="font-size: 12px; font-weight: 500; letter-spacing: 2px;">
                        01 / 06</div>
                    <div class="m-rooms-next-mob cursor-pointer d-flex align-items-center justify-content-center">
                        <span class="m-rooms-line-mob m-rooms-line-next-mob"></span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="m-rooms-nav-wrapper d-none d-lg-flex">
                    <div class="m-rooms-pagination"></div>
                    <div class="m-rooms-nav d-flex align-items-center" style="gap: 100px;">
                        <div class="m-rooms-prev cursor-pointer d-flex align-items-center "
                            style="font-size: 14px; gap: 8px;">
                            Prev
                            <span class="mgr-line"></span>
                        </div>
                        <div class="m-rooms-next cursor-pointer d-flex align-items-center "
                            style="font-size: 14px; gap: 8px;">
                            <span class="mgr-line"></span>
                            Next
                        </div>
                    </div>
                </div>
                <div class="swiper m-rooms-swiper">
                    <div class="swiper-wrapper">
                       ' . $room_list_html . '
                    </div>
                </div>
            </div>
        </section>


';
    }
}


$jVars['module:list-modalpop-up'] = $modalpopup;
$jVars['module:list-room-detail'] = $homeroomdetail;
$jVars['module:list-package-room'] = $room_package;
$jVars['module:list-package-room-bred'] = $roombread;

/* * Rooms Page - Overview Section */

// Fetch overview items from tbl_package incexc field
$overview_items_html = '';
$overviewPkg = Package::find_by_sql("SELECT incexc FROM tbl_package WHERE status=1 AND type=1 LIMIT 1");

if (!empty($overviewPkg)) {
    foreach ($overviewPkg as $pkg) {
        if (!empty($pkg->incexc)) {
            $includesList = unserialize($pkg->incexc);
            if (!empty($includesList) && is_array($includesList)) {
                foreach ($includesList as $item) {
                    if (!empty($item)) {
                        // Handle both old format (string) and new format (array)
                        $itemText = is_array($item) ? $item['text'] : $item;
                        $itemUrl = is_array($item) ? (!empty($item['url']) ? $item['url'] : '') : '';
                        $linktarget = ($item['linktype'] == '1') ? ' target="_blank"' : '';

                        $learnMoreLink = '';
                        if (!empty($itemUrl)) {
                            $learnMoreLink = '<a href="' . htmlspecialchars($itemUrl) . '"' . $linktarget . '
                                    class="text-dark fw-bold text-decoration-underline">Learn More</a>';
                        }

                        $overview_items_html .= '
                    <div class="m-overview-item-new">
                        <p>' . $itemText . ' ' . $learnMoreLink . '</p>
                    </div>';
                    }
                }
            }
        }
    }
}

$overview_section = '
        <section class="m-overview-new wow animate__fadeInUp">
            <div class="container container-custom">
                <div class="m-overview-header text-center">
                    <p class="m-overview-label-new">WELCOME TO LUMBINI PALACE RESORT</p>
                    <div class="m-overview-divider-red"></div>
                    <h2 class="m-overview-title-main">Escape to spacious Lumbini hotel <br> rooms</h2>
                </div>

                <div class="m-overview-grid-new mt-5 collapsed" id="overviewGrid">
                    ' . $overview_items_html . '
                </div>

                <div class="text-start mt-4">
                    <a href="#" class="m-overview-see-more" id="seeMoreBtn">See More</a>
                    <a href="#" class="m-overview-see-less d-none" id="seeLessBtn">See Less</a>
                </div>
            </div>
        </section>

';

$jVars['module:rooms-overview'] = $overview_section;

/* * Rooms Page - Overview Section */

// Fetch overview items from tbl_package incexc field
$overview_items_html = '';
$overviewPkg = Package::find_by_sql("SELECT incexc FROM tbl_package WHERE status=1 AND type=3 LIMIT 1");

if (!empty($overviewPkg)) {
    foreach ($overviewPkg as $pkg) {
        if (!empty($pkg->incexc)) {
            $includesList = unserialize($pkg->incexc);
            if (!empty($includesList) && is_array($includesList)) {
                foreach ($includesList as $item) {
                    if (!empty($item)) {
                        // Handle both old format (string) and new format (array)
                        $itemText = is_array($item) ? $item['text'] : $item;
                        $itemUrl = is_array($item) ? (!empty($item['url']) ? $item['url'] : '') : '';
                        $linktarget = ($item['linktype'] == '1') ? ' target="_blank"' : '';

                        $learnMoreLink = '';
                        if (!empty($itemUrl)) {
                            $learnMoreLink = '<a href="' . htmlspecialchars($itemUrl) . '"' . $linktarget . '
                                    class="text-dark fw-bold text-decoration-underline">Learn More</a>';
                        }

                        $overview_items_html .= '
                    <div class="col-md-4">
                        <div class="h-100 ps-3 py-1 m-meeting-feature-border">
                            <p class="mb-0 text-muted font-secondary m-meeting-feature-text">' . $itemText . ' ' . $learnMoreLink . '</p>
                        </div>
                    </div>



                        ';
                    }
                }
            }
        }
    }
}

$overview_section = '

        <section class="m-meeting-overview bg-white pb-5">
            <div class="container">
                <!-- Meeting Info Text -->
                <div class="text-center pb-2 mx-auto m-meeting-info-wrap">
                    ' . $jVars['module:event-content1'] . '
                </div>
                <div class="text-center pb-4">
                    <button class="btn m-btn-book-table">Learn More</button>
                </div>
                <!-- 3 Columns with Red Borders -->
                <div class="row gx-4 gx-lg-5 gy-4 align-items-stretch pb-3">
                    ' . $overview_items_html . '
                </div>

                <div class="text-center mt-3 mb-4">
                    <a href="#" class="text-muted text-decoration-none font-secondary m-meeting-see-more"><span
                            class="text-decoration-underline ul-underline-offset">See More</span></a>
                </div>
            </div>
        </section>



        <section class="m-overview-new wow animate__fadeInUp">
            <div class="container container-custom">
                <div class="m-overview-header text-center">
                    <p class="m-overview-label-new">WELCOME TO LUMBINI PALACE RESORT</p>
                    <div class="m-overview-divider-red"></div>
                    <h2 class="m-overview-title-main">Escape to spacious Lumbini hotel <br> rooms</h2>
                </div>

                <div class="m-overview-grid-new mt-5 collapsed" id="overviewGrid">
                    ' . $overview_items_html . '
                </div>

                <div class="text-start mt-4">
                    <a href="#" class="m-overview-see-more" id="seeMoreBtn">See More</a>
                    <a href="#" class="m-overview-see-less d-none" id="seeLessBtn">See Less</a>
                </div>
            </div>
        </section>

';

$jVars['module:rooms-overview'] = $overview_section;

// Experience Banner
$exp_banner = $siteRegulars = '';
$siteRegulars = Config::find_by_id(1);
$pkgExp = Package::find_by_sql("SELECT title, banner_image FROM tbl_package WHERE status=1 AND type=2 LIMIT 1");
if (!empty($pkgExp)) {
    $pkg = $pkgExp[0];
    $imglink = ($siteRegulars) ? IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload : '';
    if (!empty($pkg->banner_image) && $pkg->banner_image != "a:0:{}") {
        $pkgRowList = unserialize($pkg->banner_image);
        if (!empty($pkgRowList[0])) {
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            }
        }
    }

    $exp_banner = '
        <section class="marriott-style-banner">
        <div class="ul-banner-slider swiper" style="width:100%;height:100%;">
            <div class="ul-banner-slide marriott-slide-image" data-img="' . $imglink . '">
            </div>
        </div>
    </section>
';
}
$jVars['module:exp-banner'] = $exp_banner;

//Accomodation Banner
$room_banner = $siteRegulars = '';
$siteRegulars = Config::find_by_id(1);
$pkgroom = Package::find_by_sql("SELECT title, banner_image FROM tbl_package WHERE status=1 AND type=1 LIMIT 1");
if (!empty($pkgroom)) {
    $pkg = $pkgroom[0];
    $imglink = ($siteRegulars) ? IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload : '';
    if (!empty($pkg->banner_image) && $pkg->banner_image != "a:0:{}") {
        $pkgRowList = unserialize($pkg->banner_image);
        if (!empty($pkgRowList[0])) {
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            }
        }
    }

    $room_banner = '
        <section class="marriott-style-banner">
        <div class="ul-banner-slider swiper" style="width:100%;height:100%;">
            <div class="ul-banner-slide marriott-slide-image" data-img="' . $imglink . '">
            </div>
        </div>
    </section>
';
}
$jVars['module:room-banner'] = $room_banner;



//DINING
$dining_banner = $siteRegulars = '';
$siteRegulars = Config::find_by_id(1);
$pkgExp = Package::find_by_sql("SELECT title, banner_image FROM tbl_package WHERE status=1 AND type=0 LIMIT 1");
if (!empty($pkgExp)) {
    $pkg = $pkgExp[0];
    $imglink = ($siteRegulars) ? IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload : '';
    if (!empty($pkg->banner_image) && $pkg->banner_image != "a:0:{}") {
        $pkgRowList = unserialize($pkg->banner_image);
        if (!empty($pkgRowList[0])) {
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            }
        }
    }

    $dining_banner = '
        <section class="marriott-style-banner">
        <div class="ul-banner-slider swiper" style="width:100%;height:100%;">
            <div class="ul-banner-slide marriott-slide-image" data-img="' . $imglink . '">
            </div>
        </div>
    </section>
';
}
$jVars['module:dine-banner'] = $dining_banner;


// Event Banner & Stats
$event_banner = '';
$pkg = null;
$pkgExp = Package::find_by_sql("SELECT * FROM tbl_package WHERE status=1 AND type=3 LIMIT 1");
if (!empty($pkgExp)) {
    $pkg = $pkgExp[0];
    $siteRegulars = Config::find_by_id(1);
    $imglink = ($siteRegulars) ? IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload : '';

    if (!empty($pkg->banner_image) && $pkg->banner_image != "a:0:{}") {
        $pkgRowList = unserialize($pkg->banner_image);
        if (!empty($pkgRowList[0])) {
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            }
        }
    }

    $event_banner = '
    <section class="marriott-style-banner">
        <div class="ul-banner-slider swiper" style="width:100%;height:100%;">
            <div class="ul-banner-slide marriott-slide-image" data-img="' . $imglink . '"></div>
        </div>
    </section>';

    $jVars['module:eventroom'] = !empty($pkg->events_room) ? $pkg->events_room : '';
    $jVars['module:eventspace'] = !empty($pkg->total_event_space) ? $pkg->total_event_space : '';
    $jVars['module:eventcapacity'] = !empty($pkg->capacity_largest_space) ? $pkg->capacity_largest_space : '';
    $jVars['module:breakoutrooms'] = !empty($pkg->breakout_rooms) ? $pkg->breakout_rooms : '';
    $jVars['module:event-banner'] = $event_banner;
    $jVars['module:event-content'] = !empty($pkg->content) ? $pkg->content : '';
    $jVars['module:event-content1'] = !empty($pkg->content1) ? $pkg->content1 : '';
    $jVars['module:event-content2'] = !empty($pkg->content2) ? $pkg->content2 : '';
    $jVars['module:event-content3'] = !empty($pkg->content3) ? $pkg->content3 : '';
    $jVars['module:event-content4'] = !empty($pkg->content4) ? $pkg->content4 : '';
    $jVars['module:event-content5'] = !empty($pkg->content5) ? $pkg->content5 : '';
}


//experience
$overview_items_html2 = $local = '';
$overviewPkg = Package::find_by_sql("SELECT incexc FROM tbl_package WHERE status=1 AND type=2 LIMIT 1");

if (!empty($overviewPkg)) {
    foreach ($overviewPkg as $pkg) {
        if (!empty($pkg->incexc)) {
            $includesList = unserialize($pkg->incexc);
            if (!empty($includesList) && is_array($includesList)) {
                foreach ($includesList as $item) {
                    if (!empty($item)) {
                        // Handle both old format (string) and new format (array)
                        $itemText = is_array($item) ? $item['text'] : $item;
                        $itemUrl = is_array($item) ? (!empty($item['url']) ? $item['url'] : '') : '';
                        $linktarget = (is_array($item) && $item['linktype'] == '1') ? ' target="_blank" rel="noopener noreferrer"' : '';

                        $learnMoreLink = '';
                        if (!empty($itemUrl)) {
                            $learnMoreLink = '<a href="' . htmlspecialchars($itemUrl) . '"' . $linktarget . '
                                    class="text-dark fw-bold text-decoration-underline">Learn More</a>';
                        }

                        $overview_items_html2 .= '
                    <div class="col-md-4">
                        <div class="m-attraction-text-block h-100">
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">' . $itemText . $learnMoreLink . '</p>
                        </div>
                    </div>';
                    }
                }
            }
        }
    }
}

$overview_section2 = '
        <section class="m-local-attractions-text py-5 bg-white mt-4">
            <div class="container">
                <div class="row g-5">
                 ' . $overview_items_html2 . '
                </div>
            </div>
        </section>

';
$jVars['module:local1'] = $overview_section2;

// Step 1: Collect items from DB
$localme = Package::find_by_sql("SELECT incexc1 FROM tbl_package WHERE status=1 AND type=2 LIMIT 1");

$cardsHtml = '';

if (!empty($localme)) {
    foreach ($localme as $pkg1) {

        if (!empty($pkg1->incexc1)) {
            $includesList = unserialize($pkg1->incexc1);

            if (!empty($includesList) && is_array($includesList)) {

                foreach ($includesList as $item) {

                    if (!empty($item)) {

                        // Handle both formats
                        $text = is_array($item) ? htmlspecialchars($item['text']) : htmlspecialchars($item);
                        $subtitle = is_array($item) && !empty($item['subtitle']) ? htmlspecialchars($item['subtitle']) : '';
                        $href = is_array($item) && !empty($item['url']) ? htmlspecialchars($item['url']) : '#';
                        $target = (is_array($item) && !empty($item['linktype']) && $item['linktype'] == '1')
                            ? ' target="_blank" rel="noopener noreferrer"' : '';

                        $cardsHtml .= '
                            <div class="col-md-6 col-lg-4">
                                <a href="' . $href . '"' . $target . '
                                    class="m-attraction-card d-block p-4 bg-white rounded-3 shadow-sm text-decoration-none h-100 transition-all">
                                    <h3 class="h6 fw-bold mb-2 font-primary d-flex align-items-center gap-2"
                                        style="font-size: 0.9rem; color: #4a4a4a;">
                                         ' . $text . '
                                        <i class="bi bi-arrow-up-right" style="font-size: 0.70rem; color: #1c1c1c;"></i>
                                    </h3>
                                    <p class="text-muted small mb-0 font-secondary"
                                        style="font-size: 0.75rem; line-height: 1.6;">' . $subtitle . '</p>
                                </a>
                            </div>
                        ';
                    }
                }
            }
        }
    }
}

// Final Section
$local = '
<section class="m-local-attractions-cards py-5 bg-white pb-5 mb-3">
    <div class="container">
        <h2 class="h6 font-secondary fw-bold text-center mb-5 text-uppercase"
            style="letter-spacing: 0.5px; color: #1c1c1c;">LOCAL ATTRACTIONS</h2>

        <div class="row g-4">
            ' . $cardsHtml . '
        </div>
    </div>
</section>
';

$jVars['module:local'] = $local;

// MORE WAYS TO ENJOY YOUR STAY - Dynamic Experience Tabs
$enjoy_stay_tabs = '';
$enjoy_stay_content = '';
$enjoy_stay_section = '';
$roompkg = Package::find_by_sql("SELECT id FROM tbl_package WHERE status=1 AND type=2");
if (!empty($roompkg)) {
    $pkgids = array();
    foreach ($roompkg as $rp) {
        $pkgids[] = $rp->id;
    }
    $idstr = implode(',', $pkgids);
    $sql = "SELECT * FROM tbl_package_sub WHERE status='1' AND type IN ($idstr) ORDER BY sortorder ASC LIMIT 6";
    $pkgRec = Subpackage::find_by_sql($sql);

    if (!empty($pkgRec)) {
        $tab_counter = 0;
        foreach ($pkgRec as $expRow) {
            $tab_id = 'enjoy-exp-' . $expRow->id;
            $active_class = ($tab_counter === 0) ? 'active' : '';

            // Tab button
            $enjoy_stay_tabs .= '<button class="tab-nav ' . $active_class . '" data-tab="' . $tab_id . '">' . htmlspecialchars($expRow->title) . '</button>';

            // Tab content
            $imgpath = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
            if (!empty($expRow->image2)) {
                $imgpath = IMAGE_PATH . 'subpackage/image/' . $expRow->image2;
            } elseif ($expRow->image != "a:0:{}") {
                $imageList = unserialize($expRow->image);
                if (!empty($imageList[0])) {
                    $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                }
            }

            $content_text = !empty($expRow->sub_title) ? $expRow->sub_title : $expRow->detail;
            $content_text = substr($content_text, 0, 150) . (strlen($content_text) > 150 ? '...' : '');

            $enjoy_stay_content .= '
                    <div class="ul-tab ' . ($tab_counter === 0 ? 'active' : '') . '" id="' . $tab_id . '">
                        <div class="m-enjoy-card">
                            <div class="m-enjoy-card-img"><img src="' . $imgpath . '" alt="' . htmlspecialchars($expRow->title) . '"></div>
                            <div class="m-enjoy-card-body">
                                <p class="m-card-label">Restro &amp; Bar</p>
                                <h3 class="m-card-title">' . htmlspecialchars($expRow->title) . '</h3>
                                <p class="m-card-text">Discover the freshest flavors at our organic restaurant, where
                                    farm-to-table ingredients meet culinary excellence.</p>
                                <a href="' . BASE_URL . 'experiences/' . $expRow->slug . '" class="m-card-link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>';

            $tab_counter++;
        }

        $enjoy_stay_section = '

        <section class="m-enjoy-stay wow animate__fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
            <div class="m-enjoy-stay-inner">
                <div class="m-enjoy-stay-header">
                    <h2 class="m-enjoy-stay-title">More Ways to Enjoy Your Stay</h2>
                </div>
                <div class="m-enjoy-tabs">
                ' . $enjoy_stay_tabs . '
                </div>
                <div class="m-enjoy-content">
                    ' . $enjoy_stay_content . '
                </div>
            </div>
        </section>
';
    }
}

$jVars['module:enjoy-stay'] = $enjoy_stay_section;

$experience_list = $experience = '';
$total_experiences = 0;
$included_experiences = 0;
$roompkg = Package::find_by_sql("SELECT id FROM tbl_package WHERE status=1 AND type=2");
if (!empty($roompkg)) {
    $pkgids = array();
    foreach ($roompkg as $rp) {
        $pkgids[] = $rp->id;
    }
    $idstr = implode(',', $pkgids);
    $sql = "SELECT * FROM tbl_package_sub WHERE status='1' AND type IN ($idstr) ORDER BY sortorder DESC ";
    $pkgRec = Subpackage::find_by_sql($sql);

    if (!empty($pkgRec)) {
        $total_experiences = count($pkgRec);
        foreach ($pkgRec as $subpkgRow) {
            if (!empty($subpkgRow->image2)) {
                $imgpath = IMAGE_PATH . 'subpackage/image/' . $subpkgRow->image2;
            } elseif ($subpkgRow->image != "a:0:{}") {
                $imageList = unserialize($subpkgRow->image);
                if (!empty($imageList[0])) {
                    $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                }
            }

            $occupancy = !empty($subpkgRow->occupancy) ? $subpkgRow->occupancy : '';
            $size = !empty($subpkgRow->size) ? $subpkgRow->size : '';
            $short_desc = $occupancy . ' | ' . $size;

            $included_badge = '';
            if (!empty($subpkgRow->included) && $subpkgRow->included == 1) {
                $included_badge = '<div class="m-outlet-badge"><i class="fa-regular fa-circle-check"></i> Included</div>';
                $included_experiences++;
            }

            $experience_list .= '
                    <div class="swiper-slide">
                        <div class="m-outlet-card">
                        ' . $included_badge . '
                            <div class="m-outlet-img-wrap">
                                <img src="' . $imgpath . '" alt="' . $subpkgRow->title . '"
                                    class="img-fluid w-100 h-100 object-fit-cover" style="filter: brightness(0.85);">
                            </div>
                            <div class="m-outlet-content">
                                <h3 class="m-outlet-name">' . $subpkgRow->title . '</h3>
                                <a href="' . BASE_URL . 'experiences/' . $subpkgRow->slug . '" class="btn m-btn-learn-more">Learn More +</a>
                            </div>
                        </div>
                    </div>
';
        }
    }
}

$experience = '
<section class="m-outlets-section pb-5 pt-4 overflow-hidden">
    <div class="container container-custom">
        <div class="m-outlets-header mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
            <h2 class="m-outlets-title mb-0">On-Site Outlets</h2>
            <div class="m-outlets-stats d-flex gap-4">
                <span class="m-outlets-stat-item">total experiences (' . $total_experiences . ')</span>
                <span class="m-outlets-stat-item"><i class="fa-regular fa-circle-check"></i> included
                    experiences (' . $included_experiences . ')</span>
            </div>
        </div>

        <div class="m-outlets-slider-wrapper position-relative">
            <div class="swiper m-outlets-swiper">
                <div class="swiper-wrapper">
                    <!-- Outlet Card 1 -->
                    ' . $experience_list . '


                </div>

                <div class="m-outlets-controls mt-4 d-flex justify-content-center align-items-center gap-3">
                    <button class="m-outlets-prev"><i class="fa-solid fa-chevron-left me-2"></i>
                        Previous</button>
                    <div class="m-outlets-pagination swiper-pagination-bullets"></div>
                    <button class="m-outlets-next">Next <i class="fa-solid fa-chevron-right ms-2"></i></button>
                </div>
            </div>

        </div>
    </div>
</section>


';
$jVars['module:all-exp-list'] = $experience;




/* * Rooms Page - All Rooms List */
$all_rooms_list = '';
$accessible_rooms_list = '';
$roompkg = Package::find_by_sql("SELECT id FROM tbl_package WHERE status=1 AND type=1");
if (!empty($roompkg)) {
    $pkgids = array();
    foreach ($roompkg as $rp) {
        $pkgids[] = $rp->id;
    }
    $idstr = implode(',', $pkgids);
    $sql = "SELECT * FROM tbl_package_sub WHERE status='1' AND type IN ($idstr) ORDER BY sortorder DESC ";
    $pkgRec = Subpackage::find_by_sql($sql);

    if (!empty($pkgRec)) {
        foreach ($pkgRec as $subpkgRow) {
            $imgpath = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
            if (!empty($subpkgRow->image2)) {
                $imgpath = IMAGE_PATH . 'subpackage/image/' . $subpkgRow->image2;
            } elseif ($subpkgRow->image != "a:0:{}") {
                $imageList = unserialize($subpkgRow->image);
                if (!empty($imageList[0])) {
                    $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                }
            }

            $capacity = !empty($subpkgRow->capacity) ? $subpkgRow->capacity : '';
            $room_size = !empty($subpkgRow->room_size) ? $subpkgRow->room_size : '';
            $short_desc = $capacity . ' | ' . $room_size;

            $room_card_html = '
                                <div class="col-md-6">
                                    <div class="m-room-card-new">
                                        <div class="m-room-image-wrap">
                                            <img src="' . $imgpath . '" alt="' . $subpkgRow->title . '" class="img-fluid">
                                            <button class="m-room-zoom-btn"><i class="fa-solid fa-expand"></i></button>
                                        </div>
                                        <div class="m-room-details">
                                            <h3 class="m-room-name">' . $subpkgRow->title . '</h3>
                                            <p class="m-room-bed">' . $short_desc . '</p>
                                            <div class="m-room-divider-v2"></div>
                                            <div class="m-room-footer">
                                                <a href="' . BASE_URL . 'room/' . $subpkgRow->slug . '" class="m-view-more">View More</a>
                                               <a href="#" class="m-room-slide-btn">View Rates</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

            $all_rooms_list .= $room_card_html;
            if ($subpkgRow->accessible_rooms == 1) {
                $accessible_rooms_list .= $room_card_html;
            }
        }
    }
}
$jVars['module:all-rooms-list'] = $all_rooms_list;
$jVars['module:accessible-rooms-list'] = $accessible_rooms_list;


/* * Sub package detail */
$resubpkgDetail = '';
$subimg = '';
$imageList = '';

if ((defined('SUBPACKAGE_PAGE') || defined('EXPERIENCE_PAGE') || defined('ROOM_PAGE')) and isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
    $subpkgRec = Subpackage::find_by_slug($slug);

    if (!empty($subpkgRec)) {
        $gallRec = SubPackageImage::getImagelist_by($subpkgRec->id);



        // New gallery for Swiper in room_details.html
        $subpkg_swiper_gallery = '';
        if (!empty($gallRec)) {
            foreach ($gallRec as $row) {
                $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                if (file_exists($file_path) and !empty($row->image)) {
                    $img_url = IMAGE_PATH . 'package/galleryimages/' . $row->image;
                    $subpkg_swiper_gallery .= '
            <div class="swiper-slide">
                <div class="ul-banner-slide marriott-slide-image" data-img="' . $img_url . '"></div>
            </div>';
                }
            }
        }
        if (empty($subpkg_swiper_gallery)) {
            if (!empty($subpkgRec->image)) {
                $img_url = IMAGE_PATH . 'subpackage/' . $subpkgRec->image;
            } else {
                $siteRegulars = Config::find_by_id(1);
                $img_url = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
            }
            $subpkg_swiper_gallery = '
    <div class="swiper-slide">
        <div class="ul-banner-slide marriott-slide-image" data-img="' . $img_url . '"></div>
    </div>';
        }
        $jVars['module:sub-package-swiper-gallery'] = $subpkg_swiper_gallery;

        // Granular features for room_details.html
        $subpkg_features_list = '';
        if (!empty($subpkgRec->feature)) {
            $ftRec = unserialize($subpkgRec->feature);
            if (!empty($ftRec)) {
                foreach ($ftRec as $k => $v) {
                    if (empty($v[1]))
                        continue;
                    $feattitle = !empty($v[0][0]) ? $v[0][0] : 'Amenities';
                    $feature_items = '';
                    foreach ($v[1] as $kk => $vv) {
                        $sfetname = Features::find_by_id($vv);
                        if ($sfetname) {
                            $feature_items .= '<li>' . $sfetname->title . '</li>';
                        }
                    }
                    $subpkg_features_list .= '
            <div class="spec-item">
                <div class="spec-item-title">' . $feattitle . '</div>
                <ul class="spec-list">
                    ' . $feature_items . '
                </ul>
            </div>';
                }
            }
        }
        $jVars['module:sub-package-features-list'] = $subpkg_features_list;

        // Itinerary FAQ for room_details.html
        $subpkg_itinerary_faq = '';
        $itineraryInfos = Itinerary::get_itinerary($subpkgRec->id);
        if (!empty($itineraryInfos)) {
            $faqItems = '';
            foreach ($itineraryInfos as $i => $iti) {
                $collapseId = 'itiFaq' . ($i + 1);
                $expandedAttr = '';
                $btnClass = ' collapsed';
                $faqItems .= '
        <div class="accordion-item border-top ' . ($i === count($itineraryInfos) - 1 ? 'border-bottom' : 'border-bottom-0') . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . htmlspecialchars($iti->title) .
                    '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse ' . $expandedAttr . '" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $iti->content . '</div>
            </div>
        </div>';
            }

            $subpkg_itinerary_faq = '
    <section class="m-property-details py-5 bg-white">
        <div class="container">
            <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
            <div class="accordion accordion-flush" id="faqAccordion">
                ' . $faqItems . '
            </div>
        </div>
    </section>';
        }
        $jVars['module:sub-package-itinerary-faq'] = $subpkg_itinerary_faq;
        $jVars['module:sub-package-detail'] = $resubpkgDetail;
        $jVars['module:sub-package-title'] = $subpkgRec->title;
        $jVars['module:sub-package-subtitle'] = $subpkgRec->sub_title;
        $jVars['module:sub-package-content'] = $subpkgRec->content;
        $jVars['module:sub-package-brief'] = $subpkgRec->detail;
    } // end if !empty($subpkgRec)
} // end if defined page
