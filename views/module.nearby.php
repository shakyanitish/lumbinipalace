<?php
$nearbydetail = $nearby_list = $first_map = '';

$recRows = Nearby::find_all_active();

if (!empty($recRows)) {
    foreach ($recRows as $key => $recRow) {
        $active_class = ($key == 0) ? 'show' : '';
        $button_class = ($key == 0) ? '' : 'collapsed';
        $aria_expanded = ($key == 0) ? 'true' : 'false';
        
        // Extract src from google_embeded if it's an iframe tag
        $map_url = $recRow->google_embeded;
        if (preg_match('/src="([^"]+)"/', $map_url, $match)) {
            $map_url = $match[1];
        }

        if ($key == 0) {
            $first_map = $map_url;
        }



        $nearby_list .= '
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading' . $recRow->id . '">
                <button class="accordion-button ' . $button_class . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $recRow->id . '" aria-expanded="' . $aria_expanded . '" aria-controls="collapse' . $recRow->id . '">
                    <i class="' . $recRow->distance . '"></i> ' . $recRow->title . '
                </button>
            </h2>
            <div id="collapse' . $recRow->id . '" class="accordion-collapse collapse ' . $active_class . '" aria-labelledby="heading' . $recRow->id . '" data-bs-parent="#locationAccordion">
                <div class="accordion-body">
                    <p class="mb-1"><strong>Distance from Property:</strong> ' . $recRow->distance . '</p>
                    <div class="mb-2">' . $recRow->content . '</div>
<a href="javascript:void(0);" data-map-url="' . $map_url . '" class="mhm-right-link view-map-btn" style="color: white;">
    <i class="fa-solid fa-location-dot"></i> <span>VIEW MAP</span>
</a>
                </div>
            </div>
        </div>';
    }

    // Get hotel configuration for address/phone
    $siteConfig = Config::find_by_id(1);
    $sitename = !empty($siteConfig->sitename) ? $siteConfig->sitename : 'Lumbini Palace Resort Ltd.';
    $address = !empty($siteConfig->address) ? $siteConfig->address : '';
    $phone = !empty($siteConfig->contact_info) ? $siteConfig->contact_info : '';

    $nearbydetail = '
    <section class="m-location wow animate__fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
        <div class="m-location-inner">
            <!-- Left Content: Location details & accordion -->
            <div class="m-location-text">
                <p class="m-section-label">OUR LOCATION</p>
                <h2 class="m-section-title">Getting Here</h2>

                <div class="mb-5">
                    <p class="m-location-address"><strong>' . $siteRegulars->fiscal_address . '</strong><br>
                    </p>
                    <p class="m-location-tel mb-5">Tel: <a href="tel:' . $siteRegulars->contact_info . '">' . $siteRegulars->contact_info . '</a></p>
                </div>

                <!-- Accordion -->
                <div class="accordion m-location-accordion" id="locationAccordion">
                    ' . $nearby_list . '
                </div>
            </div>

            <!-- Right Content: Map -->
            <div class="m-location-map-wrap p-2">
                <iframe id="nearby-map-iframe" src="' . $first_map . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="' . $sitename . '">
                </iframe>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".view-map-btn").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    const mapUrl = this.getAttribute("data-map-url");
                    const iframe = document.getElementById("nearby-map-iframe");
                    if (mapUrl && iframe) {
                        iframe.src = mapUrl;
                    }
                });
            });
        });
    </script>';
}

$jVars['module:inner-nearby-detail'] = $nearbydetail;
$jVars['module:inner-nearby-detail-modals'] = '';

?>