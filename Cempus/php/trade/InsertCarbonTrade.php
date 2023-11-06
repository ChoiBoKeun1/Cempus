<?php

include_once '../common.php';
include_once "util/JwtUtil.php";

$userIdx = getUserIdx();

$carbonTradeType = $_POST['carbonTradeType'];
$allTradeYn = $_POST['allTradeYn'];
$cash = $_POST['cash'];
$carbon = $_POST['carbon'];

/// TODO:구매
/// 1 = 3(시장가). 지금 있는 걸로 구매가 다 됨
/// - 기존 탄소권 거래 테이블에 반영
/// 및 거래 내역 테이블에 등록


$sql = "INSERT INTO cempus_carbon_trades (
            USER_IDX, 
            CARBON_TRADE_TYPE, 
            CASH, 
            CARBON, 
            PAID_CARBON,
            DELETE_YN, 
            COMPLETE_YN, 
        ) VALUES (
            '$userIdx',
            'B',
            '$cash',
            '$carbon',
            '$carbon',
            'N',
            'N',
        )
        ";
$result = mysqli_query(getConnection(), $sql);
$row = mysqli_fetch_array($result);

$boardIdx = $row['IDX'];

$sql2 = "INSERT INTO cempus_trades (
            USER_IDX, 
            TRADE_TYPE, 
            CASH, 
            CASH_FUNDING,
            CARBON,
            BOARD_IDX
        ) VALUES (
            '$userIdx',
            'E',
            '-$cash',
            '$cash'
            '$carbon',
            '$boardIdx'
        )
        ";
$result2 = mysqli_query(getConnection(), $sql2);

/// 2. 지금 있는 걸로 구매가 부분 됨
///  1번도 하고
///  새로운 탄소권 거래 내역 생성
///
///UUID에 거래번호로 적용 필요


/// TODO:판매
/// 1 = 3(시장가). 지금 있는 걸로 구매가 다 됨
/// - 기존 탄소권 거래 테이블에 반영
/// 및 거래 내역 테이블에 등록
/// 2. 지금 있는 걸로 구매가 부분 됨
///  1번도 하고
///  새로운 탄소권 거래 내역 생성


$json['result'] = "200";
$json['message'] = "입찰 성공";


echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>