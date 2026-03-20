<?php
$reslideone='';
if (defined('HOME_PAGE')) {
$Recordsone = News::get_newsOne(1);
if(!empty($Recordsone)) {
    foreach($Recordsone as $RecRow1) {   
        $file_path = SITE_ROOT.'images/news/'.$RecRow1->image;
        // var_dump($file_path);
        if(file_exists($file_path) and !empty($RecRow1->image)) {
            $reslideone.='<div class="col-md-6 col-sm-6 col-xs-12 mar-bottom-30">
                        <div class="service-item">
                            <div class="service-image">
                                <img src="'.IMAGE_PATH.'news/'.$RecRow1->image.'" style="height: 276px;" alt="'.$RecRow1->title.'">
                            </div>
                            <div class="service-content">
                                <h4><a href="'.BASE_URL.'news/'.$RecRow1->slug.'">'.$RecRow1->title.'</a></h4>
                            </div>
                        </div>
                    </div>';
        }
  }
}
}

$jVars['module:home-recent-news-one'] = $reslideone;
$reslide='';
if (defined('HOME_PAGE')) {
$Records = News::get_newsTwo(2);
if(!empty($Records)) {
    foreach($Records as $RecRow) {   
        $file_path = SITE_ROOT.'images/news/'.$RecRow->image;
        // var_dump($file_path);
        if(file_exists($file_path) and !empty($RecRow->image)) {
            $reslide.='<div class="col-md-3 col-sm-3 col-xs-12 mar-bottom-30">
                        <div class="service-item">
                            <div class="service-image">
                                <img src="'.IMAGE_PATH.'news/'.$RecRow->image.'" alt="'.$RecRow->title.'">
                            </div>
                            <div class="service-content">
                                <h4><a href="'.BASE_URL.'news/'.$RecRow->slug.'">'.$RecRow->title.'</a></h4>
                            </div>
                        </div>
                    </div>';
        }
  }
}
}

$jVars['module:home-recent-news-two'] = $reslide;


$relistnews = $nbread='';
        $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"]))? $_REQUEST["pageno"] : 1;
         $year = (isset($_REQUEST["year"]) and !empty($_REQUEST["year"])) ? $_REQUEST["year"] : "";

         if (!empty($year)) {
        $sql = "SELECT * FROM tbl_news WHERE status='1' AND type='1' AND YEAR(news_date) = " . $year . " ORDER BY news_date DESC";
    } else {
        $sql = "SELECT * FROM tbl_news WHERE status='1' AND type='1' ORDER BY news_date DESC";
    }
        $limit = 8;
        $total = $db->num_rows($db->query($sql));
        // print_r($total); die();
        $startpoint = ($page * $limit) - $limit; 
        $sql.=" LIMIT ".$startpoint.",".$limit;
        $query = $db->query($sql);
     $Records=News::find_by_sql($sql);

if(!empty($Records)) {
    $relistnews.='<div class="container">';
    $relistnews.='<div class="events-main">';
    foreach($Records as $key => $RecRow) {   
        $file_path = SITE_ROOT.'images/news/'.$RecRow->image;
        if(file_exists($file_path) and !empty($RecRow->image)) {
        $day = date("d", strtotime($RecRow->news_date));
        $month = date("M", strtotime($RecRow->news_date));
        if($key % 2 == 0){
            $relistnews.='<div class="events-list mar-bottom-30">
                    <div class="row display-flex">
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <div class="time-from text-center">
                                <span class="date">'.$day.'</span>
                                <span class="maina">'.$month.'</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="events-content">
                                <h4><a href="'.BASE_URL.'news/'.$RecRow->slug.'">'.$RecRow->title.'</a></h4>
                                <div class="meta mar-bottom-15">
                                    <span class="location">  <i class="fas fa-user"></i>'.$RecRow->author.'</span>
                                </div>
                                <p class="mar-0">'.substr($RecRow->brief, 0,80).'</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="news-image">
                                <img src="'.IMAGE_PATH.'news/'.$RecRow->image.'" alt="'.$RecRow->title.'">
                            </div>
                        </div>
                    </div>
                </div>';
        }else{
            $relistnews.='<div class="events-list mar-bottom-30">
                    <div class="row display-flex">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="news-image">
                                <img src="'.IMAGE_PATH.'news/'.$RecRow->image.'" alt="'.$RecRow->title.'">
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="events-content">
                                <h4><a href="'.BASE_URL.'news/'.$RecRow->slug.'">'.$RecRow->title.'</a></h4>
                                <div class="meta mar-bottom-15">
                                    <span class="time mar-right-10"> <i class="fas fa-clock"></i> 8:00 pm - 4:00 am</span>
                                    <span class="location"> <i class="fas fa-user"></i>'.$RecRow->author.'</span>
                                </div>
                                <p class="mar-0">'.substr($RecRow->brief, 0,80).'</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <div class="time-from text-center">
                                <span class="date">'.$day.'</span>
                                <span class="maina">'.$month.'</span>
                            </div>
                        </div>
                    </div>
                </div>';
        }
        }
  }
  $relistnews.='</div>';
   if (!empty($year)) {                                              
            $relistnews .= get_front_pagination($total, $limit, $page, BASE_URL . 'news/' . $year);;
        } else {
            $relistnews .= get_front_pagination($total, $limit, $page, BASE_URL . 'news');
        }
 $relistnews.='</div>';
 $nbread .= '<div class="breadcrumb-content">
                <h2>News</h2>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="'.BASE_URL.'home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">News</li>
                    </ul>
                </nav>
            </div>';

}

$jVars['module:news-list'] = $relistnews;
$jVars['module:news-list-bred'] = $nbread;


$renewsdetail=$newsbread= '';
if (defined('NEWS_PAGE') and isset($_REQUEST['slug'])) {
$slug = !empty($_REQUEST['slug'])? addslashes($_REQUEST['slug']) : '';
    $detspRec = News::find_by_slug($slug);
if (!empty($detspRec)) {
    $day = date("d", strtotime($detspRec->news_date));
    $month = date("M", strtotime($detspRec->news_date));
        $renewsdetail.=' <div class="col-md-9">
                    <div class="detail-content">
                        <div class="title mar-bottom-30">
                            <h2 class="news-title1">'.$detspRec->title.'</h2>
                        </div>
                        <div class="detail-image mar-bottom-15">
                            <img src="'.IMAGE_PATH.'news/'.$detspRec->image.'" alt="'.$RecRow->title.'">
                        </div>
                        <div class="detail-desc">
                            <p>'.$detspRec->content.'</p>
                        </div>
                    </div>
                </div>';
                        
             $newsbread .= '<div class="breadcrumb-content">
                <h2>' . $detspRec->title . '</h2>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="'.BASE_URL.'home">Home</a></li>
                        <li class="breadcrumb-item"><a href="'.BASE_URL.'news">News</a></li>
                        <li class="breadcrumb-item active" aria-current="page">' . $detspRec->title . '</li>
                    </ul>
                </nav>
            </div>';
}
    }
        $jVars['module:news-detail'] = $renewsdetail;
$jVars['module:news-bread'] = $newsbread;

?>