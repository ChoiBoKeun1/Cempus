<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetNFTInfo.php";

// 데이터 준비
$dataURL = $_POST['image'];
$uuid = $_POST['uuid'];
$id = $_POST['id'];
$wAdr = $_POST['wAdr'];

// API 엔드포인트
$apiEndpoint = 'https://cempus-dlwnsgus07.koyeb.app/mintNFT';

// POST 데이터 설정
$postData = [
    'image' => $dataURL,
    'uuid' => $uuid,
    'id' => $id,
    'wAdr' => $wAdr,
];

// cURL 초기화
$ch = curl_init($apiEndpoint);

// cURL 옵션 설정
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

// 실행 및 결과 얻기
$response = curl_exec($ch);

// cURL 세션 종료
curl_close($ch);

// 결과 출력 (이 부분을 실제로는 필요에 맞게 처리하셔야 합니다)

$json['result'] = "200";
$json['message'] = "nft 전송 성공";
$json['data'] = $response;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>