<?php

$faq_details = $faqbred = $faq_scripts = '';

if (defined('FAQ_PAGE')) {

    // Get all FAQs
    $faqs = Faq::find_all();

    // Filter only type 0 FAQs
    $faqs = array_filter($faqs, function ($faq) {
        return isset($faq->volunteer) && $faq->volunteer == 0;
    });

    $defaultImg = BASE_URL . 'template/web/assets/img/blog-1.jpg';
    if (!empty($siteRegulars->other_upload)) {
        $defaultImg = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    }

    $faqbred = '
        <section class="ul-breadcrumb ul-section-spacing">
            <div class="ul-container">
                <div class="ul-breadcrumb-wrapper">
                    <div class="ul-breadcrumb-content">
                        <ul class="ul-breadcrumb-nav">
                            <li><a href="' . BASE_URL . '">Home</a></li>
                            <li class="separator"><i class="flaticon-right"></i></li>
                            <li>FAQs</li>
                        </ul>
                        <span class="ul-breadcrumb-subtitle">Common Questions</span>
                        <h2 class="ul-breadcrumb-title">Frequently Asked Questions</h2>
                        <p class="ul-breadcrumb-descr">Find answers to common questions about our sexual and
                            reproductive health services, clinical network, and patient care.</p>
                    </div>
                    <div class="ul-breadcrumb-img">
                        <img src="' . $defaultImg . '" alt="FAQs">
                    </div>
                </div>
            </div>
        </section>
    ';

    if (!empty($faqs)) {
        $faq_details .= '
            <section class="faq-section ul-section-spacing">
                <div class="ul-container">
                    <div class="faq-layout-row">
                        <!-- Sidebar Categories -->
                        <aside class="faq-sidebar">
                            <div class="faq-category-list" id="faq-categories">
                                <!-- Categories will be rendered here -->
                            </div>
                        </aside>
    
                        <!-- Main Content -->
                        <div class="faq-main-content">
                            <!-- Search Bar -->
                            <div class="faq-search-wrapper">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="faq-search" class="faq-search-input"
                                    placeholder="Search for questions (e.g., \'abortion\', \'clinics\', \'contraception\')...">
                            </div>
    
                            <div id="faq-results-info" class="faq-results-info" style="display: none;"></div>
                            <h2 id="current-category-title" class="faq-category-title">All Questions</h2>
    
                            <div class="ul-accordion" id="faq-accordion">
                                <!-- FAQ items will be rendered here -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        ';

        // 1️⃣ Fetch all categories that have active FAQs, ordered by category sortorder DESC
        $categories = FaqCategory::find_all_active();

        // Prepare array to hold all categories + questions
        $faqJsArray = [];

        foreach ($categories as $cat) {
            // Fetch FAQs for this category
            $faqs = Faq::find_by_sql("SELECT * FROM tbl_faq WHERE status = 1 AND category = {$cat->id} ORDER BY sortorder DESC");

            if (empty($faqs))
                continue; // skip empty categories

            $questions = [];

            foreach ($faqs as $faq) {
                $questions[] = [
                    'q' => $faq->title, // question
                    'a' => $faq->content // answer
                ];
            }

            $faqJsArray[] = [
                'category' => $cat->title,
                'questions' => $questions
            ];
        }

        $faq_scripts .= '<script>';
        $faq_scripts .= 'const faqData = ' . json_encode($faqJsArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ';';
        $faq_scripts .= '</script>';

    }
    else {
        $faq_details .= '<h3 class="text-center p-4">No FAQs Found</h3>';
    }
}

$jVars['module:faq:details'] = $faq_details;
$jVars['module:faq:bred'] = $faqbred;
$jVars['module:faq:script'] = $faq_scripts;


/**
 *      Homepage FAQ
 */




$homeFaqSection = '';

if (defined('HOME_PAGE')) {
    // Fetch different FAQs from Category 1
    $homeFaqs = Faq::find_by_sql("SELECT * FROM tbl_faq WHERE status = 1 AND category = 4 AND volunteer = 1 ORDER BY sortorder DESC");

    if (!empty($homeFaqs)) {
        $accordionItems = '';
        foreach ($homeFaqs as $i => $faq) {
            $collapseId = 'faqItemSect1' . ($i + 1);
            // All items start collapsed - user opens manually
            $accordionItems .= '
                    <div class="accordion-item border-top ' . ($i === count($homeFaqs) - 1 ? 'border-bottom' : 'border-bottom-0') . '">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-4 px-0 bg-transparent shadow-none"
                                type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                . htmlspecialchars($faq->title) .
                '</button>
                        </h2>
                        <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#faqAccordionSect1">
                            <div class="accordion-body text-muted pt-0 pb-4">' . strip_tags($faq->content) . '</div>
                        </div>
                    </div>';
        }
                // Get category title from tbl_faq_category
        $category = FaqCategory::find_by_id(1);
        $categoryTitle = (!empty($category) && !empty($category->title)) ? htmlspecialchars($category->title) : 'Frequently Asked Questions';

        $homeFaqSection = '
    <section class="m-property-details py-5 bg-white">
        <div class="container">
            <h2 class="h5 fw-bold mb-4 title">' . $categoryTitle . '</h2>
            <div class="accordion accordion-flush" id="faqAccordionSect1">'
            . $accordionItems .
            '</div>
        </div>
    </section>';
    }
}

$jVars['module:faq:homepage'] = $homeFaqSection;




$homeFaqSection2 = '';

if (defined('HOME_PAGE')) {
    // Fetch different FAQs from Category 2
    $homeFaqs = Faq::find_by_sql("SELECT * FROM tbl_faq WHERE status = 1 AND category = 5 AND volunteer = 1 ORDER BY sortorder DESC");

    if (!empty($homeFaqs)) {
        $accordionItems = '';
        foreach ($homeFaqs as $i => $faq) {
            $collapseId = 'faqItemSect2' . ($i + 1);
            // All items start collapsed - user opens manually
            $accordionItems .= '
                    <div class="accordion-item border-top ' . ($i === count($homeFaqs) - 1 ? 'border-bottom' : 'border-bottom-0') . '">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-4 px-0 bg-transparent shadow-none"
                                type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                . $faq->title .
                '</button>
                        </h2>
                        <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#faqAccordionSect2">
                            <div class="accordion-body text-muted pt-0 pb-4">' . strip_tags($faq->content) . '</div>
                        </div>
                    </div>';
        }

        // Get category title from tbl_faq_category
        $category = FaqCategory::find_by_id(2);
        $categoryTitle = (!empty($category) && !empty($category->title)) ? htmlspecialchars($category->title) : 'Property Details';

        $homeFaqSection2 = '
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">' . $categoryTitle . '</h2>
                <div class="accordion accordion-flush" id="faqAccordionSect2">'
            . $accordionItems .
            '</div>
            </div>
        </section>
';
    }
}

$jVars['module:faq:homepage1'] = $homeFaqSection2;




/**
 * Homepage Testimonials - Dynamic from Database
 */
$testimonials_section = '';

if (defined('HOME_PAGE')) {

    
    $testimonials_html = '
        <section class="m-testimonials wow animate__fadeInUp">
            <div class="m-testimonials-inner">
                <div class="m-testimonials-header">
                    <span class="m-section-label">Guest Reviews</span>
                    <h2 class="m-section-title">What Our Guests Say</h2>
                </div>
                <div class="m-testimonials-grid">
                    <div class="m-testimonial-card">
                        <div class="m-testimonial-stars">
                            <i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i
                                class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i
                                class="fa-solid fa-circle"></i>
                        </div>
                        <p class="m-testimonial-text">"This place was situated in a really good spot which was really
                            peaceful and the ambience is really good."</p>
                        <div class="m-testimonial-author">
                            <img src="' . BASE_URL . 'template/web/assets/img/team-1.jpg" alt="Abhikalp K">
                            <div>
                                <p class="m-testimonial-author-name">Abhikalp K</p>
                                <p class="m-testimonial-author-loc">India</p>
                            </div>
                        </div>
                    </div>
                    <div class="m-testimonial-card">
                        <div class="m-testimonial-stars">
                            <i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i
                                class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i
                                class="fa-solid fa-circle"></i>
                        </div>
                        <p class="m-testimonial-text">"Amazing place, wonderful service, friendly people, tasty food. We
                            loved our pond villa stay."</p>
                        <div class="m-testimonial-author">
                            <img src="' . BASE_URL . 'template/web/assets/img/team-1.jpg" alt="Vickygor">
                            <div>
                                <p class="m-testimonial-author-name">Vickygor</p>
                                <p class="m-testimonial-author-loc">London, United Kingdom</p>
                            </div>
                        </div>
                    </div>
                    <div class="m-testimonial-card">
                        <div class="m-testimonial-stars">
                            <i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i
                                class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i
                                class="fa-solid fa-circle"></i>
                        </div>
                        <p class="m-testimonial-text">"Excellent service — the director even arranged for our car to be
                            cleaned. Very peaceful and serene location."</p>
                        <div class="m-testimonial-author">
                            <img src="' . BASE_URL . 'template/web/assets/img/team-2.jpg" alt="Nick">
                            <div>
                                <p class="m-testimonial-author-name">Nick</p>
                                <p class="m-testimonial-author-loc">United Kingdom</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    ';
    
    $testimonials_section = $testimonials_html;
}

$jVars['module:testimonials:homepage'] = $testimonials_section;


/**
 * Location/Transportation Accordion - Using Category 3
 */


$faq_details = '';
if (defined('EXPERIENCE_PAGE')) {

    $faqs = Faq::find_by_sql("SELECT * FROM tbl_faq WHERE status = 1 AND category = 10 ORDER BY sortorder DESC");

    if (!empty($faqs)) {
        $faqItems = '';
        foreach ($faqs as $i => $faq) {
            $collapseId = 'experienceFaq' . ($i + 1);
            $expandedAttr = '';
            $btnClass = ' collapsed';
            $borderClass = ($i === count($faqs) - 1) ? 'border-bottom' : 'border-bottom-0';
            
            $faqItems .= '
        <div class="accordion-item border-top ' . $borderClass . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . $faq->title . '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $faq->content . '</div>
            </div>
        </div>';
        }

        $faq_details = '
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqAccordion">
                ' . $faqItems . '
                </div>
            </div>
        </section>';
    }
    else {
        $faq_details = '        
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h3 class="text-center p-4">No Experience FAQ Found</h3>
            </div>
        </section>';
    }
}

$jVars['module:faq:experience'] = $faq_details;


//Room or Accomodation faqs
$faq_room = '';
if (defined('ROOM_PAGE')) {

    $faqs = Faq::find_by_sql("SELECT * FROM tbl_faq WHERE status = 1 AND category = 8 ORDER BY sortorder DESC");

    if (!empty($faqs)) {
        $faqItems = '';
        foreach ($faqs as $i => $faq) {
            $collapseId = 'roomFaq' . ($i + 1);
            $expandedAttr = '';
            $btnClass = ' collapsed';
            $borderClass = ($i === count($faqs) - 1) ? 'border-bottom' : 'border-bottom-0';
            
            $faqItems .= '
        <div class="accordion-item border-top ' . $borderClass . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . $faq->title . '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $faq->content . '</div>
            </div>
        </div>';
        }

        $faq_room = '
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqAccordion">
                ' . $faqItems . '
                </div>
            </div>
        </section>';
    }
    else {
        $faq_room = '        
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h3 class="text-center p-4">No Accomodation FAQ Found</h3>
            </div>
        </section>';
    }
}

$jVars['module:faq:room'] = $faq_room;


//Events faq
$faq_details_event = '';
if (defined('EVENT_PAGE')) {

    $faqs = Faq::find_by_sql("SELECT * FROM tbl_faq WHERE status = 1 AND category = 9 ORDER BY sortorder DESC");

    if (!empty($faqs)) {
        $faqItems = '';
        foreach ($faqs as $i => $faq) {
            $collapseId = 'eventFaq' . ($i + 1);
            $expandedAttr = '';
            $btnClass = ' collapsed';
            $borderClass = ($i === count($faqs) - 1) ? 'border-bottom' : 'border-bottom-0';
            
            $faqItems .= '
        <div class="accordion-item border-top ' . $borderClass . '">
            <h2 class="accordion-header">
                <button class="accordion-button' . $btnClass . ' px-0 py-4 bg-transparent shadow-none"
                    type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '">'
                    . $faq->title . '</button>
            </h2>
            <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted pt-0 pb-4">' . $faq->content . '</div>
            </div>
        </div>';
        }

        $faq_details_event = '

        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h2 class="h5 fw-bold mb-4 title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqAccordion">
                ' . $faqItems . '
                </div>
            </div>
        </section>';
    }
    else {
        $faq_details_event = '
        
        <section class="m-property-details py-5 bg-white">
            <div class="container">
                <h3 class="text-center p-4">No Event FAQ Found</h3>
            </div>
        </section>
        
        
        
        
        
        
        
        
        ';
    }
}

$jVars['module:faq:event'] = $faq_details_event;


