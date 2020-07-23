<?php
session_start();
$noNavbar = "No";
$pageTitle = "Login";
if(isset($_SESSION['user'])){
    header ('Location: dashboard.php');
}
include "init.php"; 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $pass = $_POST['pass'];
    $hashpass = sha1($pass);

    $stmt = $conn->prepare("SELECT UserID,Username,Password
    FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");

    $stmt->execute(array($username , $hashpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    
    if($count > 0){
        $_SESSION['user'] = $username;
        $_SESSION['ID'] = $row['UserID'];
        header ('Location: dashboard.php');
        exit();
    }
}
?>

<form class="login" action="" method="post">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>
<?php
include $template . "footer.php";
?>
