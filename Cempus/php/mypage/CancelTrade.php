<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/CancelTrade.php";

$userIdx = getUserIdx();
$idx = $_POST['idx'];

// 캔슬
$result = cancelTrade($userIdx, $idx);

$json['result'] = "200";
$json['message'] = "내 거래중 목록 조회 성공";

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>