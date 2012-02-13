<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$dir = $env['hdfsToHiveDir'];
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            echo "filename: $file : filetype: " . filetype($dir . $file) . "<br>\n";
        }
        closedir($dh);
    }
}

?>