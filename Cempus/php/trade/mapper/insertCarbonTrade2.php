<?php

include_once '../common.php';

function insertCarbonTrade($cash, $carbon, $carbonTradeType, $userIdx) {
    // $cash 단가
    // $carbon 수량
    // $carbonTradeType B:구매, A:판매
    // $userIdx 유저 인덱스

    $sql = "INSERT INTO cempus_carbon_trades (
                USER_IDX, 
                CARBON_TRADE_TYPE, 
                CASH, 
                CARBON, 
                PAID_CARBON,
                DELETE_YN, 
                COMPLETE_YN
            ) VALUES (
                '$userIdx',
                '$carbonTradeType',
                '$cash',
                '$carbon',
                '0',
                'N',
                'N'
            )
            ";
    $result = mysqli_query(getConnection(), $sql);


    // sql 에러
    if (!$result) {
        $data['result'] = "500";
        $data['sql'] = $sql;
        $data['message'] = "쿼리 에러";
        return $data;
        exit;
    }

    // 결과 정리 및 리턴
    return true;
}
?>

