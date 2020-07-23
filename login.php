<?php
session_start();
$pageTitle = "Login";
if(isset($_SESSION['name'])){
    header("Location: index.php");
} 
include "init.php"; 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['login'])){
    $username = $_POST['name'];
    $pass = $_POST['pass'];
    $hashpass = sha1($pass);

    $stmt = $conn->prepare("SELECT UserID,Username,Password
    FROM users WHERE Username = ? AND Password = ? ");

    $stmt->execute(array($username , $hashpass));
    $data = $stmt->fetch(); 
    $count = $stmt->rowCount();
    
    if($count > 0){
        $_SESSION['name'] = $username;
        $_SESSION['id'] = $data['UserID'];
        header ('Location: index.php');
        exit();
    }
} else {
    $errors = array();
    if(isset($_POST['name'])){
        $name = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
        if(empty($name)){
            $errors[] = "Username Empty";
        }    
    } if(isset($_POST['pass'])){
        $pass = filter_var($_POST['pass'] , FILTER_SANITIZE_STRING);    
    } if(isset($_POST['email'])){
        $email = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL);
        if(filter_var($email , FILTER_VALIDATE_EMAIL) != true){
            $errors[] = "Email Empty Or Not Valid";
        }    
    }
    if(empty($pass)){
        $errors[] = "Password Empty";
    } 
    if(count($errors) == 0 ){
        if(checkItem("Username" , "users" ,$name) == 0){
        
            $stmt = $conn->prepare("INSERT INTO users (Username, Password, Email , Regstatus , Regsdate)
            VALUES( ? , ? , ? , ? , ?)");
            $stmt->execute(array($name ,sha1($pass), $email , 0 , date('y-m-d')));

            $successMsg = "Congrats ... ";
        } else {
        $errors[] = "The User Is Used";
        }
    } 
}
}
?>
 
    <div class="container login-page">
        <h1 class="text-center"><span data-class="login" class="active">Login</span> | 
        <span data-class="signup">SigUp</span></h1>
        <form class="login" action="" method="POST">
            <input class="form-control" type="text" name="name" autocomplete="off">
            <input class="form-control" type="password" name="pass">
            <input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
        </form>


        <form class="signup" action="" method="POST">
            <div class="input-container"><input class="form-control" type="text" name="name" ></div>
            <div class="input-container"><input class="form-control" type="password" name="pass" required></div>
            <div class="input-container"><input class="form-control" type="email" name="email" ></div>
            <input class="btn btn-success btn-block" type="submit" name="signup" value="SignUp">
        </form>
        <div class="the-error text-center">
            <?php if(!empty($errors)):
                    foreach($errors as $error):?>
                    <div class="alert alert-danger"><?=$error?></div>
                    <?php    
                endforeach;
                endif;
                if(isset($successMsg)) echo '<div class="alert alert-success">'.$successMsg.'</div>';
                ?>
        </div>
    </div>

<?php include $template . "footer.php"; ?>