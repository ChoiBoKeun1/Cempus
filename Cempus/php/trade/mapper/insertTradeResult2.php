<?php

include_once '../common.php';

function insertTradeResult($tradeIdx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType) {
    // $tradeIdx : 거래 번호
    // $paidCarbon : 거래할 수량

    $tradeType = $carbonTradeType == 'A' ? 'F' : 'E';
    $tradeCash = $carbonTradeType == 'A' ? $cash * $paidCarbon : $cash * $paidCarbon * -1;
    $tradeCarbon = $carbonTradeType == 'A' ? $paidCarbon * -1 : $paidCarbon;
    $sql = "INSERT INTO cempus_trades
        (USER_IDX, TRADE_TYPE, CASH, CARBON, BOARD_IDX)
        VALUES ($userIdx, '$tradeType', $tradeCash, $tradeCarbon, $tradeIdx)";

    $result = mysqli_query(getConnection(), $sql);

    $tradeType2 = $carbonTradeType == 'A' ? 'E' : 'F';
    $tradeCash2 = $carbonTradeType == 'A' ? $cash * $paidCarbon * -1 : $cash * $paidCarbon;
    $tradeCarbon2 = $carbonTradeType == 'A' ? $paidCarbon : $paidCarbon * -1;
    $sql2 = "INSERT INTO cempus_trades
        (USER_IDX, TRADE_TYPE, CASH, CARBON, BOARD_IDX)
        VALUES ($tradeUserIdx, '$tradeType2', $tradeCash2, $tradeCarbon2, $tradeIdx)";

    $result2 = mysqli_query(getConnection(), $sql2);

    // sql 에러
    if (!$result || !$result2) {
        $json['result'] = "500";
        $json['sql'] = $sql;
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 결과 정리 및 리턴
    return true;
}
?>

