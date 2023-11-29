<?php

include_once '../common.php';

function getAbleTradeList($tradeType, $cash, $carbon) {
    $tradePrice = $cash / $carbon;
    if ($tradeType == 'B') { // B : 구매 
        $sql = "SELECT CASH AS ONE_PRICE
            , CARBON - PAID_CARBON AS CARBON
            , CASH

            , IDX
            , USER_IDX
            , CARBON_TRADE_TYPE
        FROM cempus_carbon_trades 
        WHERE CARBON_TRADE_TYPE = 'A' AND DELETE_YN = 'N' AND COMPLETE_YN = 'N'
        AND $tradePrice >= CASH
        ORDER BY ONE_PRICE ASC";

        $result = mysqli_query(getConnection(), $sql);


        if (!$result) {
            $json['result'] = "500";
            $json['sql'] = $sql;
            $json['message'] = "서버 에러";
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }


        $carbonTradeList = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result -> fetch_assoc();
            $data['idx'] = $row['IDX'];
            $data['userIdx'] = $row['USER_IDX'];
            $data['cash'] = $row['CASH'];
            $data['carbon'] = $row['CARBON'];
            $data['onePrice'] = $row['ONE_PRICE'];
            $data['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
            array_push($carbonTradeList, $data);
        }



        return $carbonTradeList;

    } else { //if ($tradeType == 'A') { // A : 판매 TODO 코파일럿재생성
        $sql = "SELECT CASH AS ONE_PRICE
            , CARBON - PAID_CARBON AS CARBON
            , CASH

            , IDX
            , USER_IDX
            , CARBON_TRADE_TYPE
        FROM cempus_carbon_trades
        WHERE CARBON_TRADE_TYPE = 'B' AND DELETE_YN = 'N' AND COMPLETE_YN = 'N'
        AND $tradePrice <= CASH
        ORDER BY ONE_PRICE ASC";

        $result = mysqli_query(getConnection(), $sql);

        if (!$result) {
            $json['result'] = "500";
            $json['sql'] = $sql;
            $json['message'] = "서버 에러";
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $carbonTradeList = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result -> fetch_assoc();
            $data['idx'] = $row['IDX'];
            $data['userIdx'] = $row['USER_IDX'];
            $data['cash'] = $row['CASH'];
            $data['carbon'] = $row['CARBON'];
            $data['onePrice'] = $row['ONE_PRICE'];
            $data['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];

            array_push($carbonTradeList, $data);
        }

        return $carbonTradeList;
    }

}
?>

