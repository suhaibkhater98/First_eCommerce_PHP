<?php
session_start();
$pageTitle = "Home";
include "init.php"; ?>
<div class="container">
    <h1 class="text-center">Show Category</h1>
    <div class="row">
    <?php foreach(getRecords("*" , "items" , "WHERE Approve = 1", "ItemID") as $item): ?>
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
<?php
include $template . "footer.php";
?>
