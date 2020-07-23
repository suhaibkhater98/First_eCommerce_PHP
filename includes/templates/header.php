<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=getTitle();?></title>
    <link rel="stylesheet" href="<?=$css?>bootstrap.min.css">
    <link rel="stylesheet" href="<?=$css?>font-awesome.min.css">
    <link rel="stylesheet" href="<?=$css?>custom.css">
    
</head>
<body>

<div class="upper-bar">
  <div class="container">
  <?php if(isset($_SESSION['name'])){
           ?>
           <img class="my-img img-circle" src="img.png" alt="">
           <div class="btn-group my-info pull-right">  
              <span class="btn dropdown-toggle" data-toggle="dropdown">
                <?=$_SESSION['name']?>
                <span class="caret"></span>
              </span>
               <ul class="dropdown-menu">
                  <li><a href="profile.php">My Profile</a></li>
                  <li><a href="newad.php">New Item</a></li>
                  <li><a href="profile.php#my-ads">My Items</a></li>
                  <li><a href="logout.php">Logout</a></li>
               </ul>
           </div>
           <?php
           if(checkUserStatus($_SESSION['name']) == 1){
             //echo " Wait for Activation ... ";
           }
      }else {?>
    <a href="login.php">
      <span class="pull-right">Login/SignUp</span>
    </a>
    <?php }?>
  </div>
</div>
<nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?=lang('ADMIN_HOME')?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
        <?php
          foreach(getRecords("*","categories" , "WHERE ParentID = 0") as $cat){
            echo '<li>
            <a href="categories.php?pageid='. $cat['ID'].'">'
            .$cat['Name'] .'</a></li>';
          }
        ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

