<?
// request check to center
include_once '../../common.php';

$server = "cempus.dothome.co.kr";

$db = getConnection();

$data = $_POST;

$data = base64_encode(json_encode($data));

$result = file_get_contents("http://".$server."/php/blockchain/center/check.php?data=".$data);

if ($result == 'fail') {
    echo "인증에 실패하였습니다.";
} else if ($result == 'success') {
    echo '인증에 성공하였습니다.';
}

?>