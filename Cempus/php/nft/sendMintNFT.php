<?php

set_time_limit(300);

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetNFTInfo.php";

// 데이터 준비
$uuid = $_POST['uuid'];
$id = $_POST['id'];
$wAdr = $_POST['wAdr'];


$apiEndpoint = 'http://cempus-dlwnsgus07.koyeb.app/mintNFT';
/**
 *
 */

// 이미지 데이터 준비
$imageDataUrl = $_POST['image'];
$imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));

// cURL 초기화
$ch = curl_init($apiEndpoint);

// 이미지 데이터를 임시 파일로 저장
$tempImageFile = tempnam(sys_get_temp_dir(), 'image');
file_put_contents($tempImageFile, $imageData);

// 이미지 파일 열기
$imageFile = new CURLFile($tempImageFile, 'image/jpeg', 'image');

// POST 데이터 설정
$postData = [
    'image' => $imageFile,
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