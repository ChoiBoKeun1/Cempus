<?php

include_once '../common.php';

function getMyAsset($userIdx) {
    $sql = "SELECT SUM(CASH) AS USER_CASH
        , SUM(CARBON) AS USER_CARBON
        , SUM(CARBON_NFT) AS USER_CARBON_NFT
        , SUM(CASH_FUNDING) AS USER_CASH_FUNDING
        FROM cempus_trades
        WHERE USER_IDX = $userIdx
        AND DELETE_YN = 'N'";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

?>