<?php

include_once '../common.php';
include_once "util/JwtUtil.php";
include_once "./mapper/GetMyCompleteTradeList.php";

$userIdx = getUserIdx();

// cempus_carbon_trades에서 거래 중이 아닌 내 거래 목록 가져오기
$dbData = getMyCompleteTradeList($userIdx);

$json['result'] = "200";
$json['message'] = "내 거래종료 목록 조회 성공";
$json['myCompleteTradeList'] = $dbData['myCompleteTradeList'];

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>