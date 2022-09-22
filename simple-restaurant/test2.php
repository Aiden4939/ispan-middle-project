<?php require __DIR__ . '/parts/connect_db.php' ?>

<?php require __DIR__ . '/parts/html-head.php' ?>
<?php require __DIR__ . '/parts/navbar.php' ?>
<form action="">
<button class="save" type="submit" onclick="updateData(); return false;">儲存資料</button>
</form>


<?php require __DIR__ . '/parts/scripts.php' ?>
<script>
    let myForm = document.querySelector("form");
    let a = fetch('test.php')
        .then(r => r.json())
        .then(obj => {
            for (let type in obj) {
                myForm.innerHTML += `
                        <button>添加商品類別</button>
                        <input type="text" placeholder="餐點類別type" value="${type}">
                        <div class="product">
                    `;
            
                for(let product in obj[type]) {
                    myForm.innerHTML += `<div class="mb-3">
                        <label for="product-name" class="form-label">餐點名稱</label>
                        <input type="text" class="form-control" aria-describedby="emailHelp" name="product-name[]" value="${obj[type][product].name}">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label">餐點價格</label>
                        <input type="number" class="form-control" name="product-price[]" value="${obj[type][product].price}">
                    </div>
                    <button>添加選項類別</button>
                    `;
                    
                    for(let optionType in obj[type][product].option_type){
                        myForm.innerHTML +=`
                        <input type="text" placeholder="選項類別options_type" value =${optionType}>
                        `;

                        for (let option in obj[type][product].option_type[optionType]) {
                            console.log(option)
                            myForm.innerHTML += `
                            <input type="text" placeholder="選項名稱options_type" value =${option}>
                            <input type="text" placeholder="選項名稱options_type" value =${option[option]}>
                            `;
                        }
                    }
                }
            };
        })

    let saveBtn = document.querySelector(".save");
    function updateData() {
        const fd = new FormData(myForm);

        fetch('update-api.php', {
            method: 'POST',
            body: fd
        }).then(r => r.json()).then(obj => {
            
        })
    }
    
</script>
<?php require __DIR__ . '/parts/html-foot.php' ?>