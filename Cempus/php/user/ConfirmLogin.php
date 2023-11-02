<?php

include_once '../common.php';
include_once "util/JwtUtil.php";

$token = $_SESSION['token'];

$payload = decryptJwt($token);
$json['id'] = json_decode($payload);
$json['token'] = $token;

echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>