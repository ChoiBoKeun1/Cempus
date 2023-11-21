<?
// give to center code
include_once '../../common.php';
include_once '../../user/util/JwtUtil.php';

$userIdx = getUserIdx();

$sql = "SELECT BLOCK_CODE FROM cempus_users WHERE USER_ID = $userIdx";

$result = mysqli_query(getConnection(), $sql);

$row = mysqli_fetch_array($result);

$code = $row['BLOCK_CODE'];

echo $code;

?>