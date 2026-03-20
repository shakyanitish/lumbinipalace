<?php 
/*
* Facilities list
*/
$rescont= $facbred= '';
  
$subpkgRec = Features::find_all_byparnt(47);
$pkgRec = Features::find_by_id(119);
// var_dump($subpkgRec); die();
    if(!empty($subpkgRec)) {
        $rescont.='
        <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="template/web/images/facilities.jpg">
            <div class="container wide">
                <h1 class="mad-page-title">Hotel Amenities</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="home" class="mad-link">Home</a></span> /
                    <span>Facilities</span>
                </nav>
            </div>
        </div>
        <!--================ End of Breadcrumb ================-->
        
        <div class="mad-section mad-section.no-pb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                <div class="mad-icon-boxes align-center small-size item-col-5">';
                      
                        foreach($subpkgRec as $k=>$v){
                                 $rescont.='
                                 <div class="mad-col">
                                <!--================ Icon Box ================-->
                                <article class="mad-icon-box">
                                    <img src="'.IMAGE_PATH.'features/' . $v->image.'">
                                    <div class="mad-icon-box-content">
                                        <h6 class="mad-icon-box-title">
                                        '.$v->title.'
                                        </h6>
                                    </div>
                                </article>
                                <!--================ End of Icon Box ================-->
                                </div>
                                  
                                ';    
                }
                                       
                            
                    $rescont.='</div>
                    <!--================ End of Icon Boxes ================-->
                </div>
            </div>
        </div>
    </div>';      
    }
   
$jVars['module:generalfacilities-list'] = $rescont;

/*
* Featured Amenities On-Site (Homepage)
*/
$amenities_html = '';
$amenity_categories = Features::find_by_sql("SELECT * FROM tbl_features WHERE parentId=0 AND status=1 ORDER BY sortorder ASC");

$total_amenities = 0;
$all_amenities = array();
$cat_tabs = '';
$cat_content = '';

if (!empty($amenity_categories)) {
    $c = 0;
    foreach ($amenity_categories as $cat) {
        $children = Features::find_by_sql("SELECT * FROM tbl_features WHERE parentId=".$cat->id." AND status=1 ORDER BY sortorder ASC");
        if (!empty($children)) {
            $count = count($children);
            $total_amenities += $count;
            
            $active_cls = ($c == 0) ? 'active' : '';
            $cat_id = 'amenity-cat-' . $cat->id;
            
            $cat_tabs .= '<button class="tab-nav ' . $active_cls . '" data-tab="' . $cat_id . '">' . $cat->title . ' (' . $count . ')</button>';
            
            $cat_content .= '<!-- ' . $cat->title . ' Tab -->
                    <div class="ul-tab ' . $active_cls . '" id="' . $cat_id . '">
                        <div class="m-amenities-header">
                            <h3>' . $cat->title . '</h3>
                        </div>
                        <div class="m-amenities-grid">';
                        
            foreach ($children as $child) {
                $icon = !empty($child->icon) ? $child->icon : 'fa-light fa-check';
                $text = !empty($child->brief) ? str_replace(array('<p>', '</p>'), '', $child->brief) : $child->title;
                
                $item_html = '<div class="m-amenity-item"><i class="' . $icon . '"></i>
                                <div class="m-amenity-text">' . $text . '</div>
                            </div>';
                
                $cat_content .= $item_html;
                $all_amenities[] = $item_html;
            }
            
            $cat_content .= '</div>
                    </div>';
            $c++;
        }
    }
    
    // Add "View All" tab
    if ($total_amenities > 0) {
        $cat_tabs .= '<button class="tab-nav" data-tab="amenity-all">View All (' . $total_amenities . ')</button>';
        
        $cat_content .= '<!-- View All Tab -->
                    <div class="ul-tab" id="amenity-all">
                        <div class="m-amenities-header">
                            <h3>All Amenities On-Site</h3>
                        </div>
                        <div class="m-amenities-grid">';
                        
        foreach ($all_amenities as $index => $item) {
            if ($index >= 18) {
                // Add extra-item class
                $item = str_replace('class="m-amenity-item"', 'class="m-amenity-item extra-item"', $item);
            }
            $cat_content .= $item;
        }
        
        if ($total_amenities > 18) {
            $cat_content .= '<div class="m-amenities-footer">
                                <button class="m-see-less-btn" id="amenity-toggle-btn">See More</button>
                            </div>';
        }
        
        $cat_content .= '</div>
                    </div>';
                    
        $amenities_html .= '<h2 class="m-amenities-section-title">FEATURED AMENITIES ON-SITE</h2>
                <div class="m-amenities-tabs">
                    ' . $cat_tabs . '
                </div>
                <div class="m-amenities-content">
                    ' . $cat_content . '
                </div>';
    }
}

$jVars['module:features:amenities'] = $amenities_html;

?>