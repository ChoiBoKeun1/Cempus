<?php

include_once '../common.php';

function updateCarbonTrade($idx, $paidCarbon) {
    // $idx : 거래 번호
    // $paidCarbon : 거래할 수량

    $sql = "UPDATE cempus_carbon_trades 
        SET PAID_CARBON = PAID_CARBON + $paidCarbon 
        WHERE IDX = $idx";

    $result = mysqli_query(getConnection(), $sql);

    // sql 에러
    if (!$result) {
        $json['result'] = "500";
        $json['sql'] = $sql;
        $json['message'] = "서버 에러";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE cempus_carbon_trades 
        SET COMPLETE_YN = 'Y' 
        WHERE PAID_CARBON >= CARBON AND IDX = $idx";

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
    return true;
}
?>

