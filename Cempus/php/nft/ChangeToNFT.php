<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/ChangeToNFT.php";

$userIdx = getUserIdx();
$amount = $_POST['amount'];


// 캔슬
$recentIdx = changeToNFT($userIdx, $amount);

$json['result'] = "200";
$json['message'] = "NFT로 전환되었습니다.";
$json['recentIdx'] = $recentIdx;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>