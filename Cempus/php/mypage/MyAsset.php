<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetMyAsset.php";

$userIdx = getUserIdx();

// 내 자산 가져오기
// 현금, 탄소권, NFT화 탄소권, 입찰금액
$row = mysqli_fetch_array(getMyAsset($userIdx));

if ($row == null) {
    $json['result'] = "500";
    $json['message'] = "서버 에러";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$json['result'] = "200";
$json['message'] = "내 자산 조회 성공";
$data['userCash'] = $row['USER_CASH'];
$data['userCarbon'] = $row['USER_CARBON'];
$data['userCarbonNft'] = $row['USER_CARBON_NFT'];
$data['userCashFunding'] = $row['USER_CASH_FUNDING'];
$json['data'] = $data;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>