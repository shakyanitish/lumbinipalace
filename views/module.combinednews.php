<?php

/**
 * Display Blogs in Homepage
 **/
$home_blog = '';
if (defined("HOME_PAGE")) {
    $blogs = CombinedNews::find_all(3);
    foreach ($blogs as $blog) {
        $img = BASE_URL . "template/web/images/image_2.jpg";
        if (!empty($blog->home_image)) {
            $file_path = SITE_ROOT . "images/combinednews/home/" . $blog->home_image;
            if (file_exists($file_path)) {
                $img = IMAGE_PATH . "combinednews/home/" . $blog->home_image;
            }
        }
        $home_blog .= '
            <div class="col-md-4 d-flex ftco-animate">
                <div class="blog-entry align-self-stretch">
                    <a href="' . BASE_URL . 'blog/' . $blog->slug . '" class="block-20" style="background-image: url(' . $img . ');">
                    </a>
                    <div class="text mt-3 text-center">
                        <div class="meta mb-2">
                            <div><a href="#">' . date('F d, Y', strtotime($blog->event_stdate)) . '</a></div>
                        </div>
                        <h3 class="heading"><a href="' . BASE_URL . 'blog/' . $blog->slug . '">' . $blog->title . '</a></h3>
                        <p><a href="' . BASE_URL . 'blog/' . $blog->slug . '" class="btn-custom">Read more</a></p>
                    </div>
                </div>
            </div>
        ';
    }
}
$jVars["module:combinednews:home-blog"] = $home_blog;


/**
 * Blog listing page
 **/

$blog_list = $blog_list_breadcrumb = '';
if (defined("BLOG_PAGE")) {

    $blog_list_breadcrumb .= '
        <div class="hero-wrap" style="background-image: url(' . BASE_URL . 'template/web/images/bg_1.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-end justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <p class="breadcrumbs mb-2">
                                <span class="mr-2"><a href="' . BASE_URL . 'home">Home</a></span> 
                                <span>Blog</span>
                            </p>
                            <h1 class="mb-4 bread">Blog</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';

    $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"])) ? $_REQUEST["pageno"] : 1;
    $sql = "SELECT * FROM tbl_conbined_news WHERE status='1' ORDER BY event_stdate DESC";

    $limit = 300;
    $total = $db->num_rows($db->query($sql));
    $startpoint = ($page * $limit) - $limit;
    $sql .= " LIMIT " . $startpoint . "," . $limit;

    $blogs = CombinedNews::find_by_sql($sql);
    if ($blogs) {
        foreach ($blogs as $blog) {
            $img = BASE_URL . "template/web/images/image_2.jpg";
            if (!empty($blog->home_image)) {
                $file_path = SITE_ROOT . "images/combinednews/home/" . $blog->home_image;
                if (file_exists($file_path)) {
                    $img = IMAGE_PATH . "combinednews/home/" . $blog->home_image;
                }
            }
            $blog_list .= '
                <div class="col-md-4 d-flex ftco-animate">
                    <div class="blog-entry align-self-stretch">
                        <a href="' . BASE_URL . 'blog/' . $blog->slug . '" class="block-20" style="background-image: url(' . $img . ');"></a>
                        <div class="text mt-3 text-center">
                            <div class="meta mb-2">
                                <div><a href="#">' . date('F d, Y', strtotime($blog->event_stdate)) . '</a></div>
                            </div>
                            <h3 class="heading"><a href="' . BASE_URL . 'blog/' . $blog->slug . '">' . $blog->title . '</a></h3>
                            <p><a href="' . BASE_URL . 'blog/' . $blog->slug . '" class="btn-custom">Read more</a></p>
                        </div>
                    </div>
                </div>
            ';
        }
    }
}

$jVars["module:combinednews:blog-list-breadcrumb"] = $blog_list_breadcrumb;
$jVars["module:combinednews:blog-list-list"] = $blog_list;


/**
 * Blog Detail page
 **/

$blog_detail = $blog_breadcrumb = $recent_blogs = $gallery_section = '';
if (defined("BLOG_DETAIL_PAGE_OLD")) {
    $slug = (isset($_REQUEST["slug"]) and !empty($_REQUEST["slug"])) ? $_REQUEST["slug"] : '';
    $blogRec = CombinedNews::find_by_slug($slug);
    if (!empty($blogRec)) {
        $banner_img = BASE_URL . "template/web/images/bg_1.jpg";
        if (!empty($blogRec->banner_image)) {
            $file_path = SITE_ROOT . "images/combinednews/banner/" . $blogRec->banner_image;
            if (file_exists($file_path)) {
                $banner_img = IMAGE_PATH . "combinednews/banner/" . $blogRec->banner_image;
            }
        }
        $blog_breadcrumb .= '
            <div class="hero-wrap" style="background-image: url(' . $banner_img . ');">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row no-gutters slider-text d-flex align-itemd-end justify-content-center">
                        <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                            <div class="text">
                                <p class="breadcrumbs mb-2">
                                    <span class="mr-2"><a href="' . BASE_URL . 'home">Home</a></span> 
                                    <span><a href="' . BASE_URL . 'blog">Blog</span></a></p>
                                <h1 class="mb-4 bread">' . $blogRec->title . '</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';

        $blog_detail .= $blogRec->content;

        $recentBlogs = CombinedNews::getRelatednews_by($blogRec->id, 5);
        if (!empty($recentBlogs)) {
            foreach ($recentBlogs as $recentBlog) {
                $img = BASE_URL . "template/web/images/image_2.jpg";
                if (!empty($recentBlog->home_image)) {
                    $file_path = SITE_ROOT . "images/combinednews/home/" . $recentBlog->home_image;
                    if (file_exists($file_path)) {
                        $img = IMAGE_PATH . "combinednews/home/" . $recentBlog->home_image;
                    }
                }
                $recent_blogs .= '
                    <div class="block-21 mb-4 d-flex">
                        <a class="blog-img mr-4" style="background-image: url(' . $img . ');"></a>
                        <div class="text">
                            <h3 class="heading"><a href="' . BASE_URL . 'blog/' . $recentBlog->slug . '">' . $recentBlog->title . '</a></h3>
                            <div class="meta">
                                <div>
                                    <a href="' . BASE_URL . 'blog/' . $recentBlog->slug . '">
                                        <span class="icon-calendar"></span> '.date('F, d Y', strtotime($recentBlog->event_stdate)).'
                                    </a>
                                </div>
                                <!--<div><a href="' . BASE_URL . 'blog/' . $recentBlog->slug . '"><span class="icon-person"></span> Admin</a></div>-->
                            </div>
                        </div>
                    </div>
                    ';
            }
        }

    }
    else {
        redirect_to(BASE_URL . 'blog');
    }
}

$jVars["module:combinednews:blog-breadcrumb"] = $blog_breadcrumb;
$jVars["module:combinednews:blog-details"] = $blog_detail;
$jVars["module:combinednews:blog-recent-blogs"] = $recent_blogs;


?>