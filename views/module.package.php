<?php
$booking_code = Config::getField('hotel_code', true);

$roomlist = $roombread = $singlepage = '';
$modalpopup = '';
$room_package = '';
$single_more = '';

/*
* package listing page - LIST VIEW (no slug)
*/
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
                                    <img src="template/web/assets/images/ui/user.svg" alt="">' . $subpkgRow->occupancy  . '
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

        $room_package =  $roomlist;
    }
}

// Package detail view handling
if (isset($_REQUEST['slug'])) {
    $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    $pkgRowImg = $pkgRow->banner_image;
    if ($pkgRowImg != "a:0:{}") {
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
    $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    $pkgRowImg = $pkgRow->banner_image;
    if ($pkgRowImg != "a:0:{}") {
        $pkgRowList = unserialize($pkgRowImg);
        $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
        if (file_exists($file_path) and !empty($pkgRowList[0])) {
            $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
        } else {
            $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
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
                    <h1>' . $pkgRow->title . '</h1>
                </div>
            </div>
        </div>
    </section>
';

    $sql = "SELECT *  FROM tbl_package_sub WHERE status='1' AND type = '{$pkgRow->id}' ORDER BY sortorder DESC ";

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
            $gallRec = SubPackageImage::getImagelimit_by(3, $subpkgRow->id);
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

            //  <div class="ul-project-info">
            //                                 <span class="icon"><i class="fa-light fa-timer"></i></span>
            //                                 <span class="text">' . $subpkgRow->theatre_style. '</span>
            //                             </div>


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

/*
* package homepage listing

' . BASE_URL . 'result.php?hotel_code=' . $booking_code . '
*/
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

                $room_list_html .= '
                <div class="swiper-slide">
                    <div class="m-room-slide-card">
                        <div class="m-room-slide-img">
                            <img src="' . $imgpath . '" alt="' . $subpkgRow->title . '">
                            <a href="' . $imgpath . '" class="m-room-gallery-btn"><i class="fa-solid fa-expand"></i></a>
                        </div>
                        <div class="m-room-slide-body">
                            <a href="' . BASE_URL . 'room/' . $subpkgRow->slug . '" class="m-room-slide-title">' . $subpkgRow->title . ' <i class="fa-solid fa-chevron-right" style="font-size: 13px; margin-left: 4px;"></i></a>
                            <div class="m-room-slide-footer">
                                <a href="' . BASE_URL . 'room/' . $subpkgRow->slug . '" class="m-view-more">View More</a>
                                <a href="#" class="m-room-slide-btn">View Rates</a>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        $pRow = $roompkg[0];
        $room_package = '
        <section class="m-rooms wow animate__fadeInUp">
            <div class="m-rooms-header">
                <h2 class="m-section-title">Rooms &amp; Suites</h2>
            </div>
            <div class="m-rooms-slider-container">
                <div class="m-rooms-nav-wrapper">
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
        </section>';
    }
}


$jVars['module:list-modalpop-up'] = $modalpopup;
$jVars['module:list-room-detail'] = $homeroomdetail;
$jVars['module:list-package-room'] = $room_package;
$jVars['module:list-package-room-bred'] = $roombread;

/*
* Rooms Page - Overview Section
*/

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
                        $overview_items_html .= '
                    <div class="m-overview-item-new">
                        <p>' . htmlspecialchars($item) . '</p>
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

/*
* Rooms Page - All Rooms List
*/
$all_rooms_list = '';
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
            
            $occupancy = !empty($subpkgRow->occupancy) ? $subpkgRow->occupancy : '2 adult';
            $size = !empty($subpkgRow->size) ? $subpkgRow->size : '100 sq.ft.';
            $short_desc = $occupancy . ' | ' . $size;

            $all_rooms_list .= '
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
        }
    }
}
$jVars['module:all-rooms-list'] = $all_rooms_list;


/*
* Sub package detail
*/
$resubpkgDetail = '';
$subimg = '';
$imageList = '';

if (defined('SUBPACKAGE_PAGE') and isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
    $subpkgRec = Subpackage::find_by_slug($slug);
    $gallRec = SubPackageImage::getImagelist_by($subpkgRec->id);

    $booking_code = Config::getField('hotel_code', true);
    if (!empty($subpkgRec)) {
        $pkhdata = Package::find_by_id($subpkgRec->type);
        // pr($pkhdata);
        if ($pkhdata->type == 1) {
            $relPacs = Subpackage::get_relatedpkg(1, $subpkgRec->id, 12);
            $imglink = '';
            if (!empty($subpkgRec->image2)) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $subpkgRec->image2;
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'subpackage/image/' . $subpkgRec->image2;
                } else {
                    $imglink = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
                }
            } else {
                $imglink = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
            }

            $pkgRec = Package::find_by_id($subpkgRec->type);
            $subpkg_carousel = '';
            if (!empty($gallRec)) {
                $subpkg_carousel .= '  <div class="owl-carousel owl-theme">';
                foreach ($gallRec as $row) {
                    $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                    if (file_exists($file_path) and !empty($row->image)):
                        $subpkg_carousel .= '
                        <div class="text-center item bg-img" data-overlay-dark="3" data-background="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '"></div>
                                
                          ';
                    endif;
                }


                $subpkg_carousel .= '       </div>
';
            }


            $resubpkgDetail .= '
            <header class="header slider">
        ' . $subpkg_carousel . '
        <!-- arrow down -->
        <div class="arrow bounce text-center">
            <a href="#" data-scroll-nav="1" class=""> <i class="ti-arrow-down"></i> </a>
        </div>
    </header>
        <!-- BREADCRUMB SECTION END -->

           
            ';


            $resubpkgDetail .= '
            <section class="rooms-page section-padding" data-scroll-index="1">
        <div class="container">
            <!-- project content -->
            <div class="row">
                <div class="col-md-12"> 
                    <div class="section-title">' . $subpkgRec->title . '</div>
                </div>
                
                <div class="col-lg-8 col-md-12">
                   ' . $subpkgRec->content . '
                    <div class="col-md-12">   
                            <div class="butn-light butn-dark mb-3"><a href="' . BASE_URL . 'result.php?hotel_code=' . $booking_code . '" style="background-color: #2b2f33;" target="_blank"><span>Book Now</span></a> </div>
                        </div>
                </div>




                ';


            if (!empty($subpkgRec->feature)) {
                $ftRec = unserialize($subpkgRec->feature);
                if (!empty($ftRec)) {
                    $resubpkgDetail .= '
                                   <div class="col-lg-3 offset-lg-1 col-md-12">
                               
          ';


                    $resubpkgDetail .= '        
                                        
                                        ';
                    foreach ($ftRec as $k => $v) {
                        // pr($ftRec);
                        if (empty($v[1])) {
                            continue; // Skip if no feature IDs
                        }
                        // pr($v);
                        $feattitle = !empty($v[0][0]) ? $v[0][0] : 'Room Amenities';
                        $resubpkgDetail .= '
                        <h6>' . $feattitle . '</h6>
                         ';
                        if (!empty($v[1])) {
                            $sfetname = '';
                            $i = 0;
                            $resubpkgDetail .= '';
                            $feature_list = '';
                            foreach ($v[1] as $kk => $vv) {
                                $sfetname = Features::find_by_id($vv);
                                if (!empty($sfetname->image)) {
                                    $feature_list .= '
                                    <li>
                            <div class="page-list-icon"> <img src="' . BASE_URL . 'images/features/' . $sfetname->image . '" title="' . $sfetname->title . '"> </div>
                            <div class="page-list-text">
                                <p>' . $sfetname->title . '</p>
                            </div>
                        </li>';
                                } else {

                                    $feature_list .= '
                                    <li>
                            <div class="page-list-icon"> <i class="' . $sfetname->icon . '" title="' . $sfetname->title . '"></i> </div>
                            <div class="page-list-text">
                                <p>' . $sfetname->title . '</p>
                            </div>
                        </li>';
                                }
                                $i++;
                                if (($i % 123123123123 == 0) || (end($v[1]) == $vv)) {
                                    $resubpkgDetail .= '<ul class="list-unstyled page-list mb-30">
                                                                ' . $feature_list . '
                                                         </ul>
                                                            ';
                                    $feature_list = '';
                                }
                            }
                        }
                    }
                }
                $resubpkgDetail .= '
                </div>';
            }
            $resubpkgDetail .= ' </div>
        </div>
        </div>
    </section>
';

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

                $resubpkgDetail .= '
                <section class="m-property-details py-5 bg-white">
                    <div class="container">
                        <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                        <div class="accordion accordion-flush" id="faqAccordion">
                            ' . $faqItems . '
                        </div>
                    </div>
                </section>';
            }


            $resubpkgDetail .= '
              <div class="line-vr-section"></div>
              
              
              ';


            $otherroom = '';
            $rooms = Subpackage::get_relatedsub_by($subpkgRec->type, $subpkgRec->id);
            // pr($rooms);


            if (!empty($rooms)) {


                foreach ($rooms as $room) {
                    if (!empty($room->image)) {
                        $img123 = unserialize($room->image);

                        if (file_exists($file_path) && !empty($img123[0])) {
                            $imglink = IMAGE_PATH . 'subpackage/' . $img123[0];
                            $file_path = SITE_ROOT . 'images/subpackage/' . $img123[0];
                        } else {
                            $imglink = IMAGE_PATH . 'static/static.jpg';
                        }
                    } else {
                        $imglink = IMAGE_PATH . 'static/static.jpg';
                    }


                    $otherroom .= '
                    <div class="item">
                            <div class="position-re o-hidden"> <img src="' . $imglink . '" alt=""> </div> <span class="category"><a href="' . BASE_URL . 'result.php?hotel_code=' . $booking_code . '">Book Now</a></span>
                            <div class="con">
                                <h6><a href="' . BASE_URL . $room->slug . '">' . $room->currency . '' . $room->onep_price . ' / Night</a></h6>
                                <h5><a href="' . BASE_URL . $room->slug . '">' . $room->title . '</a></h5>
                                <div class="row facilities">
                                    <div class="col col-md-7">
                                    ';
                    if (!empty($room->feature)) {
                        // pr($subpkgRec->feature);
                        $ftRec = unserialize($room->feature);
                        if (!empty($ftRec)) {


                            foreach ($ftRec as $k => $v) {
                                if (empty($v[1])) {
                                    continue; // Skip if no feature IDs
                                }

                                if (!empty($v[1])) {
                                    $sfetname = '';
                                    $i = 0;
                                    $otherroom .= '';
                                    $feature_list = '';

                                    $max_features = 4; // show only 3
                                    $count = 0;

                                    foreach ($v[1] as $kk => $vv) {
                                        if ($count >= $max_features) {
                                            break; // stop after 3 features
                                        }

                                        $sfetname = Features::find_by_id($vv);
                                        if (!empty($sfetname->image)) {
                                            $feature_list .= '
                                            <li><img src="' . BASE_URL . 'images/features/' . $sfetname->image . '" alt="wifi" title="' . $sfetname->title . '"></li>
            
                        ';
                                        } else {
                                            $feature_list .= '
                                            <li><i class="' . $sfetname->icon . '" title="' . $sfetname->title . '"></i></li>';
                                        }

                                        $i++;
                                        $count++;

                                        if (($i % 12312312312 == 0) || (end($v[1]) == $vv) || ($count == $max_features)) {
                                            $otherroom .= '
                                            <ul>
                            ' . $feature_list . '
                             </ul>
                        ';
                                            $feature_list = '';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $otherroom .= '     
                                    </div>
                                    <div class="col col-md-5 text-end">
                                        <div class="permalink"><a href="' . BASE_URL . $room->slug . '">Details <i class="ti-arrow-right"></i></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
                // pr($otherroom);
                $resubpkgDetail .= '
                        <section class="rooms1 section-padding bg-darkbrown" data-scroll-index="2">
        <div class="container-fluid px-5">
            <div class="row">
                <div class="col-md-12 mb-30 text-center">
                    <div class="section-subtitle">Other Rooms</div>
                    <div class="section-title">Rooms & Suites</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme">
                                   ' . $otherroom . '
                                   </div>
                </div>
            </div>
        </div>
    </section>
    
    ';
            }

            $resubpkgDetail .= ' <section class="reservation">
        <div class="background bg-img bg-fixed section-padding" data-background="' . BASE_URL . 'template/web/img/slider/8.jpg" data-overlay-dark="5">
            <div class="container">
                <div class="row">
                    <!-- Reservation -->
                    <div class="col-lg-5 col-md-12 mb-30 mt-30">
                        <h5>Each of our guest rooms feature a private bath, wi-fi, cable television and include full breakfast.</h5>
                        <div class="reservations">
                            <div class="icon color-1"><span class="flaticon-call"></span></div>
                            <div class="text">
                               <p class="color-1 text-white">Reserve Now</p> <a class="color-1" href="https://wa.me/' . $siteRegulars->whatsapp_a . '">' . $siteRegulars->whatsapp_a . '</a>
                            </div>
                        </div>
                    </div>
                    <!-- Booking From -->
                    <div class="col-lg-5 offset-lg-2 col-md-12">
                        <div class="booking-box">
                            <div class="head-box text-center">
                                <h4>Luxury Stay Reservation</h4>
                            </div>
                            <div class="booking-inner clearfix">
                                <form action="' . BASE_URL . 'result.php" class="form1 clearfix" target="_blank">
                                <input type="hidden" name="hotel_code" value="' . $booking_code . '"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input1_wrapper">
                                                <label>Check in</label>
                                                <div class="input1_inner">
                                                    <input type="text" class="form-control input datepicker" name="hotel_check_in" id="checkin" placeholder="Check in">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input1_wrapper">
                                                <label>Check out</label>
                                                <div class="input1_inner">
                                                    <input type="text" class="form-control input datepicker" name="hotel_check_out" id="checkout" placeholder="Check out">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn-form1-submit mt-15">Check Availability</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>';
        }
        /********For service inner page ***************/
        elseif ($subpkgRec->type == 6) {








            $relPacs = Subpackage::get_relatedpkg(1, $subpkgRec->id, 12);
            $imglink = '';
            if (!empty($subpkgRec->image2)) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $subpkgRec->image2;
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'subpackage/image/' . $subpkgRec->image2;
                } else {
                    $imglink = IMAGE_PATH . 'static/default.jpg';
                }
            } else {
                $imglink = IMAGE_PATH . 'static/default.jpg';
            }
            $gallRec = SubPackageImage::getImagelist_by($subpkgRec->id);
            $subpkg_carousel = '';
            if (!empty($gallRec)) {
                foreach ($gallRec as $row) {
                    $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                    if (file_exists($file_path) and !empty($row->image)):
                        $subpkg_carousel .= '
                         <div class="text-center item bg-img" data-overlay-dark="3" data-background="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '"></div>
                                
                          ';
                    endif;
                }
            }

            $resubpkgDetail .= '
             <header class="header slider">
        <div class="owl-carousel owl-theme">
           ' . $subpkg_carousel . '
        </div>
        <!-- arrow down -->
        <div class="arrow bounce text-center">
            <a href="#" data-scroll-nav="1" class=""> <i class="ti-arrow-down"></i> </a>
        </div>
    </header>

           
            ';
            $resubpkgDetail .= '
              <section class="rooms-page section-padding" data-scroll-index="1">
        <div class="container">
            <!-- project content -->
            <div class="row">
                <div class="col-md-12"> 
                    <div class="section-title">Decisions That Shape Tomorrow</div>
                </div>
                <div class="col-lg-8 col-md-12">
                   ' . $subpkgRec->content . '

  <h6>Occupancy & Setup Style</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">
                                        <img src="' . BASE_URL . 'template/web/img/icons/area.png" alt="Hall Size" style="filter: invert(0%) sepia(100%) saturate(0%) hue-rotate(10deg) brightness(103%) contrast(103%);">
                                        <span>Hall Size</span>
                                    </th>
                                    <th scope="col">
                                        <img src="' . BASE_URL . 'template/web/img/icons/ushape.png" alt="U Shape">
                                        <span>U Shape</span>
                                    </th>
                                    <th scope="col">
                                        <img src="' . BASE_URL . 'template/web/img/icons/classroom.png" alt="Classroom">
                                        <span>Classroom</span>
                                    </th>
                                    <th scope="col">
                                        <img src="' . BASE_URL . 'template/web/img/icons/theatre.png" alt="Theatre">
                                        <span>Theatre</span>
                                    </th>
                                    <th scope="col">
                                        <img src="' . BASE_URL . 'template/web/img/icons/rounded.png" alt="Round Table">
                                        <span>Round Table</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>' . (!empty($subpkgRec->size) ? $subpkgRec->size : 'N/A') . '</strong></td>
                                    <td><strong>' . (!empty($subpkgRec->shape) ? $subpkgRec->shape : 'N/A')  . '	</strong></td>
                                    <td><strong>' . (!empty($subpkgRec->class_room_style) ? $subpkgRec->class_room_style : 'N/A') . '	</strong></td>
                                    <td><strong>' . (!empty($subpkgRec->theatre_style) ? $subpkgRec->theatre_style : 'N/A') . '	</strong></td>
                                    <td><strong>' . (!empty($subpkgRec->cocktail) ? $subpkgRec->cocktail : 'N/A')   . '  </strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="butn-light butn-dark mt-3 mb-3"><a href="https://wa.me/+9779851405139" target="_blank" style="background-color: #2b2f33;"><span>Whatsapp For Enquiry</span></a> </div>
             
</div>
             
';
            if (!empty($subpkgRec->feature)) {
                $ftRec = unserialize($subpkgRec->feature);
                if (!empty($ftRec)) {
                    $resubpkgDetail .= '
                                  <div class="col-lg-3 offset-lg-1 col-md-12">
                               
          ';


                    $resubpkgDetail .= '        
                                        
                                        ';
                    foreach ($ftRec as $k => $v) {
                        if (empty($v[1])) {
                            continue; // Skip if no feature IDs
                        }
                        if (!isset($v[0][0])) {
                            $feattitle = $v[0][0];
                        } else {
                            $feattitle = 'Amenities';
                        }
                        $resubpkgDetail .= '
                        <h6>' . $feattitle . '</h6>
                         ';
                        if (!empty($v[1])) {
                            $sfetname = '';
                            $i = 0;
                            $resubpkgDetail .= '';
                            $feature_list = '';
                            foreach ($v[1] as $kk => $vv) {
                                $sfetname = Features::find_by_id($vv);
                                if (!empty($sfetname->image)) {
                                    $feature_list .= '
                                     <li>
                            <div class="page-list-icon"> <img src="' . BASE_URL . 'images/features/' . $sfetname->image . '" title="' . $sfetname->title . '"></div>
                            <div class="page-list-text">
                                <p>' . $sfetname->title . '</p>
                            </div>
                        </li>';
                                } else {

                                    $feature_list .= '
                                    <li>
                            <div class="page-list-icon"><i class="' . $sfetname->icon . '" title="' . $sfetname->title . '"></i></div>
                            <div class="page-list-text">
                                <p>' . $sfetname->title . '</p>
                            </div>
                        </li>';
                                }
                                $i++;
                                if (($i % 123123123123 == 0) || (end($v[1]) == $vv)) {
                                    $resubpkgDetail .= ' <ul class="list-unstyled page-list mb-30">
                                                                ' . $feature_list . '
                                                        </ul>
                                                            ';
                                    $feature_list = '';
                                }
                            }
                        }
                        $resubpkgDetail .= '
                 
                            </div>';
                    }
                }
                $resubpkgDetail .= '
                ';
            }



            $resubpkgDetail .= '   </div>
        </div>
    </section>';
            $resubpkgDetail .= '   <section class="reservation">
        <div class="background bg-img bg-fixed section-padding" data-background="' . BASE_URL . 'template/web/img/slider/8.jpg" data-overlay-dark="5">
            <div class="container">
                <div class="row">
                    <!-- Booking From -->
                    <div class="col-lg-10 offset-lg-1 col-md-12">
                        <div class="booking-box event-booking-box">
                            <div class="head-box text-center">
                                <h4>Transform your special moments into unforgettable experiences with our expert event planning team.</h4>
                            </div>
                            <div class="booking-inner clearfix">
                                <form action="#" id="contactform" class="clearfix">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                            <input type="hidden" value="' . $subpkgRec->title . '" name="hallname"/>
                                                <div class="col-md-12 form-group">
                                                    <input name="name" type="text" placeholder="Full Name *" required>
                                                    
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <input name="email" type="email" placeholder="Email Address *" required>
                                                    
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <input name="phone" type="text" placeholder="Phone Number *" required>
                                                    
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <input name="Address" type="text" placeholder="Address *" required>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <input name="eventname" type="text" placeholder="Event Name *" required>
                                                    
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <select name="schedule"  class="form-select" aria-label="Default select example">
                                                      <option value="" selected>Schedule Slot</option>
                                                      <option value="Morning">Morning</option>
                                                      <option value="Day">Day</option>
                                                      <option value="Afternoon">Afternoon</option>
                                                    </select>
                                                    
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <input  name="eventdate" class=" input datepicker date-count" placeholder="Event Date" id="eventdate">
                                                    
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <input name="eventpax" type="text" placeholder="Pax *" required>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!--<div class="col-md-12 form-group">-->
                                            <!-- reCAPTCHA widget -->
                                         
                                        <!--</div>-->

                                        <div class="col-md-12 form-group">
                                            <textarea name="message" id="message" cols="30" rows="4" placeholder="Special Request *" required></textarea>
                                            
                                        </div>
                                        <div id="result_msg"></div>
                                        <div class="g-recaptcha" data-sitekey="6LeVBaIrAAAAALMY6DrLboZqrjelsfi-ho56pDBk" style="margin-top:12px;"></div>

                                        <div class="col-md-3">
                                            <button type="submit" id="submit" class="btn-form1-submit mt-15">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> ';

            $otherroom = '';
            $rooms = Subpackage::get_relatedsub_by($subpkgRec->type, $subpkgRec->id);


            if (!empty($rooms)) {


                foreach ($rooms as $room) {
                    if (!empty($room->image)) {
                        $img123 = unserialize($room->image);

                        if (file_exists($file_path) && !empty($img123[0])) {
                            $imglink = IMAGE_PATH . 'subpackage/' . $img123[0];
                            $file_path = SITE_ROOT . 'images/subpackage/' . $img123[0];
                        } else {
                            $imglink = IMAGE_PATH . 'static/static.jpg';
                        }
                    } else {
                        $imglink = IMAGE_PATH . 'static/static.jpg';
                    }


                    $otherroom .= '
                 <div class="col wow animate__fadeInUp">
                                <div class="ul-project">
                                    <div class="ul-project-img"><img src="' . $imglink . '" alt="' . $room->title . '"></div>
                                    <div class="ul-project-txt">
                                        <div class="top">
                                            <div class="left">
                                                <a href="' . BASE_URL . '' . $room->slug . '" class="ul-project-title">' . $room->title . '</a>
                                                <p class="ul-project-location">' . $room->detail . '</p>
                                            </div>
                                        </div>
                                        <!-- bottom -->
                                        <div class="ul-project-infos">
                                            <div class="ul-project-info">
                                                <span class="icon"><i class="fa-light fa-user"></i></span>
                                                <span class="text">' . $room->occupancy . '</span>
                                            </div>
                                            <div class="ul-project-info">
                                                <span class="icon"><i class="flaticon-scale"></i></span>
                                                <span class="text">' . $room->size . '</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


			';
                }
                $resubpkgDetail .= '
                        <div class="ul-inner-page-container d-none">
                <div class="ul-inner-page-content-wrapper">
                    <h3 class="ul-project-details-title text-center mb-5">Other Halls</h3>
                    <!-- project cards grid -->
                    <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                                   ' . $otherroom . '
                                   </div>
                </div>
            </div>
        </div>';
            }
        } elseif ($subpkgRec->type == 8) {








            $relPacs = Subpackage::get_relatedpkg(1, $subpkgRec->id, 12);
            $imglink = '';
            if (!empty($subpkgRec->image2)) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $subpkgRec->image2;
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'subpackage/image/' . $subpkgRec->image2;
                } else {
                    $imglink = IMAGE_PATH . 'static/default.jpg';
                }
            } else {
                $imglink = IMAGE_PATH . 'static/default.jpg';
            }
            $gallRec = SubPackageImage::getImagelist_by($subpkgRec->id);
            $subpkg_carousel = '';
            if (!empty($gallRec)) {
                foreach ($gallRec as $row) {
                    $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                    if (file_exists($file_path) and !empty($row->image)):
                        $subpkg_carousel .= '
                         <div class="text-center item bg-img" data-overlay-dark="3" data-background="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '"></div>
                                
                          ';
                    endif;
                }
            }

            $resubpkgDetail .= '
             <header class="header slider">
        <div class="owl-carousel owl-theme">
           ' . $subpkg_carousel . '
        </div>
        <!-- arrow down -->
        <div class="arrow bounce text-center">
            <a href="#" data-scroll-nav="1" class=""> <i class="ti-arrow-down"></i> </a>
        </div>
    </header>

           
            ';
            $resubpkgDetail .= '
             <section class="rooms-page section-padding" data-scroll-index="1">
        <div class="container">
            <!-- project content -->
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">Hiking</div>
                    <p class="mb-30">
                    ' . $subpkgRec->content . '</p>
                </div>

                <div class="col-md-12 text-center">
                    <div class="row justify-content-md-center">
                        <div class="col-md-2 pricing-card  mt-30" style="background: transparent;">
                            <div class="amount">' . $subpkgRec->currency .  $subpkgRec->onep_price . '<span>/ person</span></div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn-form1-submit activity-btn mt-15" href="https://wa.me/' . $siteRegulars->whatsapp_a . '" data-bs-toggle="modal" data-bs-target="#exampleModalactivities">Enquiry now</a>
                        </div>
                    </div>
                </div> 
            </div>

       ';
            $resubpkgDetail .= '
<div class="row">
    <div class="col-md-12 text-center"><h4 class="mt-30">Our Itinerary</h4></div>
';

            $itineraryInfos = Itinerary::get_itinerary($subpkgRec->id);
            if (!empty($itineraryInfos)) {
                $count = 0;
                foreach ($itineraryInfos as $itineraryInfo) {
                    // Open first column every 3 items (start of set)
                    if ($count % 3 == 0) {
                        // If it's not the very first item, close previous column
                        if ($count > 0 && $count % 6 == 0) {
                            // Close previous row and start a new one after 6 items
                            $resubpkgDetail .= '
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <ul class="accordion-box clearfix">';
                        } elseif ($count % 6 == 3) {
                            // Start second column after 3 items
                            $resubpkgDetail .= '
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="accordion-box clearfix">';
                        } elseif ($count == 0) {
                            // Start first column on first iteration
                            $resubpkgDetail .= '
                <div class="col-md-6">
                    <ul class="accordion-box clearfix">';
                        }
                    }

                    // Add the itinerary item
                    $resubpkgDetail .= '
        <li class="accordion block">
            <div class="acc-btn">' . $itineraryInfo->title . '</div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">' . $itineraryInfo->content . '</div>
                </div>
            </div>
        </li>';

                    $count++;
                }

                // Close open tags properly
                $resubpkgDetail .= '
                </ul>
            </div>
        </div>
        ';
            } else {
                $resubpkgDetail .= '
        <div class="col-md-12"><p class="text-center">No itinerary available.</p></div>
    </div>';
            }




            $resubpkgDetail .= '    </div>
    </section>
    
     <div class="modal fade" id="exampleModalactivities" tabindex="-1" aria-labelledby="exampleModalactivities" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: #e4e7e9;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enquiry Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="activityform" class="contact__form contactform_4" >
                        <!-- form elements -->
                        <div class="row">
                            <input type="hidden" name="slug" value="' . $subpkgRec->slug . '"/>
                            <div class="col-md-12 form-group">
                                <input name="name" type="text" placeholder="Full Name *" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <input name="email" type="email" placeholder="Email Address *" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <input name="phone" type="text" placeholder="Phone Number *" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <textarea name="message" id="message" cols="30" rows="4" placeholder="Message *" required></textarea>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="g-recaptcha" data-sitekey="6LeVBaIrAAAAALMY6DrLboZqrjelsfi-ho56pDBk" style="margin-top:15px;"></div>
                            </div>
                            <div id="result_msg"></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="butn-dark2" style="margin-top:15px;"><span>Send Message</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    ';
        } else {


            $relPacs = Subpackage::get_relatedpkg(1, $subpkgRec->id, 12);
            $imglink = '';
            if (!empty($subpkgRec->image2)) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $subpkgRec->image2;
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'subpackage/image/' . $subpkgRec->image2;
                } else {
                    $imglink = IMAGE_PATH . 'static/default.jpg';
                }
            } else {
                $imglink = IMAGE_PATH . 'static/default.jpg';
            }
            $gallRec = SubPackageImage::getImagelist_by($subpkgRec->id);
            $subpkg_carousel = '';
            if (!empty($gallRec)) {
                foreach ($gallRec as $row) {
                    $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                    if (file_exists($file_path) and !empty($row->image)):
                        $subpkg_carousel .= '
                         <div class="text-center item bg-img" data-overlay-dark="3" data-background="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '"></div>
                                
                          ';
                    endif;
                }
            }
            $resubpkgDetail .= '
            <header class="header slider">
        <div class="owl-carousel owl-theme">
            ' . $subpkg_carousel . '
        </div>
        <!-- arrow down -->
        <div class="arrow bounce text-center">
            <a href="#" data-scroll-nav="1" class=""> <i class="ti-arrow-down"></i> </a>
        </div>
    </header>


           
            ';
            $resubpkgDetail .= '
             <section class="rooms-page section-padding" data-scroll-index="1">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center"> 
                    <div class="section-subtitle">' . $subpkgRec->short_title . '</div>
                    <div class="section-title">' . $subpkgRec->title . '</div>
                </div>

            
                                ' . $subpkgRec->content . '
                             <div class="col-md-12">   
                    <div class="butn-light butn-dark mb-3"><a href="https://wa.me/+9779851405139" target="_blank" style="background-color: #2b2f33;" target="_blank"><span>Enquiry Now</span></a> </div>
                </div>
                 </div>
        </div>
    </section>
                
';




            $otherroom = '';
            $rooms = Subpackage::get_relatedsub_by($subpkgRec->type, $subpkgRec->id);


            if (!empty($rooms)) {


                foreach ($rooms as $room) {
                    if (!empty($room->image)) {
                        $img123 = unserialize($room->image);

                        if (file_exists($file_path) && !empty($img123[0])) {
                            $imglink = IMAGE_PATH . 'subpackage/' . $img123[0];
                            $file_path = SITE_ROOT . 'images/subpackage/' . $img123[0];
                        } else {
                            $imglink = IMAGE_PATH . 'static/static.jpg';
                        }
                    } else {
                        $imglink = IMAGE_PATH . 'static/static.jpg';
                    }


                    $otherroom .= '
                        <div class="col wow animate__fadeInUp">
                                <div class="ul-project">
                                    <div class="ul-project-img"><img src="' . $imglink . '" alt="' . $room->title . '"></div>
                                    <div class="ul-project-txt">
                                        <div class="top">
                                            <div class="left">
                                                <a href="' . BASE_URL . '' . $room->slug . '" class="ul-project-title">' . $room->title . '</a>
                                                <p class="ul-project-location">' . $room->detail . '</p>
                                            </div>
                                        </div>
                                        <!-- bottom -->
                                        <div class="ul-project-infos">
                                            <div class="ul-project-info">
                                                <span class="icon"><i class="fa-light fa-user"></i></span>
                                                <span class="text">' . $room->occupancy . '</span>
                                            </div>
                                            <div class="ul-project-info">
                                                <span class="icon"><i class="flaticon-scale"></i></span>
                                                <span class="text">' . $room->size . '</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



			';
                }
                $resubpkgDetail .= '
                        <div class="ul-inner-page-container d-none">
                <div class="ul-inner-page-content-wrapper">
                    <h3 class="ul-project-details-title text-center mb-5">Other Restaurants</h3>
                    <!-- project cards grid -->
                    <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                                   ' . $otherroom . '
                                   </div>
                </div>
            </div>
             </div>';
            }
        }
    }
}

$jVars['module:sub-package-detail'] = $resubpkgDetail;
$jVars['module:sub-package-title'] = $subpkgRec->title;
$jVars['module:sub-package-content'] = $subpkgRec->content;
$jVars['module:sub-package-brief'] = $subpkgRec->detail;

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
// Fallback if no gallery images
if (empty($subpkg_swiper_gallery) && !empty($subpkgRec->image)) {
    $img_url = IMAGE_PATH . 'subpackage/' . $subpkgRec->image;
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
            if (empty($v[1])) continue;
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
