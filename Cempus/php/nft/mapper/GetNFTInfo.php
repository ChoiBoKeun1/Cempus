<?php

include_once '../common.php';

function getNFTInfo($idx) {
    $sql = "SELECT A.CARBON_NFT, A.UUID, B.USER_ID, B.WALLET_ADDRESS
        FROM cempus_trades A LEFT JOIN cempus_users B ON A.USER_IDX = B.IDX
        WHERE A.IDX = $idx
        AND A.DELETE_YN = 'N'";
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