<?
// receive from center code and set code
include_once '../../common.php';
include_once '../../user/util/JwtUtil.php';

$userIdx = getUserIdx();

if (//$_GET['ip'] == '192.168.1.7' &&
 $_GET['new_code']) {
    $sql = "update cempus_users set BLOCK_CODE='".$_GET['new_code']."' where USER_ID = $userIdx";
    $result = mysqli_query(getConnection(), $sql);
}
?>