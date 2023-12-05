<?php

include_once '../common.php';
include_once "./mapper/GetRecentPrice.php";

$count = 10;
// cempus_trades에서 trade_type이 E, F인 것 조회
$dbData = getRecentPrice($count);

$json['result'] = "200";
$json['message'] = "최근 거래 결과 목록 조회 성공";
$json['list'] = $dbData;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>