<?php

include_once '../common.php';
include_once "util/JwtUtil.php";

$userIdx = getUserIdx();

$dbData = getCarbonTradeList();

$json['result'] = "200";
$json['message'] = "입찰 성공";
$json['carbonTradeList'] = $dbData['carbonTradeList'];

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>