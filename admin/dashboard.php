<?php
session_start();
if(!isset($_SESSION['user'])){
    header ('Location: index.php');
}
$pageTitle = "Dashboard";
include "init.php";

$latestUser = 5 ;
$names = getLatest("*" , "users" ,"UserID",$latestUser);

$latestItems = 6;
$items = getLatest("*" , "items" ,"ItemID",$latestItems);

$latestComment = 5;
?>
<div class="container home-stats text-center">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="stat st-members">
                Total Members
                <span><a href="members.php"><?=countItem("UserID" , "users")?></a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-pending">
                Pending Members
                <span><a href="members.php?pend=Pending"><?=checkItem("Regstatus" , "users" , 0)?></a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-items">
                Total Items
                <span><a href="items.php"><?=countItem("ItemID" , "items")?></a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-comments">
                Total Comments
                <span><a href="comments.php"><?=countItem("ID" , "comments")?></a></span>
            </div>
        </div>
    </div>
</div>
<div class="container latest">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-users"></i> Latest <?=$latestUser?> User Register
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled latest-user">
                        <?php
                            if(!empty($names)){
                            foreach($names as $name){
                                echo "<li>".$name['Username'] .
                                '<a href="members.php?action=Edit&userid='.$name['UserID'].'">'. 
                                '<span class="btn btn-success pull-right"><i class="fa fa-edit">'
                                .'</i> Edit</span></a>';
                               if($name['Regstatus'] == 0){
                                    echo '<a href="members.php?action=Activate&userid='.$name['UserID'].'"><span class="btn btn-info pull-right">Activate</a>';
                                    echo '</span>';
                                }
                                echo "</li>";
                            }
                        } else{
                            echo '<p>There is No User</p>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-tag"></i> Latest <?=$latestUser?> Items
                </div>
                <div class="panel-body">
                <ul class="list-unstyled latest-user">
                        <?php
                            if(!empty($items)){
                            foreach($items as $item){
                                echo "<li>".$item['Name'] .
                                '<a href="items.php?action=Edit&itemid='.$item['ItemID'].'">'. 
                                '<span class="btn btn-success pull-right"><i class="fa fa-edit">'
                                .'</i> Edit</span></a>';
                               if($item['Approve'] == 0){
                                    echo '<a href="items.php?action=Approve&itemid='.$item['ItemID'].'"><span class="btn btn-info pull-right">Activate</a>';
                                    echo '</span>';
                                }
                                echo "</li>";
                            }
                        } else {
                            echo '<p>There is No Item</p>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>      
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-comments-o"></i> Latest <?=$latestComment?> Comments
                </div>
                <div class="panel-body">
                    <?php
                        $stmt = $conn->prepare("SELECT comments.* , users.Username FROM comments
                        INNER JOIN users ON users.UserID = comments.UserID LIMIT $latestComment");
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        if(!empty($rows)){
                            foreach($rows as $row){
                                echo '<div class="comment-box">';
                                echo '<span class="member-n">'.$row['Username'].'</span> ';
                                echo '<p class="member-c">'.$row['Comment'] . '</p>';
                                echo "</div>";
                            }
                        }else{
                            echo "There is No comments";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include $template . "footer.php";