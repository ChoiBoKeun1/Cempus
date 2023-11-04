<?php

include_once '../common.php';
include_once "mapper/GetTotalTender.php";

// 토탈 금액 호출
$row = mysqli_fetch_array(getTotalTender());

// DB 호출 실패 에러
if ($row == null) {
    $json['result'] = "500";
    $json['message'] = "서버 에러";
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

// 어제자 토탈 금액 호출
$row2 = mysqli_fetch_array(getYesterdayTotalTender());

// 결과 값을 넣어줘야한다
$json['result'] = "200";
$json['message'] = "입찰 성공";
$json['totalTender'] = $row[0];
$json['yesterdayTotalTender'] = $row2[0];

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>