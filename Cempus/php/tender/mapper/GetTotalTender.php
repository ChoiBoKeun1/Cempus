<?php

include_once '../common.php';

function getTotalTender() {
    $sql = "SELECT SUM(`CASH_FUNDING`) FROM cempus_trades";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

function getYesterdayTotalTender() {
    $sql = "SELECT SUM(`CASH_FUNDING`) FROM cempus_trades WHERE DATE(`TRADE_DATE`) <= DATE(DATE_ADD(CURDATE(), INTERVAL -1 DAY))";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

?>