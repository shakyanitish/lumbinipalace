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

                                        <span>' . htmlspecialchars($time) . '</span>
                                    </div>


                                    <!-- Accordion/Collapse for More Details -->
                                    <div class="m-dining-more-details collapse" id="' . $accordion_id . '">
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

/*
* Dining FAQ
*/
$dining_faq_html = '';
$diningFaqItems = Package::get_itinerary(25);

if (!empty($diningFaqItems)) {
    $faqItems = '';
    foreach ($diningFaqItems as $i => $faq) {
        $collapseId = 'diningFaq' . ($i + 1);
        $expandedAttr = '';
        $btnClass = ' collapsed';
        $borderClass = ($i === count($diningFaqItems) - 1) ? 'border-bottom' : 'border-bottom-0';
        
        $faqItems .= '
        <div class="accordion-item border-top ' . $borderClass . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . $faq->title . '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#diningFaqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $faq->content . '</div>
            </div>
        </div>';
    }

    $dining_faq_html = '
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="diningFaqAccordion">
                    ' . $faqItems . '
                </div>
            </div>
        </section>';
}

$jVars['module:dining-faq'] = $dining_faq_html;



$room_package = '';
$room_package = Package::get_itinerary(24);

if (!empty($room_package)) {
    $faqItems = '';
    foreach ($room_package as $i => $room) {
        $collapseId = 'diningFaq' . ($i + 1);
        $expandedAttr = '';
        $btnClass = ' collapsed';
        $borderClass = ($i === count($room_package) - 1) ? 'border-bottom' : 'border-bottom-0';
        
        $faqItems .= '
         



        <div class="accordion-item border-top ' . $borderClass . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . $room->title . '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#diningFaqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $room->content . '</div>
            </div>
        </div>';
    }

    $room_package = '

        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqAccordion">
                ' . $faqItems . '
                </div>
            </div>
        </section>';
}

$jVars['module:room-package-faq'] = $room_package;


$experience_package = '';
$experience_package = Package::get_itinerary(28);

if (!empty($experience_package)) {
    $faqItems = '';
    foreach ($experience_package as $i => $experience) {
        $collapseId = 'experienceFaq' . ($i + 1);
        $expandedAttr = '';
        $btnClass = ' collapsed';
        $borderClass = ($i === count($experience_package) - 1) ? 'border-bottom' : 'border-bottom-0';
        
        $faqItems .= '
         



        <div class="accordion-item border-top ' . $borderClass . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . $experience->title . '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#diningFaqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $experience->content . '</div>
            </div>
        </div>';
    }

    $experience_package = '

        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqAccordion">
                ' . $faqItems . '
                </div>
            </div>
        </section>';
}

$jVars['module:experience-package-faq'] = $experience_package;
?>
