<?php

function checkId($id) {
    $sql = "SELECT COUNT(*) FROM CTB_USER WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

?>