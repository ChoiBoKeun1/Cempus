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

    if (!$result || !$result2 || !$result3 || !$result4) {
        $json['result'] = "404";
        $json['message'] = "목록 없음";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }
    $row3 = mysqli_fetch_array($result3);
    $row4 = mysqli_fetch_array($result4);

    $rowCount1 = $row3[0];
    if ($rowCount1 > 2) {
        $rowCount1 = 2;
    }
    $rowCount2 = $row4[0];
    if ($rowCount2 > 2) {
        $rowCount2 = 2;
    }

    $carbonTradeList = array();
    for ($i = $rowCount1 - 1; $i >= 0; $i--) {
        $row = mysqli_fetch_array($result);
        $data['cash'] = $row['CASH'];
        $data['carbon'] = $row['CARBON'] - $row['PAID_CARBON'];
        $data['onePrice'] = $row['ONE_PRICE'];
        $data['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
        array_push($carbonTradeList, $data);
    }

    for ($i = 0; $i < $rowCount2; $i++) {
        $row2 = mysqli_fetch_array($result2);
        $data['cash'] = $row2['CASH'];
        $data['carbon'] = $row2['CARBON'] - $row2['PAID_CARBON'];
        $data['onePrice'] = $row2['ONE_PRICE'];
        $data['carbonTradeType'] = $row2['CARBON_TRADE_TYPE'];
        array_push($carbonTradeList, $data);
    }

    $json['carbonTradeList'] = $carbonTradeList;

    return $json;
}
?>

