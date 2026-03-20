<?php
$teamdatas = team::find_all_active();
$leader = team::find_all_by_role(1);
$sales = team::find_all_by_role(2);
$guides = team::find_all_by_role(3);
$pastchairpersons = team::find_all_by_role(4);
$lifetimemembers = team::find_all_by_role(5);

$teambread = $teamLeader = $salesteam = $guide = $pastchairperson = $lifetime = '';

if (defined('TEAM_PAGE')) {



    if (!empty($pastchairpersons)) {
        foreach ($pastchairpersons as $i => $teamdata) {
            $facebookLink = (!empty($teamdata->facebook)) ? $teamdata->facebook : "javascript:void(0)";
            $instagram = (!empty($teamdata->instagram)) ? $teamdata->instagram : "javascript:void(0)";
            $imgsrc = IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload;
            $file_path = SITE_ROOT . 'images/team/' . $teamdata->image;
            if (file_exists($file_path) and !empty($teamdata->image)) {
                $imgsrc = IMAGE_PATH . 'team/' . $teamdata->image;
            }

            $real_content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($teamdata->content));
            $seen_content = $real_content[0];
            $unseen_content = $real_content[1] ?? $real_content[0];

            $pastchairperson .= '

                    <div class="col-md-4">
                        <div class="team-inner">
                            <h3 class="ul-feature-title" style="color: #d93431;">' . $teamdata->name . '</h3>
                            
                            
                        </div>
                    </div> ';
        }
    }

    $jVars['module:team:past'] = $pastchairperson;


    if (!empty($lifetimemembers)) {
        foreach ($lifetimemembers as $i => $teamdata) {
            $facebookLink = (!empty($teamdata->facebook)) ? $teamdata->facebook : "javascript:void(0)";
            $instagram = (!empty($teamdata->instagram)) ? $teamdata->instagram : "javascript:void(0)";
            $imgsrc = IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload;
            $file_path = SITE_ROOT . 'images/team/' . $teamdata->image;
            if (file_exists($file_path) and !empty($teamdata->image)) {
                $imgsrc = IMAGE_PATH . 'team/' . $teamdata->image;
            }

            $real_content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($teamdata->content));
            $seen_content = $real_content[0];
            $unseen_content = $real_content[1] ?? $real_content[0];

            $lifetime .= '


            
                    <div class="col-md-4">
                        <div class="team-inner">
                            <h3 class="ul-feature-title" style="color: #d93431;">' . $teamdata->name . '</h3>
                           
                        </div>
                    </div> ';
        }
    }

    $jVars['module:team:life'] = $lifetime;





    if (!empty($leader)) {
        foreach ($leader as $i => $teamdata) {
            $facebookLink = (!empty($teamdata->facebook)) ? $teamdata->facebook : "javascript:void(0)";
            $instagram = (!empty($teamdata->instagram)) ? $teamdata->instagram : "javascript:void(0)";
            $imgsrc = IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload;
            $file_path = SITE_ROOT . 'images/team/' . $teamdata->image;
            if (file_exists($file_path) and !empty($teamdata->image)) {
                $imgsrc = IMAGE_PATH . 'team/' . $teamdata->image;
            }

            $real_content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($teamdata->content));
            $seen_content = $real_content[0];
            $unseen_content = $real_content[1] ?? $real_content[0];

            $teamLeader .= '



                    <div class="col-md-4">
                        <div class="team-inner">
                            <h3 class="ul-feature-title" style="color: #d93431;">' . $teamdata->name . '</h3>
                            <p>' . $teamdata->title . '</p>
                            <p class="txt2">' . $seen_content . '</p>
                        </div>
                    </div> ';
        }
    }
}

if (!empty($sales)) {


    foreach ($sales as $i => $teamdata) {
        $facebookLink = (!empty($teamdata->facebook)) ? $teamdata->facebook : "javascript:void(0)";
        $instagram = (!empty($teamdata->instagram)) ? $teamdata->instagram : "javascript:void(0)";
        $imgsrc = IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload;
        $file_path = SITE_ROOT . 'images/team/' . $teamdata->image;
        if (file_exists($file_path) and !empty($teamdata->image)) {
            $imgsrc = IMAGE_PATH . 'team/' . $teamdata->image;
        }

        $salesteam .= '



                    <div class="col-md-4">
                        <div class="team-inner">
                            <h3 class="ul-feature-title" style="color: #d93431;">' . $teamdata->name . '
                            </h3>
                            <p>' . $teamdata->title . '</p>
                            <p class="txt2">' . strip_tags($teamdata->content) . '</p>
                        </div>
                    </div>






            ';
    }

    if (!empty($guides)) {

        foreach ($guides as $i => $teamdata) {
            $facebookLink = (!empty($teamdata->facebook)) ? $teamdata->facebook : "javascript:void(0)";
            $instagram = (!empty($teamdata->instagram)) ? $teamdata->instagram : "javascript:void(0)";
            $imgsrc = IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload;
            $file_path = SITE_ROOT . 'images/team/' . $teamdata->image;
            if (file_exists($file_path) and !empty($teamdata->image)) {
                $imgsrc = IMAGE_PATH . 'team/' . $teamdata->image;
            }

            $guide .= '


                    <div class="col-md-4">
                        <div class="team-inner">
                            <h3 class="ul-feature-title" style="color: #d93431;">' . $teamdata->name . '</h3>
                            <p>' . $teamdata->title . '</p>
                            <p class="txt2"> ' . strip_tags($teamdata->content) . '</p>
                        </div>
                    </div>








            ';
        }
    }
}

$jVars['module:team:leader'] = $teamLeader;
$jVars['module:team:sales'] = $salesteam;
$jVars['module:team:guide'] = $guide;
$jVars['module:team:bread'] = $teambread;
