<?php
$bl =  '';
$singleblog = '';
$singleblog_more = '';

if (defined('BLOG_PAGE')) {
    $record = Blog::get_allblog();
    $linkTarget = '';
    $pagelink = '';
    if (!empty($record)) {

        $blogItems = '';
        foreach ($record as $homebl) {

            if (!empty($homebl->linksrc)) {
                $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL . $homebl->linksrc;
            } else {
                $linksrc = BASE_URL . 'blog/' . $homebl->slug;
            }

            $blogDate = date('F d, Y', strtotime($homebl->blog_date));
            $imgsrc = IMAGE_PATH . 'blog/' . $homebl->image;

            $blogItems .= '
            <div class="col-lg-4 col-md-6 wow fadeInRight">
                <div class="article-list">
                    <div class="at-thumbnail">
                        <a href="' . $linksrc . '">
                            <img src="' . $imgsrc . '" alt="' . $homebl->title . '" />
                        </a>
                        <span class="blog-tag"> ' . $homebl->category . ' </span>
                    </div>
                    <div class="article-content">
                        <div class="artl-bottom">
                            <ul class="d-flex justify-content-start">
                                <li>' . $blogDate . '</li>
                            </ul>
                        </div>
                        <div class="artl-detail">
                            <a href="' . $linksrc . '"><h4>' . $homebl->title . '</h4></a>
                            <p>' . $homebl->brief . '</p>
                        </div>
                    </div>
                </div>
            </div>';
        }

        $bl = '
        <section class="home-3 blog-article bg-white">
            <div class="container">
                <div class="blog-wrap">
                    <div class="row">
                        ' . $blogItems . '
                    </div>
                </div>
            </div>
        </section>';
    } else {
        redirect_to(BASE_URL);
    }
}
$jVars['module:bloglist'] = $bl;







// New Home Page Blog List for Ideal Model
$homelatestblog = '';
if (defined('HOME_PAGE') || defined('BLOG_DETAIL_PAGE')) {
    $latestBlogs = Blog::get_latestblog_by(3);
    if (!empty($latestBlogs)) {
        $blogItems = '';
        foreach ($latestBlogs as $blog) {
            $linksrc = BASE_URL . 'blog/' . $blog->slug;
            $blogDate = date('F d, Y', strtotime($blog->blog_date));
            $imgsrc = IMAGE_PATH . 'blog/' . $blog->image;

            $blogItems .= '
            <div class="col-lg-4 col-md-6">
                <div class="m-blog-card border-0">
                    <div class="ratio ratio-1x1 mb-4">
                        <img src="' . $imgsrc . '" alt="' . $blog->title . '" class="object-fit-cover">
                    </div>
                    <div class="m-blog-body">
                        <h3 class="m-blog-title mb-3">' . $blog->title . '</h3>
                        <div class="m-red-line mb-3"></div>
                        <p class="m-blog-excerpt text-muted small mb-4">' . $blog->brief . '</p>
                        <a href="' . $linksrc . '" class="m-blog-link">Explore <i class="fa-solid fa-arrow-right ms-2 small"></i></a>
                    </div>
                </div>
            </div>';
        }

        $homelatestblog = '
        <section class="m-blogs py-5">
            <div class="container">
                <div class="row g-4">
                    ' . $blogItems . '
                </div>
            </div>
        </section>';
    }
}
$jVars['module:home-blog-list'] = $homelatestblog;




// Blog Detail Page

$blog_detail_header = $blog_detail_content = '';
if (defined("BLOG_DETAIL_PAGE")) {
    $slug = !empty($_REQUEST['slug']) ? $_REQUEST['slug'] : '';
    $Blogs = Blog::find_by_slug($slug);

    if (!empty($Blogs)) {
        $blogDate = date('F d, Y', strtotime($Blogs->blog_date));
        $imgsrc = IMAGE_PATH . 'blog/' . $Blogs->image;
        $fullUrl = BASE_URL . 'blog/' . $Blogs->slug;
        $encodedUrl = rawurlencode($fullUrl);
        $encodedTitle = rawurlencode($Blogs->title);

        // Detail Header Section
        $blog_detail_header = '
        <section class="m-blog-hero" id="articleHero">
            <img src="' . $imgsrc . '" alt="' . $Blogs->title . '" class="m-blog-hero-img">
            <div class="m-blog-hero-overlay">
                <div class="container">
                    <div class="m-blog-hero-content">
                        <span class="m-blog-category">' . $Blogs->category. '</span>
                        <h1 class="m-blog-hero-title">' . $Blogs->title . '</h1>
                        <div class="m-blog-meta">
                            <span><i class="fa-light fa-calendar-days me-2"></i> ' . $blogDate . '</span>
                            <span><i class="fa-light fa-clock me-2"></i>' . $Blogs->category . '</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>';

        // Recent Posts (Sidebar)
        $recent_posts_html = '';
        $recentBlogs = Blog::get_latestblog_by(6); // Get 6 to skip current
        if (!empty($recentBlogs)) {
            foreach ($recentBlogs as $rec) {
                if ($rec->id != $Blogs->id) {
                    $recLink = BASE_URL . 'blog/' . $rec->slug;
                    $recDate = date('F d, Y', strtotime($rec->blog_date));
                    $recImg = IMAGE_PATH . 'blog/' . $rec->image;
                    
                    $recent_posts_html .= '
                    <a href="' . $recLink . '" class="m-blog-related-post">
                        <img src="' . $recImg . '" alt="' . $rec->title . '">
                        <div class="m-blog-related-text">
                            <h5>' . $rec->title . '</h5>
                            <span>' . (!empty($rec->category) ? $rec->category : "Blog") . '</span>
                        </div>
                    </a>';
                }
            }
        }

        // Detail Content Section
        $blog_detail_content = '
        <section class="m-blog-section py-5">
            <div class="container">
                <div class="row g-5">
                    <!-- Main Article Content -->
                    <div class="col-lg-8">
                        <article class="m-blog-content">
                            ' . $Blogs->content . '
                            
                            <!-- Share Bar -->
                            <div class="m-blog-share-bar mt-5 pt-4">
                                <span class="m-blog-share-label">Share this article</span>
                                <div class="m-blog-share-icons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl . '" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://twitter.com/intent/tweet?text=' . $encodedTitle . '&url=' . $encodedUrl . '" target="_blank" rel="noopener noreferrer" aria-label="Share on Twitter"><i class="fab fa-x-twitter"></i></a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedUrl . '" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="javascript:void(0);" onclick="copyToClipboard(\'' . $fullUrl . '\')" aria-label="Copy link"><i class="fa-light fa-link"></i></a>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <aside class="m-blog-sidebar">
                            <!-- Quick Info -->
                            <div class="m-blog-sidebar-card">
                                <h4 class="m-blog-sidebar-title">Quick Info</h4>
                                <ul class="m-blog-sidebar-list">
                                    <li><i class="fa-light fa-calendar-days me-2"></i> Published: ' . $blogDate . '</li>
                                    <li><i class="fa-light fa-clock me-2"></i> '. $Blogs->category . '</li>
                                </ul>
                            </div>

                            <!-- Related Posts -->
                            <div class="m-blog-sidebar-card">
                                <h4 class="m-blog-sidebar-title">Related Posts</h4>
                                ' . $recent_posts_html . '
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>';
    } else {
        redirect_to(BASE_URL);
    }
}

$jVars['module:blog-detail-header'] = $blog_detail_header;
$jVars['module:blog-detail-content'] = $blog_detail_content . '
<script>
function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            alert("Link copied to clipboard!");
        }).catch(function(err) {
            fallbackCopyToClipboard(text, err);
        });
        return;
    }

    fallbackCopyToClipboard(text);
}

function fallbackCopyToClipboard(text, originalError) {
    try {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-9999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert("Link copied to clipboard!");
    } catch (err) {
        if (originalError) {
            console.error("Could not copy text: ", originalError);
        }
        console.error("Could not copy text: ", err);
        alert("Unable to copy link automatically. Please copy it manually: " + text);
    }
}
</script>';
