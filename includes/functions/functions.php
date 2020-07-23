<?php

function getRecords($fields  , $table , $where=null , $order = null){
    global $conn;
    $sql = $order == null ? " " : "ORDER BY $order DESC";
    $getRecord = $conn->prepare("SELECT $fields FROM $table $where $sql");
    $getRecord->execute();
    $record = $getRecord->fetchAll();
    return $record;

}


function checkUserStatus($username){
    global $conn;
    $stmt = $conn->prepare("SELECT Username,Regstatus
    FROM users WHERE Username = ? AND Regstatus = 0");

    $stmt->execute(array($username));
    return $stmt->rowCount();
}
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


