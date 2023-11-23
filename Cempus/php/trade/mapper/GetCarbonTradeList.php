<?php

include_once '../common.php';

function getCarbonTradeList() {
    $sql = "SELECT *, ( CASH / CARBON ) AS ONE_PRICE FROM cempus_carbon_trades WHERE CARBON_TRADE_TYPE = 'A' AND `DELETE_YN` = 'N' AND `COMPLETE_YN` = 'N' ORDER BY ONE_PRICE ASC LIMIT 0, 2";
    $result = mysqli_query(getConnection(), $sql);

    $sql2 = "SELECT *, ( CASH / CARBON ) AS ONE_PRICE FROM cempus_carbon_trades WHERE CARBON_TRADE_TYPE = 'B' AND `DELETE_YN` = 'N' AND `COMPLETE_YN` = 'N' ORDER BY ONE_PRICE DESC LIMIT 0, 2";
    $result2 = mysqli_query(getConnection(), $sql2);

    $sql3 = "SELECT COUNT(*) FROM cempus_carbon_trades WHERE CARBON_TRADE_TYPE = 'A' AND `DELETE_YN` = 'N' AND `COMPLETE_YN` = 'N'";
    $result3 = mysqli_query(getConnection(), $sql3);

    $sql4 = "SELECT COUNT(*) FROM cempus_carbon_trades WHERE CARBON_TRADE_TYPE = 'B' AND `DELETE_YN` = 'N' AND `COMPLETE_YN` = 'N'";
    $result4 = mysqli_query(getConnection(), $sql4);

    $row = mysqli_fetch_array($result);
    $row2 = mysqli_fetch_array($result2);
    $row3 = mysqli_fetch_array($result3);
    $row4 = mysqli_fetch_array($result4);

    if ($row3 == null || $row4 == null || ($row3 > 0 && $row == null) || ($row4 > 0 && $row2 == null)) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $rowCount1 = $row3[0];
    $rowCount2 = $row4[0];

    for ($i = $rowCount1 - 1; $i >= 0; $i--) {
        $row = mysqli_fetch_array($result);
        $json['carbonTradeList'][$i]['cash'] = $row['CASH'];
        $json['carbonTradeList'][$i]['carbon'] = $row['CARBON'];
        $json['carbonTradeList'][$i]['onePrice'] = $row['ONE_PRICE'];
        $json['carbonTradeList'][$i]['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
    }

    for ($i = 0; $i < $rowCount2; $i++) {
        $row2 = mysqli_fetch_array($result2);
        $json['carbonTradeList'][$i]['cash'] = $row2['CASH'];
        $json['carbonTradeList'][$i]['carbon'] = $row2['CARBON'];
        $json['carbonTradeList'][$i]['onePrice'] = $row2['ONE_PRICE'];
        $json['carbonTradeList'][$i]['carbonTradeType'] = $row2['CARBON_TRADE_TYPE'];
    }

    return $json;
}
?>

