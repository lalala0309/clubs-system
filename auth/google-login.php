<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';
session_start();


// === Load config Google ===
$config = require '../config/google.php';


// === Cấu hình Google Client ===
$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope('email');
$client->addScope('profile');


// === Nếu GG trả về code
if (isset($_GET['code'])) {

    // Lấy access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Lấy thông tin user từ GG
    $oauth = new Google_Service_Oauth2($client);
    $googleUser = $oauth->userinfo->get();
    $email = $googleUser->email;
    $google_id = $googleUser->id;
    $name = $googleUser->name;

    // Kiểm tra user rong DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    // Nếu chưa tồn tại
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
        $roleID = (int) $user['roleID'];
    }

    // Tạo SESSION
    $_SESSION['user'] = [
        'userID' => $userID,
        'email' => $email,
        'roleID' => $roleID,
        'name' => $name
    ];


    // Redirect theo role 
    if ($roleID == 3) {
        // Member
        header("Location: ../member/home.php");
    } else {
        // Admin và Manager đều vào manager/home
        header("Location: ../manager/home.php");
    }
    exit;
}


$client->setPrompt('select_account');
header("Location: " . $client->createAuthUrl());
exit;
