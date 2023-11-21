<?php

include_once '../common.php';

function getMyAbleTradeList($userIdx) {
    $sql = "SELECT CARBON_TRADE_TYPE, CASH, CARBON, PAID_CARBON, CREATED_DATE
        FROM cempus_carbon_trades
        WHERE USER_IDX = $userIdx
        AND COMPLETE_YN = 'N'
        AND DELETE_YN = 'N'
        ORDER BY CREATED_DATE DESC";

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
        $json['myAbleTradeList'][$i]['cash'] = $row['CASH'];
        $json['myAbleTradeList'][$i]['carbon'] = $row['CARBON'];
        $json['myAbleTradeList'][$i]['paidCarbon'] = $row['PAID_CARBON'];
        $json['myAbleTradeList'][$i]['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
        $json['myAbleTradeList'][$i]['createdDate'] = $row['CREATED_DATE'];
    }

    return $json;    
}
?>

