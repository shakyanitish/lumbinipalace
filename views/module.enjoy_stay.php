<?php

$enjoy_stay_section = '';

if (defined('HOME_PAGE')) {
    // Fetch all main services (service_type = 2) marked for homepage display
    $homeServices = Services::find_by_sql("SELECT * FROM tbl_services WHERE status = 1 AND service_type = 2 ORDER BY sortorder ASC");

    if (!empty($homeServices)) {
        // Build tab buttons
        $tabButtons = '';
        $tabContents = '';

        foreach ($homeServices as $i => $service) {
            $tabId = 'tab-enjoy-' . $service->id;
            $isActive = ($i === 0) ? 'active' : '';
            
            // Get first image from serialized image array (use icon image)
            $serviceImage = '';
            if (!empty($service->iconimage) && $service->iconimage != "a:0:{}") {
                $imageList = unserialize($service->iconimage);
                if (!empty($imageList[0])) {
                    $file_path = SITE_ROOT . 'images/services/icon/' . $imageList[0];
                    if (file_exists($file_path)) {
                        $serviceImage = IMAGE_PATH . 'services/icon/' . $imageList[0];
                    }
                }
            }
            
            // Build link
            $link = '#';
            if (!empty($service->linksrc)) {
                $link = ($service->linktype == 1) ? $service->linksrc : BASE_URL . 'service/' . $service->slug;
            }

            // Tab button
            $tabButtons .= '
                    <button class="tab-nav ' . $isActive . '" data-tab="' . $tabId . '">' .$service->title . '</button>';

            // Tab content
            $tabContents .= '
                    <div class="ul-tab ' . ($i === 0 ? 'active' : '') . '" id="' . $tabId . '">
                        <div class="m-enjoy-card">
                            <div class="m-enjoy-card-img"><img src="' . $serviceImage . '" alt="' . $service->title . '"></div>
                            <div class="m-enjoy-card-body">
                                <p class="m-card-label">' .$service->title . '</p>
                                <h3 class="m-card-title">' .$service->sub_title . '</h3>
                                <p class="m-card-text">' . $service->content . '</p>
                                <a href="' . $link . '" class="m-card-link">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>';
        }

        // Build complete section
        $enjoy_stay_section = '
            <section class="m-enjoy-stay wow animate__fadeInUp">
                <div class="m-enjoy-stay-inner">
                    <div class="m-enjoy-stay-header">
                        <h2 class="m-enjoy-stay-title">More Ways to Enjoy Your Stay</h2>
                    </div>
                    <div class="m-enjoy-tabs">
                        ' . $tabButtons . '
                    </div>
                    <div class="m-enjoy-content">
                        ' . $tabContents . '
                    </div>
                </div>
            </section>';
    }
}

$jVars['module:enjoy-stay'] = $enjoy_stay_section;
