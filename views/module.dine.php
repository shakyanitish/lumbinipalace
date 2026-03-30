<?php
/**
 * Dining Module - Fetches dining venues from Package table (type==0)
 * and displays subpackages as dining cards
 */

$dining_list_html = '';
$dining_card_count = 0;

// Fetch dining packages where type=0
$diningPkgs = Package::find_by_sql("SELECT id FROM tbl_package WHERE status=1 AND type=0");

if (!empty($diningPkgs)) {
    $pkgids = array();
    foreach ($diningPkgs as $pkg) {
        $pkgids[] = $pkg->id;
    }
    $idstr = implode(',', $pkgids);
    
    // Fetch all subpackages for dining
    $sql = "SELECT * FROM tbl_package_sub WHERE status='1' AND type IN ($idstr) ORDER BY sortorder DESC";
    $diningItems = Subpackage::find_by_sql($sql);
    
    if (!empty($diningItems)) {
        foreach ($diningItems as $k => $dining) {
            // Get image path
            $imgpath = '';
            if (!empty($dining->image2)) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $dining->image2;
                if (file_exists($file_path)) {
                    $imgpath = IMAGE_PATH . 'subpackage/image/' . $dining->image2;
                }
            } elseif ($dining->image != "a:0:{}") {
                $imageList = @unserialize($dining->image);
                if (!empty($imageList) && !empty($imageList[0])) {
                    $file_path = SITE_ROOT . 'images/subpackage/' . $imageList[0];
                    if (file_exists($file_path)) {
                        $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                    }
                }
            }
            
            $cuisine = !empty($dining->sub_title) ? $dining->sub_title : '';
            $short_title = !empty($dining->short_title) ? $dining->short_title : '';
            $phone = !empty($dining->phone) ? $dining->phone : '';
            $dress = !empty($dining->dress) ? $dining->dress :
            $time = !empty($dining->time) ? $dining->time : '';
            $content = !empty($dining->content) ? strip_tags($dining->content) : '';
            $accordion_id = 'diningDetails' . $dining->id;
            
            $dining_list_html .= '
                <div class="m-dining-list mt-5">
                    <!-- Dining Card -->
                    <div class="m-dining-card">
                        <div class="row g-0 h-100">';
                        
            if (!empty($imgpath)) {
                $dining_list_html .= '
                            <div class="col-md-5">
                                <div class="m-dining-img-wrap">
                                    <img src="' . $imgpath . '" alt="' . htmlspecialchars($dining->title) . '" class="img-fluid w-100 h-100 object-fit-cover">
                                </div>
                            </div>
                            <div class="col-md-7">';
            } else {
                $dining_list_html .= '
                            <div class="col-md-12">';
            }

            $dining_list_html .= '
                                <div class="m-dining-details">
                                    <h3 class="m-dining-title">' . htmlspecialchars($dining->title) . '</h3>
                                    <p class="m-dining-cuisine">' . htmlspecialchars($cuisine) . '</p>
                                    <p class="m-dining-desc">' . $content . '</p>

                                    <div class="m-dining-info mb-4">
                                        <div class="d-flex align-items-center me-5">
                                            <i class="bi bi-clock me-2"></i>
                                            <span>Everyday</span>
                                        </div>

                                        <span>' . htmlspecialchars($short_title) . '</span>
                                    </div>


                                    <!-- Accordion/Collapse for More Details -->
                                    <div class="m-dining-more-details collapse" id="' . $accordion_id . '">
                                        <div class="m-dining-extra-info">
                                            <div class="m-dining-extra-item border-bottom pb-3 mb-3">
                                                <i class="fa-solid fa-phone"></i>
                                                <span>' . htmlspecialchars($phone) . '</span>
                                            </div>
                                            <div class="m-dining-extra-item border-bottom pb-3">
                                                <i class="fa-brands fa-black-tie"></i>
                                                <span>Dress Code: ' . htmlspecialchars($dress) . '</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="m-dining-footer mt-auto">
                                        <button class="btn m-btn-more shadow-none p-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#' . $accordion_id . '"
                                            aria-expanded="false" aria-controls="' . $accordion_id . '">
                                            <span class="more-text">More <i
                                                    class="fa-solid fa-chevron-down ms-1"></i></span>
                                            <span class="less-text d-none">Less <i
                                                    class="fa-solid fa-chevron-up ms-1"></i></span>
                                        </button>
                                        <a href="#" class="btn m-btn-book-table" data-bs-toggle="modal"
                                            data-bs-target="#enquiryModal">Book a Table <i
                                                class="bi bi-arrow-up-right ms-1"></i></a>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>';
        }
    }
}

$jVars['module:dining-list'] = $dining_list_html;

$great_room_html = '';

if (defined('HOME_PAGE')) {
    // Fetch dining packages where type=0
    $diningPkgs = Package::find_by_sql("SELECT id FROM tbl_package WHERE status=1 AND type=0");

    if (!empty($diningPkgs)) {
        $pkgids = array();
        foreach ($diningPkgs as $pkg) {
            $pkgids[] = $pkg->id;
        }
        $idstr = implode(',', $pkgids);
        
        // Fetch all subpackages for dining
        $sql = "SELECT * FROM tbl_package_sub WHERE status='1' AND type IN ($idstr) ORDER BY sortorder DESC LIMIT 5";
        $diningItems = Subpackage::find_by_sql($sql);
        
        if (!empty($diningItems)) {
            // Build swiper slides
            $slides_html = '';
            
            foreach ($diningItems as $k => $dining) {
                // Get image path
                $imgpath = '';
                if (!empty($dining->image2)) {
                    $file_path = SITE_ROOT . 'images/subpackage/image/' . $dining->image2;
                    if (file_exists($file_path)) {
                        $imgpath = IMAGE_PATH . 'subpackage/image/' . $dining->image2;
                    }
                } elseif ($dining->image != "a:0:{}") {
                    $imageList = @unserialize($dining->image);
                    if (!empty($imageList) && !empty($imageList[0])) {
                        $file_path = SITE_ROOT . 'images/subpackage/' . $imageList[0];
                        if (file_exists($file_path)) {
                            $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                        }
                    }
                }
                
                // Fallback image if no dining image available
                if (empty($imgpath)) {
                    $imgpath = ASSETS_PATH . 'img/dine-1.jpg';
                }
                
                $title = !empty($dining->title) ? htmlspecialchars($dining->title) : '';
                $cuisine = !empty($dining->sub_title) ? htmlspecialchars($dining->sub_title) : '';
                $content = !empty($dining->content) ? strip_tags($dining->content) : '';
                
                $slides_html .= '
                        <!-- Slide ' . ($k + 1) . ' -->
                        <div class="swiper-slide">
                            <div class="row g-0 align-items-stretch justify-content-center m-great-room-row flex-column flex-xl-row">
                                <div class="col-xl-8 position-relative order-1 order-xl-2">
                                    <img src="' . $imgpath . '" alt="' . $title . '"
                                        class="img-fluid w-100 h-100 object-fit-cover m-great-room-img">
                                </div>
                                <div class="col-xl-4 d-flex flex-column justify-content-center p-4 p-md-5 pt-5 pt-xl-5 position-relative z-1 order-2 order-xl-1 text-center text-xl-start">
                                    <div class="mgr-text-content d-flex flex-column align-items-center align-items-xl-start mt-4 mt-xl-0">
                                        <h2 class="h3 fw-bold mb-3 mb-xl-4" style="letter-spacing: 0.5px;">' . $title . '</h2>
                                        <p class="mb-4 mb-xl-5 font-weight-normal mx-auto mx-xl-0"
                                            style="max-width: 450px; line-height: 1.7; font-size: 15px; color: #fff !important;">
                                            ' . substr($content, 0, 200) . (strlen($content) > 200 ? '...' : '') . '
                                        </p>
                                        <a href="#" class="btn mgr-btn-red px-4 py-2 fw-semibold"
                                            style="border-radius: 6px;">Explore</a>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
            
            // Build complete section HTML
            $great_room_html = '
            
        <section class="m-great-room bg-dark text-white overflow-hidden py-5 pt-lg-0 pb-lg-0">
            <div class="container mx-auto p-0 position-relative" style="max-width: 1200px;">
                <div class="swiper m-great-room-swiper w-100 h-100">
                    <div class="swiper-wrapper">
                        ' . $slides_html . '
                    </div>
                </div>

                <!-- Global Navigation exactly inside the container to align with the left col -->
                <div class="m-great-room-nav-container d-flex align-items-center position-absolute w-100">
                    <div class="col-xl-4 p-4 p-md-5 py-0 py-xl-0 d-flex align-items-center justify-content-center justify-content-xl-start w-100"
                        style="pointer-events: auto;">
                        <div class="mgr-nav d-flex align-items-center justify-content-between justify-content-xl-start w-100"
                            style="max-width: 320px;">
                            <div class="m-great-room-prev small cursor-pointer text-white m-0 d-flex align-items-center fw-semibold">
                                <span class="d-none d-xl-inline">Prev</span>
                                <span class="mgr-line ms-0 ms-xl-3"></span>
                            </div>
                            <div class="m-great-room-pagination small fw-bold tracking-widest text-center mx-3 mx-xl-4"
                                style="min-width: 60px;"></div>
                            <div class="m-great-room-next small cursor-pointer text-white m-0 d-flex align-items-center fw-semibold">
                                <span class="mgr-line me-0 me-xl-3"></span>
                                <span class="d-none d-xl-inline">Next</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
        }
    }
}

$jVars['module:home-dine'] = $great_room_html;



?>
