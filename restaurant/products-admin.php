<?php require __DIR__ . '/parts/connect_db.php';

$sid = $_GET['shop'];

$sql_all = "SELECT * FROM `products` WHERE shop_sid=$sid";
$rows_all = $pdo->query($sql_all)->fetchAll();


$sql_type = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid WHERE p.shop_sid=$sid GROUP BY p.type";
$rows_type = $pdo->query($sql_type)->fetchAll();

// $sql_options_all = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid WHERE p.shop_sid=$sid";
// $rows_options_all = $pdo->query($sql_options_all)->fetchAll();

// $sql_options_type = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid  WHERE p.shop_sid=$sid GROUP BY o.option_type";
// $rows_options_type = $pdo->query($sql_options_type)->fetchAll();


?>

<?php require __DIR__ . '/parts/html-head.php' ?>
<?php require __DIR__ . '/parts/navbar.php' ?>

<div class="container d-flex">
    <div class="row">
        <form name="form1" enctype="mutipart/form-data">
            <input type="text" name="shop_sid" value="<?= $sid ?>" style="display:none;">
            <!-- 每個商品類別一個group -->
            <?php for ($i = 0; $i < sizeof($rows_type); $i++) : ?>
                <div class="container">
                    <button>添加商品類別</button>

                    <input type="text" name="" class="parent-type" placeholder="餐點類別type" value="<?= $rows_type[$i]['type'] ?>">

                    <!-- 列出該商品類別下的所有商品 -->
                    <?php for ($k = 0; $k < sizeof($rows_all); $k++) : ?>
                        <?php if ($rows_all[$k]['type'] == $rows_type[$i]['type']) : ?>
                            <div class="product">
                                <div class="mb-3">

                                    <input type="text" name="sid[]" value="<?= $rows_all[$k]['sid'] ?>" style="display:none;">

                                    <input type="text" name="type[]" placeholder="餐點類別type" class="child-type" value="<?= $rows_type[$i]['type'] ?>" style="display:none;">

                                    <input type="file" name="photo[]" accept="image/png,image/jpeg">
                                    <img src="./upload/<?= $rows_all[$k]['src']?>" alt="">

                                    <label for="product-name" class="form-label">餐點名稱</label>
                                    <input type="text" class="form-control" aria-describedby="emailHelp" name="name[]" value="<?= $rows_all[$k]['name'] ?>">
                                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div><label for="product-price" class="form-label">餐點價格</label>
                                    <input type="number" class="form-control" name="price[]" value="<?= $rows_all[$k]['price'] ?>">
                                </div>


                                <div class="options">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" checked name="available[]">
                                        <label class="form-check-label" for="exampleCheck1">是否上架</label>
                                    </div>
                                </div>

                            </div>

                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>


            <button type="submit" class="btn btn-primary" onclick="updateData(); return false;">Submit</button>
        </form>
    </div>
    <div class="cart">

    </div>
</div>
<?php require __DIR__ . '/parts/scripts.php' ?>
<script>
    // fileReader 預覽圖片
    let container = document.querySelector(".container");
    // document.querySelector("input[type=file]").addEventListener("change", function(e) {
    container.addEventListener("change", function(e) {
        if (e.target.type == 'file') {
            let myFile =  e.target.files[0];
            let reader = new FileReader();
            
            reader.addEventListener("load", function() {
                e.target.parentElement.querySelector("img").src = reader.result;
            },false);
            reader.readAsDataURL(myFile);
        }

    },false);
    
    // 預覽圖片參考code
    // let fileInput = document.querySelector('input[type=file]');
    // previewImg = document.querySelector('img');
    // fileInput.addEventListener('change', function() {
    //     console.log(this);
    //     let file = this.files[0];
    //     let reader = new FileReader();
    //     // 監聽reader物件的的onload事件，當圖片載入完成時，把base64編碼賦值給預覽圖片
    //     reader.addEventListener("load", function() {
    //         previewImg.src = reader.result;
    //     }, false);
    //     // 呼叫reader.readAsDataURL()方法，把圖片轉成base64
    //     reader.readAsDataURL(file);
    // }, false);


    // 將每個type的value放入該type下商品的隱藏input中
    let parentInp = document.querySelectorAll(".parent-type");
    parentInp.forEach((i) => {
        i.addEventListener("input", e => {
            e.target.closest('.container').querySelectorAll(".child-type").forEach((item) => {
                item.value = i.value;
                // console.log(item.value)
            })
        })
    })


    //test event change
    // let inpFile = document.querySelectorAll("input[type=file]") ;
    // inpFile.forEach(i => {
    //     i.addEventListener("change", e => {
    //         console.log(e)
    //     })
    // })
    document.addEventListener("change", e => {
        // console.log(this)
        if(e.target.type == 'file') {
            
            // console.log(e.target)
        }

    })

    function updateData() {
        const fd = new FormData(document.form1);

        fetch('products-admin-api.php', {
            method: "POST",
            body: fd
        }).then(r => r.json()).then(obj => {
            console.log(obj);
        })
    }
</script>
<?php require __DIR__ . '/parts/html-foot.php' ?>