<?php
/*
* Contact form
*/
$rescont = '';
$img = '';
if (defined('CONTACT_PAGE')) {

    $siteRegulars = Config::find_by_id(1);

    // Phone links
    $tellinked = '';
    $telno = explode(",", $siteRegulars->contact_info);
    $lastElement = array_shift($telno);
    $tellinked .= '<a href="tel:+977' . str_replace(' ', '', $lastElement) . '" target="_blank">(+977) ' . $lastElement . '</a><br>';
    foreach ($telno as $tel) {
        $tellinked .= '<a href="tel:+977' . str_replace(' ', '', $tel) . '" target="_blank">(+977) ' . $tel . '</a>';
        if (end($telno) != $tel) {
            $tellinked .= '/';
        }
    }

    // Office address
    $office = '';
    $ot = explode(",", $siteRegulars->pobox);
    $first = trim(array_shift($ot));
    $office .= '<span>' . $first . '</span>';
    foreach ($ot as $o) {
        $o = trim($o);
        $office .= ', <span>' . $o . '</span>';
    }



    $emailinked = '';
    $emails = explode(",", $siteRegulars->email_address); // use only one field
    $emails = array_map('trim', $emails); // remove spaces
    $totalEmails = count($emails);
    $countEmail = 0;

    foreach ($emails as $email) {
        $countEmail++;
        $emailinked .= '<a href="mailto:' . $email . '" target="_blank" rel="noreferrer" title="' . $email . '">' . $email . '</a>';
        if ($countEmail < $totalEmails) {
            $emailinked .= '<br> '; // add comma only between emails
        }
    }


    // WhatsApp / phone links
    $phonelinked = '';
    $phoneno = explode("/", $siteRegulars->whatsapp);
    $lastElement = array_shift($phoneno);
    $phonelinked .= '<a href="tel:+977' . str_replace(' ', '', $lastElement) . '" target="_blank">' . $lastElement . '</a>/';
    foreach ($phoneno as $phone) {
        $phonelinked .= '<a href="tel:+977' . str_replace(' ', '', $phone) . '" target="_blank">' . $phone . '</a>';
        if (end($phoneno) != $phone) {
            $phonelinked .= '/';
        }
    }

    // Image
    $imglink = $siteRegulars->contact_upload;
    if (!empty($imglink)) {
        $img = IMAGE_PATH . 'preference/contact/' . $siteRegulars->contact_upload;
    } else {
        $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    }

    // Section HTML
    $rescont .= '

        <section class="contact-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="contact-info text-center">
                        <i class="far fa-map"></i>
                        <h3>Location</h3>
                        <div class="ct__atdetail">
                            <p>' . $siteRegulars->fiscal_address . '</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="contact-info text-center">
                        <i class="fas fa-phone-alt"></i>
                        <h3>Phone No.</h3>
                        <div class="ct__atdetail">
                            <p>' . $tellinked . '</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                    <div class="contact-info">
                        <i class="fas fa-envelope-square"></i>
                        <h3>E-mail</h3>
                        <div class="ct__atdetail">
                            <p>' . $emailinked . '</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-form">
                        <form class="m-auto text-center" id="contactform">
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-outline">
                                        <input type="text" name="name" id="form6Example1" class="form-control" placeholder="Name*" />
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-outline mb-4">
                                <input type="email" name="email" id="form6Example5" class="form-control" placeholder="Email*" />
                            </div>
        
                            <div class="form-outline mb-4">
                                <input type="text" name="phone" id="form6Example6" class="form-control" placeholder="Phone No.*" /oninput="this.value = this.value.replace(/[^0-9]/g, \'\');">
                            </div>
        
                            <div class="form-outline mb-4">
                                <textarea class="form-control" name="message" id="form6Example7" placeholder="Message" rows="4"></textarea>
                            </div>

                            <div class="form-outline mb-4">
                                <div class="g-recaptcha"
                                                data-sitekey="6Lf5y4YsAAAAADK6EUEgiOMYkpq4jT0VBWemrX5a"></div>
                            </div>
        
                            <button type="submit" id="btn" class="btn">Send Message</button>

                            <div class="col-12 text-center">
                                <div id="result_msg" class="mt-3" style="display:none;"></div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="contact-map">
                        <iframe src="' . $siteRegulars->location_map . '" width="900" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
';
   
$jVars['module:contact-us'] = $rescont;
}

//uc contact details - Available on all pages including homepage
$home_contact = '';
$siteRegulars = Config::find_by_id(1);

// Phone links for homepage
$tellinked_home = '';
$telno = explode(",", $siteRegulars->contact_info);
$lastElement = array_shift($telno);
$tellinked_home .= '<a href="tel:+977' . str_replace(' ', '', $lastElement) . '" target="_blank">(+977) ' . $lastElement . '</a><br>';
foreach ($telno as $tel) {
    $tellinked_home .= '<a href="tel:+977' . str_replace(' ', '', $tel) . '" target="_blank">(+977) ' . $tel . '</a>';
    if (end($telno) != $tel) {
        $tellinked_home .= '/';
    }
}

$home_contact .= ' 
                <div class="mb-5">
                    <p class="m-location-address">' . $siteRegulars->fiscal_address . '</p>
                    <p class="m-location-tel mb-5">Tel: ' . $tellinked_home . '</p>
                </div>

            ';

$jVars['module:contact-home'] = $home_contact;

// Homepage Map Section
$mapSection = '';
if ($siteRegulars && !empty($siteRegulars->location_map)) {
    $mapSection = '
        <div class="m-location-map-wrap bg-white p-2">
            <iframe id="homepageMap" 
                src="' . $siteRegulars->location_map . '" 
                width="100%" height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="Location Map">
            </iframe>
        </div>
    ';
}
$jVars['module:contact-map-home'] = $mapSection;
