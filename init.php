<?php

include "admin/config.php";

$sessionUser = '';
if(isset($_SESSION['name'])){
    $sessionUser = $_SESSION['name'];
} 

$template = "includes/templates/";
$lang = "includes/language/";
$func = "includes/functions/";
$css = "layout/css/";
$js = "layout/js/";


include $func . "functions.php";
include $lang . "eng.php";
include $template . "header.php";
//include $template . 'navbar.php';



