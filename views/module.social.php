<?php
/*
* Top Social Links
*/
$socialRec = SocialNetworking::getSocialNetwork();
$siteRegulars = Config::find_by_id(1);

$resocl = '';
$resocl1 = '';
$disicon = '';

if (!empty($socialRec)) {
    foreach ($socialRec as $socialRow) {
        $icon = $socialRow->image;
        
        // Skip if link is empty or just '#'
        if (empty($socialRow->linksrc) || $socialRow->linksrc === '#') {
            continue;
        }
        
        // Determine icon display method
        if (!empty($icon)) {
            $disicon = '
            <a href="' . $socialRow->linksrc . '"><img src="' . IMAGE_PATH . 'social/' . $socialRow->image . '"/></a>';
        } else {
            $disicon = '
            <a href="' . $socialRow->linksrc . '"><i class="' . $socialRow->icon . '"></i></a>';
        }
        
        $resocl .= $disicon;
    }
}

$jVars['module:socilaLinkbtm'] = $resocl;


if (!empty($socialRec)) {
    foreach ($socialRec as $socialRow) {
        $icon = $socialRow->image;
        
        // Skip if link is empty or just '#'
        if (empty($socialRow->linksrc) || $socialRow->linksrc === '#') {
            continue;
        }
        
        // Determine icon display method
        if (!empty($icon)) {
            $disicon = '
            <a href="' . $socialRow->linksrc . '" class="text-dark"><img src="' . IMAGE_PATH . 'social/' . $socialRow->image . '"/></a>';
        } else {
            $disicon = '
            <a href="' . $socialRow->linksrc . '" class="text-dark"><i class="' . $socialRow->icon . '"></i></a>';
        }
        
        $resocl1 .= $disicon;
    }
}

$jVars['module:socilaLinkbtmfooter'] = $resocl1;





/*
* Home social link
*/
$icons = '';
if (!empty($socialRec)) {
    foreach ($socialRec as $socialRow) {
        $icons .= '
            <a href="' . $socialRow->linksrc . '" target="_blank" rel="noreferrer noopener">
                <img src="'.IMAGE_PATH.'social/' . $socialRow->image . '" height="20" alt="">
            </a>
        ';
    }
}

$ressl = '
    <div class="float-text">
        <div class="de_social-icons">
            ' . $icons . '
        </div>
        <span class="text-white">Follow Us</span>

    </div>
';

$jVars['module:socilaLinktop'] = $ressl;



$otaRec = ota::getotaNetwork();
$ota = '';
if (!empty($otaRec)) {
    foreach ($otaRec as $otaRow) {
        $ota .= ' 
        <li><a href="' . $otaRow->linksrc . '" target="_blank"><img src="'.IMAGE_PATH.'ota/' . $otaRow->image . '"></a></li>';
    }
}

$jVars['module:otatop'] = $ota;

$detect = new Mobile_Detect;

$ret = '';

// Any mobile device.
if ($detect->isMobile() && !$detect->isTablet()) {
    $ret .= '<div class="mobile-fb text-center"><iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fambassadornepal&tabs=timeline&width=330&height=400&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" frameborder="0" scrolling="yes" style="border: white;float:none;margin:auto; overflow: hidden; height: 350px; width: 330px;max-width:100%;background:#fafafa;color:000;"></iframe></div>';
} else {
    $ret .= '<style type="text/css"> .theblogwidgets{background: url("images/fbwidget.png") no-repeat scroll left center transparent !important; float: right;height: 350px;padding: 0 5px 0 34px;width: auto;z-index:  99999;position:fixed;right:-255px;top:40%;} .theblogwidgets div{ padding: 0; margin-right:-8px; border:4px solid  #3b5998; background:#fafafa;} .theblogwidgets span{bottom: 4px;font: 8px "lucida grande",tahoma,verdana,arial,sans-serif;position: absolute;right: 6px;text-align: right;z-index: 99999;} .theblogwidgets span a{color: gray;text-decoration:none;} .theblogwidgets span a:hover{text-decoration:underline;} } </style>
    <div class="theblogwidgets" style="">
<div>
 <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fambassadornepal&tabs=timeline&width=250&height=400&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" frameborder="0" scrolling="yes" style="border: white; overflow: hidden; height: 350px; width: 250px; max-width:100%;background:#fafafa;color:000;"></iframe></div>
</div>';
}
$jVars['module:fb-side'] = $ret;

$ota = "";
$ota .= '<div class="row otas">
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.expedia.co.in/Pokhara-Hotels-Hotel-Peninsula.h8323628.Hotel-Information?rfrr=Redirect.From.www.expedia.com%2FPokhara-Hotels-Hotel-Peninsula.h8323628.Hotel-Information" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/ex.png" alt="social"></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.booking.com/hotel/np/peninsula.en-gb.html" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/bo.png" alt="social"></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.makemytrip.com/hotels-international/nepal/pokhara-hotels/hotel_peninsula-details.html" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/ma.png" alt="social"></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.tripadvisor.com/Hotel_Review-s1-g293891-d2064976-Reviews-Hotel_Peninsula-Pokhara_Gandaki_Zone_Western_Region.html" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/ta.png" alt="social"></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="#" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/ct.png" alt=social""></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.agoda.com/pages/agoda/default/DestinationSearchResult.aspx?selectedproperty=293557&city=5733&hid=293557&site_id=1646622&tag=c4dffc98-485d-9eb9-a1b8-ff1699d7e839&device=c&network=g&adid=204833898677&rand=11384011593909406552&expid=&adpos=1t3&gclid=Cj0KCQjwvdXpBRCoARIsAMJSKqID39KX5fYAQ1Wru2uoUkkLE-sky3RZ_nLwUNf3GrzSlmFvCy5zArMaAkwcEALw_wcB" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/ag.png" alt="social"></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.trivago.com/?iGeoDistanceItem=2108754&aDateRange%5Barr%5D=2018-01-30&aDateRange%5Bdep%5D=2018-01-31&iPathId=85969&iRoomType=7" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/tri.png" alt="social"></a>
                        </div>
                        <div class="col-md-3 col-xs-4 col-6 ota">
                            <a href="https://www.goibibo.com/hotels/peninsula-hotel-in-pokhara-1152238026423715335/?hquery=%7B%22ci%22%3A%2220180126%22%2C%22co%22%3A%2220180127%22%2C%22r%22%3A%221-2-0%22%2C%22ibp%22%3A%22na%22%2C%22ts%22%3A1%7D" target="_blank"><img src="' . BASE_URL . 'template/web/img/icon/go.png" alt="social"></a>
                        </div>
                    </div>';
$jVars['module:ota'] = $ota;
?>