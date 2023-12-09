<?php

include_once '../common.php';

function generateUUID() {
    // 16 바이트 길이의 무작위 바이트 시퀀스 생성
    $data = openssl_random_pseudo_bytes(16);

    // 4 비트와 2 비트를 설정하여 UUID 버전 4 지정
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // 버전 (4 비트)
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // 버전 (2 비트)

    // 16진수로 변환하여 UUID 문자열로 만들기
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

    return $uuid;
}

function changeToNFT($userIdx, $amount) {

    $uuid = generateUUID();

    $sql = "INSERT INTO cempus_trades (USER_IDX, CARBON_NFT, CARBON, TRADE_TYPE, UUID) VALUES ($userIdx, $amount, (-1) * $amount, 'D', '$uuid')";

    $result = mysqli_query(getConnection(), $sql);

    if (!$result) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql2 = "SELECT MAX(IDX) AS IDX FROM cempus_trades WHERE USER_IDX = $userIdx";

    $result2 = mysqli_query(getConnection(), $sql2);

    if (!$result2) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $row = mysqli_fetch_array($result2);

    return $row['IDX'];
}
?>

