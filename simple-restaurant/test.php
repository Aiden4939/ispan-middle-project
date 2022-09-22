<?php require __DIR__ . '/parts/connect_db.php';

header('Content-type: application/json');

$sql_all = "SELECT p.* ,o.product_sid,o.option_type,o.option_name,o.option_price FROM `products` p LEFT JOIN `products_options` o ON p.sid=o.product_sid WHERE p.shop_sid=1";
$rows_all = $pdo->query($sql_all)->fetchAll();


$arr1 = [];

foreach ($rows_all as $item) {
    $arr1[$item['type']][$item['name']]['price'] = $item['price'];
    $arr1[$item['type']][$item['name']]['name'] = $item['name'];
    $arr1[$item['type']][$item['name']]['option_type'][$item['option_type']][$item['option_name']] = $item['option_price'];
}


echo json_encode($arr1, JSON_UNESCAPED_UNICODE);
