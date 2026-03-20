<?php
$blogDetailHtml = '';

if (defined('BLOG_DETAIL_PAGE')) {

    $slug = isset($_GET['slug']) ? $_GET['slug'] : '';
    $blog = Blog::find_by_slug($slug);

    if ($blog) {

        $image = IMAGE_PATH . "blog/" . $blog->image;

        $blogDetailHtml .= '

        <div class="blog-single py-120">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="blog-single-wrapper">
                            <div class="blog-single-content">
                                <div class="blog-thumb-img">
                                    <img src="'.$image.'" alt="'.$blog->title.'">
                                </div>
                                <div class="blog-info">
                                    <div class="blog-details">
                                        <h3 class="blog-details-title mb-20">It is a long established fact that a reader</h3>
                                         '.$blog->content.'

                                        <div class="row">
                                            <div class="col-md-6 mb-20">
                                                <img src="'.$image.'" alt="'.$blog->title.'">
                                            </div>
                                            <div class="col-md-6 mb-20">
                                                <img src="'.$image.'" alt="'.$blog->title.'">
                                            </div>
                                        </div>
                                        <p class="mb-20">
										'.$blog->content.'
										</p>
                                    </div>
                                </div>
                                <div class="blog-comments">
                                    <div class="blog-comments-form">
                                        <h3>Leave A Comment</h3>
                                        <form action="#">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="far fa-user-tie"></i></span>
                                                        <input type="text" class="form-control" name="name"
                                                            placeholder="Your Name*" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                                        <input type="email" class="form-control" name="email"
                                                            placeholder="Your Email*" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="input-group textarea">
                                                        <span class="input-group-text"><i class="far fa-pen"></i></span>
                                                        <textarea name="message" cols="30" rows="5" class="form-control"
                                                            placeholder="Your Comment*"></textarea>
                                                    </div>
                                                    <button type="submit" class="theme-btn">Submit <i class="far fa-paper-plane"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <aside class="sidebar">
                            <!-- search-->
                            <div class="widget search">
                                <h5 class="widget-title">Find What You Need</h5>
                                <form class="search-form">
                                    <input type="text" class="form-control" placeholder="Search Blog...">
                                    <button type="submit"><i class="far fa-search"></i></button>
                                </form>
                            </div>
                            <!-- category -->
                            <div class="widget category">
                                <h5 class="widget-title">Category</h5>
                                <div class="category-list">
                                    <a href="#"><i class="far fa-arrow-right"></i>Solo & Team Fishing<span>(10)</span></a>
                                    <a href="#"><i class="far fa-arrow-right"></i>Fishing Tour<span>(15)</span></a>
                                    <a href="#"><i class="far fa-arrow-right"></i>Fishing Competitions<span>(20)</span></a>
                                    <a href="#"><i class="far fa-arrow-right"></i>Fishing Guidence<span>(30)</span></a>
                                    <a href="#"><i class="far fa-arrow-right"></i>Fishing Equipments<span>(25)</span></a>
                                </div>
                            </div>
                            <!-- recent post -->
                            <jcms:module:blog-recent-posts/>
                            <!-- social share -->
                            <div class="widget social-share">
                                <h5 class="widget-title">Share</h5>
                                <div class="social-share-link">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>































            <div class="blog-single-title">
                <h2>'.$blog->title.'</h2>
                <p><strong>Author:</strong> '.$blog->author.'</p>
                <p><strong>Date:</strong> '.date("d M Y", strtotime($blog->blog_date)).'</p>
            </div>

            <div class="blog-single-image">
                <img src="'.$image.'" alt="'.$blog->title.'">
            </div>

            <div class="blog-single-content">
                '.$blog->content.'
            </div>

        ';

    } else {
        redirect_to(BASE_URL . "blog");
    }
}

$jVars["module:blogdetail"] = $blogDetailHtml;
