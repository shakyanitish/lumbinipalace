<?php
$nearbydetail = $nearbydetail_modals= $imageList = $nearbybred = '';

if (defined('HOME_PAGE')) {
    $recRows = Nearby::find_all_active();
    // pr($recRow);
    if (!empty($recRows)) {

        foreach($recRows as $recRow){

            // $imglink = BASE_URL . 'template/web/img/slider/2.jpg';
            // if ($recRow->image != "a:0:{}") {
            //     $imageList = unserialize($recRow->image);
            //     $imgno = array_rand($imageList);
            //     $file_path = SITE_ROOT . 'images/nearby/' . $imageList[$imgno];
            //     if (file_exists($file_path)) {
            //         $imglink = IMAGE_PATH . 'nearby/' . $imageList[$imgno];
            //     }
            // }

            $nearbydetail .= '
            <div class="reservations mb-30">
            <div class="row">
                <div class="col-md-8 text">
                    <p data-bs-toggle="modal" data-bs-target="#exampleModal' . $recRow->id . '">' . $recRow->sub_title . '</p>
                       <!-- Button trigger modal -->';
            
            $nearbydetail_modals.='
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal' . $recRow->id . '" tabindex="-1" aria-labelledby="exampleModalLabel' . $recRow->id . '" aria-hidden="true"
                            style="z-index:100;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="color:#000" id="exampleModalLabel' . $recRow->id . '">' . $recRow->sub_title . '</h5>
                                        </div>
                                        <div class="modal-body">
                                        ' . $recRow->content . '
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
            ';

            $nearbydetail.='
                                </div>
                                        <div class="col-md-4">
                                            <p class="color-2">' . $recRow->distance . '</p>
                                        </div>
                                    </div>
                                </div>
            ';

        } 
    }
}
// pr($nearbydetail);


$jVars['module:inner-nearby-detail'] = $nearbydetail;
$jVars['module:inner-nearby-detail-modals'] = $nearbydetail_modals;

?>