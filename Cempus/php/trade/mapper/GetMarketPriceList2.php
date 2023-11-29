<?php

include_once '../common.php';

function getMarketPriceList($tradeType, $cash) {
    // $tradeType : A(판매), B(구매)
    // $cash : 구매자가 사려고 하는 단가
    $sql = '';
    if ($tradeType == 'B') { // B : 구매
        // 판매를 원하는 목록 중 지정한 가격보다 낮은 가격의 것들을 가져옴
        $sql = "SELECT IDX
            , CASH
            , CARBON - PAID_CARBON AS TRADE_ABLE_CARBON
            , USER_IDX
            , CARBON_TRADE_TYPE
        FROM cempus_carbon_trades 
        WHERE CARBON_TRADE_TYPE = 'A' AND DELETE_YN = 'N' AND COMPLETE_YN = 'N'
        ORDER BY CASH ASC";
    } else if ($tradeType == 'A') { // A : 판매
        // 구매를 원하는 목록 중 지정한 가격보다 높은 가격의 것들을 가져옴
        $sql = "SELECT IDX
            , CASH
            , CARBON - PAID_CARBON AS TRADE_ABLE_CARBON
            , USER_IDX
            , CARBON_TRADE_TYPE
        FROM cempus_carbon_trades
        WHERE CARBON_TRADE_TYPE = 'B' AND DELETE_YN = 'N' AND COMPLETE_YN = 'N'
        ORDER BY CASH DESC";
    }

    $result = mysqli_query(getConnection(), $sql);

    // sql 에러
    if (!$result) {
        $json['result'] = "500";
        $json['sql'] = $sql;
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 결과 정리 및 리턴
    $carbonTradeList = array();
    for ($i = 0; $i < $result->num_rows; $i++) {
        $row = $result -> fetch_assoc();
        $data['idx'] = $row['IDX'];
        $data['cash'] = $row['CASH'];
        $data['tradeAbleCarbon'] = $row['TRADE_ABLE_CARBON'];
        $data['userIdx'] = $row['USER_IDX'];
        $data['carbonTradeType'] = $row['CARBON_TRADE_TYPE'];
        array_push($carbonTradeList, $data);
    }

    return $carbonTradeList;
}
?>

