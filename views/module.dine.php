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
        foreach ($diningItems as $dining) {
            // Get image path
            $imgpath = null;
            if (!empty($dining->image2)) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $dining->image2;
                if (file_exists($file_path)) {
                    $imgpath = IMAGE_PATH . 'subpackage/image/' . $dining->image2;
                }
            } elseif ($dining->image != "a:0:{}") {
                $imageList = unserialize($dining->image);
                if (!empty($imageList[0])) {
                    $file_path = SITE_ROOT . 'images/subpackage/' . $imageList[0];
                    if (file_exists($file_path)) {
                        $imgpath = IMAGE_PATH . 'subpackage/' . $imageList[0];
                    }
                }
            }
            
            $dining_card_count++;
            
            // Get timing info from detail field
            $timing_info = !empty($dining->detail) ? $dining->detail : '';
            
            // Open dining list container on first card
            if ($dining_card_count == 1) {
                $dining_list_html .= '<div class="m-dining-list mt-5">';
            }
            
            // Create dining card with or without image
            if (!empty($imgpath)) {
                // Card WITH image (split layout)
                $dining_list_html .= '
                    <div class="m-dining-card">
                        <div class="row g-0 h-100">
                            <div class="col-md-5">
                                <div class="m-dining-img-wrap">
                                    <img src="' . $imgpath . '" alt="' . htmlspecialchars($dining->title) . '"
                                        class="img-fluid w-100 h-100 object-fit-cover">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="m-dining-details">
                                    <h3 class="m-dining-title">' . htmlspecialchars($dining->title) . '</h3>
                                    <p class="m-dining-cuisine">' . htmlspecialchars(!empty($dining->short_title) ? $dining->short_title : 'Dining') . '</p>
                                    <p class="m-dining-desc">' . htmlspecialchars(substr($dining->content, 0, 200)) . '...</p>

                                    <div class="m-dining-info mb-4">
                                        <div class="d-flex align-items-center me-5">
                                            <i class="bi bi-clock me-2"></i>
                                            <span>Everyday</span>
                                        </div>
                                        ' . (!empty($timing_info) ? '<span>' . htmlspecialchars($timing_info) . '</span>' : '<span>6:30 AM - 10:30 PM</span>') . '
                                    </div>

                                    <!-- Accordion/Collapse for More Details -->
                                    <div class="m-dining-more-details collapse" id="diningDetails' . $dining->id . '">
                                        <div class="m-dining-extra-info">
                                            <div class="m-dining-extra-item border-bottom pb-3 mb-3">
                                                <i class="fa-solid fa-phone"></i>
                                                <span>+977 01-9801971643</span>
                                            </div>
                                            <div class="m-dining-extra-item border-bottom pb-3">
                                                <i class="fa-brands fa-black-tie"></i>
                                                <span>Dress Code: Casual</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="m-dining-footer mt-auto">
                                        <button class="btn m-btn-more shadow-none p-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#diningDetails' . $dining->id . '"
                                            aria-expanded="false" aria-controls="diningDetails' . $dining->id . '">
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
                    </div>';
            } else {
                // Card WITHOUT image (full-width layout)
                $dining_list_html .= '
                    <div class="m-dining-card">
                        <div class="row g-0 h-100">
                            <div class="col-md-12">
                                <div class="m-dining-details">
                                    <h3 class="m-dining-title">' . htmlspecialchars($dining->title) . ' <span>- Opening Soon</span></h3>
                                    <p class="m-dining-cuisine">' . htmlspecialchars(!empty($dining->short_title) ? $dining->short_title : 'Dining') . '</p>
                                    <p class="m-dining-desc">' . htmlspecialchars($dining->content) . '</p>

                                    <div class="m-dining-info mb-4">
                                        <div class="d-flex align-items-center me-5">
                                            <i class="bi bi-clock me-2"></i>
                                            <span>Everyday</span>
                                        </div>
                                        ' . (!empty($timing_info) ? '<span>' . htmlspecialchars($timing_info) . '</span>' : '<span>6:30 AM - 10:30 PM</span>') . '
                                    </div>

                                    <!-- Accordion/Collapse for More Details -->
                                    <div class="m-dining-more-details collapse" id="diningDetails' . $dining->id . '">
                                        <div class="m-dining-extra-info">
                                            <div class="m-dining-extra-item border-bottom pb-3 mb-3">
                                                <i class="fa-solid fa-phone"></i>
                                                <span>+977 01-9801971643</span>
                                            </div>
                                            <div class="m-dining-extra-item border-bottom pb-3">
                                                <i class="fa-brands fa-black-tie"></i>
                                                <span>Dress Code: Casual</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="m-dining-footer mt-auto">
                                        <button class="btn m-btn-more shadow-none p-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#diningDetails' . $dining->id . '"
                                            aria-expanded="false" aria-controls="diningDetails' . $dining->id . '">
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
                    </div>';
            }
        }
        
        // Close dining list container if any items exist
        if ($dining_card_count > 0) {
            $dining_list_html .= '</div>';
        }
    }
}

$jVars['module:dining-list'] = $dining_list_html;
?>
