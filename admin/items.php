<?php

session_start();
$pageTitle = "Items";
if(!isset($_SESSION['user'])){
    header ('Location: dashboard.php');
    exit();
}
include "init.php";

$do = isset($_GET['action']) ? $do = $_GET['action'] : $do = "Manage";

if($do == 'Manage'){
    //select all exept admin users
    $stmt = $conn->prepare("SELECT items.* , categories.Name AS CatName ,users.Username FROM items
    INNER JOIN categories ON categories.ID = items.CatID
    INNER JOIN users ON users.UserID = items.MemberID");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo '<div class="container">';
    if(empty($rows)){
        echo '<div class="alert alert-info">There is No Items</div>';
    } else {
    ?>
    <h1 class="text-center">Manage Items</h1>   
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Descreption</td>
                        <td>Price</td>
                        <td>Added Date</td>
                        <td>Member Name</td>
                        <td>Category</td>
                        <td>Control</td>
                    </tr>                    
                </thead>
                <tbody>
                    
                        <?php
                        foreach($rows as $row):
                        ?>
                    <tr>
                        <td><?=$row['ItemID']?></td>
                        <td><?=$row['Name']?></td>
                        <td><?=$row['Description']?></td>
                        <td><?=$row['Price']?></td>
                        <td><?=$row['AddDate']?></td>
                        <td><?=$row['Username']?></td>
                        <td><?=$row['CatName']?></td>
                        <td>
                            <a href="items.php?action=Edit&itemid=<?=$row['ItemID']?>" class="btn btn-success">
                            <i class="fa fa-edit"></i> Edit</a>
                            <a href="items.php?action=Delete&itemid=<?=$row['ItemID']?>" class="btn btn-danger confirm">
                            <i class="fa fa-close"></i> Delete</a>
                            <?php if($row['Approve'] == 0){?>
                            <a href="items.php?action=Approve&itemid=<?=$row['ItemID']?>" class="btn btn-info">
                            <i class="fa fa-check"></i> Approve</a>
                            <?php }?>
                        </td> 
                    </tr>
                        <?php endforeach;?>
                   
                </tbody>
            </table>
        </div>
    <?php }?>
        <a class='btn btn-primary' href='items.php?action=Add'> <i class="fa fa-plus"></i> Add New Item</a> 
    </div>
<?php


} elseif ($do == 'Add'){?>
    <h1 class="text-center">Add New Categories</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=Insert" method="POST">
            <!-- Start Name -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="name">Name</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name" required>
                </div>
            </div>
            <!-- End Name -->

            <!-- Start Descrption -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="desc">Descreption</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="desc" required>
                </div>
            </div>
            <!-- End Descrption -->

            <!-- Start Price -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Price</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="price" required>
                </div>
            </div>
            <!-- End Price -->

            <!-- Start country -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Country Made</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="country" required>
                </div>
            </div>
            <!-- End country -->
            <!-- Start status -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Status</label>
                <div class="col-sm-10">
                    <select class="form-control"name="status">
                        <option value="0">...</option>
                        <option value="1">New</option>
                        <option value="2">Like New</option>
                        <option value="3">Used</option>
                        <option value="4">Very Old</option>
                    </select>
                </div>
            </div>
            <!-- End status -->

            <!-- Start members -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Member</label>
                <div class="col-sm-10">
                    <select class="form-control"name="member">
                        <option value="0">...</option>
                        <?php
                            $users = getRecords('*' , 'users');
                            foreach($users as $user){
                                echo '<option value="'.$user['UserID'].'">' .$user['Username'].'</option>'; 
                            }
                        ?>

                    </select>
                </div>
            </div>
            <!-- End members -->

            <!-- Start categ -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Categories</label>
                <div class="col-sm-10">
                    <select class="form-control"name="cat">
                        <option value="0">...</option>
                        <?php
                            $cats = getRecords('*' , 'categories');
                            foreach($cats as $cat){
                                echo '<option value="'.$cat['ID'].'">' .$cat['Name'].'</option>'; 
                                $rows = getRecords("*","categories" , "WHERE ParentID = ".$cat['ID']);
                                foreach($rows as $row){
                                    echo '<option value="'.$row['ID'].'"> --- ' .$row['Name'].'</option>'; 
                                }
                            }
                        ?>

                    </select>
                </div>
            </div>
            <!-- End categ -->

            <!-- Start tags -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Tags</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="tags">
                </div>
            </div>
            <!-- End tags -->

            <!-- Start Button -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit" value="Add Item">
                </div>
            </div>
            <!-- End Button -->
        </form>
    </div>
<?php

} elseif ($do == 'Insert'){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $user = $_POST['member'];
        $cat = $_POST['cat'];
        $tags = $_POST['tags'];
        $errors = array();
 

        if(empty($name)){
            $errors[] = "Name";
        } if(empty($desc)){
            $errors[] = "Descreption";
        }if (empty($price)){
            $errors[] = "Price";
        } if(empty($country)){
            $errors[] = "Country";
        } if($status == 0){
            $errors[] = "Choose a Status";
        } if($user == 0){
            $errors[] = "Choose a Member";
        } if($cat == 0){
            $errors[] = "Choose a Categories";
        }
        

        echo '<h1 class="text-center">Insert Item</h1>';
        echo '<div class="container">';
        if(count($errors) == 0 ){
            $stmt = $conn->prepare("INSERT INTO items (Name,Description,Price,AddDate,CountryMade,Status,Tags,CatID,MemberID)
            VALUES( ? , ? , ? , ? , ? , ? , ? , ? , ?)");
            $stmt->execute(array($name ,$desc, $price, date('Y/m/d') , $country , $status, $tags , $cat , $user));

            $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Added .</h4>";
            redirectHome($msg , 'back');
        } else{
            
            $msg = "<h2 class='text-center'>You Have the follwing empty:</h4>";
            foreach ($errors as $value) {
                $msg += "<h4 class='text-center alert alert-danger'>".$value . "</h4>";    
            }
            redirectHome($msg , 'back');
        }


    } else {
        $msg =  "<div class='alert alert-danger'>You can not access directly</div>";
        redirectHome($msg , "back");
    }
    echo "</div>";


} elseif ($do == 'Edit') {
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
    $stmt = $conn->prepare("SELECT * FROM items WHERE ItemID = ? LIMIT 1");

    $stmt->execute(array($itemid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if($count > 0){
?>
<h1 class="text-center">Edit Items</h1>
<div class="container">
    <form class="form-horizontal" action="?action=Update" method="POST">
        <input type="hidden" name="id" value="<?=$row['ItemID']?>">
        <!-- Start Username -->
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">Name</label>
            <div class="col-sm-10">
                <input value="<?=$row['Name']?>" class="form-control" type="text" name="name" required>
            </div>
        </div>
        <!-- End Username -->

        <!-- Start Email -->
        <div class="form-group">
            <label class="col-sm-2 control-label" for="pass">Description</label>
            <div class="col-sm-10">
                <input value="<?=$row['Description']?>" class="form-control" type="text" name="desc">
            </div>
        </div>
        <!-- End Email -->

        <!-- Start Password -->
        <div class="form-group">
            <label class="col-sm-2 control-label" for="price">Price</label>
            <div class="col-sm-10">
                <input value="<?=$row['Price']?>" class="form-control" type="text" name="price" required>
            </div>
        </div>
        <!-- End Password -->

        <!-- Start Fullname -->
        <div class="form-group">
            <label class="col-sm-2 control-label" for="country">Country</label>
            <div class="col-sm-10">
                <input value="<?=$row['CountryMade']?>" class="form-control" type="text" name="country" required>
            </div>
        </div>
        <!-- End Fullname -->

            <!-- Start status -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Status</label>
                <div class="col-sm-10">
                    <select class="form-control"name="status">
                        <option value="0">...</option>
                        <option value="1"<?php if($row['Status'] == 1)echo"selected";?>>New</option>
                        <option value="2"<?php if($row['Status'] == 2)echo"selected";?>>Like New</option>
                        <option value="3"<?php if($row['Status'] == 3)echo"selected";?>>Used</option>
                        <option value="4"<?php if($row['Status'] == 4)echo"selected";?>>Very Old</option>
                    </select>
                </div>
            </div>
            <!-- End status -->

            <!-- Start members -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Member</label>
                <div class="col-sm-10">
                    <select class="form-control"name="member">
                        <option value="0">...</option>
                        <?php
                            $users = getRecords("*" , 'users');
                            foreach($users as $user){
                                echo '<option value="'.$user['UserID'].'"';
                                if($user['UserID'] == $row['MemberID']) echo"selected";
                                echo '>' .$user['Username'].'</option>'; 
                            }
                        ?>

                    </select>
                </div>
            </div>
            <!-- End members -->

            <!-- Start members -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="order">Categories</label>
                <div class="col-sm-10">
                    <select class="form-control"name="cat">
                        <option value="0">...</option>
                        <?php
                            $cats = getRecords("*" , "categories");
                            foreach($cats as $cat){
                                echo '<option value="'.$cat['ID'].'"';
                                if($cat['ID'] == $row['CatID']) echo "selected";
                                echo'>' .$cat['Name'].'</option>'; 
                            }
                        ?>

                    </select>
                </div>
            </div>
            <!-- End members -->

            <!-- Start tags -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="tags">Tags</label>
                <div class="col-sm-10">
                    <input value="<?=$row['Tags']?>" class="form-control" type="text" name="tags">
                </div>
            </div>
            <!-- End tags -->

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

    } else {
        echo '<div class="container">';
        $msg =  "<h3 class='alert alert-danger'>There is No such ID.</h3>";
        redirectHome($msg);
        echo '</div>';
    }

} elseif ($do == 'Update') {
    echo "<h1 class='text-center'>Update Page</h1>";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $cat = $_POST['cat'];
        $tags = $_POST['tags'];
        $errors = array();
 
        if(empty($name)){
            $errors[] = "Name";
        } if(empty($desc)){
            $errors[] = "Descreption";
        }if (empty($price)){
            $errors[] = "Price";
        } if(empty($country)){
            $errors[] = "Country";
        } if($status == 0){
            $errors[] = "Choose a Status";
        } if($member == 0){
            $errors[] = "Choose a Member";
        } if($cat == 0){
            $errors[] = "Choose a Categories";
        }

        echo '<div class="container">';
        if(count($errors) == 0){
            $stmt = $conn->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, CountryMade = ?, 
            Status = ?,Tags = ? , CatID = ? , MemberID = ? WHERE ItemID = ?");
            $stmt->execute(array($name , $desc , $price , $country , $status , $tags , $cat , $member, $id));

            $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Updated .</h4>";
            redirectHome($msg , "back");
        } else{
            
            $msg =  "<h2 class='text-center'>You Have the follwing empty:</h4>";
            foreach ($errors as $value) {
                $msg .= "<h4 class='text-center alert alert-danger'>".$value . "</h4>";    
            }
            redirectHome($msg , 'back');
            
        }echo "</div>";

    } else {
        echo '<div class="container">';
        $msg = '<h2 class="alert alert-danger">You can not access Directly</h2>';
        redirectHome($msg);
        echo '</div>';
    }


} elseif ($do == 'Delete') {

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    $count = checkItem("ItemID" , "items" , $itemid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("DELETE FROM items WHERE ItemID = :zid");
        $stmt->bindParam(":zid" , $itemid);
        $stmt->execute();
        $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Deleted .</h4>";
        redirectHome($msg , 'back');

    } else {
        echo '<div class="container">';
        $msg = '<h1 class="alert alert-danger">This ID Does Not Exist.</h1>';
        redirectHome($msg);
        echo '</div>';
    }

    echo '</div>';

} elseif ($do == 'Approve') {

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    $count = checkItem("ItemID" , "items" , $itemid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("UPDATE items SET Approve = 1 WHERE ItemID = ?");
        $stmt->execute(array($itemid));
        $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Activated .</h4>";
        redirectHome($msg , "back");

    } else {
        echo '<div class="container">';
        $msg = '<h1 class="alert alert-danger">This ID Does Not Exist.</h1>';
        redirectHome($msg);
        echo '</div>';
    }

    echo '</div>';


}

include $template . "footer.php";