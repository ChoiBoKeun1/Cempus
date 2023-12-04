<?php

include_once '../common.php';

function cancelTrade($userIdx, $idx) {
    $sql = "UPDATE cempus_carbon_trades SET
        DELETE_YN = 'Y'
        WHERE IDX = $idx AND USER_IDX = $userIdx";

    $result = mysqli_query(getConnection(), $sql);

    if (!$result) {
        $json['result'] = "500";
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    return $result;
}
?>

