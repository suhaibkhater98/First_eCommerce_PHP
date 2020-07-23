<?php
session_start();
$pageTitle = "Members";
if(!isset($_SESSION['user'])){
    header ('Location: index.php');
} 

$pageTitle = "Memebers";
include "init.php";
$do = isset($_GET['action']) ? $do = $_GET['action'] : $do = "Manage";

if($do == "Manage"){
    $query = '';
    if(isset($_GET['pend']) && $_GET['pend'] == 'Pending'){
        $query = 'AND Regstatus = 0';
    }
    //select all exept admin users
    $stmt = $conn->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo '<div class="container">';
    if(empty($rows)){
        echo '<div class="alert alert-info">There is No record To show</div>';
    } else {
    ?>
    <h1 class="text-center">Manage Member</h1>  
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Register Date</td>
                        <td>Control</td>
                    </tr>                    
                </thead>
                <tbody>
                    
                        <?php
                        foreach($rows as $row):
                        ?>
                    <tr>
                        <td><?=$row['UserID']?></td>
                        <td><?=$row['Username']?></td>
                        <td><?=$row['Email']?></td>
                        <td><?=$row['Fullname']?></td>
                        <td><?=$row['Regsdate']?></td>
                        <td>
                            <a href="members.php?action=Edit&userid=<?=$row['UserID']?>" class="btn btn-success">Edit</a>
                            <a href="members.php?action=Delete&userid=<?=$row['UserID']?>" class="btn btn-danger confirm">Delete</a>
                            <?php if($row['Regstatus'] == 0){?>
                            <a href="members.php?action=Activate&userid=<?=$row['UserID']?>" class="btn btn-info">Activate</a>
                            <?php }?>
                        </td> 
                    </tr>
                        <?php endforeach;?>
                   
                </tbody>
            </table>
        </div>
                            <?php }?>
        <a class='btn btn-primary' href='members.php?action=Add'> <i class="fa fa-plus"></i> Add New Member</a> 
    </div>
<?php
}elseif($do == "Add"){
?>
    <h1 class="text-center">Add New Member</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=Insert" method="POST">
            <!-- Start Username -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="username">Username</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="username" required>
                </div>
            </div>
            <!-- End Username -->

            <!-- Start Pass -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="pass">Password</label>
                <div class="col-sm-10">
                    <input class="password form-control" type="password" name="pass" required>
                    <i class="show-pass fa fa-eye fa-2x"></i>
                </div>
            </div>
            <!-- End Pass -->

            <!-- Start Email -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="email">Email</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="email" required>
                </div>
            </div>
            <!-- End Email -->

            <!-- Start Fullname -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Full Name</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="fullname" required>
                </div>
            </div>
            <!-- End Fullname -->
            
            <!-- Start Button -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit" value="Add Member">
                </div>
            </div>
            <!-- End Button -->
        </form>
    </div>
<?php
}elseif($do == "Insert"){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $username = $_POST['username'];
        $pass = $_POST['pass'];
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $errors = array();
 

        if(empty($username)){
            $errors[] = "Username";
        } if(empty($_POST['pass'])){
            $errors[] = "Password";
        }if (empty($email)){
            $errors[] = "Email";
        } if(empty($fullname)){
            $errors[] = "Fullname";
        }

        $hashpass =  sha1($_POST['pass']);

        echo '<div class="container">';

        if(checkItem("Username" , "users" ,$username) == 0){
            if(count($errors) == 0 ){
                $stmt = $conn->prepare("INSERT INTO users (Username, Password, Email, Fullname , Regstatus)
                VALUES( ? , ? , ? , ? , 1)");
                $stmt->execute(array($username ,$hashpass, $email , $fullname ));

                $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Added .</h4>";
                redirectHome($msg , 'back');
            } else{
                
                $msg = "<h2 class='text-center'>You Have the follwing empty:</h4>";
                foreach ($errors as $value) {
                    $msg += "<h4 class='text-center alert alert-danger'>".$value . "</h4>";    
                }
                redirectHome($msg , 'back');
            }
        }else {
            $msg = "<h4 class='text-center alert alert-danger'>The User Is Used</h4>";
            redirectHome($msg , 'back');
        }

    } else {
        $msg =  "<div class='alert alert-danger'>You can not access directly</div>";
        redirectHome($msg , "back");
    }
    echo "</div>";

} elseif ($do == "Edit") { 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
    
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0){
    ?>
    <h1 class="text-center">Edit Profile</h1>
    <div class="container">
        <form class="form-horizontal" action="?action=Update" method="POST">
            <input type="hidden" name="id" value="<?=$row['UserID']?>">
            <!-- Start Username -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="username">Username</label>
                <div class="col-sm-10">
                    <input value="<?=$row['Username']?>" class="form-control" type="text" name="username" required>
                </div>
            </div>
            <!-- End Username -->

            <!-- Start Email -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="pass">Password</label>
                <div class="col-sm-10">
                    <input class="form-control" type="hidden" name="pass" value="<?=$row['Password']?>">
                    <input class="form-control" type="password" name="password">
                </div>
            </div>
            <!-- End Email -->

            <!-- Start Password -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="email">Email</label>
                <div class="col-sm-10">
                    <input value="<?=$row['Email']?>" class="form-control" type="text" name="email" required>
                </div>
            </div>
            <!-- End Password -->

            <!-- Start Fullname -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="fullname">Full Name</label>
                <div class="col-sm-10">
                    <input value="<?=$row['Fullname']?>" class="form-control" type="text" name="fullname" required>
                </div>
            </div>
            <!-- End Fullname -->
            
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
} elseif ($do == 'Update'){
    echo "<h1 class='text-center'>Update Page</h1>";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $errors = array();

        $pass = empty($_POST['password']) ? $_POST['pass'] : sha1($_POST['password']); 

        if(empty($username)){
            $errors[] = "Username";
        } if (empty($email)){
            $errors[] = "Email";
        } if(empty($fullname)){
            $errors[] = "Fullname";
        }

        echo '<div class="container">';
        if(count($errors) == 0){
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
            $stmt2->execute(array($username , $id));
            if ($stmt2->rowCount() == 0){
                $stmt = $conn->prepare("UPDATE users SET Username = ?, Email = ?, Fullname = ?, Password = ? WHERE UserID = ?");
                $stmt->execute(array($username , $email , $fullname , $pass , $id));

                $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Updated .</h4>";
                redirectHome($msg , "back");
            } else {
                $msg = "<h4 class='text-center alert alert-danger'>The User is Used</h4>";
                redirectHome($msg , 'back');
            }
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

} elseif($do == 'Delete'){
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $count = checkItem("UserID" , "users" , $userid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("DELETE FROM users WHERE UserID = :zid");
        $stmt->bindParam(":zid" , $userid);
        $stmt->execute();
        $msg = "<h4 class='alert alert-success'>".$stmt->rowCount() . " Record Deleted .</h4>";
        redirectHome($msg , "back");

    } else {
        echo '<div class="container">';
        $msg = '<h1 class="alert alert-danger">This ID Does Not Exist.</h1>';
        redirectHome($msg);
        echo '</div>';
    }

    echo '</div>';

} elseif($do == "Activate"){

    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $count = checkItem("UserID" , "users" , $userid);
    echo '<div class="container">';
    if($count == 1){
        $stmt = $conn->prepare("UPDATE users SET Regstatus = 1 WHERE UserID = ?");
        $stmt->execute(array($userid));
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

