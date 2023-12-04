<?php

include_once '../common.php';

function getMyAbleTradeList($userIdx) {
    $sql = "SELECT IDX, CARBON_TRADE_TYPE, CASH, CARBON, PAID_CARBON, CREATED_DATE
        FROM cempus_carbon_trades
        WHERE USER_IDX = $userIdx
        AND COMPLETE_YN = 'N'
        AND DELETE_YN = 'N'
        ORDER BY CREATED_DATE DESC";

    $result = mysqli_query(getConnection(), $sql);

    if (!$result) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $data['idx'] = $row['IDX'];
        $data['cash'] = $row['CASH'];
        $data['carbon'] = $row['CARBON'];
        $data['paidCarbon'] = $row['PAID_CARBON'];
        $data['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
        $data['createdDate'] = $row['CREATED_DATE'];

        array_push($json, $data);
    }

    return $json;    
}
?>

