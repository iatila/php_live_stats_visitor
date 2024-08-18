<?php if (!defined('X')) die('Deny Access');

$file = __DIR__.'/ajax/'.$action.'.php';
if (file_exists($file)){
    require $file;
}else{
    die('Ajax action not found');
}
exit;