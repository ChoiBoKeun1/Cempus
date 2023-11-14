<?php

include_once '../common.php';

function updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx) {
    // 1. 구매자
    $sql2 = "INSERT INTO cempus_trades (
        USER_IDX, 
        TRADE_TYPE, 
        CASH, 
        CARBON,
        BOARD_IDX
    ) VALUES (
        '$userIdx',
        'E',
        '-$finalTradeCash',
        '$ableTradeCarbon',
        '$tradeIdx'
    )
    ";
    $result2 = mysqli_query(getConnection(), $sql2);

    //2. 판매자
    $sql3 = "INSERT INTO cempus_trades (
        USER_IDX, 
        TRADE_TYPE, 
        CASH, 
        CARBON,
        BOARD_IDX
    ) VALUES (
        '$tradeUserIdx',
        'F',
        '$finalTradeCash',
        '-$ableTradeCarbon',
        '$tradeIdx'
    )
    ";
    $result3 = mysqli_query(getConnection(), $sql3);
}
?>

