<?php
$siteRegulars = Config::find_by_id(1);
$booking_code = Config::getField('hotel_code', true);
$header = ob_get_clean();
$sidebarlogo = '';
$header_class = (!defined('HOME_PAGE')) ? 'header_menu_detail' : '';

$header = '



    <!-- ============ SIDEBAR (MOBILE) ============ -->
    <div class="ul-sidebar">
        <div class="ul-sidebar-header">
            <button class="ul-sidebar-closer"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="ul-sidebar-header-nav-wrapper d-block d-lg-none"></div>
        <div class="to-go-to-sidebar-in-mobile d-block d-lg-none">
            <nav class="ul-header-nav">
                ' . $jVars['module:mobile-nav'] . ' 
            </nav>
        </div>
        <div class="ul-sidebar-footer">
            <span class="ul-sidebar-footer-title">Follow us</span>
            <div class="ul-sidebar-footer-social">
            ' . $jVars['module:socilaLinkbtm'] . '
            </div>
        </div>
    </div>

    <!-- ============ DESKTOP HEADER ============ -->
    <header class="marriott-header d-none d-lg-block w-100 bg-white">
        <div class="marriott-header-top">
            <div class="marriott-header-logo">
                <a href="' . BASE_URL . '' . '"><img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" alt="Lumbini Palace Resort"
                        style="height: 60px;"></a>
            </div>
            <div class="marriott-header-nav">
            ' . $jVars['module:desktop-nav'] . '
            </div>
        </div>
        <div class="marriott-header-middle-wrapper" style="height: 70px;">
            <div class="marriott-header-middle">
                <div class="mhm-left">
                    <h1 class="mhm-title">Lumbini Palace Resort</h1>
                    <div class="mhm-stars">
                        <i class="fa-solid fa-circle"></i>
                        <i class="fa-solid fa-circle"></i>
                        <i class="fa-solid fa-circle"></i>
                        <i class="fa-solid fa-circle"></i>
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </div>
                    <div class="mhm-reviews">
                        <span>4.6 • </span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#reviewsModal">622 Reviews</a>
                    </div>
                </div>
                <div class="mhm-right">
                    <a href="' . $siteRegulars->location_map . '"
                        target="_blank" rel="noopener noreferrer" class="mhm-right-link"><i
                            class="bi bi-geo-alt text-decoration-none"></i> <span>VIEW MAP</span></a>
                        ' .$jVars['site:phone-news-side'] . '

                </div>
            </div>
        </div>
        <div class="marriott-header-bottom-wrapper" style="height: 100px;">
            <div class="marriott-header-bottom">
                <div class="mhb-fields-container">
                    <div class="mhb-field" id="mhb-dates-field">
                        <div class="mhb-label"><i class="bi bi-calendar3"
                                style="font-size: 14px; margin-right: 5px;"></i> DATES (1 NIGHT)</div>

                        <div class="mhb-value">
                            <span>Sun, Mar 08 <span style="margin: 0 10px; color: #767676;">&rarr;</span> Mon, Mar
                                09</span>
                        </div>
                       <div class="mhb-dropdown mhb-dates-dropdown">
                            <div class="mhb-dropdown-header">
                                <div class="mhb-close-btn"><i class="fa-solid fa-xmark"></i></div>
                            </div>
                            <div class="mhb-dates-tabs">
                                <div class="mhb-date-tab active">Specific Dates</div>
                                <div class="mhb-date-tab">Flexible Dates</div>
                            </div>
                            <div class="mhb-calendar-container" id="mhb-calendar-container">
 
           
                            </div>
                            <div class="mhb-dropdown-done">
                                <button class="mhb-done-btn">Done</button>
                            </div>
                        </div>
                    </div>
                    <div class="seperator-line"></div>
                    <div class="mhb-field" id="mhb-rooms-field">
                        <div class="mhb-label">ROOMS &amp; GUESTS</div>
                        <div class="mhb-value">
                            <span>1 Room, 1 Adult</span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #1c1c1c;"></i>
                        </div>
                        <!-- Rooms Dropdown -->
                        <div class="mhb-dropdown mhb-rooms-dropdown">
                            <div class="mhb-dropdown-header">
                                <div class="mhb-close-btn"><i class="fa-solid fa-xmark"></i></div>
                            </div>
                            <div class="mhb-rooms-msg">MAXIMUM 8 GUESTS PER ROOM</div>
                            <div class="mhb-row" data-type="rooms">
                                <div class="mhb-row-info">
                                    <span class="mhb-row-label">Rooms</span>
                                    <span class="mhb-row-subtext">(Max: 3 Rooms/person)</span>
                                </div>
                                <div class="mhb-counter">
                                    <button class="mhb-count-btn minus disabled">-</button>
                                    <span class="mhb-count-num">1</span>
                                    <button class="mhb-count-btn plus">+</button>
                                </div>
                            </div>
                            <div class="mhb-row" data-type="adults">
                                <div class="mhb-row-info">
                                    <span class="mhb-row-label">Adults</span>
                                    <span class="mhb-row-subtext">(Max: 8 total guests/room)</span>
                                </div>
                                <div class="mhb-counter">
                                    <button class="mhb-count-btn minus disabled">-</button>
                                    <span class="mhb-count-num">1</span>
                                    <button class="mhb-count-btn plus">+</button>
                                </div>
                            </div>
                            <div class="mhb-row" data-type="children">
                                <div class="mhb-row-info">
                                    <span class="mhb-row-label">Children</span>
                                    <span class="mhb-row-subtext">(Max: 8 total guests/room)</span>
                                </div>
                                <div class="mhb-counter">
                                    <button class="mhb-count-btn minus disabled">-</button>
                                    <span class="mhb-count-num">0</span>
                                    <button class="mhb-count-btn plus">+</button>
                                </div>
                            </div>
                            <div class="mhb-dropdown-done">
                                <button class="mhb-done-btn">Done</button>
                            </div>
                        </div>
                    </div>
                    <div class="seperator-line"></div>
                    <div class="mhb-field" style="border-right: none;" id="mhb-rates-field">
                        <div class="mhb-label">SPECIAL RATES</div>
                        <div class="mhb-value">
                            <span>Lowest Regular Rate</span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #1c1c1c;"></i>
                        </div>
                        <!-- Rates Dropdown -->
                        <div class="mhb-dropdown mhb-rates-dropdown">
                            <div class="mhb-dropdown-header">
                                <div class="mhb-close-btn"><i class="fa-solid fa-xmark"></i></div>
                            </div>
                            <div class="mhb-rate-item active" data-rate="Lowest Regular Rate">
                                <span class="mhb-rate-text">Lowest Regular Rate</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-rate-item" data-rate="Corp/Promo Code">
                                <span class="mhb-rate-text">Corp/Promo Code</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-promo-field" style="display: none;">
                                <input type="text" class="mhb-promo-input" placeholder="Enter Code">
                                <i class="fa-solid fa-circle-xmark mhb-promo-clear"></i>
                            </div>
                            <div class="mhb-rate-item" data-rate="Senior Discount">
                                <span class="mhb-rate-text">Senior Discount</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-rate-item" data-rate="AAA/CAA">
                                <span class="mhb-rate-text">AAA/CAA</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-rate-item" data-rate="Government & Military">
                                <span class="mhb-rate-text">Government & Military</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-rate-item" data-rate="Group Code">
                                <span class="mhb-rate-text">Group Code</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-rate-item" data-rate="Marriott Bonvoy Points">
                                <span class="mhb-rate-text">Marriott Bonvoy Points</span>
                                <div class="mhb-radio"></div>
                            </div>
                            <div class="mhb-dropdown-done">
                                <button class="mhb-done-btn">Done</button>
                            </div>
                        </div>
                    </div>
                    <div class="mhb-action">
                        <a href="#" class="mhb-btn">View Rates</a>
                    </div>
                </div>
            </div>
        </div>
    </header>



        <header class="ul-header d-lg-none bg-white border-bottom sticky-top">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <!-- Branding Left -->
                    <div class="m-mob-brand-left pe-3 border-end">
                        <a href="' . BASE_URL . '' . '" class="d-block mb-1">
                            <img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" alt="logo" style="height: 60px;">
                        </a>

                    </div>
                    <!-- Branding Right -->
                    <div class="m-mob-brand-right ps-3">
                        <h2 class="m-mob-hotel-name mb-0">
                            Lumbini Palace Resort
                        </h2>
                    </div>
                </div>
                <div class="ul-header-actions">
                    <button class="ul-header-sidebar-opener"
                        style="border: none; background: transparent; padding: 5px;">
                        <i class="fa-solid fa-bars" style="font-size: 20px; color: #1c1c1c;"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>



    <!-- ============ MOBILE HEADER ============ -->



';

$jVars['module:header'] = $header;

$sidebarlogo = '
                <a href="' . BASE_URL . 'home' . '">
                    <img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" alt="logo" class="logo">
                </a>



';

$jVars['module:sidebarlogo'] = $sidebarlogo;






// $header1 = '
//                 <header class="site-header">
//                <div class="logo">
//                	<a href="' . BASE_URL . 'home' . '"><img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" style="border-radius: 6%; background-color: white;"></a>
//                </div> 
//             </header>

//             <div id="main-content" class="twelve columns">
//                 ' . $jVars['module:slideshow-content'] . '
$headerscript = '';
$tellinked = '';
$telno = explode("/", $siteRegulars->contact_info);
$lastElement = array_shift($telno);
$tellinked .= '
<a href="tel:' . $lastElement . '" class="mhm-right-link"><i class="fa-solid fa-phone"></i> <span>' . $lastElement . '</span></a>
';
foreach ($telno as $tel) {

    $tellinked .= '
    <a href="tel:' . $tel . '" class="mhm-right-link"><i class="fa-solid fa-phone"></i> <span>' . $tel . '</span></a>
';
    if (end($telno) != $tel) {
        $tellinked .= '/';
    }
}
