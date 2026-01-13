<?php
session_start();

require_once '../vendor/autoload.php';
$config = require '../config/google.php';

// Tạo đổi tượng
$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);

// Revoke token nếu có
if (isset($_SESSION['google_access_token'])) {
    $client->setAccessToken($_SESSION['google_access_token']);
    $client->revokeToken();
}

// Xoá toàn bộ session PHP
session_unset();
session_destroy();

//Logout Google hoàn toàn
//header("Location: https://accounts.google.com/Logout");


// Quay về trang đăng nhập hệ thống
header("Location: http://localhost/clubs-system/public/login.php");
exit;
?>