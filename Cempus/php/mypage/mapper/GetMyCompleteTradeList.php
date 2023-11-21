<?php

include_once '../common.php';

function getMyCompleteTradeList($userIdx) {
    $sql = "SELECT TRADE_TYPE, CASH, CARBON, TRADE_DATE, BOARD_IDX
        FROM cempus_trades
        WHERE USER_IDX = $userIdx
        AND TRADE_TYPE IN ('E', 'F')
        AND DELETE_YN = 'N'
        ORDER BY TRADE_DATE DESC";

    $result = mysqli_query(getConnection(), $sql);

    $row = mysqli_fetch_array($result);

    if ($row == null) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    for ($i = 0; $i < 1; $i++) {
        $row = mysqli_fetch_array($result);
        $json['myCompleteTradeList'][$i]['cash'] = $row['CASH'];
        $json['myCompleteTradeList'][$i]['carbon'] = $row['CARBON'];
        $json['myCompleteTradeList'][$i]['tradeType'] = $row['TRADE_TYPE'];
        $json['myCompleteTradeList'][$i]['tradeDate'] = $row['TRADE_DATE'];
        $json['myCompleteTradeList'][$i]['boardIdx'] = $row['BOARD_IDX'];
    }

    return $json;    
}
?>

