<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetNFTInfo.php";

$idx = $_GET['idx'];

// 내 자산 가져오기
// 현금, 탄소권, NFT화 탄소권, 입찰금액
$row = mysqli_fetch_array(getNFTInfo($idx));

if ($row == null) {
    $json['result'] = "500";
    $json['message'] = "서버 에러";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$json['result'] = "200";
$json['message'] = "nft 변환 정보 조회 성공";
$data['carbonNFT'] = $row['CARBON_NFT'];
$data['userId'] = $row['USER_ID'];
$data['uuid'] = $row['UUID'];
$data['walletAddress'] = $row['WALLET_ADDRESS'];
$json['data'] = $data;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>