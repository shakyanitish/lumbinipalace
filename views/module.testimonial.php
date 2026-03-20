<?php

/*
* Testimonial List Home page
*/
$restst = '';
$tstRec = Testimonial::get_alltestimonial(5);
if (!empty($tstRec)) {
    $restst .= '';
    foreach ($tstRec as $tstRow) {
        $slink = !empty($tstRow->linksrc) ? $tstRow->linksrc : 'javascript:void(0);';
        $target = !empty($tstRow->linksrc) ? 'target="_blank"' : '';
        $rating = '';
        for ($i = 0; $i < $tstRow->type; $i++) {
            $rating .= '<a href="#"><i class="fa-solid fa-star"></i></a>';
        }
        $restst .= '';
        $restst .= '
                                <div class="swiper-slide">
                                    <div class="ul-review ul-review-2">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="reviewer-image"><img src="' . IMAGE_PATH . 'testimonial/' . $tstRow->image . '"
                                                        alt="reviewer image"></div>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="ul-review-bottom">
                                                    <div class="ul-review-reviewer">
                                                        <div>
                                                            <h3 class="reviewer-name">' . $tstRow->name . '</h3>
                                                            <span class="reviewer-role">' . $tstRow->via_type . '</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="impact-text">
                                                    <p class="ul-review-descr">
                                                        ' . strip_tags($tstRow->content) . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                    ';
    }
}

$result_last = '
        <section class="ul-testimonial-2 ul-section-spacing">
            <div class="ul-container wow animate__fadeInUp">
                <div class="ul-section-heading">
                    <div>
                        <span class="ul-section-sub-title"> Generous contribution to support communities</span>
                        <h2 class="ul-section-title">Impact stories</h2>
                    </div>
                </div>

                <div class="row ul-testimonial-2-row gy-4">
                    <div class="col-md-9">
                        <div class="ul-testimonial-2-slider swiper">
                            <div class="swiper-wrapper">
                            ' . $restst . '
                            </div>

                            <div class="ul-testimonial-2-slider-nav">
                                <button class="prev"><i class="flaticon-back"></i></button>
                                <button class="next"><i class="flaticon-next"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="ul-testimonial-2-overview">
                            <img src="template/web/assets/img/images.jpg">
                        </div>
                    </div>
                </div>
            </div>
        </section> 
';


$jVars['module:testimonialList123'] = $result_last;



/*
* Testimonial Header Title
*/



/*
* Testimonial Rand
*/
$tstHead = '';

$tstRand = Testimonial::get_by_rand();
if (!empty($tstRand)) {
    $tstHead .= '<!-- Quote | START -->
	<div class="section quote fade">
		<div class="center">
	    
	        <div class="col-1">
	        	<div class="thumb"><img src="' . IMAGE_PATH . 'testimonial/' . $tstRand->image . '" alt="' . $tstRand->name . '"></div>
	            <h5><em>' . strip_tags($tstRand->content) . '</em></h5>
	            <p><span><strong>' . $tstRand->name . ', ' . $tstRand->country . '</strong> (Via : ' . $tstRand->via_type . ')</span></p>
	        </div>
	        
	    </div>
	</div>
	<!-- Quote | END -->';
}

$jVars['module:testimonial-rand'] = $tstHead;


/*
* Testimonial List
*/
$restst = '';
$tstRec = Testimonial::get_alltestimonial(9);

if (!empty($tstRec)) {
    $restst .= '<div class="testimonial-slider owl-carousel owl-theme">';

    foreach ($tstRec as $tstRow) {
        // Rating stars
        $rating = '';
        for ($i = 0; $i < $tstRow->rating; $i++) {
            $rating .= '<i class="fas fa-star"></i>';
        }
        $slink = !empty($tstRow->linksrc) ? $tstRow->linksrc : 'javascript:void(0);';
        $target = !empty($tstRow->linksrc) ? 'target="_blank"' : '';

        $restst .= '
                    <div class="testimonial-single">
                        <div class="testimonial-quote">
                            <span class="testimonial-quote-icon"><i class="fal fa-quote-right"></i></span>
                            <p>' . strip_tags($tstRow->content) . '</p>
                            <div class="testimonial-rate">
                                ' . $rating . '
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="' . IMAGE_PATH . 'testimonial/' . $tstRow->image . '" alt="">
                            </div>
                            <div class="testimonial-author-info">
                                <h4> ' . $tstRow->name . '</h4>
<p><a href="' . $slink . '" ' . $target . '>' . $tstRow->via_type . '</a></p>                            
</div>
                        </div>
                    </div>
                    ';
    }

    $restst .= '</div>';
}
$jVars['module:testimonialList'] = $restst;



// New Home Page Testimonial Structure for Ideal Model
$hometst = '';
if (defined('HOME_PAGE')) {
    $tstRec = Testimonial::get_alltestimonial(5);
    if (!empty($tstRec)) {
        $tstItems = '';
        foreach ($tstRec as $tstRow) {
            $imgsrc = IMAGE_PATH . 'testimonial/' . $tstRow->image;
            $tstItems .= '
            <div class="col-md-6">
                <div class="feedback-inner">
                    <div class="consult-content">
                        <p class="mb-0">' . strip_tags($tstRow->content) . '</p>
                    </div>
                    <div class="consult-title mt-3 d-flex justify-content-start">
                        <img src="' . $imgsrc . '" alt="' . $tstRow->name . '" />
                        <div class="ps-name">
                            <h5 class="mb-0">' . $tstRow->name . '</h5>
                            <span class="cl-orange">' . $tstRow->via_type . '</span>
                        </div>
                    </div>
                </div>
            </div>';
        }

        $hometst = '
        <section class="testimonial">
            <div class="container">
                <div class="section-title sc-center justify-content-center text-center borderline">
                    <div class="title-top">
                        <div class="title-quote">
                            <span>Our Reviews</span>
                        </div>
                        <h2>Student & Alumni <span class="cl-blue">Testimonials</span></h2>
                    </div>
                </div>

                <div class="row review-slider feedback-main wow fadeInUp">
                    ' . $tstItems . '
                </div>
            </div>
        </section>';
    }
}
$jVars['module:home-testimonial'] = $hometst;



$alumini_list = '';
if (defined("ALUMINI_PAGE")) {
    $sql = "SELECT * FROM tbl_testimonial WHERE status='1' ORDER BY sortorder DESC";
    $aluminiRecs = Testimonial::find_by_sql($sql);
    if (!empty($aluminiRecs)) {
        foreach ($aluminiRecs as $alumini) {
            $img = BASE_URL . "template/web/images/user-2.jpg";
            if (!empty($alumini->image)) {
                $file_path = SITE_ROOT . "images/testimonial/" . $alumini->image;
                if (file_exists($file_path)) {
                    $img = IMAGE_PATH . "testimonial/" . $alumini->image;
                }
            }

            $alumini_list .= '
            <div class="about-us-wrap-alumini">
                <div class="row justify-content-md-center">
                    <div class="col-lg-3 col-md-12 wow fadeInLeftBig">
                        <div class="about-wrap-img">
                            <img src="' . $img . '" alt="' . $alumini->name . '" />
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-12 wow fadeInRightBig">
                        <div class="about-us-wrap">
                            <div class="about-title">
                                <div class="ps-name">
                                    <h3 class="mb-0 text-start">' . $alumini->name . '</h3>
                                    <span class="cl-orange">' . $alumini->via_type . '</span>
                                </div>
                            </div>
                            <div class="about-content">
                                <p class="mb-0">' . strip_tags($alumini->content) . '</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
}
$jVars["module:combinednews:alumni"] = $alumini_list;
