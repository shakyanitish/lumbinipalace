<?php
/* First Slideshow */
$reslide = '';

$Records = Slideshow::getSlideshow_by(0);

if ($Records) {
    $reslide .= '
    <section class="marriott-style-banner">
        <div class="ul-banner-slider swiper" style="width:100%;height:100%;">
            <div class="swiper-wrapper">';

    foreach ($Records as $RecRow) {
        $file_path = SITE_ROOT . 'images/slideshow/' . $RecRow->image;
        if (file_exists($file_path) && !empty($RecRow->image)) {
            $reslide .= '
                <div class="swiper-slide">
                    <div class="ul-banner-slide marriott-slide-image" data-img="' . IMAGE_PATH . 'slideshow/' . $RecRow->image . '">
                    </div>
                </div>';
        }
    }

    $reslide .= '
            </div>
        </div>
        <div class="ul-banner-address-slider swiper" style="display:none;">
            <div class="swiper-wrapper">';

    foreach ($Records as $RecRow) {
        $reslide .= '<div class="swiper-slide"></div>';
    }

    $reslide .= '
            </div>
        </div>
        <div class="marriott-banner-bottom-controls">
            <div class="ul-banner-slider-nav marriott-slider-nav">
                <button class="prev"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="ul-banner-slider-pagination marriott-pagination"></div>
                <button class="next"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
            <a href="gallery" class="marriott-gallery-btn">
                <i class="fa-light fa-images"></i> Gallery
            </a>
        </div>
    </section>';
}

$jVars["module:slideshow-uc"] = $reslide;
?>