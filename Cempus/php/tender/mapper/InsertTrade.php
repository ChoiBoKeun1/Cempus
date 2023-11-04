<?php

include_once '../common.php';

function insertTrade($userIdx, $cashFunding) {
    $sql = "
        INSERT INTO cempus_trades
        (
            USER_IDX,
            TRADE_TYPE,
            CASH,
            CASH_FUNDING
        ) VALUES (
            '$userIdx',
            'B',
            '-$cashFunding',
            '$cashFunding'
        )
        ";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

?>