<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
header('Content-type: application/xml; charset=utf-8');
require_once("includes/initialize.php");

$xml = '';

/**
 *  different formats to get lastmod:
 */
// date('c')
// date('Y-m-d\TH:i:sP')

$xml .= '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="sitemap.xsl"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xmlns:xhtml="http://www.w3.org/1999/xhtml"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
    <loc>' . BASE_URL . '</loc>
    <lastmod>' . date('c') . '</lastmod>
    <priority>1</priority>
</url>
';

$staticblocks = array('home', 'contact-us', 'services', 'gallery-list');

foreach ($staticblocks as $page) {
    $xml .= '
        <url>
            <loc>' . BASE_URL . $page . '</loc>
            <lastmod>' . date('c') . '</lastmod>
            <priority>0.9</priority>
        </url>
    ';
}

$pages = Article::find_all_active();
foreach ($pages as $page) {
    $xml .= '
        <url>
            <loc>' . BASE_URL . '' . $page->slug . '</loc>
            <lastmod>' . date('c', strtotime($page->modified_date)) . '</lastmod>
            <priority>0.75</priority>
        </url>
    ';
}

// $offers=Offers::find_allactive_offers();
// foreach ($offers as $offer) {
//     $xml.='<url>
//         <loc>'.BASE_URL.'offer/'.$offer->slug.'</loc>
//         <lastmod>'.date('c', strtotime($offer->added_date)).'</lastmod>
//         <priority>0.70</priority>
//        </url>
//        ';
//     }

$packages = Package::getPackage();
foreach ($packages as $package) {
    $chkChild = Subpackage::getTotalSub($package->id);
    $pakgLink = !empty($chkChild) ? '' . $package->slug . '' : '' . $package->slug . '';
    $xml .= '
        <url>
            <loc>' . BASE_URL . '' . $pakgLink . '</loc>
            <lastmod>' . date('c', strtotime($package->modified_date)) . '</lastmod>
            <priority>0.8</priority>
        </url>
    ';
}

$spackages = Subpackage::getallPackage();
foreach ($spackages as $spackage) {
    $chkPar = Package::find_by_id($spackage->type);
//    $pakgLink = !empty($chkPar) ? '' . $spackage->slug . '' . $spackage->slug : '';
    $xml .= '
        <url>
            <loc>' . BASE_URL . '' . $spackage->slug . '</loc>
            <lastmod>' . date('c', strtotime($spackage->modified_date)) . '</lastmod>
            <priority>0.85</priority>
        </url>
    ';
}


$xml .= '</urlset>';
$myfile = fopen("sitemap.xml", "w") or die("Unable to open file!");
fwrite($myfile, $xml);
fclose($myfile);
echo $xml;

?>