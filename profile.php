<?php
session_start();
$pageTitle = "Profile";
include "init.php"; 
if(isset($_SESSION['name'])){
    $getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    //getRecords("*" , "users" , "WHERE Username = ".$sessionUser);
    $info = $getUser->fetch();

?>
<h1 class="text-center">My Profile</h1>
<div class="informtion block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span> :  <?=$info['Username']?> 
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email</span> : <?=$info['Email']?> 
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>FullName</span> : <?=$info['Fullname']?> 
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Regester Date</span> : <?=$info['Regsdate']?> 
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favourite Category</span> :
                    </li>
                </ul>
                <a href="#" class="btn btn-default">Edit Profile</a>     
            </div>
        </div>
    </div>
</div>

<div class="myads block">
    <div class="container">
        <div class="panel panel-default">
            <div id="my-ads" class="panel-heading">My Items</div>
            <div class="panel-body">
            
                <?php
                    $items = getRecords("*" , 'items' , 'WHERE MemberID = '.$info['UserID']);
                    if(!empty($items)){ 
                        echo '<div class="row">';
                    foreach($items as $item): ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail item-box">
                            <?php if($item['Approve'] == 0):?>
                            <span class="approve-status">Waiting Approval</span> 
                            <?php endif;?>
                            <span class="price-tag"><?=$item['Price']?></span>
                            <img class="img-responsive" src="img.png" alt="">
                            <div class="caption">
                                <h3><a href="items.php?itemid=<?=$item['ItemID']?>"><?=$item['Name']?></a></h3>
                                <p><?=$item['Description']?></p>
                                <div class="date"><?=$item['AddDate']?></div>
                            </div>
                        </div>
                    </div>      
                <?php endforeach;echo '</div>';
                } else {
                    echo 'You Have No Ads, Creat <a href="newad.php">New Add</a>';
                }?>
            </div>
        </div>
    </div>
</div>

<div class="mycomm block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                    <?php 
                        $comments = getRecords('Comment' , "comments" , "WHERE UserID = ".$info['UserID']);
                        if(!empty($comments)){
                            foreach($comments as $comment){
                                echo "<p>".$comment['Comment']."</p>" ;
                            }
                        } else {
                            echo 'You Have No Comments';
                        }
                         
                    ?>
            </div>
        </div>
    </div>
</div>
<?php
}else {
    header("Location: login.php");
    exit();
}
include $template . "footer.php";
?>
