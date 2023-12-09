<?php

include_once '../common.php';

function getRecentPrice($count) {
    $sql = "SELECT (SUM(CASH) / SUM(CARBON) * (-1)) PRICE, DATE(TRADE_DATE) TRADE_DATE
        FROM cempus_trades
        WHERE TRADE_TYPE = 'F'
        AND DELETE_YN = 'N'
        GROUP BY DATE(TRADE_DATE)
        ORDER BY DATE(TRADE_DATE) DESC
        LIMIT 0, $count";

    $result = mysqli_query(getConnection(), $sql);

    if (!$result) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $data['price'] = $row['PRICE'];
        $data['tradeDate'] = $row['TRADE_DATE'];
        array_push($json, $data);
    }

    return $json;    
}
?>

