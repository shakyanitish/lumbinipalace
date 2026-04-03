<?php
$resoffr = $socialshare = '';
$more_offers_html = '';
$expired = '';
$enquiry = '';
$offer_details = '';
$offer_hero = '';
$resrandoffr = $hmresoffr = $resinndetail = $offbredd = '';
$offrRec = Offers::get_offer_by();

if (defined('OFFERS_PAGE_DETAIL') and isset($_REQUEST['slug'])) {
    $slug = addslashes($_REQUEST['slug']);
    $recRow = Offers::find_by_slug($slug);
    if (!empty($recRow)) {  

        $imageFile = !empty($recRow->image) ? $recRow->image : $recRow->list_image;
        $imageFolder = !empty($recRow->image) ? 'offers/' : 'offers/listimage/';
        $file_path = SITE_ROOT . 'images/' . $imageFolder . $imageFile;
        if (file_exists($file_path) && !empty($imageFile)) {
            $imglink = IMAGE_PATH . $imageFolder . $imageFile;
        } else {
            $imglink = IMAGE_PATH . 'static/inner-img.jpg';
        }
        $socialshare = '<div class="share-social">
            <a class="facebook-share" target="blank" href="https://www.facebook.com/sharer/sharer.php?u=' . BASE_URL . 'offer/' . $recRow->slug . '&p=' . $recRow->title . '&p[images][0]=' . $imglink . '">
                <i class="fa fa-facebook" aria-hidden="true"></i><span>Share</span></a> 
            <a class="twitter-share" target="blank" href="https://twitter.com/intent/tweet?text=' . $recRow->title . ' ?url=' . BASE_URL . 'offer/' . $recRow->slug . '" >
                <i class="fa fa-twitter" aria-hidden="true"></i><span>Share</span></a>
            <a class="gplus-share" target="blank" href="https://plus.google.com/share?url=' . BASE_URL . 'offer/' . $recRow->slug . '">
                <i class="fa fa-google-plus" aria-hidden="true"></i><span>Share</span></a>
        </div>';
        $rescontent = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($recRow->content));
        $content = !empty($rescontent[1]) ? $rescontent[1] : $rescontent[0];
        $currentdate = date("Y-m-d");
        // pr($recRow);
        // pr($currentdate);
        if ($recRow->deadline_type == 'alltime' || empty($recRow->offer_date)) {
            $enquiry = '<a href="' . BASE_URL . 'book/' . $recRow->slug . '" class="btn btn-primary btn-book" style="color: #fff;background-color: #7b2b2e;border-color: #7b2b2e;">Enquiry</a>';
        } elseif ($recRow->offer_date > $currentdate) {
            $enquiry = '<a href="' . BASE_URL . 'book/' . $recRow->slug . '" class="btn btn-primary btn-book" style="color: #fff;background-color: #7b2b2e;border-color: #7b2b2e;">Enquiry</a>';
        } else {
            $enquiry = '';
        }
        $resinndetail .= $socialshare . '
                        <div class="offer-detail3">
                            <h2>' . $recRow->title . '</h2>
                            ' . $content . '
                            ' . $enquiry . '
                        </div>';

        $offer_details = '
        <section class="m-offer-details-section">
            <div class="container container-custom">
                <div class="row">
                    ' . $content . '
                </div>
            </div>
        </section>
        ';

        $offer_title = htmlspecialchars($recRow->title);
        $offer_subtitle = substr(strip_tags((!empty($recRow->brief)) ? $recRow->brief : $recRow->content), 0, 150) . '...';
        
        $offer_hero = '
    <section class="m-offer-hero" style="background-image: url(\'' . $imglink . '\');">
        <div class="m-offer-hero-content">
            <h1 class="m-offer-hero-title">' . $offer_title . '</h1>
            <p class="m-offer-hero-subtitle">' . $offer_subtitle . '</p>
        </div>

        <!-- Offer Enquiry Widget -->
        <div class="m-offer-booking-wrapper">
            <form action="#">
                <div class="m-offer-booking-grid">
                    <div class="m-offer-booking-field">
                        <div class="m-offer-booking-label"><i class="fa-solid fa-user"></i> FULL NAME</div>
                        <input type="text" class="m-offer-booking-input" placeholder="Enter your name">
                    </div>
                    <div class="m-offer-booking-field">
                        <div class="m-offer-booking-label"><i class="fa-solid fa-phone"></i> MOBILE NUMBER</div>
                        <input type="tel" class="m-offer-booking-input" placeholder="Enter mobile number">
                    </div>
                    <div class="m-offer-booking-field">
                        <div class="m-offer-booking-label"><i class="fa-solid fa-calendar-days"></i> PREFERRED DATES
                        </div>
                        <input type="text" class="m-offer-booking-input" placeholder="Select dates">
                    </div>
                    <div class="m-offer-booking-action">
                        <button type="submit" class="btn m-btn-offer-book">Enquire Now</button>
                    </div>
                </div>
            </form>
        </div>
    </section>';


        
        // Fetch "More Offers At This Property" (excluding current offer)
        $sqlOther = "SELECT * FROM tbl_offers WHERE status=1 AND id != " . $recRow->id . " AND (deadline_type='alltime' OR (start_date IS NOT NULL AND offer_date IS NOT NULL AND CURDATE() BETWEEN start_date AND offer_date)) ORDER BY sortorder DESC";
        $otherOffers = Offers::find_by_sql($sqlOther);

        if (!empty($otherOffers)) {
            $other_offer_cards = '';
            $counter = 1;
            foreach ($otherOffers as $oOffer) {
                // Determine image
                $imageFile = !empty($oOffer->image) ? $oOffer->image : $oOffer->list_image;
                $imageFolder = !empty($oOffer->image) ? 'offers/' : 'offers/listimage/';
                $$imageFile_path = SITE_ROOT . 'images/' . $imageFolder . $imageFile;
                $imglink = file_exists($$imageFile_path) && !empty($imageFile) ? IMAGE_PATH . $imageFolder . $imageFile : IMAGE_PATH . 'static/inner-img.jpg';

                $dNoneClass = ($counter > 3) ? ' d-none' : '';
                
                // Content description
                $desc = substr(strip_tags($oOffer->content), 0, 150) . '...';
                
                // Alternate image position if desired like in static HTML
                $orderClassImg = ($counter % 2 != 0) ? ' order-md-2' : '';
                $orderClassContent = ($counter % 2 != 0) ? ' order-md-1' : '';

                $other_offer_cards .= '
                    <!-- Offer Card ' . $counter . ' -->
                    <div class="m-offer-list-card' . $dNoneClass . '">
                        <div class="row g-0">
                            <div class="col-md-6' . $orderClassImg . '">
                                <img src="' . $imglink . '" alt="' . htmlspecialchars($oOffer->title) . '"
                                    class="img-fluid w-100 m-offer-list-img">
                            </div>
                            <div class="col-md-6' . $orderClassContent . '">
                                <div class="m-offer-list-content">
                                    <h3 class="m-offer-list-title">' . htmlspecialchars($oOffer->title) . '</h3>
                                    <p class="m-offer-list-desc">' . $desc . '</p>
                                    <a href="' . BASE_URL . 'offer/' . $oOffer->slug . '" class="btn m-btn-see-details shadow-none">See details</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                
                $counter++;
            }

            $showMoreBtn = ($counter > 4) ? '
                    <div class="text-center mt-5">
                        <button class="btn m-btn-see-details px-4 py-2" id="btnShowMoreOffers">Show More</button>
                    </div>' : '';

            $more_offers_html = '
        <section class="m-more-offers-section">
            <div class="container container-custom">
                <h2 class="m-more-offers-title">More Offers At This Property</h2>

                <div class="m-offer-list">
                    ' . $other_offer_cards . '
                    ' . $showMoreBtn . '
                </div>
            </div>
        </section>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const showMoreBtn = document.getElementById("btnShowMoreOffers");
                if(showMoreBtn) {
                    const hiddenCards = document.querySelectorAll(".m-offer-list-card.d-none");

                    showMoreBtn.addEventListener("click", function () {
                        hiddenCards.forEach(card => card.classList.remove("d-none"));
                        showMoreBtn.parentElement.classList.add("d-none");
                    });
                }
            });
        </script>';
        }
    } else {
        redirect_to(BASE_URL);
    }
} else {

    $offbredd .= '<section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . BASE_URL . 'images/fac.jpg); background-repeat: no-repeat; "> 
               
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                               <!-- <ul class="breadcrumb">
                                    <li><a href="' . BASE_URL . '">Home</a></li>
                                    <li>Offers</li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
    $offList = Offers::find_all();
    $resinndetail .= '<div class="row">
                            <div class="col-sm-12">
                                <h1 class="text-center">Exclusive Offers</h1>
                                <br/>
                            </div>
                        ';
    foreach ($offList as $offer) {

        $currentdate = date("Y-m-d");
        // Show offer if it's "All Time" or if dates are valid and within range
        $isAllTime = ($offer->deadline_type == 'alltime' || empty($offer->start_date) || empty($offer->offer_date));
        $isActive = $isAllTime || ($offer->start_date <= $currentdate && $offer->offer_date >= $currentdate);

        if ($isActive) {
            // Only mark as expired if it has a deadline and is past the deadline
            if (!$isAllTime && $offer->offer_date < $currentdate) {
                $expired .= '<div class="offer__expire position-absolute"><span>Expired</span></div>';
            } else {
                $expired .= '';
            }
            //  pr($expired);
            $resinndetail .= '<div class="col-md-4">
                                <div class="offer offer-item position-relative">
                                    <a href="' . BASE_URL . 'offer/' . $offer->slug . '">
                                        <img src="' . IMAGE_PATH . 'offers/listimage/' . $offer->list_image . '" alt="' . $offer->image . '">
                                        <div class="details">
                                            <h3>' . $offer->title . '</h3>
                                        </div>
                                    </a>
                                </div>
                                ' . $expired . '
                            </div>';
            $expired = '';
        }
    }
    $resinndetail .= '</div>';
}


// Rand offer
$randRec = Offers::get_offer_rand();
if (!empty($randRec)) {
    $file_path = SITE_ROOT . 'images/offers/' . $randRec->image;
    if (file_exists($file_path) and !empty($randRec->image)) {
        $linkTarget = ($randRec->linktype == 1) ? ' target="_blank" ' : '';
        $linksrc = ($randRec->linktype != 1) ? BASE_URL . $randRec->linksrc : $randRec->linksrc;
        $linkstart = ($randRec->linksrc != '') ? '<a href="' . $linksrc . '" ' . $linkTarget . '>' : '<a href="javascript:void(0);">';
        $linkend = ($randRec->linksrc != '') ? '</a>' : '</a>';


        $resrandoffr .= '<div class="section panel">
                <div class="item fade">
                    <div class="back" data-image="' . IMAGE_PATH . 'offers/' . $randRec->image . '"></div>
                    <div class="panel-button">
                        <div class="button-container">
                            ' . $linkstart . $randRec->title . $linkend . '
                            <span>Our Offer <i class="icon ion-ios-arrow-right"></i>
                        </div>
                    </div>
                </div>

            </div>';
    }
}

$home_offers = $homeie = '';
if (defined('HOME_PAGE') and !isset($_REQUEST['slug'])) {
    $sql = "SELECT * FROM tbl_offers WHERE status='1' ORDER BY sortorder DESC";
    $offList = Offers::find_by_sql($sql);
    
    if (!empty($offList)) {
        foreach ($offList as $offer) {
            $currentdate = date("Y-m-d");
            // Show offer if it's "All Time" or if dates are valid and within range
            $isAllTime = ($offer->deadline_type == 'alltime' || empty($offer->start_date) || empty($offer->offer_date));
            $isActive = $isAllTime || ($offer->start_date <= $currentdate && $offer->offer_date >= $currentdate);

            
            if ($isActive) {
                $imageFile = !empty($offer->image) ? $offer->image : $offer->list_image;
                $imageFolder = !empty($offer->image) ? 'offers/' : 'offers/listimage/';
                $file_path = SITE_ROOT . 'images/' . $imageFolder . $imageFile;
                
                if (file_exists($file_path) && !empty($imageFile)) {
                    $badge = !empty($offer->tag) ? '
                                       <div class="m-offer-badge"><i class="fa-solid fa-lock"
                                            style="font-size: 10px; margin-right: 4px;"></i> ' . htmlspecialchars($offer->tag) . '</div>
                    

                        ' : '';
                    
                    $dateRange = '';
                    if (!empty($offer->start_date) && !empty($offer->offer_date)) {
                        $dateRange = date("M j, Y", strtotime($offer->start_date)) . ' - ' . date("M j, Y", strtotime($offer->offer_date));
                    } elseif (!empty($offer->offer_date)) {
                        $dateRange = 'Valid until ' . date("M j, Y", strtotime($offer->offer_date));
                    }
                    $calendarHtml = '';
                    if ($offer->deadline_type == 'deadline') {
                        $calendarHtml = '
                        <span class="m-offer-dates">' . $dateRange . '</span>
                        ';
                    }
                    
                    $home_offers .= '
                            <div class="swiper-slide">
                                <a href="' . BASE_URL . 'offer/' . $offer->slug . '" class="m-offer-card">
                                    <img src="' . IMAGE_PATH . $imageFolder . $imageFile . '" alt="' . htmlspecialchars($offer->title) . '"
                                        class="m-offer-bg">
                                            ' . $badge . '
                                    <div class="m-offer-content">
                                        ' . $calendarHtml . '
                                        <h3 class="m-offer-title">' . htmlspecialchars($offer->title) . ' <i
                                                class="fa-solid fa-chevron-right"></i><i
                                                class="fa-solid fa-arrow-up-right"></i></h3>
                                    </div>
                                </a>
                            </div>
           ';
                }
            }
        }
        
        $homeie = '
        <section class="m-offers wow animate__fadeInUp">
            <div class="m-offers-inner">
                <h2 class="m-offers-section-title">Offers &amp; Packages</h2>
                <div class="m-offers-slider-container position-relative">
                    <div class="swiper m-offers-swiper">
                        <div class="swiper-wrapper">
                            ' . $home_offers . '
                        </div>
                    </div>
                    <!-- Mobile Navigation / Shared Pagination -->
                    <div class="d-flex justify-content-center align-items-center d-lg-block mt-4" style="gap: 15px;">
                        <!-- Mobile Prev Arrow -->
                        <div class="m-offers-prev-mob cursor-pointer d-flex d-lg-none align-items-center justify-content-center"
                            style="font-size: 16px; color: #1c1c1c;">
                            <i class="fa-solid fa-chevron-left"></i>
                        </div>

                        <!-- Shared Pagination (Centered automatically in flex on mobile, text-center on desktop via CSS) -->
                        <div class="m-offers-pagination d-flex align-items-center justify-content-center"
                            style="margin-top: 0 !important; width: auto !important;"></div>

                        <!-- Mobile Next Arrow -->
                        <div class="m-offers-next-mob cursor-pointer d-flex d-lg-none align-items-center justify-content-center"
                            style="font-size: 16px; color: #1c1c1c;">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>

                    <!-- Desktop Navigation Arrows -->
                    <div class="m-offers-prev d-none d-lg-flex"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="m-offers-next d-none d-lg-flex"><i class="fa-solid fa-chevron-right"></i></div>
                </div>
            </div>
        </section>';
    }
}
$jVars['module:homeoffers-grid'] = $homeie;


// OFFERS GRID SECTION - for offers listing page
$offers_grid = '';
if (defined('OFFERS_PAGE') and !isset($_REQUEST['slug'])) {
    $offList = Offers::find_all();
    
    if (!empty($offList)) {
        $offerCards = '';
        foreach ($offList as $offer) {
            $currentdate = date("Y-m-d");
            // Show offer if it's "All Time" or if dates are valid and within range
            $isAllTime = ($offer->deadline_type == 'alltime' || empty($offer->start_date) || empty($offer->offer_date));
            $isActive = $isAllTime || ($offer->start_date <= $currentdate && $offer->offer_date >= $currentdate);

            
            if ($isActive) {
                $imageFile = !empty($offer->image) ? $offer->image : $offer->list_image;
                $imageFolder = !empty($offer->image) ? 'offers/' : 'offers/listimage/';
                $file_path = SITE_ROOT . 'images/' . $imageFolder . $imageFile;
                
                if (file_exists($file_path) && !empty($imageFile)) {
                    $badge = !empty($offer->tag) ? '<span class="m-offer-badge"><i class="fa-solid fa-lock"></i> ' . htmlspecialchars($offer->tag) . '</span>' : '';
                    
                    $dateRange = '';
                    if (!empty($offer->start_date) && !empty($offer->offer_date)) {
                        $dateRange = date("M j, Y", strtotime($offer->start_date)) . ' - ' . date("M j, Y", strtotime($offer->offer_date));
                    } elseif (!empty($offer->offer_date)) {
                        $dateRange = 'Valid until ' . date("M j, Y", strtotime($offer->offer_date));
                    }
                    $calendarHtml = '';
                    if ($offer->deadline_type == 'deadline') {
                        $calendarHtml = '
                        <div class="m-offer-location">
                            <i class="fa-regular fa-calendar-days"></i> ' . $dateRange . '
                        </div>';
                    }
                    
                    $offerCards .= '
                    <div class="col-md-6 col-lg-4">
                        <div class="m-offer-v-card">
                            <div class="m-offer-v-img-wrap">
                                ' . $badge . '
                                <img src="' . IMAGE_PATH . $imageFolder . $imageFile . '" alt="' . htmlspecialchars($offer->title) . '">
                            </div>
                            <div class="m-offer-v-content">
                                <h3 class="m-offer-v-title">' . htmlspecialchars($offer->title) . '</h3>
                                ' . $calendarHtml . '
                                <p class="m-offer-v-desc">' . substr(strip_tags($offer->content), 0, 120) . '...</p>
                                <a href="' . BASE_URL . 'offer/' . $offer->slug . '" class="btn m-btn-pill-dark">Details</a>
                            </div>
                        </div>
                    </div>';
                }
            }
        }
        
        $offers_grid = '
        <section class="m-offers-grid-section">
            <div class="container container-custom">
                <div class="row g-4">
                    ' . $offerCards . '
                </div>
            </div>
        </section>';



    }
}
$jVars['module:offers-grid'] = $offers_grid;
$jVars['module:offers-detailed'] = $offer_details;
$jVars['module:more_offers'] = $more_offers_html;
$jVars['module:offer_hero'] = $offer_hero;

$jVars['module:homeoffers-list'] = $hmresoffr;
$jVars['module:offers-details'] = $resinndetail;
$jVars['module:offer_breadcrum'] = $offbredd;


//hompage popup code
$homepopup = '';


if (defined('HOME_PAGE')) {
    $homepopupdatas = offers::get_offer_by_popup();
    // pr($homepopupdatas);
    if (!empty($homepopupdatas)) {
        //modal img
        $count = 1;
        $active = '';
        $homepopup = ' 
     <div class="col-sm-10 center-block center-text">
        <div class="modal fade" id="modal-popup-image">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
					<!--CAROUSEL CODE GOES HERE-->
                        <div id="myCarousel" class="carousel slide">
                            <div class="carousel-inner">		
                            ';
        foreach ($homepopupdatas as $popr) {
            if (!empty($popr->list_image)) {
                $q = $popr->list_image;
                $file_path = SITE_ROOT . 'images/offers/listimage/' . $q;
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'offers/listimage/' . $q;
                } else {
                    $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
                }
                $active = ($count == 1) ? 'active' : '';
                $linkhref = ($popr->linktype == 1) ? $popr->linksrc : BASE_URL . $popr->linksrc;
                $target = ($popr->linktype == 1) ? 'target="_blank"' : '';
                $homepopup .= '  
                <div class="carousel-item ' . $active . '">
                    <a href="' . $linkhref . '" ' . $target . '><img src="' . $imglink . '" alt="' . $popr->title . '"></a>
                </div>
                ';
                // pr($imglink);

                $count++;
            }
        }
        $homepopup .= ' <!--end carousel-inner-->
                        </div>
    ';
        if (sizeof($homepopupdatas) > 1) {
            $homepopup .= '
            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        ';
        }
        $homepopup .= '
                        
                        </div>
                        <!--end carousel-->
                    </div>
                    <!--end modal-body-->
                </div>
                <!--end modal-content-->
            </div>
            <!--end modal-dialoge-->
        </div>
        <!--end myModal-->
    </div>
    <!--end col-->					
';
    }
}

$jVars['module:offer_homepopup'] = $homepopup;


//mutli,dynamic,fixed,none mode for offer detail page 
$resbpkg = '';

if (defined('OFFERS_PAGE') and isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
    $sRec = Offers::find_by_slug($slug);

    if (!empty($sRec)) {
        $resbpkg .= '
	 	<div class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . IMAGE_PATH . 'offers/' . $sRec->image . '); background-repeat: no-repeat; ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text text-center">
                            <div class="breadcrumb-bar position-absolute">
                                <!--<ul class="breadrum-list">
                                    <li><a href="' . BASE_URL . 'home">Home</a></li>
                                    <li><a href="' . BASE_URL . 'offer/' . $sRec->slug . '"> ' . $sRec->title . '</a></li>
                                    <li>Book Now</li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
	 	<section class="gallery-inner">
	 	    <div class="container">
			    <div class="row">
			        <div class="col-md-12">
			            <h4 class="text=center">' . $sRec->title . '</h4>
			        </div>
			    </div>
			    
			    <div class="alert alert-success" id="msg" style="display:none;"></div>
					<form action="" method="post" id="frm-booking">
					    <div class="row">
							<div class="col-lg-7 col-md-7 col-xs-12">
							    <input type="hidden" name="offer_type" value="' . $sRec->type . '">';
        if ($sRec->type == 1) {
            $resbpkg .= '
								<table class="table table-bordered">
									<tr>
										<th>Package</th>
										<th>Price(US$)</th>
										<th>No. of People</th>
										<th>Total Amount</th>
									</tr>

									<tr class="parent">
										<td>
											<a class="text-info" href="' . BASE_URL . 'offer/' . $sRec->slug . '" target="_blank">' . $sRec->title . '</a>
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
										</td>
										<td>
											' . $sRec->rate . '
											<input type="hidden" name="package_price[]" value="' . $sRec->rate . '">
											<input type="hidden" name="package_discount[]" value="' . $sRec->discount . '">
										</td>
										<td class="form-group">
											<!--<input type="text" name="no_pax[]" class="form-control"/>-->
											<select name="no_pax[]" class="form-control">
                                              <option value="">Select</option>
                                              ';
            for ($i = 1; $i <= $sRec->adults; $i++) {
                $resbpkg .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $resbpkg .= '
                                            </select>
										</td>
										<td class="text-center totalamt">0</td>
									</tr>';
            if (!empty($sRec->discount) and $sRec->discount > 0) {
                $resbpkg .= '
                                    <tr>
										<td colspan="3">Discount (' . $sRec->discount . '%)<br>
										<small>* Discount not applicable for only 1 person</small></td>
										<td class="text-center discountamt">0</td>
									</tr>
                    ';
            }
            $resbpkg .= '
                                    <tr>
										<td colspan="3">Grand Total</td>
										<td class="text-center grand-total">0</td>
									</tr>
								</table>
								';
        }

        if ($sRec->type == 0) {
            $resbpkg .= '
								<table class="table">
									<tr>
										<th class="text-center">Choose</th>
										<th class="text-center">Price(US$)</th>
										<th class="text-center">Number Of People</th>
									</tr>
									';
            $sql = "SELECT * FROM tbl_offer_child WHERE offer_id=$sRec->id";
            $query = $db->query($sql);
            $num = $db->num_rows($query);

            if ($num > 0) {
                while ($row = $db->fetch_array($query)) {
                    $resbpkg .= '
                                    <tr class="parent">
										<td class="col-sm-3 text-center">
											<input type="radio" value="' . $row['offer_pax'] . ';;' . $row['offer_usd'] . '" name="radio_type" id="radio_type" style="height:1em;"> 
										</td>
										<td class="col-sm-3 text-center">
											' . $row['offer_usd'] . '
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
											<input type="hidden" name="package_price[]" value="' . $row['offer_usd'] . '">
										</td>
										<td class="col-sm-3 text-center">
											<input type="text" name="no_pax[]" class="hidden" value="' . $row['offer_pax'] . '"/>
											' . $row['offer_pax'] . '
										</td>
									</tr>
                    ';
                }
            }
            $resbpkg .= '
								</table>
								';
        }
        if ($sRec->type == 2) {
            $resbpkg .= '
								<table class="table">
									<tr>
										<th class="text-center">Choose</th>
										<th class="text-center">Items</th>
										<th class="text-center">Price of Item</th>
										<th class="text-center">no of pax</th>
										<th class="text-center">total</th>
									</tr>
									';
            $sql = "SELECT * FROM tbl_offer_child WHERE offer_id=$sRec->id";
            $query = $db->query($sql);
            $num = $db->num_rows($query);

            if ($num >= 0) {
                while ($row = $db->fetch_array($query)) {
                    $resbpkg .= '
                                    <tr class="parent">
										<td class="col-sm-3 text-center">
											<input type="checkbox" name="multi_item[]" value="' . $row['multi_offer_title'] . '|' . $row['multi_offer_npr'] . '"
											
										</td>
										<td class="col-sm-3 text-center">
											' . $row['multi_offer_title'] . '
										</td>
										<td class="col-sm-3 text-center">
											' . $row['multi_offer_npr'] . '
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
											<input type="hidden" name="package_price[]" value="' . $row['multi_offer_npr'] . '">
											<input type="hidden" name="package__item[]" value="' . $row['multi_offer_title'] . '">
										</td>
										<td class="col-sm-3 text-center">
											<input type="number" name="no_pax[]" class="hidden" min="1" value="" disabled/>
										</td>
										<td class="col-sm-3 text-center">
											<div class="row_total"></div>
											<input type="hidden" name="row_hidden[]" class="row_hidden" value=""/>
											</td>
											
											</tr>
											';
                }
            }
            $resbpkg .= '
									<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>Grand Total</td>
									<td class="gtotal">0</td>
									<input type="hidden" class="gtotal" name="multitotal" value=""/>
									</tr>
								</table>
								';
        }
        if ($sRec->type == 3) {
            $resbpkg .= '';
        }

        $resbpkg .= '
							</div>

							<div class="col-lg-5 col-md-5 col-xs-12">
								<div class="row">
    								<div class="form-group col-sm-6">
    						            <input id="person_checkin" name="person_checkin" type="text" placeholder="Check In Date" class="form-control"/>						            
    						        </div>
    						        <div class="clearfix"></div>
    								<div class="form-group col-sm-6">
    						            <input name="person_first" type="text" placeholder="First Name" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_last" type="text" placeholder="Last Name" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_contact" type="text" placeholder="Contact No." class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_email" type="text" placeholder="Email Address" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_address" type="text" placeholder="Address" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <select name="person_country" class="form-control">
    						            	<option value="">Choose</option>';
        $contRec = Countries::find_all();
        foreach ($contRec as $contRow) {
            $resbpkg .= '<option value="' . $contRow->country_name . '">' . $contRow->country_name . '</option>';
        }
        $resbpkg .= '</select>					            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_city" type="text" placeholder="City" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_zpicode" type="text" placeholder="Zip Code" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-12">
    						            <textarea name="person_message" placeholder="Message" class="form-control"></textarea>
    						        </div>						        
    				                <div class="form-group col-sm-6">
    				        			<img src="' . BASE_URL . 'captcha/imagebuilder.php?rand=310333" border="1"  onclick="updateCaptcha(this);">						
    				        		
    				                    <input placeholder="Enter Security Code" type="text" class="form-control" name="userstring" maxlength="5" />
    				                </div>
    				                <div class="form-group col-sm-12">
    						            <button class="btn btn-primary pay-btn" id="submit" type="submit">Submit</button>
    						        </div>
                                </div>
							</div>
						</div>
					</form>						
	   				</div>
				</div>
			
		</section>';
    }
}

$jVars['module:bookpkg_detail'] = $resbpkg;


