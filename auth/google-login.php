<?php
require_once '../vendor/autoload.php';

$config = require '../config/google.php';

$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope('email');
$client->addScope('profile');

//BẮT BUỘC chọn lại tài khoản + hiện màn hình consent
$client->setPrompt('consent select_account');

header("Location: " . $client->createAuthUrl());
exit;
?>