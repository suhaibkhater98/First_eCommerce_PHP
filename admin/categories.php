<?php
session_start();
$pageTitle = "Categories";
if(!isset($_SESSION['user'])){
    header ('Location: dashboard.php');
    exit();
}
include "init.php";

$do = isset($_GET['action']) ? $do = $_GET['action'] : $do = "Manage";

if($do == 'Manage'){
    $sort = "DESC";
    $sort_array = array('ASC' , 'DESC');
    if(isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
        $sort = $_GET['sort'];
    }
    $stmt = $conn->prepare("SELECT * FROM categories WHERE ParentID = 0 ORDER BY Ordering $sort");
    $stmt->execute();
    $cats = $stmt->fetchAll();
    echo '<div class="container categories">';
    if(empty($cats)){
        echo '<div class="alert alert-info"></div>';
    } else {
    ?>
    <h1 class="text-center">Manage Categories</h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                Managing
                <div class="ordering pull-right">
                    Ordering:
                    <a href="?sort=ASC">Asc</a> | 
                    <a href="?sort=DESC">Desc</a>
                </div>
            </div>
            <div class="panel-body">
                <?php foreach($cats as $cat):?>
                    <div class="cat">
                        <div class="hidden-buttons">
                            <a href="categories.php?action=Edit&catid=<?=$cat['ID']?>" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
                            <a href="categories.php?action=Delete&catid=<?=$cat['ID']?>" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i> Delete</a>
                        </div>
                        <h3><?=$cat['Name']?></h3>
                        <p><?php if(empty($cat['Description'])){echo"Has No Description";}else echo $cat['Description'];?></p>
                        Allow Visibility: <span><?=$cat['Visibility']?></span>
                        Allow Comment: <span><?=$cat['Allow_Comment']?></span>
                        Allow Ads: <span><?=$cat['Allow_Ads']?></span>
                        <?php
                            $rows = getRecords("*","categories" , "WHERE ParentID = ".$cat['ID']);
                            if(!empty($rows)){
                                echo '<h5>Sub Categories: </h5>
                                <ul class="list-unstyled">';
                                foreach($rows as $row){
                                    echo '<li>
                                    <a href="categories.php?action=Edit&catid='. $row['ID'].'">'
                                    .$row['Name'] .'</a>';?>
                                    <a href="categories.php?action=Delete&catid=<?=$row['ID']?>" class="confirm btn btn-xs btn-danger">Delete</a>
                                    </li>
                                <?php
                                }
                                echo '</ul>';
                            }
                        ?>
                    </div>
                    <hr>
                <?php endforeach;?>
            </div>
        </div>
    <?php }?>
        <a class="btn btn-primary" href="categories.php?action=Add"><i class="fa fa-plus"></i>Add New</a>
    </div>
<?php
} elseif ($do == 'Add'){?>
    <h1 class="text-center">Add New Categories</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=Insert" method="POST">
            <!-- Start Name -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="username">Name</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name" required>
                </div>
            </div>
            <!-- End Name -->

            <!-- Start Descrption -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="pass">Descreption</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="desc">
                </div>
            </div>
            <!-- End Descrption -->

            <!-- Start Ordering -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Oredring</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="order">
                </div>
            </div>
            <!-- End Ordering -->
            
            <!-- Start Parent ID -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Have Parent</label>
                <div class="col-sm-10">
                    <select name="parentid">
                        <option value="0">...</option>
                        <?php
                            $cats = getRecords("*" , "categories" , "WHERE ParentID = 0");
                            foreach($cats as $cat){
                                echo '<option value="'.$cat['ID'].'">' .$cat['Name'].'</option>'; 
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- End Parent ID -->

            <!-- Start Visible -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Visible</label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="visible" value=0 checked>
                        <label>Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="visible" value=1>
                        <label>No</label>
                    </div>
                </div>
            </div>
            <!-- End Visible -->

            <!-- Start Commenting -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Commenting</label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="comment" value=0 checked>
                        <label>Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="comment" value=1>
                        <label>No</label>
                    </div>
                </div>
            </div>
            <!-- End Commenting -->

            <!-- Start Ads -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Ads</label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="ads" value=0 checked>
                        <label>Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="ads" value=1>
                        <label>No</label>
                    </div>
                </div>
            </div>
            <!-- End Ads -->

            
            <!-- Start Button -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit" value="Add Categories">
                </div>
            </div>
            <!-- End Button -->
        </form>
    </div>
<?php
} elseif ($do == 'Insert'){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        echo '<h1 class="text-center">Insert Categories</h1>';
        
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $order = empty($_POST['order']) ? null : $_POST['order'];
        $parentid = $_POST['parentid'];
        $visible = $_POST['visible'];
        $comment = $_POST['comment'];
        $ads = $_POST['ads'];
        

        echo '<div class="container">';
        if(checkItem("Name" , "categories" ,$name) == 0){
            if(!(empty($name)) ){
                $stmt = $conn->prepare("INSERT INTO categories (Name, Description, Ordering, Visibility , Allow_Comment , Allow_Ads ,ParentID)
                VALUES( :cname , :cdesc , :corder , :visible , :comment , :ads , :pid)");
                $stmt->execute(array(
                    ':cname' => $name ,
                    ':cdesc' => $desc , 
                    ':corder' => $order , 
                    ':visible' => $visible , 
                    ':comment' => $comment , 
                    ':ads' => $ads,
                    ':pid' => $parentid 
                ));

                $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Added .</h4>";
                redirectHome($msg , 'back');
            } else{
                
                $msg = "<h4 class='text-center alert alert-danger'>".$value . "</h4>";    
                redirectHome($msg , 'back');
            }
        }else {
            $msg = "<h4 class='text-center alert alert-danger'>The Name Is Used</h4>";
            redirectHome($msg , 'back');
        }

    } else {
        $msg =  "<div class='alert alert-danger'>You can not access directly</div>";
        redirectHome($msg , "back");
    }
    echo "</div>";

} elseif ($do == 'Edit') {
    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
    $stmt = $conn->prepare("SELECT * FROM categories WHERE ID = ?");

    $stmt->execute(array($catid));
    $cat = $stmt->fetch();
    $count = $stmt->rowCount();
    if($count > 0){
?>
<h1 class="text-center">Edit Categories</h1>
<div class="container">
        <form class="form-horizontal" action="?action=Update" method="POST">
            <input type="hidden" name="id" value="<?=$cat['ID']?>">
            <!-- Start Name -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="username">Name</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name" value="<?=$cat['Name']?>" required>
                </div>
            </div>
            <!-- End Name -->

            <!-- Start Descrption -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="pass">Description</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="desc" value="<?=$cat['Description']?>">
                </div>
            </div>
            <!-- End Descrption -->

            <!-- Start Ordering -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Ordering</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="order" value="<?=$cat['Ordering']?>">
                </div>
            </div>
            <!-- End Ordering -->

            <!-- Start Parent ID -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Have Parent</label>
                <div class="col-sm-10">
                    <select name="parentid" value="10">
                        <option value="0">...</option>
                        <?php
                            $rows = getRecords("*" , "categories" , "WHERE ParentID = 0");
                            foreach($rows as $row){
                                if($row['ID'] == $cat['ParentID'])
                                echo '<option value="'.$row['ID'].'"selected>' .$row['Name'].'</option>';
                                else
                                echo '<option value="'.$row['ID'].'">' .$row['Name'].'</option>'; 
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- End Parent ID -->

            <!-- Start Visible -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Visible</label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="visible" value=0 
                       <?php if($cat['Visibility'] == 0 ) echo "checked";?>>
                        <label>Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="visible" value=1 
                        <?php if($cat['Visibility'] == 1 ) echo "checked";?>>
                        <label>No</label>
                    </div>
                </div>
            </div>
            <!-- End Visible -->

            <!-- Start Commenting -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Commenting</label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="comment" value=0 
                        <?php if($cat['Allow_Comment'] == 0 ) echo "checked";?>>
                        <label>Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="comment" value=1
                        <?php if($cat['Allow_Comment'] == 1 ) echo "checked";?>>
                        <label>No</label>
                    </div>
                </div>
            </div>
            <!-- End Commenting -->

            <!-- Start Ads -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Ads</label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="ads" value=0 
                        <?php if($cat['Allow_Ads'] == 0 ) echo "checked";?>>
                        <label>Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="ads" value=1
                        <?php if($cat['Allow_Ads'] == 1 ) echo "checked";?>>
                        <label>No</label>
                    </div>
                </div>
            </div>
            <!-- End Ads -->

            
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

}elseif ($do == 'Update') {
    echo "<h1 class='text-center'>Update Categories</h1>";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $order = $_POST['order'];
        $visible = $_POST['visible'];
        $comment = $_POST['comment'];
        $ads = $_POST['ads'];
        $parentid = $_POST['parentid'];

        echo '<div class="container">';
        if(!empty($name) ){
            $stmt2 = $conn->prepare("SELECT * FROM categories WHERE Name = ? AND ID != ?");
            $stmt2->execute(array($name , $id));
            if ($stmt2->rowCount() == 0){
            $stmt = $conn->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ? , Allow_Comment = ?, Allow_Ads = ?, ParentID = ? WHERE ID = ?");
            $stmt->execute(array($name , $desc , $order , $visible , $comment , $ads,$parentid ,$id));

            $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Updated .</h4>";
            redirectHome($msg , "back");
            } else {
                $msg = "<h4 class='text-center alert alert-danger'>The Name is Used</h4>";    
                redirectHome($msg , 'back');
            }
        } else{
            
            $msg = "<h4 class='text-center alert alert-danger'>The Name should be Fill out</h4>";    
            redirectHome($msg , 'back');
            
        }echo "</div>";

    } else {
        echo '<div class="container">';
        $msg = '<h2 class="alert alert-danger">You can not access Directly</h2>';
        redirectHome($msg);
        echo '</div>';
    }

}elseif ($do == 'Delete') {
    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

    $count = checkItem("ID" , "categories" , $catid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("DELETE FROM categories WHERE ID = :zid");
        $stmt->bindParam(":zid" , $catid);
        $stmt->execute();
        $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Deleted .</h4>";
        redirectHome($msg);

    } else {
        echo '<div class="container">';
        $msg = '<h1 class="alert alert-danger">This Categories Does Not Exist.</h1>';
        redirectHome($msg);
        echo '</div>';
    }

    echo '</div>';
}

include $template . "footer.php";