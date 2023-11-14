<?php

include_once '../common.php';

function getAbleTradeList($tradeType, $cash, $carbon) {
    $tradePrice = $cash / $carbon;
    if ($tradeType == 'B') { // B : 구매 
        $sql = "SELECT ( CASH / CARBON ) AS ONE_PRICE
            , PAID_CARBON AS CARBON
            , ( CASH / CARBON ) * PAID_CARBON AS CASH

            , IDX
            , USER_IDX
            , CARBON_TRADE_TYPE
        FROM cempus_carbon_trades 
        WHERE CARBON_TRADE_TYPE = 'A' AND DELETE_YN = 'N' AND COMPLETE_YN = 'N'
        AND $tradePrice >= ( CASH / CARBON )
        ORDER BY ONE_PRICE ASC";

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
            $json['carbonTradeList'][$i]['idx'] = $row['IDX'];
            $json['carbonTradeList'][$i]['userIdx'] = $row['USER_IDX'];
            $json['carbonTradeList'][$i]['cash'] = $row['CASH'];
            $json['carbonTradeList'][$i]['carbon'] = $row['CARBON'];
            $json['carbonTradeList'][$i]['onePrice'] = $row['ONE_PRICE'];
            $json['carbonTradeList'][$i]['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
        }

        return $json;

    } else { //if ($tradeType == 'A') { // A : 판매 TODO 코파일럿재생성
        $sql = "SELECT *, ( CASH / CARBON ) AS ONE_PRICE  
        FROM cempus_carbon_trades 
        WHERE CARBON_TRADE_TYPE = 'B' AND DELETE_YN = 'N' AND COMPLETE_YN = 'N'
        AND $tradePrice <= ( CASH / CARBON )
        ORDER BY ONE_PRICE DESC";

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
            $json['carbonTradeList'][$i]['idx'] = $row['IDX'];
            $json['carbonTradeList'][$i]['cash'] = $row['CASH'];
            $json['carbonTradeList'][$i]['carbon'] = $row['CARBON'];
            $json['carbonTradeList'][$i]['onePrice'] = $row['ONE_PRICE'];
            $json['carbonTradeList'][$i]['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
        }

        return $json;
    }

}
?>

