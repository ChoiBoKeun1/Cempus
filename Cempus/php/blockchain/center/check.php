<?
// request check from node
include_once '../../common.php';

$db = getConnection();
$result = true;
$data = json_decode(base64_decode($_GET['data']), true);

//ip구하기
// $sql = "select ip from member where company='".$data['company_number']."'";
// $res = $db->query($sql);
// list($ip) = $res->fetch_array();

//코드구해오기
// $check_code = file_get_contents("http://".$ip."/give.php");
$sql = "SELECT BLOCK_CODE, COUNT(BLOCK_CODE) AS BLOCK_CODE_COUNT 
    FROM cempus_users 
    GROUP BY BLOCK_CODE
    ORDER BY BLOCK_CODE_COUNT DESC
    LIMIT 1";
// $res = $db->query($sql);
// while ($row = $res->fetch_array()) {
//     unset($code);

//     $code = file_get_contents("http://".$row['ip']."/give.php");

//     if ($check_code != $code) {
//         $result = false;
//     }
// }
$result = mysqli_query(getConnection(), $sql);
$row = mysqli_fetch_array($result);
$check_code = $row['BLOCK_CODE']; // 다수결 증명

if ($check_code == $data['code']) {
    ///새로운 코드 만들기
    $new_code = hash('sha256', $data['userIdx'].'///'.$data['boardIdx'].'///'.$data['carbon'].'///'.$data['cash'].'///'.$check_code);

    //새로운 코드 배포
    $sql = "UPDATE cempus_users SET BLOCK_CODE='".$new_code."'";
    $result = mysqli_query(getConnection(), $sql);

    echo 'success';
} else {
    echo 'fail';
}



// if (!$result) {
//     //인증실패
//     echo 'fail';
// } else {
//     //인증성공
//     //새로운 코드 만들기
//     $new_code = hash('sha256', $data['company_number'].$data['contents'].$data['price'].$check_code);

//     //새로운 코드 배포
//     $sql = "select ip from member where 1";

//     $res = $db->query($sql);

//     while ($row = $res->fetch_array()) {
//         file_get_contents("http://".$row['ip']."/set.php?new_code=".$new_code."&ip=192.168.1.7");
//     }
//     echo 'success';
//} 
?>