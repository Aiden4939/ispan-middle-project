<?php
require __DIR__ . '/parts/connect_db.php';
$sid = $_GET['shop'];
if (empty($sid)) {
    header('Location: shop.php');
}
$sql_n = "SELECT * FROM products WHERE shop_sid={$sid}";
$rows_n = [];
$rows_n = $pdo->query($sql_n)->fetchAll();

$sql_type = "SELECT * FROM products WHERE shop_sid={$sid} GROUP BY type";
$rows_type = [];
$rows_type = $pdo->query($sql_type)->fetchAll();



// $sql_options = "SELECT option_type,option_name,option_price,products_options.product_sid,products.sid FROM products JOIN products_options ON products.sid=products_options.product_sid WHERE shop_sid={$sid}";
// $rows_options = [];
// $rows_options = $pdo->query($sql_options)->fetchAll();

// $sql_options_type = "SELECT option_type,products_options.product_sid,products.sid FROM products JOIN products_options ON products.sid=products_options.product_sid WHERE shop_sid={$sid} GROUP BY option_type";
// $rows_options_type = [];
// $rows_options_type = $pdo->query($sql_options_type)->fetchAll();



?>

<?php require __DIR__ . '/parts/html-head.php' ?>
<?php require __DIR__ . '/parts/navbar.php' ?>

<div class="container d-flex">
    <div class="row">
        <div class="col-lg-3">
            <?php foreach ($rows_type as $t) : ?>
                <h2><?= $t['type'] ?></h2>
                <?php foreach ($rows_n as $v) : ?>
                    <?php if ($v['type'] == $t['type']) : ?>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" value="<?= $v['name'] ?>"><?= $v['name'] ?></h5>
                                <p class="card-text" value="<?= $v['price'] ?>"><?= $v['price'] ?></p>
                                <button type="button" class="btn btn-primary buy-button" >購買</button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>



        </div>
    </div>
    <div class="cart">
        
    </div>
</div>
<!-- <div class="big">
    </div> -->

   


<?php require __DIR__ . '/parts/scripts.php' ?>
<script>
    let big = document.querySelector(".big");
    let productList = document.querySelector("div.col-lg-3");
    let shopping = false;
    
    //add item to cart
    let cartList = document.querySelector(".cart");
    let proInfo = document.querySelectorAll(".card-body");
    
    productList.addEventListener("click", e => {
        if (e.target.classList.contains("buy-button")) {
            let buyBtn = e.target;
            let myPro = buyBtn.parentElement;
            let proName = myPro.children[0].innerText;
            let proPrice = myPro.children[1].innerText;
            let count = 1;
            let newPro = document.createElement("div");
            newPro.classList.add("product");
            newPro.innerHTML = `<h5>${proName}</h5>
                    <p>${proPrice}</p>
                    <h5 class="count">${count}</h5>`;
            
                     
    
            let addBtn = document.createElement("button");
            addBtn.innerText = "+";
            addBtn.addEventListener("click", e => {
                e.target.parentElement.children[2].innerText = ++count;
            })
            let delBtn = document.createElement("button");
            delBtn.innerText = "-";
            delBtn.addEventListener("click", e => {
                e.target.parentElement.children[2].innerText = --count;
                if(count == 0) {
                    e.target.parentElement.remove();
                }
            })
            newPro.appendChild(addBtn);
            newPro.appendChild(delBtn);

            cartList.appendChild(newPro);   
        }
    })







<?php /*
    productList.addEventListener("click", e => {
        let thisPro = e.target;
        if (thisPro.constructor.name == "HTMLButtonElement") {
            big.innerHTML = ` <?php foreach ($rows_options_type as $t) : ?>
        <h2><?= $t['option_type'] ?></h2>
        <?php foreach ($rows_options as $v) : ?>
            <?php if ($v['option_type'] == $t['option_type']) : ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="<?= $t['option_type'] ?>" value="<?= $v['option_name'] ?>" price-val="<?= $v['option_price'] ?>">
                    <label class="form-check-label" for="flexRadioDefault1">
                        <?= $v['option_name'] ?>
                    </label>
                    <label class="form-check-label" for="flexRadioDefault1">
                        +<?= $v['option_price'] ?>
                    </label>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>`;
        }
    })
*/ ?>
</script>
<?php require __DIR__ . '/parts/html-foot.php' ?>