<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/sb_site/init.php';

function dd($data, $die = true) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($die)
        die();
}