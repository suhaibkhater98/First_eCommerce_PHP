<?php 
session_start();
$pageTitle = "Category";
include "init.php";

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['name'])){
?>


<div class="container">
    <h1 class="text-center">Show Item By [<?=$_GET['name']?>] Tags</h1>
    <div class="row">
    <?php 
    $items = getRecords("*" ,"items","WHERE Tags LIKE '%".$_GET['name']."%' AND Approve = 1");
    foreach($items as $item): ?>
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
