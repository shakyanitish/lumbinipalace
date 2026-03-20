<?php

$configRec = Config::find_by_id(1);
/*
* Top Location
*/
$tpres = '';
$tellinked = '';
$roomcontact = '';

/*$telno = explode(",", $configRec->contact_info);
foreach ($telno as $tel) {
    $tellinked .= '<a href="tel:' . $tel . '">' . $tel . '</a>';
}*/
$telno = explode(",", $configRec->contact_info);
$telno1 = explode(",", $configRec->contact_info2);
if (!@$telno[1]) {
    $tellinked = '<a href="tel:' . $telno[0] . '">' . $telno[0] . '</a> ';
} else {
    $tellinked = '<a href="tel:' . $telno[0] . '">' . $telno[0] . '</a> / <a href="tel:' . $telno[1] . '">' . $telno[1] . '</a>';
    /*$telenum='<a href="tel:'.$telno[3].'">'.$telno[3].'</a> / <a href="tel:'.$telno[4].'">'.$telno[4].'</a>';
    */
}
if (!@$telno1[1]) {
    $tellinked1 = '<a href="tel:' . $telno1[0] . '">' . $telno1[0] . '</a> ';

} else {
    $tellinked1 = '<a href="tel:' . $telno1[0] . '">' . $telno1[0] . '</a> / <a href="tel:' . $telno1[1] . '">' . $telno1[1] . '</a>';
    /*$telenum='<a href="tel:'.$telno[3].'">'.$telno[3].'</a> / <a href="tel:'.$telno[4].'">'.$telno[4].'</a>';
    */
}

$emlAddress = str_replace('@', '&#64;', $configRec->email_address);
$mlAddress = str_replace('@', '&#64;', $configRec->mail_address);

$tpres .= ' <div class="col-md-3 col-sm-12">
                    <h3>Hotel Peninsula Pvt. Ltd.</h3>
                    <ul id="contact_details_footer">
                        <li>' . $siteRegulars->fiscal_address . '</br>
                        ' . $tellinked . '<br><a href="mailto:' . $emlAddress . '">' . $emlAddress . '</a></li>
                    </ul>  
                </div>
                <div class="col-md-3 col-sm-4">
                     <h3>Reservation Office</h3>
                    <ul>
                        <li>' . $siteRegulars->address . '</li>
                         <li>' . $tellinked1 . '<br> <a href="mailto:' . $mlAddress . '">' . $mlAddress . '</a></li>
                    </ul>
                </div> 

        ';
$roomcontact .= ' <div class="box_style_2">
		<h4>Need help?</h4>
                <i class="icon_set_1_icon-90"></i>
                <a href="tel:' . $telno[0] . '">' . $telno[0] . '</a>
                <i class="icon_set_1_icon-84"></i>
                <a href="mailto:' . $emlAddress . '"> ' . $emlAddress . '</a>
            </div>';

$jVars['module:footer-location'] = $tpres;
$jVars['module:room-location'] = $roomcontact;


$reslocinfo = '';
$resgmap = '';
$resbrief = '';

if ($configRec) {

    /*
    * Office location
    */
    $reslocinfo .= '    
        <div class="row">
            <div class="col-md-4">
                <img src="' . BASE_URL . 'template/web/images/misc/pic_contact.jpg" alt="" class="img-responsive">
            </div>
            <div class="col-md-9 pos-right vc">
                <div class="vc-inner overlay-light-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="col-md-12 contact-icon text-center">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="col-md-12 contact-address">
                                <h4><a href="' . $siteRegulars->contact_info2 . '" target="_blank" class="text-white">' . $siteRegulars->mail_address . '</a></h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12 contact-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="col-md-12 contact-address">
                                <h4><a href="tel:' . $siteRegulars->contact_info . '" class="text-white">' . $siteRegulars->contact_info . '</a>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12 contact-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="col-md-12 contact-address">
                                <h4><a href="mailto:' . $emlAddress . '" class="text-white">' . $emlAddress . '</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   ';

    /*
    * Google map
    */

    if ($configRec->location_type == 1) {
        $resgmap .= '
        <div class="mad-booking-wrap">
        <iframe src="' . $configRec->location_map . '" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        ';
    } else {
        $resgmap .= '
            <img src="' . IMAGE_PATH . 'preference/locimage/' . $configRec->location_image . '" alt="' . $configRec->sitetitle . '" class="img-responsive">
        ';
    }


}

$jVars['module:office_information'] = $reslocinfo;
$jVars['module:office_map'] = $resgmap;

$reslocinfo1='';

$reslocinfo1 .= '    
        <section id="mod-map">
      <!-- Modal toggle -->
      <div class="modal-toggle">
         <a href="#" title="close" id="modal-close"><span>Close</span></a>
      </div>
      <div id=""><iframe src="' . $configRec->location_map . '"width="100%" height="650" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
      </div>
      <address>
         '. $configRec->fiscal_address.'
      </address>
      </section>';

$jVars['module:office_location'] = $reslocinfo1;
?>