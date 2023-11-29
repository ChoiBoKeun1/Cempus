<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetAbleTradeList.php";
include_once "./mapper/UpdateCarbonTrade.php";

$userIdx = getUserIdx();

$carbonTradeType = $_POST['carbonTradeType']; // A:판매, B:구매
$allTradeYn = $_POST['allTradeYn']; // 시장가 여부
$cash = $_POST['cash']; // 금액
$carbon = $_POST['carbon']; // 탄소권
$onePrice = $cash / $carbon; // 1개당 가격

/// TODO:구매
/// 1 = 3(시장가). 지금 있는 걸로 구매가 다 됨
/// - 기존 탄소권 거래 테이블에 반영
/// 및 거래 내역 테이블에 등록
if ($carbonTradeType == "B") { // B : 구매

    if ($allTradeYn == "N") { // 지정가 구매
        // 1. 이미 존재하는 거래 중 거래 가능한 거래 있는지 확인
        $ableTradeList = getAbleTradeList($carbonTradeType, $cash, $carbon);
        // 2. 있을 경우, 거래
        $currentCash = $cash;
        
        if ($ableTradeList && count($ableTradeList)) {
            for ($i = 0; $i < count($ableTradeList); $i++) {
                $tradeIdx = $ableTradeList[$i]['idx'];
                $tradeUserIdx = $ableTradeList[$i]['userIdx'];
                $tradeCash = $ableTradeList[$i]['cash'] * $ableTradeList[$i]['carbon'];
                $tradeCarbon = $ableTradeList[$i]['carbon'];
                $tradeOnePrice = $ableTradeList[$i]['onePrice'];
                $tradeCarbonTradeType = $ableTradeList[$i]['carbonTradeType'];

                if ($tradeCash > $currentCash) { // 테이블 내 거래 내용이 현재 금액보다 클 경우

                    // 판매 거래 테이블 수정
                    $ableTradeCarbon = (int) $currentCash / $tradeOnePrice;
                    $finalTradeCash = $ableTradeCarbon * $tradeOnePrice;
                    $sql = "UPDATE cempus_carbon_trades 
                        SET PAID_CARBON = PAID_CARBON + $ableTradeCarbon
                        WHERE IDX = $tradeIdx";
                    $result = mysqli_query(getConnection(), $sql);

                    // 거래 내역 테이블 등록
                    updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                    $currentCash = 0;
                    break;

                } else {
                    $ableTradeCarbon = $tradeCarbon;
                    $finalTradeCash = $tradeCash;
                    // 판매 거래 테이블 수정(거래 완료 처리)
                    $sql = "UPDATE cempus_carbon_trades 
                        SET PAID_CARBON = $ableTradeCarbon, COMPLETE_YN = 'Y'
                        , COMPLETE_DATE = NOW()
                        WHERE IDX = $tradeIdx";
                    $result = mysqli_query(getConnection(), $sql);

                    updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                    $currentCash = $currentCash - $tradeCash;
                }
            }
                
        }

        // 3. 거래 완료 하고도 거래해야 하는 양이 남았거나, 없을 경우 구매 거래 생성
        if ($currentCash > 0) {
            $currentCarbon = $currentCash / $onePrice;
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
                'B',
                '$currentCash',
                '$currentCarbon',
                '0',
                'N',
                'N'
            )
            ";
            $result = mysqli_query(getConnection(), $sql);
        }
    } else { // 시장가 구매

        // 1. 시장가 list를 불러온다 (등록된 판매글 중에 가장 싼 순서대로)
        $marketPriceList = getMarketPriceList($carbonTradeType);

        // 2. 거래
        $currentCash = $cash;
        for ($i = 0; $i < count($marketPriceList); $i++) {
            $tradeIdx = $marketPriceList[$i]['idx'];
            $tradeUserIdx = $marketPriceList[$i]['userIdx'];
            $tradeCash = $marketPriceList[$i]['cash'] * $marketPriceList[$i]['carbon'];
            $tradeCarbon = $marketPriceList[$i]['carbon'];
            $tradeOnePrice = $marketPriceList[$i]['onePrice'];
            $tradeCarbonTradeType = $marketPriceList[$i]['carbonTradeType'];


            if ($tradeCash > $currentCash) { // 테이블 내 거래 내용이 현재 금액보다 클 경우

                // 판매 거래 테이블 수정
                $ableTradeCarbon = (int) $currentCash / $tradeOnePrice;
                $finalTradeCash = $ableTradeCarbon * $tradeOnePrice;
                $sql = "UPDATE cempus_carbon_trades
                    SET PAID_CARBON = PAID_CARBON + $ableTradeCarbon
                    WHERE IDX = $tradeIdx";
                $result = mysqli_query(getConnection(), $sql);

                // 거래 내역 테이블 등록
                updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                $currentCash = 0;
                break;

            } else {
                $ableTradeCarbon = $tradeCarbon;
                $finalTradeCash = $tradeCash;
                // 판매 거래 테이블 수정(거래 완료 처리)
                $sql = "UPDATE cempus_carbon_trades
                    SET PAID_CARBON = $ableTradeCarbon, COMPLETE_YN = 'Y'
                    , COMPLETE_DATE = NOW()
                    WHERE IDX = $tradeIdx";
                $result = mysqli_query(getConnection(), $sql);

                updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                $currentCash = $currentCash - $tradeCash;
            }
        }
    }
            
        
    

} else { // A : 판매
     if ($allTradeYn == "N") { // 지정가 판매매
         // 1. 이미 존재하는 거래 중 거래 가능한 거 있는지 확인
         $ableTradeList = getAbleTradeList($carbonTradeType, $cash, $carbon);
         // 2. 있을 경우, 거래
         $currentCash = $cash;


echo 'currentCash ::::: '.$currentCash.'<br>';
echo 'ableTradeList ::::: '.count($ableTradeList).'<br>';
         if ($ableTradeList && count($ableTradeList)) {
             for ($i = 0; $i < count($ableTradeList); $i++) {
                 $tradeIdx = $ableTradeList[$i]['idx'];
                 $tradeUserIdx = $ableTradeList[$i]['userIdx'];
                 $tradeCash = $ableTradeList[$i]['cash'] * $tradeCarbon = $ableTradeList[$i]['carbon'];
                 $tradeCarbon = $ableTradeList[$i]['carbon'];
                 $tradeOnePrice = $ableTradeList[$i]['onePrice'];
                 $tradeCarbonTradeType = $ableTradeList[$i]['carbonTradeType'];


echo 'tradeCash ::::: '.$tradeCash.'<br>';
echo 'currentCash ::::: '.$currentCash.'<br>';
                 if ($tradeCash > $currentCash) { // 테이블 내 거래 내용이 현재 금액보다 클 경우

                     // 구매 거래 테이블 수정
                        $ableTradeCarbon = (int) $currentCash / $tradeOnePrice;
                        $finalTradeCash = $ableTradeCarbon * $tradeOnePrice;
                        $sql = "UPDATE cempus_carbon_trades
                            SET PAID_CARBON = PAID_CARBON + $ableTradeCarbon
                            WHERE IDX = $tradeIdx";
                        $result = mysqli_query(getConnection(), $sql);

                        // 거래 내역 테이블 등록
                        updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                     $currentCash = 0;
                     break;

                 } else {
                     $ableTradeCarbon = $tradeCarbon;
                     $finalTradeCash = $tradeCash;
                     // 판매 거래 테이블 수정(거래 완료 처리)
                     $sql = "UPDATE cempus_carbon_trades
                         SET PAID_CARBON = $ableTradeCarbon, COMPLETE_YN = 'Y'
                         , COMPLETE_DATE = NOW()
                         WHERE IDX = $tradeIdx";
                     $result = mysqli_query(getConnection(), $sql);

                     updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                     $currentCash = $currentCash - $tradeCash;
                 }
             }

         }

         // 3. 거래 완료 하고도 거래해야 하는 양이 남았거나, 없을 경우 구매 거래 생성
         if ($currentCash > 0) {
             $currentCarbon = $currentCash / $onePrice;
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
                 'A',
                 '$currentCash',
                 '$currentCarbon',
                 '0',
                 'N',
                 'N'
             )
             ";
             $result = mysqli_query(getConnection(), $sql);
         }
     } else { // 시장가 구매

         // 1. 시장가 list를 불러온다 (등록된 판매글 중에 가장 싼 순서대로)
         $marketPriceList = getMarketPriceList($carbonTradeType);

         // 2. 거래
         $currentCash = $cash;
         for ($i = 0; $i < count($marketPriceList); $i++) {
             $tradeIdx = $marketPriceList[$i]['idx'];
             $tradeUserIdx = $marketPriceList[$i]['userIdx'];
             $tradeCash = $marketPriceList[$i]['cash'] * $tradeCarbon = $marketPriceList[$i]['carbon'];
             $tradeCarbon = $marketPriceList[$i]['carbon'];
             $tradeOnePrice = $marketPriceList[$i]['onePrice'];
             $tradeCarbonTradeType = $marketPriceList[$i]['carbonTradeType'];


             if ($tradeCash > $currentCash) { // 테이블 내 거래 내용이 현재 금액보다 클 경우

                 // 판매 거래 테이블 수정
                 $ableTradeCarbon = (int) $currentCash / $tradeOnePrice;
                 $finalTradeCash = $ableTradeCarbon * $tradeOnePrice;
                 $sql = "UPDATE cempus_carbon_trades
                     SET PAID_CARBON = PAID_CARBON + $ableTradeCarbon
                     WHERE IDX = $tradeIdx";
                 $result = mysqli_query(getConnection(), $sql);

                 // 거래 내역 테이블 등록
                 updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                 $currentCash = 0;
                 break;

             } else {
                 $ableTradeCarbon = $tradeCarbon;
                 $finalTradeCash = $tradeCash;
                 // 판매 거래 테이블 수정(거래 완료 처리)
                 $sql = "UPDATE cempus_carbon_trades
                     SET PAID_CARBON = $ableTradeCarbon, COMPLETE_YN = 'Y'
                     , COMPLETE_DATE = NOW()
                     WHERE IDX = $tradeIdx";
                 $result = mysqli_query(getConnection(), $sql);

                 updateCarbonTrade($userIdx, $finalTradeCash, $ableTradeCarbon, $tradeIdx, $tradeUserIdx);

                 $currentCash = $currentCash - $tradeCash;
             }
         }
     }
}


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