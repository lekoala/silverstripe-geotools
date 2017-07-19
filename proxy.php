<?php
// A simple map proxy
// Usage: yoursite.com/{provider}/{zoom}/{x}/{y}.png

$baseDir = basename(__DIR__);
$cacheDir = dirname(__DIR__) . '/assets/_tiles';

$ttl = 86400; //cache timeout in seconds

$providers = [];
$providers['osm_mapnik'] = [
    'url' => "http://{s}.tile.osm.org/{z}/{x}/{y}",
    'subdomains' => "abc",
    'attributions' => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
];
$providers['osm_bw'] = [
    'url' => "http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png",
    'subdomains' => "abc",
    'attributions' => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
];
$providers['osm_fr'] = [
    'url' => "http://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png",
    'subdomains' => "abc",
    'attributions' => '&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
];
$providers['hydda_full'] = [
    'url' => "https://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png",
    'subdomains' => "abc",
    'attributions' => 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
];
$providers['esri_worldstreetmap'] = [
    'url' => "https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
    'attributions' => 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
];

$requestURI = $_SERVER['REQUEST_URI'];
if (substr($requestURI, 0, 1) == "/") {
    $requestURI = substr($requestURI, 1);
}
$requestURI = str_replace($baseDir . "/", "", $requestURI);
list($maptype, $zoom, $x, $y) = explode("/", $requestURI);

if ($maptype == "" || $zoom == "" || $x == "" || $y == "") {
    var_dump($maptype);
    var_dump($zoom);
    var_dump($x);
    var_dump($y);
    die("Some arguments are missing. Please follow this url format: /{maptype}/{zoom}/{x}/{y}.png");
}
if (!array_key_exists($maptype, $providers)) {
    var_dump($maptype);
    die("Invalid map type");
}

$tileUrl = $providers[$maptype]['url'];
$tileHostSubdomains = $providers[$maptype]['subdomains'];
$tileUrl = str_replace("{z}", $zoom, $tileUrl);
$tileUrl = str_replace("{x}", $x, $tileUrl);
$tileUrl = str_replace("{y}", $y, $tileUrl);

if ($tileHostSubdomains) {
    $tileHostSubdomain = $tileHostSubdomains[rand(0, strlen($tileHostSubdomains) - 1)];
    $tileUrl = str_replace("{s}", $tileHostSubdomain, $tileUrl);
}

$cacheDir = $cacheDir . "/" . $maptype;
$cacheTileFile = $cacheDir . "/" . $zoom . "/" . $x . "/" . $y;

$img = null;
$tries = 0;

if (!is_file($cacheTileFile) || filemtime($cacheTileFile) < time() - (86400 * 30)) {
    if (!is_dir(dirname($cacheTileFile))) {
        mkdir(dirname($cacheTileFile), 0755, true);
    }

    do {
        $img = file_get_contents($tileUrl);

        if ($img) {
            file_put_contents($cacheTileFile, $img);
        }

        if ($tries++ > 5) {
            exit(); // Give up after five tries
        }
    } while (!$img);  // If download has returned a broken file, then try downloading again
} else {
    $img = file_get_contents($cacheTileFile);
}

$exp_gmt = gmdate("D, d M Y H:i:s", time() + $ttl * 60) . " GMT";
$mod_gmt = gmdate("D, d M Y H:i:s", filemtime($cacheTileFile)) . " GMT";
header("Expires: " . $exp_gmt);
header("Last-Modified: " . $mod_gmt);
header("Cache-Control: public, max-age=" . $ttl * 60);
// for MSIE 5
header("Cache-Control: pre-check=" . $ttl * 60, FALSE);
header('Content-Type: image/png');
echo $img;
