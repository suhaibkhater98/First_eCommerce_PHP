<?php

/*
    print tilte for page if exsts or print the defualt value
*/
function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    } else {
        echo "Defualt";
    }
}

function redirectHome($errorMsg, $url = null , $seconds = 3){
    if($url === null){
        $url = "index.php";
    } else {
        if( isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
        $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = 'index.php';
        }
    }
    echo $errorMsg;
    echo '<div class="alert alert-info"> you will redirect after '.$seconds.' Seconds</div>';
    
    header("refresh:$seconds;url=$url");
    exit();
}

function checkItem($select , $from , $value){
    global $conn;
    $statment = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statment->execute(array($value));
    $count = $statment->rowCount();
    
    return $count;
}


function countItem($select , $table){
    global $conn;
    $stmt2 = $conn->prepare("SELECT COUNT($select) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

/*
    Get latest items function
*/

function getLatest($select , $table , $order , $limit = 5 ){
    global $conn;   
    $getStmt = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}

function getRecords($fields  , $table , $where=null , $order = null){
    global $conn;
    $sql = $order == null ? " " : "ORDER BY $order DESC";
    $getRecord = $conn->prepare("SELECT $fields FROM $table $where $sql");
    $getRecord->execute();
    $record = $getRecord->fetchAll();
    return $record;

}
