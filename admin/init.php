<?php

include "config.php";

$template = "includes/templates/";
$lang = "includes/language/";
$func = "includes/functions/";
$css = "layout/css/";
$js = "layout/js/";


include $func . "functions.php";
include $lang . "eng.php";
include $template . "header.php";

if(!isset($noNavbar)){
    include $template . 'navbar.php';
}


