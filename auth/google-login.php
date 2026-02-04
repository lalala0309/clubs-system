<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';

session_start();

$config = require '../config/google.php';

$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $oauth = new Google_Service_Oauth2($client);
    $googleUser = $oauth->userinfo->get();

    $email = $googleUser->email;
    $google_id = $googleUser->id;
    $name = $googleUser->name;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("
            INSERT INTO users (email, full_name, google_id, roleID, status)
            VALUES (?, ?, ?, 3, 1)
        ");
        $stmt->bind_param("sss", $email, $name, $google_id);
        $stmt->execute();

        $userID = $conn->insert_id;
        $roleID = 3;
    } else {
        $user = $result->fetch_assoc();
        $userID = $user['userID'];
        $roleID = (int)$user['roleID'];
    }

    $_SESSION['user'] = [
        'userID' => $userID,
        'email'  => $email,
        'roleID' => $roleID,
        'name'   => $name
    ];

    if ($roleID === 1) {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../member/home.php");
    }
    exit;
}

$client->setPrompt('select_account');
header("Location: " . $client->createAuthUrl());
exit;
