<?php

function jwt($id) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode(['id' => $id]);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'secret', true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function decryptJwt($token) {
    $jwt = explode('.', $token);
    $header = base64_decode($jwt[0]);
    $payload = base64_decode($jwt[1]);
    $signature = base64_decode($jwt[2]);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'secret', true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    if ($base64UrlSignature == $jwt[2]) {
        return $payload;
    } else {
        return null;
    }
}

function getUserIdx() {
    $token = $_SESSION['token'];

    if ( $token == null ) {
        $json['result'] = "400";
        $json['message'] = "로그인이 필요합니다.";
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $payload = decryptJwt($token);
    $data = json_decode($payload, true)['id'];

    return (int)$data;
}

?>