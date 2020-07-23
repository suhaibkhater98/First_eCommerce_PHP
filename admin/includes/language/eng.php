<?php

function lang( $phrase ){
    static $lang = array(
        'ADMIN_HOME' => 'Home',
        'CATEG' => 'Categories',
        'ITEMS' => 'Items',
        'MEMBERS' => 'Members',
        'STAT' => 'Statistics',
        'LOGS' => 'Logs',
        'COMM' => 'Comments'
    );
    return array_key_exists($phrase , $lang) ? $lang[$phrase] : "Key not exists";
}