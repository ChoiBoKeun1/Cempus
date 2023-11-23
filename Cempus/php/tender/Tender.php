<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "mapper/InsertTrade.php";

$userIdx = getUserIdx();

$cashFunding = $_POST['cashFunding'];

if ($cashFunding == null || $cashFunding == "" || $cashFunding == 0) {
    $json['result'] = "400";
    $json['message'] = "입찰 금액을 입력해주세요.";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$result = insertTrade($userIdx, $cashFunding);

if (!$result) {
    $json['result'] = "500";
    $json['message'] = "서버 에러";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$json['result'] = "200";
$json['message'] = "입찰 성공";

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>