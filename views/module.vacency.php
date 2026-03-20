<?php

$vacencylist = $vacency_bread = '';

if (defined('CAREER_PAGE')) {
    $siteRegulars = Config::find_by_id(1);
    $hremail= User::field_by_id(1,'hr_email');
    $imglink= $siteRegulars->gallery_upload ;
if(!empty($imglink)){
    $img= IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload ;
}
else{
    $img='';
}


    // $vacency_bread .= '
    // <!--================ Breadcrumb ================-->
    //     <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="'.$img.'">
    //         <div class="container wide">
    //             <h1 class="mad-page-title">Career Page</h1>
    //             <nav class="mad-breadcrumb-path">
    //                 <span><a href="' . BASE_URL . 'home" class="mad-link">Home</a></span> /
    //                 <span>Career</span>
    //             </nav>
    //         </div>
    //     </div>
    //     <!--================ End of Breadcrumb ================-->=
       
    // ';

    $vacencies = Vacency::find_all();

    // pr($vacencies);
    if (!empty($vacencies)) {
        foreach ($vacencies as $i => $vacancy) {
            $collapseId = "collapseThree" . ($i + 1);
            $headingId = "headingThree" . ($i + 1);
            $show = ($i == 0) ? 'show' : '';
            $collapsed = ($i == 0) ? '' : 'collapsed';
            $expanded = ($i == 0) ? 'true' : 'false';

            $vacencylist .= '



                       <!-- Accordion card -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header" role="tab" id="' . $headingId . '">
                    <a class="' . $collapsed . '" data-toggle="collapse" data-parent="#accordionEx1" href="#' . $collapseId . '" aria-expanded="' . $expanded . '" aria-controls="' . $collapseId . '">
                        <h5 class="mb-0">' . $vacancy->title . ' <i class="fas fa-plus"></i></h5>
                    </a>
                </div>

                <!-- Card body -->
                <div id="' . $collapseId . '" class="collapse ' . $show . '" role="tabpanel" aria-labelledby="' . $headingId . '" data-parent="#accordionEx1">
                    <div class="card-body call-action ">
                        <div class="call-wrap">
                            <div class="call-main">
                                ' . $vacancy->content . '
                            </div>
                            <div class="call-btn">
                                <div class="btn" data-toggle="modal" data-target="#exampleModal" data-title="'.$vacancy->title.'" data-vacancy-id="'.$vacancy->id.'">Apply</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            ';
        }
    } else {
        $vacencylist .= '';
        // <h4 style="text-align: center;font-size: 50px;">SEND YOUR RESUME!!!! in our email<br> <a href="mailto:'.$hremail.'">'.$hremail.'</a></h4></br></br>
    }
}

$jVars['module:vacencylist'] = $vacencylist;
$jVars['module:vacency:list-bread'] = $vacency_bread;





/**
 *      Career Form
 */

$careerform = $innerbred = '';
if (defined('CAREER_PAGE') and isset($_REQUEST['slug'])) {
    
    $innerbred .= '  
    <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="'. BASE_URL .'template/web/images/default.jpg">
            <div class="container wide">
                
                <nav class="mad-breadcrumb-path">
                    <span><a href="' . BASE_URL . 'home" class="mad-link">Home</a></span> /
                    
                    <span>Apply Now</span>
                </nav>
            </div>
        </div>
        <!--================ End of Breadcrumb ================--> 
    
        ';
    $slug = $_REQUEST['slug'];
    $v = Vacency::find_by_slug($slug);
    
    $vacen = (!empty($v->id))? $v->id :"none";
    $careerform = '
        
        <form class="cons-contact-form form-transparent mb-5" id="careerform" enctype="multipart/form-data">
            <input type="hidden" name="position" value="' . $v->id . '">
        
            <div class="row career-label">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input name="name" type="text" required="" class="form-control" placeholder="Name">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Your E-mail</label>
                        <input name="email" type="text" class="form-control" required="" placeholder="Email">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Your Number</label>
                        <input name="phone" type="text" class="form-control" required="" placeholder="Phone">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Your Address</label>
                        <input name="address" type="text" class="form-control" required="" placeholder="Address">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Upload Your File</label>
                        <div class="file">
                            <i class="fa fa-file"></i>
                            <input type="file" id="FileAttachment" name="myfile">
                            <input type="hidden" name="fileArrayname"/>
                            <span class="car123">(Maximum file size is 500KB)</span>
                        </div>
                        <div id="responseFile"></div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label style="margin-top: 20px;">Your MESSAGE</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Message"></textarea>
                    </div>
                </div>
            </div>

            <button type="submit"  class="btn btn-light" id="submit">
                <span class="font-weight-700 inline-block text-uppercase p-lr15 suubmit">Apply Now</span>
            </button>

            <div class="alert alert-success" id="msg" style="display:none;"></div>
        </form>
    ';
}
$jVars['module:careerform'] = $careerform;
$jVars['module:vacency-bread'] = $innerbred;