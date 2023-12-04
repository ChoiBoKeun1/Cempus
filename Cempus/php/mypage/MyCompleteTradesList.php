<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetMyCompleteTradeList.php";

$userIdx = getUserIdx();

// cempus_trades에서 trade_type이 E, F인 것 조회
$dbData = getMyCompleteTradeList($userIdx);

$json['result'] = "200";
$json['message'] = "내 거래종료 목록 조회 성공";
$json['list'] = $dbData;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>