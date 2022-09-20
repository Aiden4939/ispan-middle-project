<?php require __DIR__ . '/parts/connect_db.php';

header('Content-Type: application/json');

$folder = __DIR__ . '/upload/';


// $folder = __DIR__ . '/upload/';


//上傳圖片
// $extMap = [
//     'image/jpeg' => '.jpg',
//     'image/png' => '.png'
// ];
// echo json_encode($_FILES['photo']['name'], JSON_UNESCAPED_UNICODE);
// exit;


// $ext = $extMap[$_FILES['photo']['type'][0]];
// if(empty($ext)){
//     $output['error'] = '檔案格式錯誤: 要 jpeg, png';
//     echo json_encode($output, JSON_UNESCAPED_UNICODE);
//     exit;
// }

// $filename = md5($_FILES['photo']['name'][0]. uniqid()). $ext;
// $output['filename'][] = $filename;

// move_uploaded_file(
//     $_FILES['photo']['tmp_name'][0],
//     $folder.$filename
// );




$shop_sid = $_POST['shop_sid'];

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'postData' => ''
];
$sql_check = "SELECT * FROM products WHERE shop_sid=$shop_sid";

$rows_check = $pdo->query($sql_check)->fetchAll();

// echo json_encode($rows_check, JSON_UNESCAPED_UNICODE);
// exit;



$sql = "UPDATE `products` SET `name`=?,`shop_sid`=?,`price`=?,`type`=?,`available`=?,`src`=?,`note`=? WHERE  sid=?";



$stmt = $pdo->prepare($sql);

//檔案位置


//檢查並更新每一筆資料
for ($i = 0; $i < sizeof($_POST['name']); $i++) {

    if (!empty($_FILES['photo']['name'][$i])) {
        $extMap = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png'
        ];
        // echo json_encode($_FILES['photo']['name'], JSON_UNESCAPED_UNICODE);
        // exit;


        $ext = $extMap[$_FILES['photo']['type'][$i]];
        if (empty($ext)) {
            $output['error'] = '檔案格式錯誤: 要 jpeg, png';
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $filename = md5($_FILES['photo']['name'][$i] . uniqid()) . $ext;
        $output['filename'][] = $filename;

        move_uploaded_file(
            $_FILES['photo']['tmp_name'][$i],
            $folder . $filename
        );

        $src = $filename;
    } 
    else {
        $src = $rows_check[$i]['src'];
    }


    // //檢查圖片是否為空
    // if (empty($_FILES['photo'][$i])) {
    //     $src = $folder . "0.jpg";
    // } else {
    //     $extMap = [
    //         'image/jpeg' => '.jpg',
    //         'image/png' => '.png'
    //     ];

    //     $ext = $extMap[$_FILES['photo']['type'][$i]];
    //     if (empty($ext)) {
    //         $output['error'] = '檔案格式錯誤: 要 jpeg, png';
    //         echo json_encode($output, JSON_UNESCAPED_UNICODE);
    //         exit;
    //     }

    //     $filename = md5($_FILES['photo']['name'][$i] . uniqid()) . $ext;
    //     $output['filename'][] = $filename;

    //     move_uploaded_file(
    //         $_FILES['photo']['tmp_name'][$i],
    //         $folder . $filename
    //     );

    //     $src = $folder . $filename;
    // }




    //檢測圖片

    if (
        $rows_check[$i]['src'] !== $src or
        $rows_check[$i]['sid'] !== $_POST['sid'][$i] or
        $rows_check[$i]['name'] !== $_POST['name'][$i] or
        $rows_check[$i]['price'] !== $_POST['price'][$i] or
        $rows_check[$i]['type'] !== $_POST['type'][$i] or
        $rows_check[$i]['available'] !== $_POST['available'][$i]
    ) {
        $isAvail = ($_POST['available'][$i] == 'on') ? 1 : 0;

        $stmt->execute([
            $_POST['name'][$i],
            $_POST['shop_sid'],
            $_POST['price'][$i],
            $_POST['type'][$i],
            $isAvail,
            $src,
            1,
            $_POST['sid'][$i]
        ]);
    }
}



// if ($stmt->rowCount()) {
//     $output['success'] = true;
// } else {
//     if (empty($output['error'])) {
//         $output['error'] = '資料沒有修改';
//         echo json_encode($output, JSON_UNESCAPED_UNICODE);
//     }
// }

echo json_encode($output, JSON_UNESCAPED_UNICODE);
    // echo json_encode($list, JSON_UNESCAPED_UNICODE);
