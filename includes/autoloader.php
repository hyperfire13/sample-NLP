<?php
// original
//require dirname(__DIR__) . '/config.php';
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
spl_autoload_register('classAutoLoader');

function classAutoLoader($className)
{
    $paths = array('classes/');
    $extension = ".php";
    $className = (str_replace('\\', DIRECTORY_SEPARATOR , $className));
    $fullPath = $_SERVER['DOCUMENT_ROOT'] .'/' . $className . $extension;
    if (!file_exists($fullPath)) {
        return false;
    }
    require $fullPath;
}
?>