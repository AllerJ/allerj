<?php
header('Content-type: application/javascript');

$doc_root = $_SERVER["DOCUMENT_ROOT"];
$url = $_SERVER["SCRIPT_NAME"];
$break = Explode('/', $url);
$file = $break[count($break) - 1];
$cachefile = substr_replace($file ,"",-4).'.js';

$theme_mod = isset($_ENV['THEME_MODE']) ? $_ENV['THEME_MODE'] : 'dev';
$cachetime = $theme_mod === 'dev' ? 0 : 18000;


if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
    echo $doc_root." / Cached copy, generated ".date('H:i', filemtime($cachefile))."\n";
    readfile($cachefile);
    exit;
}

function compress( $minify ) {
  $minified = $minify;
  $minified = str_replace("\n", "", $minified);
  return $minified;
}

ob_start("compress");


// GETWID Includes
// include($doc_root.'/wp-content/plugins/getwid/assets/js/frontend.blocks.js');

include('theme.js');
include('bootstrap.js');
//include('line.min.js');

$output = ob_get_contents();
$cached = fopen($cachefile, 'w');
fwrite($cached, compress($output));
fclose($cached);

ob_end_flush(); // Send the output to the browser