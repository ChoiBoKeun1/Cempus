<?php

include_once "./util/jwt.php";

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

$sql = "SELECT * FROM user WHERE id = '$id' AND pw = '$pw'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($row == null) {
    $json['result'] = "400";
    $json['message'] = "아이디 또는 비밀번호가 일치하지 않습니다.";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$json['result'] = "200";
$json['message'] = "로그인 성공";
$json['token'] = jwt($id);

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>