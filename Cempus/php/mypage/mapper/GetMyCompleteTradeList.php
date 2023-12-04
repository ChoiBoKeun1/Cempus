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

    if (!$result) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $data['cash'] = $row['CASH'];
        $data['carbon'] = $row['CARBON'];
        $data['tradeType'] = $row['TRADE_TYPE'];
        $data['tradeDate'] = $row['TRADE_DATE'];
        $data['boardIdx'] = $row['BOARD_IDX'];
        array_push($json, $data);
    }

    return $json;    
}
?>

