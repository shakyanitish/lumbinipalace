<?php
/*
* Comment Header Title
*/
$restst = '';

$tstRec = Popup::get_allpopup(0);
if (!empty($tstRec)) {
    $count = 1;
    $active = '';
    $restst .= '
    <div class="col-sm-10 center-block center-text">
        <div class="modal fade" id="modal-popup-video">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" title="Close"> 
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <div class="clearfix"></div>
                    <div class="modal-body">
					<!--CAROUSEL CODE GOES HERE-->
                        <div id="videoCarousel" class="carousel slide" data-interval="false">
                            <div class="carousel-inner">
                            ';
    $auto = (count($tstRec) == 1) ? 'autoplay=1' : '';
    foreach ($tstRec as $tstRow) {
        //if(!empty($tstRow->source){
        $active = ($count == 1) ? 'active' : '';
        $parts = explode('.',$tstRow->source);
        if($parts[1] == 'facebook'){
            $restst .= ' 
                <style>
                @media (min-width: 768px){
                    #modal-popup-video .modal-dialog {
                        width: 28%;
                    }
                }
                </style>
                <div class="item ' . $active . '">
                    <iframe src="https://www.facebook.com/plugins/video.php?href='.urlencode($tstRow->source).'&width=365&show_text=false&appId=668102922175064&height=650" 
                    width="365" height="650" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                </div>
                ';
        } else {
            $restst .= ' 
                <div class="item ' . $active . '">
                    <iframe width="100%" id="yt-video" height="600px" src="https://www.youtube.com/embed/' . get_youtube_code($tstRow->source) . '?' . $auto . '" frameborder="0" allow="accelerometer; autoplay ; encrypted-media; gyroscope; picture-in-picture" allowfullscreen ></iframe>  
                </div>
                ';
        }
        $count++;
    }
    if(sizeof($tstRec) > 1) {
        $restst .= '
                <!--Begin Previous and Next buttons-->
                <a class="left carousel-control" href="#videoCarousel" role="button" data-slide="prev"> 
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a> 
                <a class="right carousel-control" href="#videoCarousel" role="button" data-slide="next"> 
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
        ';
    }
    $restst .= ' <!--end carousel-inner-->
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


$popRec = Popup::get_allpopup(1);
if (!empty($popRec)) {
    //modal img
    $count = 1;
    $active = '';
    $restst = ' 
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
    foreach ($popRec as $popr) {
        if (($popr->image) != "a:0:{}") {
            $q = implode(unserialize($popr->image));
            $file_path = SITE_ROOT . 'images/popup/' . $q;
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'popup/' . $q;
            } else {
                $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
            }
            $active = ($count == 1) ? 'active' : '';
            $linkhref = ($popr->linktype == 1) ? $popr->linksrc : BASE_URL . $popr->linksrc;
            $target = ($popr->linktype == 1) ? 'target="_blank"' : '';
            $restst .= '  
                <div class="carousel-item ' . $active . '">
                    <a href="' . $linkhref . '" ' . $target . '><img src="' . $imglink . '" alt="' . $popr->title . '"></a>
                </div>
                ';
                // pr($imglink);

            $count++;
        }
    }
    $restst .= ' <!--end carousel-inner-->
                        </div>
    ';
    if(sizeof($popRec) > 1) {
        $restst .= '
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
    $restst .='
                        
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

    //side img
    // $count = 1;
    // $active = '';
    // $restst .= ' 
    //         <div class="deals d-none">
    //         <a href="javascript:void(0);" class="close closepop">*</a>
    //             <div id="carouselExampleControlsss" class="carousel slide" data-ride="carousel">
    //               <div class="carousel-inner">';
    // foreach ($popRec as $popr) {
    //     if (($popr->image) != "a:0:{}") {
    //         $q = implode(unserialize($popr->image));
    //         $file_path = SITE_ROOT . 'images/popup/' . $q;
    //         if (file_exists($file_path)) {
    //             $imglink = IMAGE_PATH . 'popup/' . $q;
    //         } else {
    //             $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
    //         }
    //         $active = ($count == 1) ? 'active' : '';
    //         $restst .= '  
    //             <div class="item ' . $active . '">
                    
    //                 <div class="cover_img">
    //                     <a href="' . BASE_URL . '' . $popr->linksrc . '">
    //                         <img src="' . $imglink . '" class="img-responsive">
    //                     </a>
    //                  </div>
    //             </div>
    //             ';
    //         $count++;
    //     }
    // }
    // $restst .= ' </div>
    //             <a class="left carousel-control" href="#carouselExampleControlsss" role="button" data-slide="prev"> 
    //                 <span class="glyphicon glyphicon-chevron-left"></span>
    //             </a> 
    //             <a class="right carousel-control" href="#carouselExampleControlsss" role="button" data-slide="next"> 
    //                 <span class="glyphicon glyphicon-chevron-right"></span>
    //             </a>
    //         </div>
    //     </div>';

    // //side img button
    // $restst .= '
    //     <!--<ul class="side-icon-block">
    //         <li class="">
    //             <a id="offon" href="javaScript:void(0);">
    //                 <img class="img-fluid" alt="Offers" title="Offers" width="50" src="' . IMAGE_PATH . 'offerside.png">
    //             </a> 
    //         </li> 
    //     </ul>-->
    // ';
}

// pr($restst,1);
$jVars['module:popup'] = $restst;
?>
