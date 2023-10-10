<?php

include_once './mapper/CheckId.php';

$id = $_POST['id'];

if ($id == null) {
    $json['result'] = "400";
    $json['message'] = "아이디를 입력해주세요.";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$result = checkId($id);

$json = array();
if($result->num_rows > 0){
    $row = mysqli_fetch_array($result);
    if($row[0] == 0){
        $json['result'] = "200";
        $json['message'] = "사용 가능한 아이디입니다.";
    }else{
        $json['result'] = "400";
        $json['message'] = "이미 사용중인 아이디입니다.";
    }
}else{
    $json['result'] = "500";
    $json['message'] = "서버 오류";
}

echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>