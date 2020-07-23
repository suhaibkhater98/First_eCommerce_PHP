<?php
session_start();
$pageTitle = "Create Ad";
include "init.php"; 
if(isset($_SESSION['name'])){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errors = array();
        $title = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $cat = filter_var($_POST['cat'], FILTER_SANITIZE_NUMBER_INT);
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        if(empty($title)){
            $errors[] = 'the Title empty';  
        }if(empty($desc)){
            $errors[] = 'the descreption empty';  
        }if(empty($country)){
            $errors[] = 'the Country empty';  
        }if(empty($price)){
            $errors[] = 'the Price empty';  
        }if(empty($status)){
            $errors[] = 'the Status empty';  
        }if(empty($cat)){
            $errors[] = 'the Category empty';  
        }

        if(count($errors) == 0 ){
            $stmt = $conn->prepare("INSERT INTO items (Name,Description,Price,AddDate,CountryMade,Status,Tags,CatID,MemberID)
            VALUES( ? , ? , ? , ? , ? , ? , ? , ? , ?)");
            $stmt->execute(array($title ,$desc, $price, date('Y/m/d') , $country , $status , $tags , $cat , $_SESSION['id']));

            if($stmt) $successMsg = 'Item Added';
        } 

    }

?>
<h1 class="text-center">Create Item</h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Item</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="text-center">Add New Item</h1>
                            <form class="form-horizontal main-form" action="" method="POST">
                                <!-- Start Name -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="name">Name</label>
                                    <div class="col-sm-10">
                                        <input class="form-control live" data-class=".live-name" type="text" name="name" required>
                                    </div>
                                </div>
                                <!-- End Name -->

                                <!-- Start Descrption -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="desc">Descreption</label>
                                    <div class="col-sm-10">
                                        <input class="form-control live" data-class=".live-desc" type="text" name="desc" required>
                                    </div>
                                </div>
                                <!-- End Descrption -->

                                <!-- Start Price -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">Price</label>
                                    <div class="col-sm-10">
                                        <input class="form-control live" data-class=".live-price" type="text" name="price" required>
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
                                        <select class="form-control" name="status" required>
                                            <option value="">...</option>
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
                                    <label class="col-sm-2 control-label" for="order">Categories</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="cat" required>
                                            <option value="">...</option>
                                            <?php
                                                $cats = getRecords("*" , "categories" , null , "ID");
                                                foreach($cats as $cat){
                                                    echo '<option value="'.$cat['ID'].'">' .$cat['Name'].'</option>'; 
                                                }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <!-- End members -->
                                <!-- Start country -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">Tags</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="tags">
                                    </div>
                                </div>
                                <!-- End country -->

                                <!-- Start Button -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input class="btn btn-primary" type="submit" value="Add Item">
                                    </div>
                                </div>
                                <!-- End Button -->
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">$<span class="live-price">0</span></span>
                                <img class="img-responsive" src="img.png" alt="">
                                <div class="caption">
                                    <h3 class="live-name">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>

                    </div>

                <?php 
                    if(!empty($errors)){
                        foreach($errors as $erros){
                            echo '<div class="alert alert-danger text-center">'.$erros.'</div>';
                        }
                    }
                    if(isset($successMsg)) echo '<div class="alert alert-success">'.$successMsg.'</div>';
                ?>
                </div>
</div>

<?php
}else {
    header("Location: login.php");
    exit();
}
include $template . "footer.php";
?>
