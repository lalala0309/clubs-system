<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../config/database.php';
$config = require '../config/google.php';


// === Cấu hình Google Client ===
$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);
$client->addScope('email');
$client->addScope('profile');
$client->setPrompt('select_account');
$authUrl = $client->createAuthUrl(); // Tránh tự đăng nhập tài khoản cũ


// === Kiểm tra Google có trả code về không ===
if (!isset($_GET['code'])) {
    header("Location: ../public/login.php");
    exit;
}

// === Lấy Access Token ===
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    echo "<pre>";
    print_r($_GET);
    die("Google authentication failed...");
}
$client->setAccessToken($token);


// === Lấy thông tin user từ Google ===
$oauth = new Google_Service_Oauth2($client);
$userInfo = $oauth->userinfo->get();
$_SESSION['google_json'] = $userInfo->toSimpleObject();
$email = $userInfo->email;
$full_name = $userInfo->name;
$google_id = $userInfo->id;
$avatar = null;

// Lấy avt kích thước 200px
if (!empty($userInfo->picture)) {
    $avatar = $userInfo->picture . '?sz=200';
}


// === Kiểm tra domain email ===
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
// == Kiểm tra user trong Database
$stmt = $conn->prepare("
    SELECT u.userID, u.status, r.role_name
    FROM users u
    JOIN roles r ON u.roleID = r.roleID
    WHERE u.email = ?
    LIMIT 1
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $roleID = 3; // MEMBER mặc định
    $role = 'MEMBER';

    // Nếu là email sinh viên thì cắt MSSV
    $student_code = strstr($email, '@', true);
    $stmt = $conn->prepare("
        INSERT INTO users (email, full_name, google_id, roleID, avatar_url, student_code)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssiss", $email, $full_name, $google_id, $roleID, $avatar, $student_code);
    $stmt->execute();

    $userID = $conn->insert_id;
} else {

    // User đã tồn tại
    $user = $result->fetch_assoc();

    if ($user['status'] == 0) {
        die("Tài khoản đã bị khóa");
    }

    $userID = $user['userID'];
    $role = $user['role_name'];
    $student_code = null;

    if ($role === 'MEMBER') {
        $student_code = strstr($email, '@', true);

        $updateCode = $conn->prepare("
            UPDATE users SET student_code = ?
            WHERE userID = ?
        ");
        $updateCode->bind_param("si", $student_code, $userID);
        $updateCode->execute();
    }
    // Cập nhật avata
    $update = $conn->prepare("
        UPDATE users 
        SET avatar_url = ?, full_name = ?, google_id = ?
        WHERE userID = ?
    ");
    $update->bind_param("sssi", $avatar, $full_name, $google_id, $userID);
    $update->execute();
}


// === Tạo SESSION ===
$_SESSION['userID'] = $userID;
$_SESSION['email'] = $email;
$_SESSION['role'] = $role;
$_SESSION['avatar'] = $avatar;
$_SESSION['full_name'] = $full_name;



// === REDIRECT theo quyền của người dùng ===
if ($role === 'ADMIN') {
    header("Location: ../manager/home.php");
} elseif ($role === 'MANAGER') {
    header("Location: ../manager/home.php");
} else {
    header("Location: ../member/home.php");
}
exit;
?>