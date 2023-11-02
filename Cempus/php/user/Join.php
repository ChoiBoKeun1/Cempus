<?php

include_once '../common.php';
include_once './mapper/CheckId.php';

$id = $_POST['id'];
$pw = $_POST['pw'];

if ($id == null) {
    $json['result'] = "400";
    $json['message'] = "아이디를 입력해주세요.";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($pw == null) {
    $json['result'] = "400";
    $json['message'] = "비밀번호를 입력해주세요.";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$result = checkId($id);

if($result->num_rows <= 0){
    $json['result'] = "500";
    $json['message'] = "서버 오류";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}else{
    $row = mysqli_fetch_array($result);
    if($row[0] != 0){
        $json['result'] = "400";
        $json['message'] = "이미 사용중인 아이디입니다.";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$sql = "INSERT INTO cempus_users (USER_ID, USER_PW) VALUES ('$id', '$pw')";
$result = mysqli_query(getConnection(), $sql);

if ($result) {
    $json['result'] = "200";
    $json['message'] = "회원가입 성공";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
} else {
    $json['result'] = "500";
    $json['message'] = "서버 오류";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}

?>