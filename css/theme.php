<?php
header('Content-type: text/css');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');
$doc_root = $_SERVER["DOCUMENT_ROOT"];
$url = $_SERVER["SCRIPT_NAME"];
$upOne = dirname(__DIR__, 1);
$break = Explode('/', $url);
$file = $break[count($break) - 1];
$cachefile = substr_replace($file ,"",-4).'.css';

$theme_mod = isset($_ENV['THEME_MODE']) ? $_ENV['THEME_MODE'] : 'dev';
$cachetime = $theme_mod === 'dev' ? 0 : 18000;

if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
    echo "/* Cached copy, generated ".date('H:i', filemtime($cachefile))."*/ \n";
    readfile($cachefile);
    exit;
}


function compress( $minify ) {
    $minified = $minify;
    $minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minified);
    $minified = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minified);
    // $minified = str_replace("\n", "", $minified);
    // $minified = str_replace("  ", " ", $minified);
    // $minified = str_replace("  ", " ", $minified);
    // $minified = str_replace(" {", "{", $minified);
    // $minified = str_replace("{ ", "{", $minified);
    // $minified = str_replace(" }", "}", $minified);
    // $minified = str_replace("} ", "}", $minified);
    // $minified = str_replace(", ", ",", $minified);
    // $minified = str_replace("; ", ";", $minified);
    // $minified = str_replace(": ", ":", $minified);
    return $minified;
}


ob_start("compress");

// GETWID Includes
include($doc_root.'/wp-content/plugins/getwid/vendors/fontawesome-free/css/all.min.css');
include($doc_root.'/wp-content/plugins/getwid/assets/css/blocks.style.css');
include($doc_root.'/wp-content/plugins/getwid/vendors/slick/slick/slick.min.css');
include($doc_root.'/wp-content/plugins/getwid/vendors/slick/slick/slick-theme.min.css');

/* css files for combining */
include($doc_root.'/wp-includes/css/dist/block-library/style.min.css');
include('bootstrap.css');
include($upOne.'/blocks/aller-block/style.css');
include('accessibility.css');
include('style.css');
include('allerj.css');


$output = ob_get_contents();
$cached = fopen($cachefile, 'w');
fwrite($cached, compress($output));
fclose($cached);


ob_end_flush(); // Send the output to the browser
