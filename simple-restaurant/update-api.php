<?php require __DIR__ . '/parts/connect_db.php';
    
    header('Content-Type: application/json');

    $output = [
        'success'=> false,
        'error' => '',
        'code' => 0,
        'postData' => $_POST
    ];
    

    $sql_p = "UPDATE `products` SET 
    `name`=?,
    `shop_sid`=?,
    `price`=?,
    `type`=?,
    `available`=?,
    `src`=?,
    `note`=?
    WHERE sid=1";

    $stmt_p = $pdo->prepare($sql_p);
    $stmt_p->execute([
        $_POST['name'],
        $_POST['shop_sid'],
        $_POST['price'],
        $_POST['type'],
        $_POST['availble'],
        $_POST['src'],
        $_POST['note']
    ]);

    $sql_o = "UPDATE `products_options` SET 
    `product_sid`=?,
    `option_type`=?,
    `option_name`=?,
    `option_price`=? 
    WHERE sid=1 " ;

    $stmt


?>



