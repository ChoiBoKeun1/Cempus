<?php

include_once '../common.php';
include_once "util/JwtUtil.php";
include_once "./mapper/GetMyAbleTradeList.php";

$userIdx = getUserIdx();

// 현재 활성화된 내 거래 목록 가져오기
// cempus_carbon_trades에서 complete_YN = 'N'인 내 거래 목록 가져오기
$dbData = getMyAbleTradeList($userIdx);

$json['result'] = "200";
$json['message'] = "내 거래중 목록 조회 성공";
$json['myAbleTradeList'] = $dbData['myAbleTradeList'];


echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>