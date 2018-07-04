<?php
function fl_get_list($dir)
{

    $files = array();
    $dir_list = scandir($dir);
    foreach ($dir_list as $file) {
        if ($file == '..' or $file == '.') {
            continue;
        }
        $files[] = $file;
    }
    return $files;
}
