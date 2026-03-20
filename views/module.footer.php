<?php
$siteRegulars = Config::find_by_id(1);
$lastElement = '';
$phonelinked = '';
$whatsapp = '';
$tellinked = '';
$contact = '';

$tellinked = '';
$telno = array_map('trim', explode(',', $siteRegulars->contact_info));

foreach ($telno as $index => $tel) {
    // remove spaces for tel link
    $cleanTel = str_replace(' ', '', $tel);

    $tellinked .= '<a href="tel:+977' . $cleanTel . '">
                    <i class="flaticon-telephone-call"></i>+977 ' . $tel . '
               </a>';

    // separator except last item
    if ($index !== array_key_last($telno)) {
        $tellinked .= ' ';
    }
}


$tolllinked = '';
$telno = array_map('trim', explode(',', $siteRegulars->address));

foreach ($telno as $index => $tel) {
    // remove spaces for tel link
    $cleanTel = str_replace(' ', '', $tel);

    $tolllinked .= '
    <a href="tel:+977' . $cleanTel . '" class="text-dark text-decoration-none">+977
                            ' . $tel . '</a>';

    // separator except last item
    if ($index !== array_key_last($telno)) {
        $tolllinked .= ' ';
    }
}

$faxlinked = '';
$faxno = array_map('trim', explode(',', $siteRegulars->pobox));

foreach ($faxno as $index => $fax) {
    // remove spaces for tel link
    $cleanFax = str_replace(' ', '', $fax);

    $faxlinked .= '


    <a href="tel:+977' . $cleanFax . '" class="text-dark text-decoration-none">+977 ' . $fax . '</a>';

    // separator except last item
    if ($index !== array_key_last($faxno)) {
        $faxlinked .= ' ';
    }
}
$roomlinked = '';
$roomno = array_map('trim', explode(',', $siteRegulars->room_reservation_number));

foreach ($roomno as $index => $room) {
    // remove spaces for tel link
    $cleanRoom = str_replace(' ', '', $room);

    $roomlinked .= '


    <a href="tel:+977' . $cleanRoom . '" class="text-dark text-decoration-none">+977 ' . $room . '</a>';

    // separator except last item
    if ($index !== array_key_last($roomno)) {
        $roomlinked .= ' ';
    }
}




$office = '';
$ot = explode(",", $siteRegulars->pobox);

$first = trim(array_shift($ot));
$office .= '<span>' . $first . '</span>';

foreach ($ot as $o) {
    $o = trim($o);
    $office .= ', <span>' . $o . '</span>';
}


$emailinked = '';
$emails = array_map('trim', explode(',', $siteRegulars->email_address));

foreach ($emails as $index => $email) {
    $emailinked .= '<a href="mailto:' . $email . '">
                        <i class="flaticon-mail"></i>' . $email . '
                   </a>';

    // separator except last item
    if ($index !== array_key_last($emails)) {
        $emailinked .= ' ';
    }
}

$whatsapp = '';
$phoneno = explode("/", $siteRegulars->whatsapp);
$lastElement = array_shift($phoneno);
$phonelinked .= '<a href="tel:+977-' . $lastElement . '" target="_blank" rel="noreferrer">' . $lastElement . '</a>/';
foreach ($phoneno as $phone) {

    $phonelinked .= '<a href="tel:+977-' . $phone . '" target="_blank" rel="noreferrer">' . $phone . '</a>';
    if (end($phoneno) != $phone) {
        $phonelinked .= '/';
    }
}
$breif = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($siteRegulars->breif));
$icons = '';
if (!empty($socialRec)) {
    foreach ($socialRec as $socialRow) {
        $icons .= '
            <a href="' . $socialRow->linksrc . '" class="ms-2" target="_blank" rel="noreferrer noopener">
                <img src="' . IMAGE_PATH . 'social/' . $socialRow->image . '" height="20" alt="">
            </a>
        ';
    }
}




$footer = '
    <footer class="ul-footer py-5 footer-main">
        <div class="container">
            <h2 class="footer-title">Lumbini Palace Resort</h2>
            <div class="row gx-lg-5">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-6">
                            <div class="footer-divider"></div>
                            <ul class="list-unstyled mb-0" style="line-height: 2.2;">
                            ' . $jVars['module:footer-menu-list1'] . '
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="footer-divider"></div>
                            <ul class="list-unstyled mb-0" style="line-height: 2.2;">
                                ' . $jVars['module:footer-menu-list'] . '
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 border-start-md ps-md-5" style="letter-spacing: 0.5px;">
                    <div class="footer-divider"></div>
                    <p class="small mb-4 text-uppercase fw-normal" style="line-height: 1.6;">
                        ' . $siteRegulars->fiscal_address . '
                    </p>
                    <p class="small mb-0 fw-normal">
                        TOLL FREE: ' . $tolllinked . '<br>
                        ROOM RESERVATIONS PHONE NUMBER: ' . $roomlinked . '<br>
                        FAX: ' . $faxlinked . '<br>
                    </p>
                </div>
            </div>
            <div class="footer-divider" style="margin-bottom: 30px;"></div>
            <div class="row">
                <div class="col-12">
                    <p class="small d-inline-block me-3 mb-0">Follow Lumbini Palace Resort</p>
                    ' . $jVars['module:socilaLinkbtmfooter'] . '

                </div>
            </div>

        </div>
    </footer>














 ';



$jVars['module:footer'] = $footer;
