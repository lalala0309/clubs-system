<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../config/database.php';
$config = require '../config/google.php';

$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);

$client->addScope('email');
$client->addScope('profile');

$client->setPrompt('none');


$authUrl = $client->createAuthUrl();

if (!isset($_GET['code'])) {
    header("Location: ../public/login.php");
    exit;
}

/* TOKEN */
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if (isset($token['error'])) {
    die("Google authentication failed");
}

$client->setAccessToken($token);


/* USER INFO */
$oauth = new Google_Service_Oauth2($client);
$userInfo = $oauth->userinfo->get();
$_SESSION['google_json'] = $userInfo->toSimpleObject();

$email     = $userInfo->email;
$full_name = $userInfo->name;
$google_id = $userInfo->id;

// Xem thông tin json trả về
// header('Content-Type: application/json; charset=utf-8');
// echo json_encode(
//     $userInfo->toSimpleObject(),
//     JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
// );

/* ================= DOMAIN CHECK ================= */
$allowedDomains = [
    'gmail.com',
    'student.ctu.edu.vn',
    'ctump.edu.vn'
];

$emailDomain = substr(strrchr($email, "@"), 1);

if (!in_array($emailDomain, $allowedDomains)) {
    die("Email không được phép đăng nhập hệ thống");
}
/* ================================================= */



/* CHECK USER + ROLE */
$stmt = $conn->prepare("
    SELECT u.userID, u.status, r.role_name
    FROM users u
    JOIN roles r ON u.roleID = r.roleID
    WHERE u.email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {

    // USER MỚI → MEMBER
    $roleID = 3; // MEMBER

    $stmt = $conn->prepare("
        INSERT INTO users (email, full_name, google_id, roleID)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("sssi", $email, $full_name, $google_id, $roleID);
    $stmt->execute();

    $userID = $conn->insert_id;
    $role   = 'MEMBER';

} else {

    $user = $result->fetch_assoc();

    if ($user['status'] == 0) {
        die("Tài khoản đã bị khóa");
    }

    $userID = $user['userID'];
    $role   = $user['role_name'];
}

/* SESSION */
$_SESSION['userID'] = $userID;
$_SESSION['email']  = $email;
$_SESSION['role']   = $role;

/* REDIRECT THEO QUYỀN */
if ($role === 'ADMIN') {
    header("Location: ../admin/dashboard.php");
} elseif ($role === 'MANAGER') {
    header("Location: ../manager/dashboard.php");
} else {
    header("Location: ../member/home.php");
}
exit;
?>