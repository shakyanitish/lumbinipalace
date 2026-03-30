
<?php
// SITE REGULARS
$jVars['site:header'] = Config::getField('headers', true);
$jVars['site:footer'] = Config::getField('footer', true);
$siteRegulars = Config::find_by_id(1);
// $jVars['site:copyright'] = str_replace(
//     '{year}',
//     date('Y'),
//     $siteRegulars->copyright
// ) . '
// <a href="https://longtail.info/">Longtail e-media</a>
// ';


$jVars['site:copyright'] = str_replace('{year}', date('Y'), $siteRegulars->copyright);

$jVars['site:infoss'] = $siteRegulars->breif;
$jVars['site:contact-header'] = '<span class="address"><a href="tel:' . $siteRegulars->contact_info . '" data-toggle="tooltip" data-placement="bottom" title="Call"><i class="fa fa-phone"></i></a></span>
<span class="address"><a href="mailto:' . $siteRegulars->mail_address . '" data-toggle="tooltip" data-placement="bottom" title="Mail"><i class="fa fa-envelope-o"></i></a></span>';

$jVars['site:phone-news-side'] = '


<a href="tel:+977' . $siteRegulars->contact_info . '" class="mhm-right-link"><i class="fa-solid fa-phone"></i> <span>+977' . $siteRegulars->contact_info . '</span></a>
';

$jVars['site:fevicon'] = '<link rel="shortcut icon" href="' . IMAGE_PATH . 'preference/' . $siteRegulars->icon_upload . '"> 
							    <link rel="apple-touch-icon" href="' . IMAGE_PATH . 'preference/' . $siteRegulars->icon_upload . '"> 
							    <link rel="apple-touch-icon" sizes="72x72" href="' . IMAGE_PATH . 'preference/' . $siteRegulars->icon_upload . '"> 
							    <link rel="apple-touch-icon" sizes="114x114" href="' . IMAGE_PATH . 'preference/' . $siteRegulars->icon_upload . '">';
$jVars['site:logo'] = '

                        <a href="' . BASE_URL . 'home" class="d-block mb-4"></a>
                        <img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" class="img-fluid mb-3 wow fadeInUp" width="250"
                            data-wow-duration="1s" alt="Main Logo">
                        </a>
';



$imglink = $siteRegulars->offer_upload;

if (!empty($imglink)) {
    $img = IMAGE_PATH . 'preference/offer/' . $siteRegulars->offer_upload;
}
else {
    $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
}

$jVars['site:faq'] = '


        <header class="header-fullwidth menu-expand transparent">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="col-md-12">
                        <!-- logo begin -->
                        <div id="logo">
                            <a href="' . BASE_URL . 'home' . '">
                                <img class="logo" src="' . $img . '" alt="">
                            </a>
                        </div>
                        <!-- logo close -->

                        <!-- small button begin -->
                        <!-- small button close -->
                    </div>
                </div>
            </div>
        </header>
';


$imglink = $siteRegulars->contact_upload;

if (!empty($imglink)) {
    $img = IMAGE_PATH . 'preference/contact/' . $siteRegulars->contact_upload;
}
else {
    $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
}
$jVars['site:blog'] = ' 
                            <img src="' . $img . '" alt />

';



$jVars['site:seotitle'] = MetaTagsFor_SEO();
$jVars['site:googleanalatic'] = $siteRegulars->google_anlytics;

$jVars['site:pixel-code'] = $siteRegulars->pixel_code;



require_once("views/module.booking.php");
require_once("views/module.contact.php");
require_once("views/module.download.php");
require_once("views/module.gallery.php");
require_once("views/module.services.php");

// SITE MODULES
$modulesList = Module::getAllmode();
foreach ($modulesList as $module):
    $fileName = "module." . $module->mode . ".php";
    if (file_exists("views/" . $fileName)) {
        require_once("views/" . $fileName);
    }
endforeach;

// view modules

require_once("views/module.contact.php");
require_once("views/module.programs.php");
require_once("views/module.offers.php");
require_once("views/module.faq.php");
require_once("views/module.dine.php");
require_once("views/module.header.php");
require_once("views/module.footer.php");


?>