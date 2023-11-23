<?php

include_once '../common.php';

function getTotalTender() {
    $sql = "SELECT IFNULL(SUM(`CASH_FUNDING`), 0) FROM cempus_trades";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

function getYesterdayTotalTender() {
    $sql = "SELECT IFNULL(SUM(`CASH_FUNDING`), 0) FROM cempus_trades WHERE DATE(`TRADE_DATE`) <= DATE(DATE_ADD(CURDATE(), INTERVAL -1 DAY))";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

?>