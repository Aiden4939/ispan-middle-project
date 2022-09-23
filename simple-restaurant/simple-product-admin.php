<?php require __DIR__ . '/parts/connect_db.php';

$sid = $_GET['shop'];

$sql_all = "SELECT * FROM `products` WHERE shop_sid=$sid";
$rows_all = $pdo->query($sql_all)->fetchAll();

$sql_type = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid WHERE p.shop_sid=$sid GROUP BY p.type";
$rows_type = $pdo->query($sql_type)->fetchAll();

?>

<?php require __DIR__ . '/parts/html-head.php' ?>
<?php require __DIR__ . '/parts/navbar.php' ?>

<div class="container">
    <table>

        <thead>
            <tr>
                <th>圖片</th>
                <th>名字</th>
                <th>價格</th>
                <th>上架狀態</th>
                <th>修改</th>
                <th>刪除</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($k = 0; $k < sizeof($rows_all); $k++) : ?>
                <tr>
                    <td style="display:none ;"><?= $rows_all[$k]['sid'] ?></td>
                    <td><img src="<?= $rows_all[$k]['src'] ?>" alt=""></td>
                    <td><?= $rows_all[$k]['name'] ?></td>
                    <td><?= $rows_all[$k]['price'] ?></td>
                    <td><?= ($rows_all[$k]['available'] == '1') ? '上架中' : '尚未上架' ?></td>
                    <td>
                        <button type="button" onclick="editProduct();return false;" class="edit-btn">修改</button>
                    </td>
                    <td>
                        <button type="button" onclick="deleteProduct(event);return false;" class="delete-btn">刪除</button>
                    </td>
                </tr>
            <?php endfor; ?>

        </tbody>
    </table>
    <button type="button" class="add-btn">新增商品
    </button>
</div>
<div class="edit-form hidden">
    <form action="" name="form1">
        <label for=""></label>
        <input type="text" name="sid" class="sid" value="" style="display:none;" class='hidden-sid'>

        <input type="file" class="file" name="photo" accept="image/png,image/jpeg">

        <img class="photo" src="" alt="">

        <label for="product-name" class="">餐點名稱</label>
        <input type="text" class="name" name="name" value="">

        <label for="product-price" class="">餐點價格</label>
        <input type="number" class="price" name="price" value="">

        <input type="checkbox" class="available" name="available" id="available">
        <label class=" available" for="available">是否上架</label>
        <input type="text" name="shop_sid" value="<?= $sid ?>" style="display:none;">
        <input type="text" name="state" value="" style="display:none;">
        <button type="button" class="submit-btn" onclick="submitForm();return false;">儲存</button>
        <button type="button" class="submit-btn" onclick="giveUpForm();return false;">放棄修改</button>
    </form>
</div>

<?php require __DIR__ . '/parts/scripts.php' ?>
<script>
    let container = document.querySelector('.container');
    let editBox = document.querySelector(".edit-form");
    container.addEventListener("click", e => {
        if (e.target.classList.contains("edit-btn")) {
            editBox.classList.remove("hidden");
            let editProduct = e.target.closest("tr");
            let editProductList = editProduct.querySelectorAll("td")
            let editBoxList = editBox.querySelectorAll("input");
            editBox.querySelector('img').src = editProduct.querySelector('img').src;
            editBoxList[0].value = editProductList[0].innerText;
            editBoxList[2].value = editProductList[2].innerText;
            editBoxList[3].value = editProductList[3].innerText;
            console.log(editBoxList[4])
            if (editProductList[4].innerText == '上架中') {
                editBoxList[4].checked = true;
            }

        }
        if (e.target.classList.contains("add-btn")) {
            editBox.classList.remove("hidden");
        }
    })

    function editProduct() {
        // let editForm = document.querySelector(".edit-form");
        let editBox = document.querySelector(".edit-form");
        editBox.classList.remove("hidden");
        // document.form1.style.display = 'block';
        // document.form1.name.value
    }

    function deleteProduct(e) {
        let delProduct = e.target.closest("tr");
        if (confirm("確定要刪除這個商品?")) {
            const fd = new FormData();
            console.log(delProduct.querySelectorAll("td")[0].innerText)
            fd.append('sid', delProduct.querySelectorAll("td")[0].innerText)
            fd.append('state', 0)
            fetch('simple-product-admin-api.php', {
                method: 'POST',
                body: fd
            }).then(r => r.json()).then(obj => {
                console.log(obj);
                if (!obj.success) {
                    alert(obj.error);
                } else {
                    alert('刪除成功');
                    location.reload();
                }
            })

        }
    }

    function submitForm() {
        const fd = new FormData(document.form1);

        fetch('simple-product-admin-api.php', {
            method: 'POST',
            body: fd
        }).then(r => r.json()).then(obj => {
            console.log(obj);
            if (!obj.success) {
                alert(obj.error);
            } else {
                alert('儲存成功');
                location.reload();
            }
        })
    }
</script>
<?php require __DIR__ . '/parts/html-foot.php' ?>