<?php
session_start();
$pageTitle = "Show Item";
include "init.php"; 

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$stmt = $conn->prepare("SELECT items.*, categories.Name,users.Fullname FROM items 
                       INNER JOIN categories ON categories.ID = items.CatID
                       INNER JOIN users ON users.UserID = items.MemberID  
                       WHERE itemId = ?
                       AND Approve = 1");

$stmt->execute(array($itemid));
$count = $stmt->rowCount();
if($count > 0){
    $row = $stmt->fetch();
?>
<h1 class="text-center">Show Item</h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="img-responsive img-thumbnail center-block" src="img.png" alt="">   
        </div>
        <div class="col-md-9 item-info">
            <h2><?=$row['Name']?></h2>
            <p><?=$row['Description']?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>Added Date<span> : <?=$row['AddDate']?>
                </li>
                <li>
                    <i class="fa fa-money fa-fw"></i>
                    <span>Price<span> : $<?=$row['Price']?>
                </li>
                <li>
                    <i class="fa fa-building fa-fw"></i>
                    <span>Made In<span> : <?=$row['CountryMade']?>
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Category<span> : <a href="categories.php?pageid=<?=$row['CatID']?>"><?=$row['Name']?></a>
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span>Added By<span> : <a href="#"><?=$row['Fullname']?></a>
                </li >
                <li class="tags-tags">
                    <i class="fa fa-user fa-fw"></i>
                    <span>Tags<span> : 
                        <?php $allTage = explode(",",$row['Tags']);
                        foreach($allTage as $tag){
                            $tag = str_replace(' ','',$tag);
                            if(!empty($tag)){
                            echo "<a href='tags.php?name=".strtolower($tag)."'>".$tag .' </a>';}
                        }
                    ?>
                </li >
            </ul>
        </div>
    </div> 
    <hr class='custom-hr'>
    <?php if(isset($_SESSION['name'])){ ?>
    <!-- start add comment -->
    <div class="row">
        <div class="col-md-offset-3">
            <div class="comment">
                <h3>Add Your Comment</h3>
                <form action="<?php echo"?itemid=".$row['ItemID'];?>" method="POST">
                    <textarea name="comment" required></textarea>
                    <input class="btn btn-primary"type="submit" value="Add Comment">
                </form>
                <?php 
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $comment = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING);
                    $userid = $_SESSION['id'];
                    $itemid = $row['ItemID'];
                    if(!empty($comment)){
                        $stmt = $conn->prepare("INSERT INTO 
                        comments( Comment, Status, AddedDate, ItemID, UserID) 
                        VALUES(? , ? , ? , ? , ?)");
                        $stmt->execute(array($comment , 0 , date('y-m-d') , $itemid , $userid));
                        if($stmt){
                            echo '<div class="alert alert-success text-center">Comment Added</div>';
                        }
                    } else{
                        echo '<div class="alert alert-danger text-center">You Must Add Comment</div>';
                    }

                }
                ?>
            </div>
        </div>
    </div>
    <?php } else {
                echo '<a href="login.php">Login</a> or <a href="login.php">Rigister</a> to activate Commenting';
    } ?>
    <!-- End add comment -->
    <hr class='custom-hr'>
    <?php 
                $stmt = $conn->prepare("SELECT comments.* , users.Fullname  FROM comments
                INNER JOIN users ON users.UserID = comments.UserID
                WHERE  ItemID = ? AND Status = 1
                ORDER BY comments.ID DESC");
                $stmt->execute(array($row['ItemID']));
                $rows = $stmt->fetchAll();

            ?>
    <div class="row">
            <?php 
            foreach($rows as $row):?>
                <div class="row comment-box">
                <div class="col-sm-2 text-center">
                    <img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="">
                    <?=$row['Fullname']?>
                </div>
                <div class="col-sm-10">
                    <p class="lead"><?=$row['Comment']?></p>
                </div>
            </div>
            <hr class="custom-hr">
            <?php endforeach;?>
    </div>
</div>



<?php
} else {
    echo '<h1 class="alert alert-danger">There is No Such ID or Item waiting for Activate</h1>';
}
include $template . "footer.php";
?>
