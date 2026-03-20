<?php
$reslgall = '';
$res_gallery = '';
$gallRec = Gallery::getParentgallery(2);
if (!empty($gallRec)) {
    foreach ($gallRec as $gallRow) {
        $childRec = GalleryImage::getGalleryImages($gallRow->id);
        if (!empty($childRec)) {
            $reslgall .= '';
            foreach ($childRec as $childRow) {
                $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $childRow->image;
                if (file_exists($file_path) and !empty($childRow->image)) {
                    $reslgall .= '
                <div class="gallery-image">
                    <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $childRow->image . '" alt="' . $childRow->title . '">
                </div>
                    ';
                }
            }
            $reslgall .= '';
        }
    }
}

$res_gallery = '
                <!-- Gallery starts -->
                <section class="content gallery gallery1">
                    <div class="container">
                        <div class="section-title title-white">
                            <h2>Beautiful View of <span>Shangrila Blu</span></h2>
                            <p class="mar-bottom-30">Few collection of our pictures. We are quiet sure that you will find it more beautiful once you physically visit us.</p>
                        </div>
                    </div>
                    <div class="gallery-main gallery-slider">
                        ' . $reslgall . '
                    </div>
                </section>
                <!-- Gallery Ends -->';

$jVars['module:galleryHome'] = $res_gallery;



$dininggallery = '';
$galldining = GalleryImage::getImagelist_by(19, 3);
if (!empty($galldining)) {
    $dininggallery .= '<div class="row about">
                     <div class="demo-gallery">
    		     <div id="lightgallery" class="list-unstyled">';
    foreach ($galldining as $row) {
        $dininggallery .= '<div class="item col-sm-4 col-xs-12" data-responsive="' . IMAGE_PATH . 'gallery/galleryimages/' . $row->image . '" data-src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row->image . '" data-sub-html="<h4>' . $row->title . '</h4>">
                        <a href="">
                            <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row->image . '"/>
                        </a>
                    </div>';
    }
    $dininggallery .= '</div>
    </div>
    </div>';
}
$jVars['module:dining-gallery'] = $dininggallery;

$gallerybread = '';
$videobread = '';
$siteRegulars = Config::find_by_id(1);
$imglink = $siteRegulars->gallery_upload;
if (!empty($imglink)) {
    $img = IMAGE_PATH . 'preference/gallery/' . $siteRegulars->gallery_upload;
}
else {
    $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
}

$gallerybread = '

        <div class="site-breadcrumb" style="background: url(' . $img . ')">
            <div class="container">
                <h2 class="breadcrumb-title">Photo Gallery</h2>
            </div>
        </div>';

$jVars['module:gallery-bread'] = $gallerybread;

$videobread = '

        <div class="site-breadcrumb" style="background: url(' . $img . ')">
            <div class="container">
                <h2 class="breadcrumb-title">Video Gallery</h2>
            </div>
        </div>';

$jVars['module:video-bread'] = $videobread;


/**
 *      Main Gallery
 */
$thegal = $gallerylistbread = $thegalnav = '';
$gallRectit = Gallery::getParentgallery();

if ($gallRectit) {
    $navCount = 0;
    foreach ($gallRectit as $row) {
        $activeClass = ($navCount === 0) ? ' active' : '';
        $thegalnav .= '

                    <li class="nav-item">
                        <a class="nav-link m-gallery-nav-link font-secondary' . $activeClass . '" href="#' . $row->slug . '">' . $row->title . '</a>
                    </li>';
        $navCount++;
    }

    $count = 0;
    foreach ($gallRectit as $row) {
        $gallRec = GalleryImage::getGalleryImages($row->id);
        if (!empty($gallRec)) {
            if ($count > 0) {
                $thegal .= '
            <div class="m-gallery-section-divider"></div>';
            }

            $thegal .= '
            <!-- ' . strtoupper($row->title) . ' -->
            <div id="' . $row->slug . '" class="mb-5 pb-4 m-gallery-section">
                <h3 class="text-center text-uppercase m-gallery-subtitle font-secondary mb-4 pb-3">' . $row->title . '</h3>
                <div class="row g-4 justify-content-start">';

            foreach ($gallRec as $row1) {
                $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $row1->image;
                if (file_exists($file_path) and !empty($row1->image)):
                    $thegal .= '
                    <div class="col-md-4">
                        <div class="m-gallery-img-wrap">
                            <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row1->image . '" alt="' . $row1->title . '">
                        </div>
                    </div>';
                endif;
            }
            $thegal .= '
                </div>
            </div>';
            $count++;
        }
    }
}

$jVars['module:gallery-list'] = $thegal;
$jVars['module:gallery-nav'] = $thegalnav;




// <video src=""></video>



$videomain = '';
if (defined('HOME_PAGE')) {
    $videodatas = Video::getAllVideos();
    if (!empty($videodatas)) {
        $videoitems = '';
        foreach ($videodatas as $videodata) {
            $videoitems .= ' 
                    <div class="col-lg-4 col-md-6 col-sm-12 customize-wrap mb-4 wow fadeInUp">
                        <div class="customize-item">
                            <h4>' . $videodata->title . '</h4>
                            <iframe width="560" height="315" src="' . $videodata->url . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>

';
        }

        $videomain .= '  
            <section class="courses">
        <div class="container">
            <div class="section-title borderline">
                <div class="title-top">
                    <h3>OUR <span class="cl-blue">STORIES</span></h3>
                    <p>Experience transformative education at Ideal Model School, shaping brighter future.</p>
                </div>
            </div>

            <div class="wrap-customize">
                <div class="row">
                ' . $videoitems . '
                </div>
            </div>
        </div>
    </section>
        
        ';
    }

// pr($videodatas);


}
$jVars['module:video-list'] = $videomain;


/**
 *      Gallery Lightbox
 */
$galleryLightbox = '';

// Get all gallery categories
$galleryCategories = Gallery::getParentgallery();

if (!empty($galleryCategories)) {
    // Build dynamic tabs and lightbox content
    $tabsHTML = '';
    $allImages = '';
    $tabCount = 0;
    $totalImages = 0;
    
    foreach ($galleryCategories as $category) {
        // Get images for this category
        $images = GalleryImage::getGalleryImages($category->id);
        
        if (!empty($images)) {
            // Add tab button
            $isActive = ($tabCount === 0) ? 'active' : '';
            $tabsHTML .= '<li class="nav-item m-lightbox-tab ' . $isActive . '" data-category="' . htmlspecialchars($category->slug) . '">' . htmlspecialchars($category->title) . '</li>';
            
            // Add images for this category
            foreach ($images as $image) {
                $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $image->image;
                if (file_exists($file_path) && !empty($image->image)) {
                    $allImages .= '<div class="m-lightbox-image" data-category="' . htmlspecialchars($category->slug) . '" data-src="' . IMAGE_PATH . 'gallery/galleryimages/' . $image->image . '" data-title="' . htmlspecialchars($image->title) . '" style="display: ' . (htmlspecialchars($category->slug) === ($galleryCategories[0]->slug ?? '') ? 'block' : 'none') . ';">
                        <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $image->image . '" alt="' . htmlspecialchars($image->title) . '">
                    </div>';
                    $totalImages++;
                }
            }
            
            $tabCount++;
        }
    }
    
    // Build lightbox HTML
    $galleryLightbox = '
    <!-- GALLERY LIGHTBOX MODAL -->
    <div id="m-gallery-lightbox" class="m-lightbox-overlay d-none">
        <div class="m-lightbox-header">
            <ul class="nav m-lightbox-tabs">
                ' . $tabsHTML . '
            </ul>
            <div class="m-lightbox-close"><i class="fa-light fa-xmark"></i></div>
        </div>
        <div class="m-lightbox-content">
            <button class="m-lightbox-nav m-lightbox-prev"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="m-lightbox-img-container">
                <img id="m-lightbox-img" src="" alt="">
            </div>
            <button class="m-lightbox-nav m-lightbox-next"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="m-lightbox-footer text-center">
            <div class="m-lightbox-metadata">
                <span id="m-lightbox-counter">1 of ' . $totalImages . '</span> - <span id="m-lightbox-title">Gallery Image</span>
            </div>
        </div>
    </div>';
}

$jVars['module:gallery-lightbox'] = $galleryLightbox;
