<?php
$booking_code = Config::getField('hotel_code', true);// true simply return single value (not an array)


/*
* Home accmodation list
*/
$reshmpkg = '';// stores html code for display rooms
$imageList = '';// stores image list

if (defined('HOME_PAGE')) {// check we are in the home page or not
    $acid = Package::get_accommodationId();// gets accomodation id
    $pkgRec = Package::find_by_id($acid);//fetches the accomodation details like rooms or suites using the id
    // gets subpackage individual rooms or suites
    if (!empty($pkgRec)) {
        $subRec = Subpackage::getPackage_limit($acid);

        if (!empty($subRec)) {
            $imglink = '';
            $reshmpkg .= '';

            $reshmpkg .= "";
            foreach ($subRec as $subRow) {

                $features_of_rooms = '';
                if ($subRow->class_room_style == 'best_deal') {
                    $features_of_rooms = '<div class="tags discount">Best Deal</div>';
                } elseif ($subRow->class_room_style == 'featured_room') {
                    $features_of_rooms = '<div class="tags featured">Featured Room</div>';
                }

                $img123 = unserialize($subRow->image);//converts stores image string to array

                if (!empty($subRow->image)) {//picks first image from the array

                    $imgpath = IMAGE_PATH . 'subpackage/' . $img123[0];//if image exist, set the image path (subpackage/filename.jpg).
                } else {
                    $imgpath = IMAGE_PATH . 'static/inner-img.jpg';//If not, default fallback image (static/inner-img.jpg).
                }
                $file_path = SITE_ROOT . 'images/subpackage/' . $img123[0];//checks file path exist in server
                if (file_exists($file_path) and !empty($subRow->image)) {//if only exist then it start to generate html codes
                    $reshmpkg .= '      
                            <div class="col-md-4 room-item wow fadeInUp" data-wow-delay=".4s">
                               <div class="inner">
                                   ' . $features_of_rooms . '
                                   <img src="' . $imgpath . '" class="img-responsive" alt="' . $subRow->title . '">
                                   <h3>' . $subRow->title . '</h3>
                                   <div class="price_from">Start From <span>' . $subRow->currency . ' ' . $subRow->onep_price . '++</span>/night</div>
                                   <div class="spacer-half"></div>
                                   <a href="' . BASE_URL . $subRow->slug . '" class="btn-detail">View Details</a>
                               </div>
                           </div>
                                ';

                }
            }
            $reshmpkg .= '';
        }
    }


}


$jVars['module:home-accommodation'] = $reshmpkg;


/*
* Home sub package list
*/
$newpkg = '';

if (defined('HOME_PAGE')) {
//$slug = !empty($_REQUEST['slug'])? addslashes($_REQUEST['slug']) : '';
//$pkgRec = Package::getPackage();
//if (!empty($pkgRec)) {

    /* foreach($pkgRec as $pkgRow) {
        $imglink = '';*/
    /* if ($pkgRow->banner_image != "a:0:{}") {
         $imageList = unserialize($pkgRow->banner_image);
         $file_path = SITE_ROOT . 'images/package/banner/' . $imageList[0];
         if (file_exists($file_path)) {
             $imglink = IMAGE_PATH . 'package/banner/' . $imageList[0];
         }
     } */
    // if(($pkgRow->type)==0) {
    $newpkg .= '<div class="col-sm-6">
                <div class="mosaic_container">
                     <a href="' . BASE_URL . 'page/about-us">
                    <img src="' . BASE_URL . 'template/web/img/mosaic_1.jpg" alt="image" class="img-responsive add_bottom_30"><span class="caption_2">Experience Peninsula</span>
                    </a>
                </div>
            </div>';
    //}else{
    $newpkg .= '<div class="col-sm-6">
         
         <div class="col-xs-12">
                    <div class="mosaic_container">
                        <a href="' . BASE_URL . 'services">
                        <img src="' . BASE_URL . 'template/web/img/mosaic_2.jpg" alt="image" class="img-responsive add_bottom_30"><span class="caption_2">Services & Faciities</span>
                        </a>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="mosaic_container">
                        <a href="' . BASE_URL . 'rooms">
                        <img src="' . BASE_URL . 'template/web/img/room.jpg" alt="rooms" class="img-responsive add_bottom_30"><span class="caption_2">
Accommodation</span>
                        </a>
                    </div>
                </div>
                  <div class="col-xs-6">
                     <a href="' . BASE_URL . 'dining">
                    <div class="mosaic_container">
                        <img src="' . BASE_URL . 'template/web/img/dining.jpg" alt="dining" class="img-responsive add_bottom_30"><span class="caption_2">Dining & Bar</span>
                    </div>
                    </a>
                </div>
                
                  </div>
                ';

    //}
    //}
//}
}
$jVars['module:newpkg'] = $newpkg;


/////
$reshplist = $pakagehometype = '';
$cnt = 1;
if (defined('HOME_PAGE')) {
    $pgkRows = Package::find_by_id(1);
    $pkgRec = Subpackage::getPackage_limits(1, 6);

    if (!empty($pkgRec)) {

        foreach ($pkgRec as $pkgRow) {
            //echo "<pre>";print_r($pkgRow);die();

            //if(!empty($pkgRow->image2)) {


            //echo "<pre>";print_r($reshplist);die();
            if (($cnt % 3) == 2) {
                $reshplist .= ' <div class="container margin_60">
        <div class="row">
            <div class="col-md-5 col-md-offset-1 col-md-push-5">
                  <figure class="room_pic left"><a href="' . BASE_URL . '' . $pkgRow->slug . '"><img src="' . IMAGE_PATH . 'subpackage/image/' . $pkgRow->image2 . '" alt="' . $pkgRow->title . '" class="img-responsive"></a><span class="wow zoomIn"><sup>' . $pkgRow->currency . ' </sup>' . $pkgRow->onep_price . '<small>Per night</small></span></figure>
            </div>
            <div class="col-md-4 col-md-offset-1 col-md-pull-6">
                <div class="room_desc_home">
                    <h3>' . $pkgRow->title . '</h3>
                    <p>
                         ' . $pkgRow->detail . ' 
                    </p>
                    <ul>';
                $saveRec = unserialize($pkgRow->feature);
                $count = 1;
                if ($saveRec != null) {
                    $featureList = $saveRec[47][1];
                    //echo "<pre>";print_r($featureList);die();

                    if (!empty($featureList)) {
                        $icoRec = '';

                        foreach ($featureList as $fetRow) {

                            $icoRec = Features::get_by_id($fetRow);
                            $reshplist .= '<li>
                            <div class="tooltip_styled tooltip-effect-4">
                                <span class="tooltip-item"><i class="' . $icoRec->icon . '"></i></span>
                                    <div class="tooltip-content">' . $icoRec->title . '</div>
                              </div>
                              </li>';


                            if ($count++ == 5) break;
                        }
                    }
                }
                $reshplist .= '</ul>
                    <a href="' . BASE_URL . '' . $pkgRow->slug . '" class="btn_1_outline">Read more</a>
                </div><!-- End room_desc_home -->
            </div>
        </div><!-- End row -->
    </div>';

            } else {
                $reshplist .= '  <div class="container_styled_1">
        <div class="container margin_60">
            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <figure class="room_pic"><a href="' . BASE_URL . '' . $pkgRow->slug . '"><img src="' . IMAGE_PATH . 'subpackage/image/' . $pkgRow->image2 . '" alt="' . $pkgRow->title . ' " class="img-responsive"></a><span class="wow zoomIn"><sup>' . $pkgRow->currency . ' </sup>' . $pkgRow->onep_price . '<small>Per night</small></span></figure>
                </div>
                <div class="col-md-4 col-md-offset-1">
                    <div class="room_desc_home">
                        <h3>' . $pkgRow->title . '  </h3>
                        <p>
                            ' . $pkgRow->detail . '
                        </p>
                        <ul>';
                $saveRec = unserialize($pkgRow->feature);
                $count = 1;
                if ($saveRec != null) {
                    $featureList = $saveRec[47][1];
                    //echo "<pre>";print_r($featureList);die();

                    if (!empty($featureList)) {
                        $icoRec = '';

                        foreach ($featureList as $fetRow) {

                            $icoRec = Features::get_by_id($fetRow);
                            $reshplist .= '<li>
                            <div class="tooltip_styled tooltip-effect-4">
                                <span class="tooltip-item"><i class="' . $icoRec->icon . '"></i></span>
                                    <div class="tooltip-content">' . $icoRec->title . '</div>
                              </div>
                              </li>';


                            if ($count++ == 5) break;
                        }
                    }
                }
                $reshplist .= '</ul>
                        <a href="' . BASE_URL . '' . $pkgRow->slug . '" class="btn_1_outline">Read more</a>
                    </div><!-- End room_desc_home -->
                </div>
            </div><!-- End row -->
        </div><!-- End container -->
    </div>';
            }
            $cnt++;
//}

        }
    }
    /* $reshplist.= '</div>
                 </div>
             </div>';*/
}

$jVars['module:home-packagelist'] = $reshplist;
$jVars['module:home-package-type-list'] = $pakagehometype;


$roomlist = $roombread = '';
$modalpopup = '';
$room_package = '';
if (defined('PACKAGE_PAGE') and isset($_REQUEST['slug'])) {

    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';

    $pkgRow = Package::find_by_slug($slug);
    if ($pkgRow->type == 1) {

        $imglink = BASE_URL . 'template/web/images/bg/room-banner.jpg';
        $pkgRowImg = $pkgRow->banner_image;
        if ($pkgRowImg != "a:0:{}") {
            $pkgRowList = unserialize($pkgRowImg);
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            if (file_exists($file_path) and !empty($pkgRowList[0])) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            }
        }

        $roombread .= '
    <!--================ Breadcrumb ================-->
    <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="' . $imglink . '">
        <div class="container wide">
            <h1 class="mad-page-title">' . $pkgRow->title . '</h1>
            <nav class="mad-breadcrumb-path">
                <span><a href="index.html" class="mad-link">Home</a></span> /
                <span>' . $pkgRow->title . '</span>
            </nav>
        </div>
    </div>
    <!--================ End of Breadcrumb ================-->

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


            foreach ($pkgRec as $key => $subpkgRow) {
                $imageList = '';
                if ($subpkgRow->image != "a:0:{}") {
                    $imageList = unserialize($subpkgRow->image);
                }


                $roomlist .= '
            <div class="mad-col">
                        <!--================ Entity ================-->
                        <article class="mad-entity">
                            <div class="mad-entity-media">
                                <a href="' . BASE_URL . $subpkgRow->slug . '">
                                    <img src="' . IMAGE_PATH . 'subpackage/' . $imageList[0] . '" alt="" />
                                </a>
                            </div>
                            <div class="mad-entity-content">
                                <h4 class="mad-entity-title">' . $subpkgRow->title . '</h4>
                                <div class="mad-pricing-value">
                                    <span>From</span>
                                    <span class="mad-pricing-value-num">' . $subpkgRow->currency . $subpkgRow->onep_price . '/</span>
                                    <span>night</span>
                                </div>
                                <div class="mad-entity-footer">
                                    <div class="btn-set justify-content-center">
                                        <a href="' . BASE_URL . 'result.php?hotel_code=' . $booking_code . '" class="btn btn-big" target="_blank">Book Now</a>
                                        <a href="' . BASE_URL . $subpkgRow->slug . '" class="btn btn-big style-2">Details</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!--================ End of Entity ================-->
                    </div>

                
                ';

            }
            $room_package = '
                <!-- Rooms starts -->
                <div class="mad-content">
            <div class="container-fluid">
                <div class="mad-entities with-hover align-center type-3 item-col-3">
                       
                                ' . $roomlist . '
                                </div>
                                </div>
                            </div>
                <!-- Room Ends -->';
        }
    } else {
        $imglink = BASE_URL . 'template/web/images/default.jpg';
        $pkgRowImg = $pkgRow->banner_image;
        if ($pkgRowImg != "a:0:{}") {
            $pkgRowList = unserialize($pkgRowImg);
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            if (file_exists($file_path) and !empty($pkgRowList[0])) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            } else {
                $imglink = BASE_URL . 'template/web/images/default.jpg';
            }
        }

        $roombread .= '
    <!--================ Breadcrumb ================-->
    <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="' . $imglink . '">
        <div class="container wide">
            <h1 class="mad-page-title">' . $pkgRow->title . '</h1>
            <nav class="mad-breadcrumb-path">
                <span><a href="index.html" class="mad-link">Home</a></span> /
                <span>' . $pkgRow->title . '</span>
            </nav>
        </div>
    </div>
    <!--================ End of Breadcrumb ================-->

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
                if ($subpkgRow->image != "a:0:{}") {
                    $imageList = unserialize($subpkgRow->image);
                }
                if ($pkgRow->id == 11) {
                    $button = '<a href="contact-us" class="btn">Book Now</a>';
                    if (!empty($subpkgRow->below_content)) {
                        $modal = '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#' . $subpkgRow->slug . '">
                details
              </button>';
                    } else {
                        $modal = '';
                    }
                } else {
                    $button = '<a href="#" class="btn">View Menu</a>';
                }

                if ($subpkgRow->type == 11) {

                    $modalpopup .= '
        <div class="modal fade" id="' . $subpkgRow->slug . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">' . $subpkgRow->title . ' details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            ' . $subpkgRow->below_content . '
            </div>
          </div>
        </div>
      </div>';
                    if ($count % 2 == 1) {
                        $roomlist .= '
            <div class="mad-section mad-section--stretched mad-colorizer--scheme-color-4">
                    <div class="mad-entities style-3 type-4">
                        <!--================ Entity ================-->
                        <article class="mad-entity">
                            <div class="mad-entity-media">
                                <div class="owl-carousel mad-simple-slideshow mad-grid with-nav">
                                    ' . $subpkg_caro . '
                                </div>
                            </div>

                            <div class="mad-entity-content">
                                <h2 class="mad-entity-title">' . $subpkgRow->title . '</h2>
                                <p>' . strip_tags($subpkgRow->content) . '</p>
                                <div class="mad-rest-info">
                                    <div class="mad-rest-info-item">
                                        <span class="mad-rest-title">Hall Amenities</span>
                                        <span>' . $subpkgRow->cocktail . '</span>
                                    </div>
                                    <div class="mad-rest-info-item">
                                        <span class="mad-rest-title">Size</span>
                                        <span>' . $subpkgRow->seats . '</span>
                                    </div>
                                </div>
                                ' . $button . ' ' . $modal . '
                                </div>


                        </article>
                        <!--================ End of Entity ================-->
                    </div>
                </div>

                
                ';

                    } else {
                        $roomlist .= '<div class="mad-section">
                <div class="mad-entities mad-entities-reverse type-4">
                    <!--================ Entity ================-->
                    <article class="mad-entity">
                        <div class="mad-entity-media">
                            <div class="owl-carousel mad-simple-slideshow mad-grid with-nav">
                            ' . $subpkg_caro . '
                            </div>
                        </div>
                        <div class="mad-entity-content">
                            <h2 class="mad-entity-title">' . $subpkgRow->title . '</h2>
                            <p>' . strip_tags($subpkgRow->content) . '</p>
                            <div class="mad-rest-info">
                            <div class="mad-rest-info-item">
                            <span class="mad-rest-title">Hall Amenities</span>
                            <span>' . $subpkgRow->cocktail . '</span>
                        </div>
                        <div class="mad-rest-info-item">
                            <span class="mad-rest-title">Size</span>
                            <span>' . $subpkgRow->seats . '</span>
                        </div>
                            </div>
                            ' . $button . ' ' . $modal . '
                        </div>

                    </article>
                    <!--================ End of Entity ================-->
                </div>
            </div>';
                    }
                    $count++;


                }


                if ($subpkgRow->type == 12) {
                    if ($count % 2 == 1) {
                        $roomlist .= '
            <div class="mad-section mad-section--stretched mad-colorizer--scheme-color-4">
                    <div class="mad-entities style-3 type-4">
                        <!--================ Entity ================-->
                        <article class="mad-entity">
                            <div class="mad-entity-media">
                                <div class="owl-carousel mad-simple-slideshow mad-grid with-nav">
                                    ' . $subpkg_caro . '
                                </div>
                            </div>

                            <div class="mad-entity-content">
                                <h2 class="mad-entity-title">' . $subpkgRow->title . '</h2>
                                <p>' . strip_tags($subpkgRow->content) . '</p>
                                <div class="mad-rest-info">
                                    <div class="mad-rest-info-item">
                                        <span class="mad-rest-title">Opening hours</span>
                                        <span>' . $subpkgRow->theatre_style . ' <br />' . $subpkgRow->class_room_style . '</span>
                                    </div>
                                    <div class="mad-rest-info-item">
                                        <span class="mad-rest-title">Cuisine</span>
                                        <span>' . $subpkgRow->shape . '</span>
                                    </div>
                                    <div class="mad-rest-info-item">
                                        <span class="mad-rest-title">Dess Code</span>
                                        <span>' . $subpkgRow->round_table . '</span>
                                    </div>
                                </div>
                                ' . $button . '
                                </div>
                        </article>
                        <!--================ End of Entity ================-->
                    </div>
                </div>

                
                ';
                    } else {
                        $roomlist .= '<div class="mad-section">
                <div class="mad-entities mad-entities-reverse type-4">
                    <!--================ Entity ================-->
                    <article class="mad-entity">
                        <div class="mad-entity-media">
                            <div class="owl-carousel mad-simple-slideshow mad-grid with-nav">
                            ' . $subpkg_caro . '
                            </div>
                        </div>
                        <div class="mad-entity-content">
                            <h2 class="mad-entity-title">' . $subpkgRow->title . '</h2>
                            <p>' . strip_tags($subpkgRow->content) . '</p>
                            <div class="mad-rest-info">
                                <div class="mad-rest-info-item">
                                    <span class="mad-rest-title">Opening hours</span>
                                    <span>' . $subpkgRow->theatre_style . '<br />' . $subpkgRow->class_room_style . ' </span>
                                </div>
                                <div class="mad-rest-info-item">
                                    <span class="mad-rest-title">Cuisine</span>
                                    <span>' . $subpkgRow->shape . '</span>
                                </div>
                                <div class="mad-rest-info-item">
                                    <span class="mad-rest-title">Dess Code</span>
                                    <span>' . $subpkgRow->round_table . '</span>
                                </div>
                            </div>
                            ' . $button . '
                        </div>

                    </article>
                    <!--================ End of Entity ================-->
                </div>
            </div>';
                    }
                    $count++;

                }

            }
            $room_package = '
                <!-- Rooms starts -->
                <div class="mad-content no-pd">
            <div class="container">
                <div class="mad-section">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="mad-pre-title">M.I.C.E</div>
                            <h2 class="mad-page-title" style="font-size: 42px;line-height: 46px;">' . $pkgRow->sub_title . '</h2>
                        </div>
                        <div class="col-lg-7">
                            <p class="mad-text-medium">' . $pkgRow->content . '
                            </p>
                        </div>
                    </div>
                </div>
                                ' . $roomlist . '
                            </div>
                        </div>
                    
                
                <!-- Room Ends -->';
        }

    }
    if ($pkgRow->id >= 14) {

        $room_package = '
                <!-- Rooms starts -->
                <div class="mad-content no-pd">
            <div class="container">
                <div class="mad-section">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="mad-pre-title">' . $pkgRow->title . '</div>
                            <h2 class="mad-page-title" style="font-size: 42px;line-height: 46px;">' . $pkgRow->sub_title . '</h2>
                        </div>
                        
                    </div>
                    <div class="col-lg-7">
                            <p class="mad-text-medium">' . $pkgRow->content . '
                            </p>
                        </div>
                </div>
                            </div>
                        </div>
                    
                
                <!-- Room Ends -->';
    }
}


if (defined('HOME_PAGE')) {


    $sql = "SELECT *  FROM tbl_package_sub WHERE status='1' AND type = '1' ORDER BY sortorder DESC ";

    $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"])) ? $_REQUEST["pageno"] : 1;
    $limit = 200;
    $total = $db->num_rows($db->query($sql));
    $startpoint = ($page * $limit) - $limit;
    $sql .= " LIMIT " . $startpoint . "," . $limit;
    $query = $db->query($sql);
    $pkgRec = Subpackage::find_by_sql($sql);


    // pr($pkgRec);
    if (!empty($pkgRec)) {

        foreach ($pkgRec as $key => $subpkgRow) {
            $gallRec = SubPackageImage::getImagelist_by($subpkgRow->id);
            $imageList = '';
            $imagepath = '';
            $imageList = $gallRec[0];


            $file_path = SITE_ROOT . 'images/package/galleryimages/' . $imageList->image;
            if (file_exists($file_path) and !empty($imageList)):

                $imagepath = IMAGE_PATH . 'package/galleryimages/' . $imageList->image;


            endif;
// pr($imagepath);

            $roomlist .= '
            <div class="mad-col">
                                <div class="mad-section with-overlay mad-colorizer--scheme-" data-bg-image-src="' . $imagepath . '" alt="' . $subpkgRow->title . '">
                                    <!--================ Entity ================-->
                                    <article class="mad-entity">
                                        <h3 class="mad-entity-title">' . $subpkgRow->title . '</h3>
                                        <p>
                                        ' . strip_tags($subpkgRow->detail) . '
                                        </p>
                                        <div class="btn-set justify-content-center">
                                            <a href="' . BASE_URL . 'result.php?hotel_code=' . $booking_code . '" class="btn btn-big" target="_blank">Book Now</a>
                                            <a href="' . BASE_URL . $subpkgRow->slug . '" class="btn btn-big style-2">Details</a>
                                        </div>
                                    </article>
                                    <!--================ End of Entity ================-->
                                </div>
                            </div>

                
                ';

        }
        $room_package = '
        <div class="mad-section no-pb mad-section--stretched-content-no-px mad-colorizer--scheme-color-">
        <div class="mad-title-wrap align-center">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="mad-pre-title">accommodation</div>
                    <h2 class="mad-page-title">Rooms & Suites</h2>
                    <p class="mad-text-medium">The hotel offers 68 rooms: Standard, Deluxe, Deluxe premium & Junior suite. The highlight of all the rooms are the spacious private balcony where guests can enjoy the most breathtaking views from the comforts of their own room.</p>
                </div>
            </div>
        </div>

        <div class="mad-section no-pd mad-section--stretched-content-no-px mad-colorizer--scheme-">
            <div class="mad-entities single-entity style-2 mad-grid owl-carousel mad-grid--cols-1 mad-owl-moving nav-size-2 no-dots">
                                ' . $roomlist . '
                                </div>
                                </div>
                            </div>';
    }
}


$jVars['module:list-modalpop-up'] = $modalpopup;
$jVars['module:list-package-room1'] = $room_package;
$jVars['module:list-package-room-bred'] = $roombread;


/**
 *      Package Record
 */
$resubpkgDetail = $resubpkgbann = $bcont = '';

if (defined('SUBPACKAGE_PAGE') and isset($_REQUEST['slug'])) {

    $id = !empty($_REQUEST['id']) ? addslashes($_REQUEST['id']) : '';
    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
    $subpkgRec = Subpackage::find_by_slug($slug);
    $pkgRec = Package::find_by_id($subpkgRec->type);
    //echo "<pre>";print_r($slug);die();
    $gallRec = SubPackageImage::getImagelist_by($subpkgRec->id);
    $otherPacs = Subpackage::get_relatedpkg($subpkgRec->type, $subpkgRec->id, 12);


    $pgkRow = Package::find_by_id(3);
    if (!empty($subpkgRec)) {
        //$resubpkgbann.='';
        foreach ($gallRec as $row) {
            $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
            if (file_exists($file_path) and !empty($row->image)):

                $resubpkgbann .= ' <div><img src="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '" alt="' . $row->title . '"><div class="caption cpation_room">
     <h3>
     <ul>
     <li><a href="' . BASE_URL . 'home">Home</a></li>
     <li><a href="' . BASE_URL . $pkgRec->slug . '">' . $pkgRec->title . '</a></li>
     <li>' . $subpkgRec->title . '</li>
     </ul>
     </h3>
     </div></div>';
            endif;

        }


        $pkgType = Package::field_by_id($subpkgRec->type, 'type');
        /* if(!empty($pkgType)) {
                         */
        $subpkgImg = $subpkgRec->image;

        if ($pkgType == 1) {
            $resubpkgDetail .= '<h1 class="main_title_in">' . $subpkgRec->short_title . '</h1>
          <div class="container add_bottom_60">
          
          <div class="row">
          <div class="col-md-8" id="room_detail_desc">';

            $resubpkgDetail .= ' <div id="single_room_feat">
          <ul>';
            $saveRec = unserialize($subpkgRec->feature);
            $count = 1;
            if ($saveRec != null) {
                $featureList = $saveRec[47][1];
                if ($featureList) {


                    foreach ($featureList as $fetRow) {

                        $icoRec = Features::get_by_id($fetRow);

                        if (!empty($icoRec->icon)) {
                            $resubpkgDetail .= ' <li><i class="' . $icoRec->icon . '"></i>' . $icoRec->title . '</li>';


                        } else {
                            $resubpkgDetail .= ' <li><img src="' . IMAGE_PATH . 'features/' . $icoRec->image . '" style=" width: 25px; height: 25px;margin-right:7px;" alt="' . $icoRec->title . '">' . $icoRec->title . '</li>';
                        }

                    }

                }
            }


            $resubpkgDetail .= '
       
       </ul>
       </div>  <div class="row">
       <div class="col-md-3">
       <h3>Description</h3>
       </div>
       <div class="col-md-9">
       
       ' . $subpkgRec->content . '
       
       </div><!-- End col-md-9  -->
       </div><!-- End row  -->

       <div class="row">
       <div class="col-md-3">
       <h3>Occupancy | Tariff</h3>
       </div>
       <div class="col-md-9">
       <table class="table table-striped">
       <tbody>
       <tr>
       <td>Single Occupancy</td>
       <td>' . $subpkgRec->currency . ' ' . $subpkgRec->onep_price . '</td>
       </tr>
       <tr>
       <td>Double Occupancy</td>
       <td>' . $subpkgRec->currency . ' ' . $subpkgRec->twop_price . '</td>
       </tr>
       <tr>
       <td>Extra Bed Charge</td>
       <td> ' . $subpkgRec->currency . ' ' . $subpkgRec->threep_price . '</td>
       </tr>
       </tbody>
       </table>
       </div>
       </div> </div>
       <div class="col-md-4" id="sidebar">
       <div class="theiaStickySidebar">
       <div class="box_style_1">
       <div id="message-booking"></div>
      <form action="" target="_blank" autocomplete="off" id="hotel_booking" data-url="' . BASE_URL . 'result.php">
       
         <input type="hidden" name="hotel_code" value="2AXhJ6">
       <div class="row">
       <div class="col-md-12 col-sm-12">
       <div class="form-group">
       <label>Arrival date</label>
       <input class="startDate1 form-control datepick" type="text" data-field="date" data-startend="start" data-startendelem=".endDate1" readonly placeholder="Arrival" id="checkin" name="hotel_check_in">
       <span class="input-icon"><i class="icon-calendar-7"></i></span>
       </div>
       </div>
       <div class="col-md-12 col-sm-12">
       <div class="form-group">
       <label>Departure date</label>
       <input class="endDate1 form-control datepick" type="text" data-field="date" data-startend="end" data-startendelem=".startDate1" readonly placeholder="Departure" id="checkout" name="hotel_check_out">
       <span class="input-icon"><i class="icon-calendar-7"></i></span>
       </div>
       </div>
       </div><!-- End row -->

       <div class="row">
       <div class="col-md-12 col-sm-12">
       <div class="form-group">
       <input type="submit" value="Book now" class="btn_full" id="submit-booking">
       </div>
       </div>
       </div>
       </form>
       ' . $jVars['module:room-location'] . '
       </div><!-- End box_style -->
       </div><!-- End theiaStickySidebar -->
       </div><!-- End col -->
       
       </div><!-- End row -->
       
       </div><!-- End container -->';
        }


    }
}
$jVars['module:form-controll'] = $bcont;
$jVars['module:sub-package-banner'] = $resubpkgbann;
// $jVars['module:sub-package-detail'] = $resubpkgDetail;


/*
* Sub package 
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
        if ($subpkgRec->type == 1) {
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
            foreach ($gallRec as $row) {
                $file_path = SITE_ROOT . 'images/package/galleryimages/' . $row->image;
                if (file_exists($file_path) and !empty($row->image)):
                    $subpkg_carousel .= '
                    <div class="mad-col"><img src="' . IMAGE_PATH . 'package/galleryimages/' . $row->image . '" alt="' . $row->title . '" /></div>
                              
                                ';
                endif;
            }

            $resubpkgDetail .= '
                <div class="owl-carousel mad-grid mad-grid--cols-1 mad-owl-moving nav-size-2 no-dots mad-gallery-slider">
                ' . $subpkg_carousel . '
                </div>
            ';

            // $content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($subpkgRec->content));
            // pr($subpkgRec);

            // for video section
            $vid_txt = '';
            if (!empty($subpkgRec->source_vid)) {
                $file_path = SITE_ROOT . 'images/subpackage/video/' . $subpkgRec->source_vid;
                if (file_exists($file_path)) {
                    $vid_txt .= '
                        <div class="video-container mb-5">
                            <video id="uploaded-video" controls>
                                <source src="' . IMAGE_PATH . 'subpackage/video/' . $subpkgRec->source_vid . '">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <style>
                            .video-container {
                                width: 100%; /* Make the container take up full width */
                                margin: 0 auto; /* Center the container */
                                overflow: hidden; /* Prevent overflow */
                            }
                            
                            #uploaded-video {
                                width: 100%; /* Ensure the video takes up full width of the container */
                                height: auto; /* Maintain the aspect ratio */
                                display: block; /* Remove any inline spacing issues */
                                object-fit: cover; /* Ensure video content scales well */
                            }
                        </style>
                    ';
                }
            }

            // for 360 image
            $image_360 = '';
            if (!empty($subpkgRec->three60_image)) {
                $file_path = SITE_ROOT . 'images/subpackage/360/' . $subpkgRec->three60_image;
                if (file_exists($file_path)) {
                    $image_360 .= '
                    <div class="iframe-container mb-5">
                        <iframe id="iframe-video" allowfullscreen style="border-style:none;" 
                            src="https://cdn.pannellum.org/2.5/pannellum.htm#panorama=' . IMAGE_PATH . 'subpackage/360/' . $subpkgRec->three60_image . '"></iframe>
                        <style>
                            .iframe-container {
                                position: relative;
                                width: 100%; /* Full width of the container */
                                margin: 0 auto; /* Center the container */
                                overflow: hidden; /* Prevent overflow */
                                padding-top: 56.25%; /* Aspect ratio for 16:9 video */
                            }
                            #iframe-video {
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%; /* Full width */
                                height: 100%; /* Full height to maintain aspect ratio */
                                border: 0; /* Remove border */
                                display: block; /* Ensure proper rendering */
                            }    
                        </style>
                    </div>
                    ';
                }
            }

            $resubpkgDetail .= '
                <!-- details starts-->
                <div class="mad-content mad-single-content">
            <div class="container-fluid">
                <div class="content-element-main">
                    <div class="row hr-size-3 vr-size-3 sticky-bar">
                        <main id="main" class="col-xxl-9 col-lg-8">
                            <div class="mad-entities mad-single-room content-element-7">
                                <div class="mad-single-room-content">';


            $resubpkgDetail .= '
                                    <div class="mad-col">
                                        <h2 class="mad-title" data-hover="Superior Single Room">
                                        ' . $subpkgRec->title . '
                                        </h2>
                                        <div class="mad-room-details">
                                            <span class="mad-room-detail">
                                                <img src="template/web/images/icons/cube.png" alt="" class="svg"/>
                                                <span>' . $subpkgRec->room_size . '</span>
                                            </span>

                                            <span class="mad-room-detail">
                                                <img src="template/web/images/icons/king-bed.png" alt="" class="svg"/>
                                                <span>' . $subpkgRec->bed . '</span>
                                            </span>

                                            <span class="mad-room-detail">
                                                <img src="template/web/images/icons/people.png" alt="" class="svg"/>
                                                <span>' . $subpkgRec->occupancy . '</span>
                                            </span>

                                            <span class="mad-room-detail">
                                                <img src="template/web/images/icons/view.png" alt="" class="svg"/>
                                                <span>' . $subpkgRec->view . '</span>
                                            </span>
                                        </div>
                                    </div>';


            $resubpkgDetail .= '
                            <div class="mad-col">
                                        <div class="mad-pricing-value content-element-3">
                                            <span>Starting From</span>
                                            <span class="mad-pricing-value-num">' . $subpkgRec->currency . $subpkgRec->onep_price . '/
                                                <span>night</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--================ Accordion ================--> ';


            $resubpkgDetail .= '
            <dl role="presentation" class="mad-panels ">
                                <dt class="mad-panels-title ">
                                    <button id="panel-7-button" type="button" aria-expanded="false" aria-controls="panel-7" aria-disabled="false">
                                        Description
                                    </button>
                                </dt>

                                <dd id="panel-7" class="mad-panels-definition">
                                    <p class="mad-text-medium">
                                    ' . $subpkgRec->content . '
                                    </p>
                                </dd>';
            if (!empty($subpkgRec->feature)) {
                $ftRec = unserialize($subpkgRec->feature);
                if (!empty($ftRec)) {
                    $resubpkgDetail .= '
                                        <!-- amenities starts -->
                                        <dt class="mad-panels-title ">
                                        <button id="panel-8-button" type="button" aria-expanded="true" aria-controls="panel-8" aria-disabled="false">
                                            Room Amenities
                                        </button>
                                    </dt>
                                    <dd id="panel-8" class="mad-panels-definition">
                                    <div class="row">';


                    $resubpkgDetail .= '        
                                        
                                        ';
                    foreach ($ftRec as $k => $v) {
                        // $resubpkgDetail .= '<h3 class="room_d_title">' . $v[0][0] . '</h3>';
                        if (!empty($v[1])) {
                            $sfetname = '';
                            $i = 0;
                            $resubpkgDetail .= '';
                            $feature_list = '';
                            foreach ($v[1] as $kk => $vv) {
                                $sfetname = Features::find_by_id($vv);
                                $feature_list .= '
                                                        <span class="mad-room-detail">
                                                    <img src="' . BASE_URL . 'images/features/' . $sfetname->image . '" alt="' . $sfetname->title . '" class="svg"/>
                                                    <span>' . $sfetname->title . '</span>
                                                    </span>';
                                $i++;
                                if (($i % 4 == 0) || (end($v[1]) == $vv)) {
                                    $resubpkgDetail .= '
                                                        <div class="col-md-4">
                                                        <div class="mad-room-details vr-type size-2 style-2">
                                                                ' . $feature_list . '
                                                            </div>
                                                            </div>
                                                        
                                                            ';
                                    $feature_list = '';
                                }
                            }
                        }
                    }

                }
                $resubpkgDetail .= '
                                                                
                                                                </div>
                                                            </dd>
                                                        </dl>
                                                        <!--================ End of Accordion ================-->';
            }


            $resubpkgDetail .= '                    
            </main>';


            $resubpkgDetail .= '
            <aside id="sidebar" class="col-xxl-3 col-lg-4 mad-sidebar">
                            <div class="mad-widget">
                                <div class="mad-booking-wrap size-2" style="background: #1db263;">
                                    <h3 class="mad-booking-title">
                                        <i class="mad-booking-icon">
                                            <img src="template/web/images/icons/calendar.png" alt="" class="svg" />
                                        </i><span>Check Availability</span>
                                    </h3>
                                    <div class="mad-form-row">
                                        <div class="mad-form-col">
                                            <label>Arrival Date</label>
                                            <div class="mad-datepicker">
                                                <div class="mad-datepicker-body">
                                                    <span class="mad-datepicker-others">
                                                        <span class="mad-datepicker-month-year">Friday, 15 April</span>
                                                    </span>
                                                </div>

                                                <div class="mad-datepicker-select">
                                                    <div class="calendar_wrap mad-calendar-rendered">
                                                        <table class="wp-calendar">
                                                            <caption>
                                                                September 2021
                                                                <a class="calendar-caption-prev" href="#"><i class="material-icons">keyboard_arrow_left</i></a>
                                                                <a class="calendar-caption-next" href="#"><i class="material-icons">keyboard_arrow_right</i></a>
                                                            </caption>
                                                            <thead class="div">
                                                                <tr>
                                                                    <th>Sun</th>
                                                                    <th>Mon</th>
                                                                    <th>Tue</th>
                                                                    <th>Wed</th>
                                                                    <th>Thu</th>
                                                                    <th>Fri</th>
                                                                    <th>Sat</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="first">
                                                                      <div class="marker">30</div>
                                                                    </td>
                                                                    <td>
                                                                      <div class="marker">31</div>
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>2</td>
                                                                    <td>3</td>
                                                                    <td>4</td>
                                                                    <td>5</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">6</td>
                                                                    <td>7</td>
                                                                    <td>8</td>
                                                                    <td ><a href="#">9</a></td>
                                                                    <td>10</td>
                                                                    <td>11</td>
                                                                    <td>12</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">13</td>
                                                                    <td>14</td>
                                                                    <td>15</td>
                                                                    <td>16</td>
                                                                    <td>17</td>
                                                                    <td>18</td>
                                                                    <td>19</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">20</td>
                                                                    <td>21</td>
                                                                    <td>22</td>
                                                                    <td>23</td>
                                                                    <td>24</td>
                                                                    <td>25</td>
                                                                    <td>26</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">27</td>
                                                                    <td>28</td>
                                                                    <td>29</td>
                                                                    <td>30</td>
                                                                    <td>
                                                                      <div class="marker">1</div>
                                                                    </td>
                                                                    <td>
                                                                      <div class="marker">2</div>
                                                                    </td>
                                                                    <td>
                                                                      <div class="marker">3</div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mad-form-col">
                                            <label>Departure Date</label>
                                            
                                            <div class="mad-datepicker">
                                                <div class="mad-datepicker-body">
                                                    <span class="mad-datepicker-others">
                                                        <span class="mad-datepicker-month-year">Wednesday, 27 April</span>
                                                    </span>
                                                </div>
                                                <div class="mad-datepicker-select">
                                                    <div class="calendar_wrap mad-calendar-rendered">
                                                        <table class="wp-calendar">
                                                            <caption>
                                                                September 2021
                                                                <a class="calendar-caption-prev" href="#">
                                                                    <i class="material-icons">keyboard_arrow_left</i>
                                                                </a>
                                                                <a class="calendar-caption-next" href="#">
                                                                    <i class="material-icons">keyboard_arrow_right</i>
                                                                </a>
                                                            </caption>
                                                            <thead class="div">
                                                                <tr>
                                                                    <th>Sun</th>
                                                                    <th>Mon</th>
                                                                    <th>Tue</th>
                                                                    <th>Wed</th>
                                                                    <th>Thu</th>
                                                                    <th>Fri</th>
                                                                    <th>Sat</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="first">
                                                                      <div class="marker">30</div>
                                                                    </td>
                                                                    <td>
                                                                      <div class="marker">31</div>
                                                                    </td>
                                                                    <td>1</td>
                                                                    <td>2</td>
                                                                    <td>3</td>
                                                                    <td>4</td>
                                                                    <td>5</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">6</td>
                                                                    <td>7</td>
                                                                    <td>8</td>
                                                                    <td ><a href="#">9</a></td>
                                                                    <td>10</td>
                                                                    <td>11</td>
                                                                    <td>12</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">13</td>
                                                                    <td>14</td>
                                                                    <td>15</td>
                                                                    <td>16</td>
                                                                    <td>17</td>
                                                                    <td>18</td>
                                                                    <td>19</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">20</td>
                                                                    <td>21</td>
                                                                    <td>22</td>
                                                                    <td>23</td>
                                                                    <td>24</td>
                                                                    <td>25</td>
                                                                    <td>26</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="first">27</td>
                                                                    <td>28</td>
                                                                    <td>29</td>
                                                                    <td>30</td>
                                                                    <td>
                                                                      <div class="marker">1</div>
                                                                    </td>
                                                                    <td>
                                                                      <div class="marker">2</div>
                                                                    </td>
                                                                    <td>
                                                                      <div class="marker">3</div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mad-form-col">
                                            <label>Rooms</label>
                                            <div class="mad-custom-select">
                                                <select data-default-text="1 room">
                                                    <option>2 rooms</option>
                                                    <option>3 rooms</option>
                                                    <option>4 rooms</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mad-form-col short-col">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label>Adults</label>
                                                    <div class="quantity size-2">
                                                        <input type="text" value="1" readonly="" />
                                                        <button type="button" class="qty-plus">
                                                            <i class="material-icons">keyboard_arrow_up</i>
                                                        </button>
                                                        <button type="button" class="qty-minus">
                                                            <i class="material-icons">keyboard_arrow_down</i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <label>children</label>
                                                    <div class="quantity size-2">
                                                        <input type="text" value="0" readonly="" />
                                                        <button type="button" class="qty-plus">
                                                            <i class="material-icons">keyboard_arrow_up</i>
                                                        </button>
                                                        <button type="button" class="qty-minus">
                                                            <i class="material-icons">keyboard_arrow_down</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>';
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
                    <div class="mad-col">
                        <!--================ Entity ================-->
                        <article class="mad-entity">
                            <div class="mad-entity-media">
                                <a href="' . BASE_URL . $room->slug . '">
                                    <img src="' . $imglink . '" alt=""/>
                                </a>
                            </div>
                            <div class="mad-entity-content">
                                <h4 class="mad-entity-title">' . $room->title . '</h4>
                                <div class="mad-pricing-value">
                                    <span>From</span>
                                    <span class="mad-pricing-value-num">' . $room->currency . $room->onep_price . '/</span>
                                    <span>night</span>
                                </div>
                                <div class="mad-entity-footer">
                                    <div class="btn-set justify-content-center">
                                        <a href="#" class="btn btn-big">Book Now</a>
                                        <a href="' . BASE_URL . $room->slug . '" class="btn btn-big style-2">Details</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!--================ End of Entity ================-->
                    </div>
            
                    
			';

                }
                //$otherroom.='';
                $resubpkgDetail .= '
    <h2 class="mad-page-title">Related Rooms</h2>
    <div class="mad-entities with-hover align-center type-3 item-col-3">
                            ' . $otherroom . '
                            </div>
                            </div>
                        </div>
                
        ';
            }


        } /********For service inner page ***************/
        else {
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


            $resubpkgDetail .= '
                        <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="' . $imglink . '">
            <div class="container wide">
                <h1 class="mad-page-title">' . $subpkgRec->title . '</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="home" class="mad-link">Home</a></span> /
                    <span>' . $subpkgRec->title . '</span>
                </nav>
            </div>
        </div>
        <!--================ End of Breadcrumb ================-->
                                                
                                        ';

            // for video section
            $vid_txt = '';
            if (!empty($subpkgRec->source_vid)) {
                $file_path = SITE_ROOT . 'images/subpackage/video/' . $subpkgRec->source_vid;
                if (file_exists($file_path)) {
                    $vid_txt .= '
                        <div class="video-container mb-5">
                            <video id="uploaded-video" controls>
                                <source src="' . IMAGE_PATH . 'subpackage/video/' . $subpkgRec->source_vid . '">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <style>
                            .video-container {
                                width: 100%; /* Make the container take up full width */
                                margin: 0 auto; /* Center the container */
                                overflow: hidden; /* Prevent overflow */
                            }
                            
                            #uploaded-video {
                                width: 100%; /* Ensure the video takes up full width of the container */
                                height: auto; /* Maintain the aspect ratio */
                                display: block; /* Remove any inline spacing issues */
                                object-fit: cover; /* Ensure video content scales well */
                            }
                        </style>
                    ';
                }
            }

            // for 360 image
            $image_360 = '';
            if (!empty($subpkgRec->three60_image)) {
                $file_path = SITE_ROOT . 'images/subpackage/360/' . $subpkgRec->three60_image;
                if (file_exists($file_path)) {
                    $image_360 .= '
                    <div class="iframe-container mb-5">
                        <iframe id="iframe-video" allowfullscreen style="border-style:none;" 
                            src="https://cdn.pannellum.org/2.5/pannellum.htm#panorama=' . IMAGE_PATH . 'subpackage/360/' . $subpkgRec->three60_image . '"></iframe>
                        <style>
                            .iframe-container {
                                position: relative;
                                width: 100%; /* Full width of the container */
                                margin: 0 auto; /* Center the container */
                                overflow: hidden; /* Prevent overflow */
                                padding-top: 56.25%; /* Aspect ratio for 16:9 video */
                            }
                            #iframe-video {
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%; /* Full width */
                                height: 100%; /* Full height to maintain aspect ratio */
                                border: 0; /* Remove border */
                                display: block; /* Ensure proper rendering */
                            }    
                        </style>
                    </div>
                    ';
                }
            }


            $resubpkgDetail .= '
                            <div class="mad-content no-pd">
            <div class="container">
                <div class="mad-section">
                    <div class="mad-entities mad-entities-reverse type-4">
                                ' . $subpkgRec->content . '
                                </div>
                </div>
            </div>
        </div>';
            $resubpkgDetail .= $subpkgRec->below_content;


            $resubpkgDetail .= '';


        }
    }
}

$jVars['module:sub-package-detail'] = $resubpkgDetail;


/**********        For What;s nearby from package **************/
$resubpkgDetail = '';
$relPacs = Subpackage::get_relatedpkg(10, 0, 12);

foreach ($relPacs as $relPac) {

    $imglink = '';
    if (!empty($relPac->image)) {
        $img123 = unserialize($relPac->image);
        $file_path = SITE_ROOT . 'images/subpackage/' . $img123[0];
        if (file_exists($file_path)) {
            $imglink = IMAGE_PATH . 'subpackage/' . $img123[0];
        } else {
            $imglink = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
        }
    } else {
        $imglink = IMAGE_PATH . 'static/default-art-pac-sub.jpg';
    }
    $resubpkgDetail .= '

                                            <div class="col-lg-3 col-md-6">
                                                <div class="top-hotels-ii">
                                                    <img src="' . $imglink . '" alt=" ' . $relPac->title . '"/>
                                                    ' . $relPac->content . '
                                                    <div class="pp-details yellow">
                                                        <span class="pull-left">More Info</span>
                                                        <span class="pp-tour-ar">
                                                                <a href="javascript:void(0)"><i class="fa fa-angle-right pad-0"></i></a>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        ';


}

$whats_nearby = '
            <section class="top-hotel">
                <div class="container-xxl px-5">
                    <div class="top-title">
                        <div class="row display-flex">
                            <div class="col-lg-8 mx-auto text-center">
                                <h2>What\'s <span>Nearby</span></h2>
                                <p class="mar-0">
                                    We are located at the heart of Lalitpur. Major shopping outlets, Patan Durbar Square, Hospitals, Banks, UN office, Government offices, etc are
                                    within walking distance.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!--Gallery Section-->
                    <div class="row activities-slider">
                        ' . $resubpkgDetail . '
                    </div>
                </div>
            </section>';

// pr($whats_nearby);
$jVars['module:whats-nearby'] = $whats_nearby;

