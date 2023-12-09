<?php

include_once '../common.php';
include_once "../user/util/JwtUtil.php";
include_once "./mapper/GetAbleTradeList2.php";
include_once "./mapper/insertCarbonTrade2.php";
include_once "./mapper/GetMarketPriceList2.php";
include_once "./mapper/UpdateCarbonTrade2.php";
include_once "./mapper/insertTradeResult2.php";

$userIdx = getUserIdx();

$carbonTradeType = $_POST['carbonTradeType']; // A:판매, B:구매
$allTradeYn = $_POST['allTradeYn']; // 시장가 여부
$cash = $_POST['cash']; // 단가
$carbon = $_POST['carbon']; // 탄소권 수량

$sendResult = array();

$tradeList = array();
// 먼저 지정가만
if ($allTradeYn == 'N') {
    // 지정가 거래 가능 목록 가져오기
    $ableTradeList = getAbleTradeList($carbonTradeType, $cash);

    // 해당 목록을 이용해 거래
    // 수량이 종료될 경우, 거래 종료
    // 수량이 남을 경우, 신규 거래 등록
    $currentCarbon = $carbon;
    for ($i = 0; $i < count($ableTradeList); $i++) {
        $idx = $ableTradeList[$i]['idx'];
        $cash = $ableTradeList[$i]['cash'];
        $tradeAbleCarbon = $ableTradeList[$i]['tradeAbleCarbon'];
        $tradeUserIdx = $ableTradeList[$i]['userIdx'];
        $carbonTradeType = $ableTradeList[$i]['carbonTradeType'];

        array_push($tradeList, $ableTradeList[$i]);
        // 현재 남은 거래 수량보다 현존하는 거래 수량이 더 많을 경우
        if ($currentCarbon <= $tradeAbleCarbon) {

            // 거래할 수량 설정
            $paidCarbon = $currentCarbon;
            // 남은 거래 수량 to 0
            $currentCarbon = 0;

            // 거래 내역 업데이트
            updateCarbonTrade($idx, $paidCarbon);

            // 거래 결과 내역 등록
            $result1 = insertTradeResult($idx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType);

            array_push($sendResult, $result1);
            // 거래 종료

            break;
        } else {// 남은 거래 수량이 더 많을 경우

            // 거래할 수량 설정
            $paidCarbon = $tradeAbleCarbon;
            // 남은 거래 수량에서 현존 거래 수량 빼기
            $currentCarbon = $currentCarbon - $tradeAbleCarbon;

            // 거래 내역 업데이트
            updateCarbonTrade($idx, $paidCarbon);

            // 거래 결과 내역 등록
            $result2 = insertTradeResult($idx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType);

            array_push($sendResult, $result2);
            // 거래 계속
        }
    }

    // 수량 종료 확인 후, 남았을 경우 신규 거래 등록
    if ($currentCarbon > 0) {
        insertCarbonTrade($cash, $carbon, $carbonTradeType, $userIdx);
    }

} else { // 시장가 구매

    // 1. 시장가 list를 불러온다 (등록된 판매글 중에 가장 싼 순서대로)
    $marketPriceList = getMarketPriceList($carbonTradeType);

    // 2. 거래
    // 해당 목록을 이용해 거래
    // 수량이 종료될 경우, 거래 종료
    // 수량이 남을 경우, 신규 거래 등록

    if ($carbonTradeType == 'B') {
        $currentCash = $cash;
        for ($i = 0; $i < count($marketPriceList); $i++) {
            $idx = $marketPriceList[$i]['idx'];
            $cash = $marketPriceList[$i]['cash'];
            $tradeAbleCarbon = $marketPriceList[$i]['tradeAbleCarbon'];
            $tradeUserIdx = $marketPriceList[$i]['userIdx'];
            $carbonTradeType = $marketPriceList[$i]['carbonTradeType'];

            array_push($tradeList, $marketPriceList[$i]);
            // 현재 가진 돈으로 해당 거래를 전부 살 수 있을 경우
            if ($currentCash >= $cash * $tradeAbleCarbon) {

                // 쓰는 돈 재설정
                $currentCash = $currentCash - ($cash * $tradeAbleCarbon);
                // 최대 수량 설정
                $paidCarbon = $tradeAbleCarbon;

                // 거래 내역 업데이트
                updateCarbonTrade($idx, $paidCarbon);

                // 거래 결과 내역 등록
                $result3 = insertTradeResult($idx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType);

                array_push($sendResult, $result3);
                // 거래 계속
            } else {// 이번 거래로 돈을 다 쓸경우

                // 최대 수량 설정
                $paidCarbon = floor($currentCash / $cash);

                $currentCash = $currentCash - ($cash * $paidCarbon);

                // 거래 내역 업데이트
                updateCarbonTrade($idx, $paidCarbon);

                // 거래 결과 내역 등록
                $result4 = insertTradeResult($idx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType);
                // 거래 종료

                array_push($sendResult, $result4);

                break;
            }
        }
    } else {
        $currentCarbon = $carbon;
        for ($i = 0; $i < count($marketPriceList); $i++) {
            $idx = $marketPriceList[$i]['idx'];
            $cash = $marketPriceList[$i]['cash'];
            $tradeAbleCarbon = $marketPriceList[$i]['tradeAbleCarbon'];
            $tradeUserIdx = $marketPriceList[$i]['userIdx'];
            $carbonTradeType = $marketPriceList[$i]['carbonTradeType'];

            array_push($tradeList, $marketPriceList[$i]);
            // 현재 남은 거래 수량보다 현존하는 거래 수량이 더 많을 경우
            if ($currentCarbon <= $tradeAbleCarbon) {

                // 거래할 수량 설정
                $paidCarbon = $currentCarbon;
                // 남은 거래 수량 to 0
                $currentCarbon = 0;

                // 거래 내역 업데이트
                updateCarbonTrade($idx, $paidCarbon);

                // 거래 결과 내역 등록
                $result5 = insertTradeResult($idx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType);

                array_push($sendResult, $result5);
                // 거래 종료
                break;
            } else {// 남은 거래 수량이 더 많을 경우

                // 거래할 수량 설정
                $paidCarbon = $tradeAbleCarbon;
                // 남은 거래 수량에서 현존 거래 수량 빼기
                $currentCarbon = $currentCarbon - $tradeAbleCarbon;

                // 거래 내역 업데이트
                updateCarbonTrade($idx, $paidCarbon);

                // 거래 결과 내역 등록
                $result6 = insertTradeResult($idx, $userIdx, $tradeUserIdx, $cash, $paidCarbon, $carbonTradeType);

                array_push($sendResult, $result6);
                // 거래 계속
            }
        }
    }

}

$json['result'] = "200";
$json['message'] = "입찰 성공";
$json['tradeList'] = $tradeList;
$json['sendResult'] = $sendResult;

echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>