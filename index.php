<?php ob_start(); define('X',1);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$page = isset($_GET['page']) ? strip_tags(addslashes($_GET['page'])): '';
$action = isset($_GET['action']) ? strip_tags(addslashes($_GET['action'])): '';
require __DIR__.'/src/config.php';
if (($_SERVER['PHP_AUTH_USER'] != $cnf['user']) || ($_SERVER['PHP_AUTH_PW'] != $cnf['pass'])){
    header('www-Authenticate: Basic realm="Yetkisiz erisim');
    header('HTTP/1.0 401 Unauthorized');
    echo "Yetkisiz erisim..";
    exit;
}



if ($page == 'ajax'){
    require __DIR__.'/inc/ajax.php';
}

require 'inc/home.php';
ob_end_flush();
