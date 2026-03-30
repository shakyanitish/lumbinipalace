<?php
$resoffr = $socialshare = '';
$expired = '';
$enquiry = '';
$resrandoffr = $hmresoffr = $resinndetail = $offbredd = '';
$offrRec = Offers::get_offer_by();

if (defined('OFFERS_PAGE') and isset($_REQUEST['slug'])) {
    $slug = addslashes($_REQUEST['slug']);
    $recRow = Offers::find_by_slug($slug);
    if (!empty($recRow)) {

        if (!empty($recRow->image)) {
            $imglink = IMAGE_PATH . 'offers/' . $recRow->image;
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

        $offbredd .= '<section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . $imglink . '); background-repeat: no-repeat; "> 
               
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                                <ul class="breadcrumb">
                                    <li><a href="' . BASE_URL . '">Home</a></li>
                                    <li><a href="' . BASE_URL . 'offer-list">Offer</a></li>
                                    <li>' . $recRow->title . '</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
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

if (defined('HOME_PAGE')) {
    $sql = "SELECT * FROM tbl_offers WHERE status='1' and homepage='1' AND (deadline_type='alltime' OR (start_date IS NOT NULL AND offer_date IS NOT NULL AND CURDATE() BETWEEN start_date AND offer_date)) ORDER BY sortorder DESC ";
    $offrRec = Offers::find_by_sql($sql);
    if ($offrRec) {
        $slides = '';
        foreach ($offrRec as $offrRow) {
            // Use image if available, fallback to list_image
            $imageFile = !empty($offrRow->image) ? $offrRow->image : $offrRow->list_image;
            $imageFolder = !empty($offrRow->image) ? 'offers/' : 'offers/listimage/';
            $file_path = SITE_ROOT . 'images/' . $imageFolder . $imageFile;
            if (file_exists($file_path) && !empty($imageFile)) {
                $badge = !empty($offrRow->tag) ? '
                    <div class="m-offer-badge"><i class="fa-solid fa-lock" style="font-size: 10px; margin-right: 4px;"></i> ' . $offrRow->tag . '</div>' : '';

                $dateRange = '';
                if (!empty($offrRow->start_date) && !empty($offrRow->offer_date)) {
                    $dateRange = date("F j, Y", strtotime($offrRow->start_date)) . ' - ' . date("F j, Y", strtotime($offrRow->offer_date));
                } elseif (!empty($offrRow->offer_date)) {
                    $dateRange = 'Valid until ' . date("F j, Y", strtotime($offrRow->offer_date));
                }

                $slides .= '
                <div class="swiper-slide">
                    <a href="' . BASE_URL . 'offer/' . $offrRow->slug . '" class="m-offer-card">
                        <img src="' . IMAGE_PATH . $imageFolder . $imageFile . '" alt="' . $offrRow->title . '" class="m-offer-bg">
                        ' . $badge . '
                        <div class="m-offer-content">
                            <span class="m-offer-dates">' . $dateRange . '</span>
                            <h3 class="m-offer-title">' . $offrRow->title
                    . ' <i class="fa-solid fa-chevron-right"></i><i class="fa-light fa-arrow-up-right"></i></h3>
                        </div>
                    </a>
                </div>';
            }
        }

        $hmresoffr = '
        <section class="m-offers wow animate__fadeInUp">
            <div class="m-offers-inner">
                <h2 class="m-offers-section-title">Offers &amp; Packages</h2>
                <div class="m-offers-slider-container position-relative">
                    <div class="swiper m-offers-swiper">
                        <div class="swiper-wrapper">
                            ' . $slides . '
                        </div>
                    </div>
                    <!-- Navigation / Pagination -->
                    <div class="m-offers-prev"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="m-offers-next"><i class="fa-solid fa-chevron-right"></i></div>
                    <div class="m-offers-pagination"></div>
                </div>
            </div>
        </section>';
    }
}


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
                    
                    $offerCards .= '
                    <div class="col-md-6 col-lg-4">
                        <div class="m-offer-v-card">
                            <div class="m-offer-v-img-wrap">
                                ' . $badge . '
                                <img src="' . IMAGE_PATH . $imageFolder . $imageFile . '" alt="' . htmlspecialchars($offer->title) . '">
                            </div>
                            <div class="m-offer-v-content">
                                <h3 class="m-offer-v-title">' . htmlspecialchars($offer->title) . '</h3>
                                <div class="m-offer-location">
                                    <i class="fa-regular fa-calendar-days"></i> ' . $dateRange . '
                                </div>
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

$jVars['module:homeoffers-list'] = $hmresoffr;
$jVars['module:offers-details'] = $resinndetail;
$jVars['module:offers-grid'] = $offers_grid;
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


