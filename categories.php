<?php 
session_start();
$pageTitle = "Category";
include "init.php";
$pageid = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;

if($_SERVER['REQUEST_METHOD'] == 'GET' && $pageid != 0){
?>


<div class="container">
    <h1 class="text-center">Show Category</h1>
    <div class="row">
    <?php foreach(getRecords("*" ,"items","WHERE CatID =".$_GET['pageid']." AND Approve = 1") as $item): ?>
        <div class="col-sm-6 col-md-3">
            <div class="thumbnail item-box">
                <span class="price-tag"><?=$item['Price']?></span>
                <img class="img-responsive" src="img.png" alt="">
                <div class="caption">
                    <h3><a href="items.php?itemid=<?=$item['ItemID']?>"><?=$item['Name']?></a></h3>
                    <p><?=$item['Description']?></p>
                    <div class="date"><?=$item['AddDate']?></div>
                </div>
            </div>
        </div>      
    <?php endforeach;?>
    </div>
</div>

<?php }
else {echo '<div class="container">
            <h1 class="text-center"> You con not Access Directly</h1>  
            </div>';
}
include $template . "footer.php"; ?>
