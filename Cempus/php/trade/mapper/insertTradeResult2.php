<?php

include_once '../common.php';

function sendTradeResult($buyer, $seller, $quantity, $price) {

    $apiEndpoint = 'http://cempus-dlwnsgus07.koyeb.app/cert';
    $ch = curl_init($apiEndpoint);
    // POST 데이터 설정
    $postData = array(
        'buyer' => $buyer,
        'seller' => $seller,
        'quantity' => $quantity,
        'price' => $price
    );

    $jsonData = json_encode($postData);

// cURL 초기화
    $ch = curl_init($apiEndpoint);

// 옵션 설정
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
    ));

// 실행 및 결과 얻기
    $response = curl_exec($ch);

// cURL 세션 종료
    curl_close($ch);

// 결과 출력 (이 부분을 실제로는 필요에 맞게 처리하셔야 합니다)
    return $response;

}

function insertTradeResult($tradeIdx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType) {
    // $tradeIdx : 거래 번호
    // $paidCarbon : 거래할 수량

    $tradeType = $carbonTradeType == 'A' ? 'F' : 'E';
    $tradeCash = $carbonTradeType == 'A' ? $cash * $paidCarbon * -1 : $cash * $paidCarbon;
    $tradeCarbon = $carbonTradeType == 'A' ? $paidCarbon : $paidCarbon * -1;
    $sql = "INSERT INTO cempus_trades
        (USER_IDX, TRADE_TYPE, CASH, CARBON, BOARD_IDX)
        VALUES ($userIdx, '$tradeType', $tradeCash, $tradeCarbon, $tradeIdx)";

    $result = mysqli_query(getConnection(), $sql);

    $tradeType2 = $carbonTradeType == 'A' ? 'E' : 'F';
    $tradeCash2 = $carbonTradeType == 'A' ? $cash * $paidCarbon : $cash * $paidCarbon * -1;
    $tradeCarbon2 = $carbonTradeType == 'A' ? $paidCarbon * -1 : $paidCarbon;
    $sql2 = "INSERT INTO cempus_trades
        (USER_IDX, TRADE_TYPE, CASH, CARBON, BOARD_IDX)
        VALUES ($tradeUserIdx, '$tradeType2', $tradeCash2, $tradeCarbon2, $tradeIdx)";

    $result2 = mysqli_query(getConnection(), $sql2);


    $buyer = $carbonTradeType == 'A' ? $userIdx : $tradeUserIdx;
    $seller = $carbonTradeType == 'A' ? $tradeUserIdx : $userIdx;

    $sql3 = "SELECT * FROM cempus_users WHERE IDX = $buyer";
    $result3 = mysqli_query(getConnection(), $sql3);
    $row3 = mysqli_fetch_assoc($result3);
    $buyerWAdr = $row3['WALLET_ADDRESS'];

    $sql4 = "SELECT * FROM cempus_users WHERE IDX = $seller";
    $result4 = mysqli_query(getConnection(), $sql4);
    $row4 = mysqli_fetch_assoc($result4);
    $sellerWAdr = $row4['WALLET_ADDRESS'];

    // sql 에러
    if (!$result || !$result2) {
        $json['result'] = "500";
        $json['sql'] = $sql;
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    return sendTradeResult($buyerWAdr, $sellerWAdr, $paidCarbon, $cash);
}
?>

