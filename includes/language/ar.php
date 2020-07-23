<?php

function lang( $phrase ){
    static $lang = array(
        'MESSAGE' => 'مرحبا',
        'ADMIN' => 'مدير'
    );
    return array_key_exists($phrase , $lang) ? $lang[$phrase] : "Key not exists";
}