<?php

include_once '../common.php';

function checkId($id) {
    $sql = "SELECT COUNT(*) FROM cempus_users WHERE `USER_ID` = '$id'";
    $result = mysqli_query(getConnection(), $sql);
    return $result;
}

?>