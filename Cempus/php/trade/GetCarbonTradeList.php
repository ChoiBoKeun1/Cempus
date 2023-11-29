<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetCarbonTradeList.php";

$userIdx = getUserIdx();

$dbData = getCarbonTradeList();

$json['result'] = "200";
$json['message'] = "조회 성공";
$json['carbonTradeList'] = $dbData['carbonTradeList'];

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>