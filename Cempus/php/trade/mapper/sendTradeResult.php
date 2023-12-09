<?php

include_once '../common.php';

function sendTradeResult($buyer, $seller, $quantity, $price) {

    echo 'why?';
    echo $buyer;
    echo $seller;
    echo $quantity;
    echo $price;

    $apiEndpoint = 'http://cempus-dlwnsgus07.koyeb.app/cert';
    $ch = curl_init($apiEndpoint);
    // POST 데이터 설정
    $postData = [
        'buyer' => $buyer,
        'seller' => $seller,
        'quantity' => $quantity,
        'price' => $price,
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

    return $json;

}

?>