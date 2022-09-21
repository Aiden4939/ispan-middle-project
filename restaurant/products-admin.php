<?php require __DIR__ . '/parts/connect_db.php';

$sid = $_GET['shop'];

$sql_all = "SELECT * FROM `products` WHERE shop_sid=$sid";
$rows_all = $pdo->query($sql_all)->fetchAll();

$sql_num = "SELECT COUNT(num) FROM `products` WHERE shop_sid=$sid";
$rows_num = $pdo->query($sql_all)->fetchAll();



$sql_type = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid WHERE p.shop_sid=$sid GROUP BY p.type";
$rows_type = $pdo->query($sql_type)->fetchAll();

// $sql_options_all = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid WHERE p.shop_sid=$sid";
// $rows_options_all = $pdo->query($sql_options_all)->fetchAll();

// $sql_options_type = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid  WHERE p.shop_sid=$sid GROUP BY o.option_type";
// $rows_options_type = $pdo->query($sql_options_type)->fetchAll();

$id = 0;

?>

<?php require __DIR__ . '/parts/html-head.php' ?>
<?php require __DIR__ . '/parts/navbar.php' ?>

<div class="container">
    <div class="row">
        <!-- form -->
        <form name="form1" enctype="mutipart/form-data">
            <input type="text" name="shop_sid" value="<?= $sid ?>" style="display:none;">
            <!-- 第一次使用、還沒有任何商品的商家 -->
            <?php if (sizeof($rows_all) == '0') : ?>
                <div class="type-container">
                    <!-- 目錄 -->




                    <h2>類別: </h2>
                    <input type="text" name="" class="parent-type" placeholder="餐點類別type" value="">
                    <!-- 刪除類別按鈕 -->
                    <button type="button" class="delete-type-btn">刪除本類別</button>

                    <!-- 列出該商品類別下的所有商品 -->
                    <div class="product">
                        <div class="mb-3">

                            <input type="text" class="state" name="state[]" value="0" style="display:none;">

                            <input type="text" name="sid[]" value="" style="display:none;" class='hidden-sid'>

                            <input type="text" name="type[]" placeholder="餐點類別type" class="child-type" value="" style="display:none;">

                            <input type="file" class="file" name="photo[]" accept="image/png,image/jpeg">

                            <img src="" alt="">

                            <label for="product-name" class="form-label">餐點名稱</label>
                            <input type="text" class="form-control name" aria-describedby="emailHelp" name="name[]" value="">

                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div><label for="product-price" class="form-label">餐點價格</label>

                            <input type="number" class="form-control price" name="price[]" value="">
                        </div>


                        <div class="options">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input available" name="available[]" id="<?= $id ?>">
                                <label class="form-check-label available" for="<?= $id++ ?>">是否上架</label>
                            </div>
                        </div>
                        <button type="button" class="insert-btn">插入一個新的商品欄位</button>
                        <button type="button" class="copy-btn">複製目前商品欄位</button>
                        <button type="button" class="delete-btn">刪除目前商品欄位</button>

                    </div>

                    <button type="button" class="add-type-btn">添加新類別</button>
                </div>
            <?php endif; ?>
            <!-- 每個商品類別一個group -->
            <?php for ($i = 0; $i < sizeof($rows_type); $i++) : ?>
                <div class="type-container">
                    <!-- 目錄 -->




                    <h2>類別: </h2>
                    <input type="text" name="" class="parent-type" placeholder="餐點類別type" value="<?= $rows_type[$i]['type'] ?>">
                    <!-- 刪除類別按鈕 -->
                    <button type="button" class="delete-type-btn">刪除本類別</button>

                    <!-- 列出該商品類別下的所有商品 -->
                    <?php for ($k = 0; $k < sizeof($rows_all); $k++) : ?>
                        <?php if ($rows_all[$k]['type'] == $rows_type[$i]['type']) : ?>
                            <div class="product">
                                <div class="mb-3">

                                    <input type="text" class="state" name="state[]" value="1" style="display:none;">

                                    <input type="text" name="sid[]" value="<?= $rows_all[$k]['sid'] ?>" style="display:none;" class='hidden-sid'>

                                    <input type="text" name="type[]" placeholder="餐點類別type" class="child-type" value="<?= $rows_type[$i]['type'] ?>" style="display:none;">

                                    <input type="file" class="file" name="photo[]" accept="image/png,image/jpeg">

                                    <img src="./upload/<?= $rows_all[$k]['src'] ?>" alt="">

                                    <label for="product-name" class="form-label">餐點名稱</label>
                                    <input type="text" class="form-control name" aria-describedby="emailHelp" name="name[]" value="<?= $rows_all[$k]['name'] ?>">

                                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div><label for="product-price" class="form-label">餐點價格</label>

                                    <input type="number" class="form-control price" name="price[]" value="<?= $rows_all[$k]['price'] ?>">
                                </div>


                                <div class="options">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input available" <?= ($rows_all[$k]['available'] == '1') ? 'checked' : '' ?> name="available[]" id="<?= $id ?>">
                                        <label class="form-check-label available" for="<?= $id++ ?>">是否上架</label>
                                    </div>
                                </div>
                                <button type="button" class="insert-btn">插入一個新的商品欄位</button>
                                <button type="button" class="copy-btn">複製目前商品欄位</button>
                                <button type="button" class="delete-btn">刪除目前商品欄位</button>

                            </div>

                        <?php endif; ?>
                    <?php endfor; ?>
                    <button type="button" class="add-type-btn">添加新類別</button>
                </div>
            <?php endfor; ?>


            <button type="submit" class="btn btn-primary" onclick="updateData();return false;">儲存資料</button>
            <button type="button" class="btn btn-primary" onclick="giveUp();return false;">放棄修改</button>
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
            let myFile = e.target.files[0];
            let reader = new FileReader();

            reader.addEventListener("load", function() {
                e.target.parentElement.querySelector("img").src = reader.result;
            }, false);
            reader.readAsDataURL(myFile);
        }
    }, false);

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

    //放棄修改按鈕
    function giveUp() {
        if (confirm("確定要放棄修改?")) {
            location.reload();
        }
    }


    // 將每個type的value放入該type下商品的隱藏input中
    function giveType() {
        let parentInp = document.querySelectorAll(".parent-type");
        parentInp.forEach((i) => {
            i.addEventListener("input", e => {
                e.target.closest('.type-container').querySelectorAll(".child-type").forEach((item) => {
                    item.value = i.value;
                    // console.log(item.value)
                })
            })
        })
    }

    giveType();



    // test event change
    // let inpFile = document.querySelectorAll("input[type=file]") ;
    // inpFile.forEach(i => {
    //     i.addEventListener("change", e => {
    //         console.log(e)
    //     })
    // })
    // document.addEventListener("change", e => {
    // console.log(this)
    // if(e.target.type == 'file') {

    // console.log(e.target)
    // }
    // })



    // button
    let insertBtn = document.querySelectorAll("insert-btn")
    let copyBtn = document.querySelectorAll("copy-btn")
    let delBtn = document.querySelectorAll("delete-btn")
    // click event
    container.addEventListener("click", function(e) {
        let tar = e.target;
        //新增空白商品
        if (tar.classList.contains("insert-btn")) {
            let thisType = tar.parentNode;
            let newProduct = thisType.cloneNode(true);
            // console.log(newProduct.querySelector("input.available"));
            newProduct.querySelector("input.available").setAttribute('id', <?= $id ?>);
            newProduct.querySelector("label.available").setAttribute('for', <?= $id++ ?>);
            newProduct.querySelector(".state").value = 0;
            newProduct.querySelector(".name").value = '';
            newProduct.querySelector(".price").value = '';
            newProduct.querySelector("img").src = '0.jpg';
            thisType.parentNode.insertBefore(newProduct, thisType.nextSibling);
        }



        //複製空白商品
        if (tar.classList.contains("copy-btn")) {
            let thisType = tar.parentNode;
            let newProduct = thisType.cloneNode(true);
            newProduct.querySelector("input.available").setAttribute('id', <?= $id ?>);
            newProduct.querySelector("label.available").setAttribute('for', <?= $id++ ?>);
            newProduct.querySelector(".state").value = 0;
            thisType.parentNode.insertBefore(newProduct, thisType.nextSibling);
            // tar.parentElement.parentElement.appendChild(newProduct);
        }



        // 刪掉商品
        if (tar.classList.contains("delete-btn")) {
            tar.parentElement.style.display = 'none';
            tar.parentElement.querySelector(".state").value = -1;
        }


        //新增一個類別(加上一個空的商品)
        if (tar.classList.contains("add-type-btn")) {
            let newType = document.createElement("div");
            newType.classList.add("type-container");
            newType.innerHTML += `
            <h2>類別: </h2>
            <input type="text" name="" class="parent-type" placeholder="餐點類別type" value="">
            `;
            let newProduct = document.getElementsByClassName("product")[0].cloneNode(true);
            // newProduct.querySelector(".hidden-sid").value = 0;
            newProduct.querySelector(".state").value = 0;
            newProduct.querySelector(".name").value = '';
            newProduct.querySelector(".price").value = '';
            newProduct.querySelector("img").src = '0.jpg';
            newType.appendChild(newProduct);
            console.log(e.target.parentNode);
            e.target.parentNode.insertBefore(newType, e.target.nextSibling);
        }

        if (tar.classList.contains("delete-type-btn")) {
            tar.parentElement.style.display = 'none';
            tar.parentElement.querySelectorAll(".state").forEach(i => {
                i.value = -1;
            })
        }
        giveType();

    })


    // function confirmSave() {
    //     if(confirm("確定要儲存資料嗎?")) {
    //         updateData();
    //         location.reload();
    //     }
    // }
    // update function
    function updateData() {
        if (confirm("確定要儲存資料嗎?")) {
            const fd = new FormData(document.form1);

            fetch('products-admin-api.php', {
                method: "POST",
                body: fd
            }).then(r => r.json()).then(obj => {
                console.log(obj);
            })
            // location.reload();
        }

    }
</script>
<?php require __DIR__ . '/parts/html-foot.php' ?>