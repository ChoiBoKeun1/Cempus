<?php

include_once '../common.php';
include_once "util/JwtUtil.php";
include_once "./mapper/GetAbleTradeList.php";

$userIdx = getUserIdx();

$dbData = getAbleTradeList();

$json['result'] = "200";
$json['message'] = "입찰 성공";
$json['carbonTradeList'] = $dbData['carbonTradeList'];

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>