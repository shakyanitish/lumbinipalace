<?php
$resinndetail = $imageList = $innerbred = $t = '';
$homearticle = mservices::find_by_id(22);
if (!empty($homearticle)) {
    if ($homearticle->image != "a:0:{}") {
        $imageList = unserialize($homearticle->image);
        $imgno = array_rand($imageList);
        $file_path = SITE_ROOT . 'images/mservices/' . $imageList[$imgno];
        if (file_exists($file_path)) {
            $imglink = IMAGE_PATH . 'mservices/' . $imageList[$imgno];
        } else {
            $imglink = BASE_URL . 'template/web/img/mosaic_2.jpg';
        }
    } else {
        $imglink = BASE_URL . 'template/cms/img/mosaic_2.jpg';
    }
    $t .= ' <div class="col-xs-12">
                     <a href="' . BASE_URL . 'page/' . $homearticle->slug . '">
                    <div class="mosaic_container">
                        <img src="' . $imglink . '" alt="' . $homearticle->title . '" class="img-responsive add_bottom_30"><span class="caption_2"> ' . $homearticle->title . '</span>
                    </div>
                    </a>
                </div>';


}

$jVars['module:aboutarticle'] = $t;

/**
 *      Home page
 */
$servicecont = '';

if (defined('HOME_PAGE') ) {
    // $slug = addslashes($_REQUEST['slug']);
    // $recRow = mservices::find_by_slug($slug);
    $recInn = mservices::homepageArticle();
    
    $imagem='';
    if (!empty($homearticle)) {
        
}
        $servicecont .=' <article class="mad-entity" id="mad-sync-elements">
        <div class="mad-entity-media">
        <div class="mad-panels-img">';
        

        foreach ($recInn as $i => $innRow) {
            $collapsed = ($i == 0) ? '' : 'mad-panels-active';
            $show = ($i == 0) ? 'clicked' : '';
            $homearticle = mservices::find_by_id($innRow->id);
    if ($homearticle->image != "a:0:{}") {
        $imageList = unserialize($homearticle->image);
        $imgno = array_rand($imageList);
        $file_path = SITE_ROOT . 'images/mservices/' . $imageList[$imgno];
        if (file_exists($file_path)) {
            $imglink = IMAGE_PATH . 'mservices/' . $imageList[$imgno];
        } else {
            $imglink = BASE_URL . 'template/web/img/mosaic_2.jpg';
        }
    } else {
        $imglink = BASE_URL . 'template/cms/img/mosaic_2.jpg';
    }
    $imagem ='<img src="'.$imglink.'" alt="">';

   

            $servicecont .='<section data-active="'. $innRow->slug .'" class="'.$show.'">
            '. $imagem .'
        </section>';
        }
        $servicecont .='</div>
        </div>
        <div class="mad-entity-content services-section">
                                <div class="content-element-7">
                                    <div class="mad-entity-pre-title">Our Services</div>
                                    <!--================ Accordion ================-->
                                    <dl role="presentation" class="mad-panels mad-panels--accordion type-big">';
                                    
        foreach ($recInn as $innRow) {
            $linkTarget = ($innRow->linktype == 1) ? ' target="_blank" ' : '';
        $linksrc = ($innRow->linktype == 1) ? $innRow->linksrc : BASE_URL . $innRow->linksrc;
            
        $servicecont .='
        <dt class="mad-panels-title $collapsed" data-active="'. $innRow->slug .'">
                                            <button id="'. $innRow->slug .'-button" type="button" aria-expanded="false" aria-controls="panel-7" aria-disabled="false">
                                            '. $innRow->title .'
                                            
                                            </button>
                                        </dt>
                                        <dd id="'. $innRow->slug .'" class="mad-panels-definition">
                                            <p>
                                                <b>'. $innRow->sub_title .'</b>
                                            </p>
                                            <p>
                                            '. $innRow->content .'
                                            </p>
                                            <a href="'.$linksrc.'  " class="mad-text-link">
                                                <div class="link-container">
                                                    <span class="link-title1 title">Explore More</span>
                                                    <span class="link-title2 title">Explore More</span>
                                                </div>
                                            </a>
                                        </dd>
                                       ';
                                    //    pr($recInn);
                                    } 
                                    
                                    $servicecont .='</dl>
                                    <!--================ End of Accordion ================-->
                                </div>
                            </div>
                        </article>
                        <!--================ End of Entity ================-->
                    </div>
                </div>';                           
    
    }
    


$jVars['module:home-mainservice'] = $servicecont;

/**
 *      Inner page detail
 */




$restyp = '';

$typRow = Article::get_by_type();
if (!empty($typRow)) {
    $content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($typRow->content));
    $readmore = '';
    if (!empty($typRow->linksrc)) {
        $linkTarget = ($typRow->linktype == 1) ? ' target="_blank" ' : '';
        $linksrc = ($typRow->linktype == 1) ? $typRow->linksrc : BASE_URL . $typRow->linksrc;
        $readmore = '<a class="text-link link-direct" href="' . $linksrc . '">see more</a>';
    } else {
        $readmore = (count($content) > 1) ? '<a href="' . BASE_URL . $typRow->slug . '">Read more...</a>' : '';
    }
    $restyp .= '<h3 class="h3 header-sidebar">' . $typRow->title . '</h3>
	<div class="home-content">
		' . $content[0] . ' ' . $readmore . '
	</div>';

}

$jVars['module:article_by_type'] = $restyp;



/*
    Why Choose Us
*/
$resinnh1 = '';

if (defined('HOME_PAGE')) {

    $resinnh1 .= '';

// pr($resinnh1);
    $recInn1 = Article::find_by_id(2);
    if (!empty($recInn1)) {
            $resinnh1 .= $recInn1->content;

        
    }

}

$jVars['module:home_article'] = $resinnh1;


/*
    HomePage Facilities
*/
$resinnh1 = '';

if (defined('HOME_PAGE')) {

    $resinnh1 .= '';


    $recInn1 = Article::find_by_id(3);

    if (!empty($recInn1)) {

            $resinnh1 .= $recInn1->content;

        
    }

}

$jVars['module:home_facilities'] = $resinnh1;

?>