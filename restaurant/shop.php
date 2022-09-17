<?php require __DIR__ . '/parts/connect_db.php';
$sql = "SELECT * FROM shop";

$rows = [];

$rows = $pdo->query($sql)->fetchAll();
?>
<?php require __DIR__ . '/parts/html-head.php' ?>
<?php require __DIR__ . '/parts/navbar.php' ?>
<div class="container">
    <div class="row">
        <div class="col-lg-3">

            <?php foreach($rows as $k => $v) :?>
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?= $v['name'] ?></h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="products.php?shop=<?= $v['sid'] ?>" class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</div>
<?php require __DIR__ . '/parts/scripts.php' ?>
<?php require __DIR__ . '/parts/html-foot.php' ?>