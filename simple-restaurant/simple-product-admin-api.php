<?php require __DIR__ . '/parts/connect_db.php';

header('Content-Type: application/json');



$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'postData' => $_POST, // 除錯用的
];

$folder = __DIR__ . '/upload/'; // 上傳檔案的資料夾

// $list = [
//     'name' => $_POST['name'],
//     'shop_sid' => $_POST['shop_sid'],
//     'price'  =>      $_POST['price'],
//          'available' =>  $_POST['available'],
//           'src' =>   $_FILES['photo']

// ];
// echo json_encode($list, JSON_UNESCAPED_UNICODE);
// exit;




$output = [
    'success' => false,
    'error' => '',
    'data' => [],
    'files' => $_FILES, // 除錯用
];


$sql_delete = "DELETE FROM `products` WHERE sid=?";
$stmt_delete = $pdo->prepare($sql_delete);

//刪除
if ($_POST['state'] == '0') {
    $stmt_delete->execute([
        $_POST['sid']
    ]);
    if ($stmt_delete->rowCount()) {
        $output['success'] = true;
    } else {
        if (empty($output['error']))
            $output['error'] = '資料沒有改動';
    }
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


//上傳圖片
if (!empty($_FILES['photo']['name'])) {
    $extMap = [
        'image/jpeg' => '.jpg',
        'image/png' => '.png',
    ];

    $ext = $extMap[$_FILES['photo']['type']];
    if (empty($ext)) {
        $output['error'] = '檔案格式錯誤: 要 jpeg, png';
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 隨機檔名
    $filename = md5($_FILES['photo']['name'] . uniqid()) . $ext;
    $output['filename'] = $filename;

    if (!move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $folder . $filename
    )) {
        $output['error'] = '無法移動上傳檔案, 注意資料夾權限問題';
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $src = './upload/' . $filename;
} else {
    $src = './upload/' . '0.jpg';
}






// 判斷name和price是否為空
if (empty($_POST['name']) or empty($_POST['price'])) {
    $output['error'] = '參數不足';
    $output['code'] = 400;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}



$sql_insert = "INSERT INTO `products`(
     `name`,
     `shop_sid`, 
     `price`, 
     `type`, 
     `available`, 
     `src`
     ) VALUES (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
        )";

$stmt_insert = $pdo->prepare($sql_insert);

$sql_update = "UPDATE `products` SET `name`=?,`shop_sid`=?,`price`=?,`type`=?,`available`=?,`src`=? WHERE sid=?";

$stmt_update = $pdo->prepare($sql_update);



// 將available轉成1,0

// $isAvail = $_POST['available'] == 'on' ? 1 : 0;
$isAvail = (!empty($_POST['available'])) ? 1 : 0;


if (empty($_POST['sid'])) {
    $stmt_insert->execute([
        $_POST['name'],
        $_POST['shop_sid'],
        $_POST['price'],
        1,
        $isAvail,
        $src
    ]);
} else {
    $stmt_update->execute([
        $_POST['name'],
        $_POST['shop_sid'],
        $_POST['price'],
        1,
        $isAvail,
        $src,
        $_POST['sid']
    ]);
}



//判斷結果是否成功
if ($stmt_insert->rowCount() or $stmt_update->rowCount()) {
    $output['success'] = true;
} else {
    if (empty($output['error']))
        $output['error'] = '資料沒有新增';
}




echo json_encode($output, JSON_UNESCAPED_UNICODE);
