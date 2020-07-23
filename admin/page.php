<?php

$do = isset($_GET['action']) ? $do = $_GET['action'] : $do = "Manage";

if($do == 'Manage'){
    echo "Welcom manage";

} elseif ($do == 'Add'){

} elseif ($do == 'Insert'){

} else {

}