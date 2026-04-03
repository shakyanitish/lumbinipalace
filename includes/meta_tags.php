<?php
// SEO Meta Tags And Meta Description

function className_metatags()
{
    $current_url = pathinfo($_SERVER["PHP_SELF"]);
    $fileName = $current_url['filename'];

    if ($fileName == 'inner'):
        $className = 'Article';
        return $className;
        exit;
    endif;
    if ($fileName == 'teams'):
        $className = 'Article';
        return $className;
        exit;
    endif;

    if ($fileName == 'partners'):
        $className = 'Article';
        return $className;
        exit;
    endif;

    if ($fileName == 'career-form'):
        $className = 'vacency';
        return $className;
        exit;
    endif;

    if ($fileName == 'subpkgdetail' or $fileName == 'exp_detail'):
        $className = 'Subpackage';
        return $className;
        exit;
    endif;
    if ($fileName == 'conference_detail'):
        $className = 'Subpackage';
        return $className;
        exit;
    endif;

    if ($fileName == 'service_list'):
        $className = 'Services';
        return $className;
        exit;
    endif;
    if ($fileName == 'dining_list'):
        $className = 'Dining';
        return $className;
        exit;
    endif;

    if ($fileName == 'blog_detail'):
        $className = 'Blog';
        return $className;
        exit;
    endif;
    if ($fileName == 'package_detail'):
        $className = 'Package';
        return $className;
        exit;
    endif;

    if ($fileName != 'index'):
        $className = ucfirst(strtolower($fileName));
        return $className;
        exit;
    endif;

    return '';
}

function getImageMetaTag($localPath, $webURL, $defaultURL, &$seoSources)
{
    $imageToShow = (!empty($localPath) && file_exists($localPath)) ? $webURL : $defaultURL;
    $seoSources .= '<meta property="og:image" content="' . $imageToShow . '">' . "\n";
    $seoSources .= '<meta property="twitter:image" content="' . $imageToShow . '">' . "\n";
}

function MetaTagsFor_SEO()
{

    $c_url = pathinfo($_SERVER["PHP_SELF"]);
    $chk = $c_url['filename'];
    $config = Config::find_by_id(1);
    $sitetitle = (!empty($config->meta_title) and $chk == 'index') ? $config->meta_title : $config->sitetitle;
    $keywords = $config->site_keywords;
    $description = $config->site_description;

    $addtitle = '';
    $class = className_metatags();
    // pr($class);

    $pagename = strtolower($class);
    global $db;
    $metasql = $db->query("SELECT * FROM tbl_metadata WHERE page_name='$pagename'");
    $metadata = $metasql->fetch_object();


    // Transaction start
    if (isset($_REQUEST['slug']) and !empty($_REQUEST['slug'])) {
        if ($class == 'Global') {
            $nrec = Mlink::find_by_slug(addslashes($_REQUEST['slug']));
            if (!empty($nrec)) {
                $cls = new $nrec->mod_class;
                $rec = $cls->find_by_slug(addslashes($_REQUEST['slug']));
                if (!empty($rec)) {
                    $addtitle = !empty($rec->meta_title) ? $rec->meta_title : $rec->title;
                    if (!empty($rec->meta_keywords)) {
                        $keywords = $rec->meta_keywords;
                        $description = $rec->meta_description;
                    }
                }
            }
        } else {

            $cls = new $class;
            $rec = $cls->find_by_slug(addslashes($_REQUEST['slug']));
            if (!empty($rec)) {
                $addtitle = !empty($rec->meta_title) ? $rec->meta_title : $rec->title;
                if (!empty($rec->meta_keywords)) {
                    $keywords = $rec->meta_keywords;
                    $description = $rec->meta_description;
                }
            }
        }
    } else {
        if (!empty($metadata)) {

            $addtitle = !empty($metadata->meta_title) ? $metadata->meta_title : $metadata->title;
            if (!empty($metadata->meta_keywords)) {
                $keywords = $metadata->meta_keywords;
                $description = $metadata->meta_description;
            }
        }
    }

    if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
        $cls = new $class;
        $rec = $cls->find_by_id($_REQUEST['id']);
        if ($rec) {
            $addtitle = $rec->title;
        }
    }

    $altclass = !empty($class) ? $class : '';
    $addtitle = !empty($addtitle) ? $addtitle : $altclass;
    $addsep = !empty($addtitle) ? ' - ' : '';

    $sociallinks = SocialNetworking::getSocialNetwork();
    $sameas = '';
    foreach ($sociallinks as $social) {
        if (end($sociallinks) === $social) {
            $sameas .= '"' . $social->linksrc . '"';
        } else {
            $sameas .= '"' . $social->linksrc . '",';
        }
    }

    $schema = '<script type="application/ld+json">{
		"@context": "https://schema.org/",
		"@type": "Organization",
		"name": "' . $config->sitetitle . '",
		"keywords": "' . $config->site_keywords . '",
		"description": "' . $config->site_description . '",
		"url": "' . BASE_URL . '",
		"logo": "' . IMAGE_PATH . 'preference/' . $config->logo_upload . '",
		"sameAs": [
            ' . $sameas . '
        ]';
    if (!empty($config->schema_code)) {
        $schema .= ',' . $config->schema_code;
    }
    $schema .= '}</script>';

    $seoSources = '<title>' . $addtitle . $addsep . $sitetitle . '</title>' . "\n";
    $seoSources .= '<meta charset="utf-8">' . "\n";
    $seoSources .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\n";
    $seoSources .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    $seoSources .= '<meta name="robots" content="index,follow">' . "\n";
    $seoSources .= '<meta name="Googlebot" content="index, follow"/>' . "\n";
    $seoSources .= '<meta name="distribution" content="Global">' . "\n";
    $seoSources .= '<meta name="revisit-after" content="2 Days" />' . "\n";
    $seoSources .= '<meta name="classification" content="Hotel, Hotels in Nepal" />' . "\n";
    $seoSources .= '<meta name="category" content="Hotel, Hotels in Nepal" />' . "\n";
    $seoSources .= '<meta name="language" content="en-us" />' . "\n";
    $seoSources .= '<meta name="keywords" content="' . $keywords . '">' . "\n";
    $seoSources .= '<meta name="description" content="' . $description . '">' . "\n";
    $seoSources .= '<meta name="author" content="Longtail-e-media">' . "\n\n";

    //Facebook and twitter sharing
    $tot = strlen(SITE_FOLDER) + 2;
    $data = substr($_SERVER['REQUEST_URI'], $tot);
    // Correct URL formatting to avoid double slashes
    $full_og_url = rtrim(BASE_URL, '/') . '/' . ltrim($data, '/');

    // Dynamic Facebook and Twitter sharing titles/descriptions
    $og_title = !empty($addtitle) ? $addtitle . $addsep . $sitetitle : $sitetitle;
    $og_description = !empty($description) ? $description : $config->site_description;

    $seoSources .= '<meta property="og:title" content="' . $og_title . '">' . "\n";
    $seoSources .= '<meta property="og:description" content="' . $og_description . '">' . "\n";

    if (!empty($_REQUEST['slug'])) {
        $defaultImageWeb = !empty($config->fb_upload) ? IMAGE_PATH . 'preference/' . $config->fb_upload : IMAGE_PATH . 'preference/' . $config->logo_upload;
        $imagePathLocal = null;
        $imagePathWeb = null;
        $classname = $class;

        if ($class == 'Global') {
            $nrec = Mlink::find_by_slug($_REQUEST['slug']);
            if (!empty($nrec)) {
                $cls = new $nrec->mod_class;
                $classname = $nrec->mod_class;
                $rec = $cls->find_by_slug($_REQUEST['slug']);
            }
        }
        // For non-Global classes, $rec was already fetched above at line 123.

        if (!empty($rec)) {
            switch ($classname) {
                case 'Article':
                    $unserialized = @unserialize($rec->image);
                    $img = ($unserialized !== false && is_array($unserialized)) ? $unserialized[0] : $rec->image;
                    if (!empty($img)) {
                        $imagePathLocal = SITE_ROOT . 'images/articles/' . $img;
                        $imagePathWeb = IMAGE_PATH . 'articles/' . $img;
                    }
                    break;
                case 'Package':
                    $unserialized = @unserialize($rec->banner_image);
                    $img = ($unserialized !== false && is_array($unserialized)) ? $unserialized[0] : $rec->banner_image;
                    if (!empty($img)) {
                        $imagePathLocal = SITE_ROOT . 'images/package/banner/' . $img;
                        $imagePathWeb = IMAGE_PATH . 'package/banner/' . $img;
                    }
                    break;
                case 'Subpackage':
                    $unserialized = @unserialize($rec->image);
                    $img = ($unserialized !== false && is_array($unserialized)) ? $unserialized[0] : $rec->image;
                    if (!empty($img)) {
                        $imagePathLocal = SITE_ROOT . 'images/subpackage/' . $img;
                        $imagePathWeb = IMAGE_PATH . 'subpackage/' . $img;
                    }
                    break;
                case 'Services':
                    $unserialized = @unserialize($rec->iconimage);
                    $img = ($unserialized !== false && is_array($unserialized)) ? $unserialized[0] : $rec->iconimage;
                    if (!empty($img)) {
                        $imagePathLocal = SITE_ROOT . 'images/services/' . $img;
                        $imagePathWeb = IMAGE_PATH . 'services/' . $img;
                    }
                    break;
                case 'Blog':
                    $unserialized = @unserialize($rec->image);
                    $img = ($unserialized !== false && is_array($unserialized)) ? $unserialized[0] : $rec->image;
                    if (!empty($img)) {
                        $imagePathLocal = SITE_ROOT . 'images/blog/' . $img;
                        $imagePathWeb = IMAGE_PATH . 'blog/' . $img;
                    }
                    break;
            }

            // Schema for specific record
            $schema .= '
                <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "WebPage",
                  "name": "' . $addtitle . $addsep . $sitetitle . '",
                  "url": "' . $full_og_url . '",
                  "description": "' . $og_description . '",
                  "publisher": {
                    "@type": "Organization",
                    "name": "' . $config->sitetitle . '",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "' . IMAGE_PATH . 'preference/' . $config->logo_upload . '"
                    }
                  },
                  "datePublished": "' . (isset($rec->added_date) ? $rec->added_date : '') . '",
                  "dateModified": "' . (isset($rec->modified_date) ? $rec->modified_date : '') . '",
                  "potentialAction": {
                    "@type": "ReadAction",
                    "target": "' . $full_og_url . '"
                  }
                }
                </script>
            ';

            // Output image tag
            getImageMetaTag($imagePathLocal, $imagePathWeb, $defaultImageWeb, $seoSources);
        } else {
            getImageMetaTag('', '', $defaultImageWeb, $seoSources);
        }
    } else {
        $defaultImageWeb = !empty($config->fb_upload) ? IMAGE_PATH . 'preference/' . $config->fb_upload : IMAGE_PATH . 'preference/' . $config->logo_upload;
        $schema .= '
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "WebPage",
              "name": "' . $addtitle . $addsep . $sitetitle . '",
              "url": "' . $full_og_url . '",
              "description": "' . $og_description . '",
              "publisher": {
                "@type": "Organization",
                "name": "' . $config->sitetitle . '",
                "logo": {
                  "@type": "ImageObject",
                  "url": "' . IMAGE_PATH . 'preference/' . $config->logo_upload . '"
                }
              },
              "potentialAction": {
                "@type": "ReadAction",
                "target": "' . $full_og_url . '"
              }
            }
            </script>
        ';

        // Default image
        getImageMetaTag('', '', $defaultImageWeb, $seoSources);
    }

    $seoSources .= '<meta property="og:url" content="' . $full_og_url . '">' . "\n";
    $seoSources .= '<meta property="og:type" content="website">' . "\n";
    $seoSources .= '<meta property="twitter:card" content="summary_large_image">' . "\n\n";
    $seoSources .= '<link rel="canonical" href="' . curPageURL() . '" />' . "\n";

    $seoSources .= '<base url="' . BASE_URL . '"/>' . "\n";
    $seoSources .= $config->google_anlytics . "\n";
    $seoSources .= $schema . "\n";
    $seoSources .= $config->headers . "\n";

    return $seoSources;
}
