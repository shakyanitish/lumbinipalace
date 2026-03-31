<?php

$vt_bread = $vt_details = $subScript = '';

//VIRTUAL TOUR ID FOR USING SPECIFIC; TO CREATE NEW VIRTUAL TOUR [VT]
//$vid = 4;

 $imglink = BASE_URL . 'template/web/img/banner/09.jpg';
// default image from Preference Mgmt
if (!empty($siteRegulars->other_upload)) {
    if (file_exists(SITE_ROOT . "images/preference/other/" . $siteRegulars->other_upload)) {
        $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    }
}

$vt_bread .= '
 <div class="banner-header section-padding valign bg-darkbrown1" data-overlay-dark="4"
     style="background-image:url('.$imglink.'); background-size:cover; background-position:center;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center caption mt-90">
                    <h1>Virtual Tour</h1>
                </div>
            </div>
        </div>
    </div>
';

function generate_virtual_tour($vtId)
{

    $virtual    = VirtualTour::find_by_id_active($vtId);
    $images     = Image360::find_by_v_id($vtId);
    $siteRegulars = Config::find_by_id(1);
    $vt_script_f = $vt_detail_f = '';
    $vt_nav = $vt_cases = '';

    if(!empty($virtual)){
        $vt_script_f .= '
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
        <script>
            // remove pannellum title when right clicking inside virtual tour
            var observer = new MutationObserver(function (mutations) {
                mutations.forEach((mutation) => {
                    $(mutation.addedNodes).each(function () {
                        if ($(this).hasClass("pnlm-about-msg")) {
                            setTimeout(() => {
                                $(this).remove(); // Automatically remove the element after 3 seconds
                                // console.log("Element removed:", this);
                            }, 3000);
                        }
                    });
                });
            });
            
            // Start observing the body for changes
            observer.observe(document.body, { childList: true, subtree: true });

            var viewer' . $vtId . ' = pannellum.viewer("panorama' . $vtId . '", {   
                "default": {
                    "firstScene": "img' . @$images[0]->id . '",
                    "author": "'.$siteRegulars->sitetitle.'",
                    "sceneFadeDuration": ' . $virtual->scene_fade_duration . ',
                    "autoLoad": true
                },
                "scenes": {
        ';

        if(!empty($images)){
            foreach ($images as $img) {
                $vt_nav .= '
                    <li class="list-group-item">
                        <a href="javascript:void(0)" onclick="loadScene' . $vtId . '(\'img' . $img->id . '\')" class="text-decoration-none d-block">' . $img->title . '</a>
                    </li>
                ';
                $vt_cases .= '
                    case \'img' . $img->id . '\':
                    scenePath = \'' . IMAGE_PATH . '360/' . $img->panorama . '\';
                    break;
                ';
                $vt_script_f .= ' 
                        "img' . $img->id . '": {
                            "title": "' . $img->title . '",
                            "hfov": ' . $img->hfov . ',
                            "pitch": ' . $img->pitch . ',
                            "yaw": ' . $img->yaw . ',
                            "type": "equirectangular",
                            "panorama": "' . IMAGE_PATH . '360/' . $img->panorama . '",
                            "hotSpots": [
                ';

                //GETTING 360 IMAGES BY IDS
                $hotspot = Hotspots::find_by_images($img->id);
                if(!empty($hotspot)){
                    foreach ($hotspot as $hp) {
                        // $img_title = (!empty($hp->scene_id) AND $hp->scene_id != ' ') ? Image360::field_by_id($hp->scene_id, 'title') : '';
                        $img_title = (!empty($hp->scene_id) AND $hp->scene_id != ' ') ? 'img'.Image360::field_by_id($hp->scene_id, 'id') : '';
                        $vt_script_f .= '
                                    {
                                        "pitch": ' . $hp->hotspot_pitch . ',
                                        "yaw": ' . $hp->hotspot_yaw . ',
                                        "type": "'.$hp->hotspot_type.'",
                                        "text": "' . $hp->title . '",
                                        "sceneId": "' . $img_title . '",
                        ';

                        if (!empty($hp->target_yaw)) {
                            $vt_script_f .= '"targetYaw": ' . $hp->target_yaw . ',';
                        }

                        $vt_script_f .= '
                                        "targetPitch": ' . $hp->target_pitch . '
                                    },
                        ';
                    }
                }

                $vt_script_f .= '
                            ]
                        },
                ';
            }
        }

        $vt_script_f .= '
                }
            });

            function loadScene' . $vtId . '(sceneID) {
                
                // Load the selected scene
                viewer' . $vtId . '.loadScene(sceneID);
            }
            
            // Start tracking yaw/pitch/hfov for this viewer
            function trackViewer' . $vtId . '() {
                if (!viewer' . $vtId . ') return;
            
                const yaw = viewer' . $vtId . '.getYaw();
                const pitch = viewer' . $vtId . '.getPitch();
                const hfov = viewer' . $vtId . '.getHfov();
            
                console.log("Viewer ' . $vtId . ' -> Yaw: " + yaw.toFixed(2) + " | Pitch: " + pitch.toFixed(2) + " | HFOV: " + hfov.toFixed(2));
            
                requestAnimationFrame(trackViewer' . $vtId . ');
            }
            
            // Wait until viewer is loaded
            viewer' . $vtId . '.on("load", function() {
                trackViewer' . $vtId . '();
            });
            
            // Function to load a scene
            function loadScene' . $vtId . '(sceneID) {
                viewer' . $vtId . '.loadScene(sceneID);
            }
                
        </script>
        ';

        $vt_detail_f .= '
            <!--
            <div class="d-none hide">
                <h2>Choose a Scene</h2>
                <ul>
                    '.$vt_nav.'
                </ul>
            </div>
            <div id="panorama' . $vtId . '" class="panellum-context"></div>
            -->
            
            <style>
                #panorama' . $vtId . ' {
                    /*width:  ' . $virtual->image_width . 'px;*/
                    height: ' . $virtual->image_height . 'px;
                }
                .pnlm-dragfix {cursor: grab;}
                .nav-section {height: ' . $virtual->image_height . 'px;}
            </style> 
            
            <div class="row">
                <!-- Panorama Viewer -->
                <div class="col-lg-9 col-12 mb-3 mb-lg-0">
                    <div id="panorama' . $vtId . '" class="panellum-context rounded shadow"></div>
                </div>
        
                <!-- Navigation Sidebar -->
                <div class="col-lg-3 col-12">
                    <div class="nav-section bg-light rounded shadow p-3" style="overflow-y: auto;">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Quick Navigation</h5>
                        <ul class="list-group list-group-flush">
                            ' . $vt_nav . '
                        </ul>
                    </div>
                </div>
            </div>
        ';
    }


    return [$vt_detail_f, $vt_script_f];
}


// $virtuals = generate_virtual_tour(4);
// $jVars['module:vt:detail-f-4'] = $virtuals[0];
// $jVars['module:vt:script-f-4'] = $virtuals[1];

$virtuals = generate_virtual_tour(1);
$jVars['module:vt:detail-f-1'] = $virtuals[0];
$jVars['module:vt:script-f-1'] = $virtuals[1];

$virtualss = generate_virtual_tour(7);
$jVars['module:vt:detail-f-7'] = $virtualss[0];
$jVars['module:vt:script-f-7'] = $virtualss[1];

$virtualsss = generate_virtual_tour(8);
$jVars['module:vt:detail-f-8'] = $virtualsss[0];
$jVars['module:vt:script-f-8'] = $virtualsss[1];


//DEPLOYING DYNAMIC VIRTUAL TOUR IN HTML PAGES VIA JCMS TAG
$jVars['module:virtualtour:bread'] = $vt_bread;



// listing all virtual tours in one page
$allVirtualTours = VirtualTour::find_by_sql("SELECT * FROM tbl_vt_virtual_tour where status=1 ORDER BY sortorder DESC ");
$_detail = '';

foreach ($allVirtualTours as $virtualTourRec) {
    $_parts = generate_virtual_tour($virtualTourRec->id);
    $_detail .= '<div style="margin-bottom: 20px;">' . $_parts[0] . '</div>';
    $_detail .= $_parts[1];

    // load all virtual tours at once, so they can be loaded into other modules as required
    $jVars['module:vt:detail-f-' . $virtualTourRec->id] = $_parts[0];
    $jVars['module:vt:script-f-' . $virtualTourRec->id] = $_parts[1];
}

$jVars['module:virtual-tour:list'] = $_detail;