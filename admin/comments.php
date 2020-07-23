<?php

session_start();
$pageTitle = "Comments";
if(!isset($_SESSION['user'])){
    header ('Location: index.php');
    exit();
}
include "init.php";

$do = isset($_GET['action']) ? $do = $_GET['action'] : $do = "Manage";

if($do == 'Manage'){

    $stmt = $conn->prepare("SELECT comments.* , users.Username , items.Name FROM comments
    INNER JOIN users ON users.UserID = comments.UserID 
    INNER JOIN items ON items.ItemID = comments.ItemID");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    ?>
    <h1 class="text-center">Manage Comments</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>User Name</td>
                        <td>Item Name</td>
                        <td>published Date</td>
                        <td>Control</td>
                    </tr>                    
                </thead>
                <tbody>
                    
                        <?php
                        foreach($rows as $row):
                        ?>
                    <tr>
                        <td><?=$row['ID']?></td>
                        <td><?=$row['Comment']?></td>
                        <td><?=$row['Username']?></td>
                        <td><?=$row['Name']?></td>
                        <td><?=$row['AddedDate']?></td>
                        <td>
                            <a href="comments.php?action=Edit&comid=<?=$row['ID']?>" class="btn btn-success">
                            <i class="fa fa-edit"></i> Edit</a>
                            <a href="comments.php?action=Delete&comid=<?=$row['ID']?>" class="btn btn-danger confirm">
                            <i class="fa fa-close"></i> Delete</a>
                            <?php if($row['Status'] == 0){?>
                            <a href="comments.php?action=Approve&comid=<?=$row['ID']?>" class="btn btn-info">
                            <i class="fa fa-check"></i> Approve</a>
                            <?php }?>
                        </td> 
                    </tr>
                        <?php endforeach;?>
                   
                </tbody>
            </table>
        </div>
    </div>
<?php

}elseif ($do == 'Edit'){
    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
    $stmt = $conn->prepare("SELECT * FROM comments WHERE ID = ? LIMIT 1");

    $stmt->execute(array($comid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if($count > 0){
?>
<h1 class="text-center">Edit Comment</h1>
<div class="container">
    <form class="form-horizontal" action="?action=Update" method="POST">
        <input type="hidden" name="comid" value="<?=$row['ID']?>">
        <!-- Start Username -->
        <div class="form-group">
            <label class="col-sm-2 control-label" for="username">Comment</label>
            <div class="col-sm-10">
                <textarea class="form-control"name="comment"><?=$row['Comment']?></textarea>
            </div>
        </div>
        <!-- End Username -->
        
        <!-- Start Button -->
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input class="btn btn-primary" type="submit" value="Save">
            </div>
        </div>
        <!-- End Button -->
    </form>
</div>    
<?php
    }
    else {
        echo '<div class="container">';
        $msg =  "<h3 class='alert alert-danger'>There is No such ID.</h3>";
        redirectHome($msg);
        echo '</div>';
    }

}elseif ($do == 'Update'){
    echo "<h1 class='text-center'>Update Page</h1>";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['comid'];
        $comment = $_POST['comment'];

        echo '<div class="container">';
        if(!empty($comment)){
            $stmt = $conn->prepare("UPDATE comments SET Comment = ? WHERE ID = ?");
            $stmt->execute(array($comment , $id));

            $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Updated .</h4>";
            redirectHome($msg , "back");
        } else{
            
            $msg = "<h4 class='text-center alert alert-danger'>Please Inter Comment To apdate</h4>";    
            redirectHome($msg , 'back');
            
        }echo "</div>";

    } else {
        echo '<div class="container">';
        $msg = '<h2 class="alert alert-danger">You can not access Directly</h2>';
        redirectHome($msg);
        echo '</div>';
    }

}elseif ($do == 'Delete'){

    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

    $count = checkItem("ID" , "comments" , $comid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("DELETE FROM comments WHERE ID = :zid");
        $stmt->bindParam(":zid" , $comid);
        $stmt->execute();
        $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Deleted .</h4>";
        redirectHome($msg , "back");

    } else {
        echo '<div class="container">';
        $msg = '<h1 class="alert alert-danger">This Comments Does Not Exist.</h1>';
        redirectHome($msg);
        echo '</div>';
    }

    echo '</div>';

}elseif ($do == 'Approve'){
    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

    $count = checkItem("ID" , "comments" , $comid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("UPDATE comments SET Status = 1 WHERE ID = ?");
        $stmt->execute(array($comid));
        $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Activated .</h4>";
        redirectHome($msg , "back");

    } else {
        echo '<div class="container">';
        $msg = '<h1 class="alert alert-danger">This Comment Does Not Exist.</h1>';
        redirectHome($msg);
        echo '</div>';
    }

    echo '</div>';


}

include $template . "footer.php";