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

$client->setPrompt('select_account');


$authUrl = $client->createAuthUrl();

if (!isset($_GET['code'])) {
    header("Location: ../public/login.php");
    exit;
}

/* TOKEN */
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if (isset($token['error'])) {
    echo "<pre>";
    print_r($_GET);
    exit;
    die("Google authentication failed: " . $token['error'] .
        " | Description: " . ($token['error_description'] ?? 'No description'));

}

$client->setAccessToken($token);


/* USER INFO */
$oauth = new Google_Service_Oauth2($client);
$userInfo = $oauth->userinfo->get();
$_SESSION['google_json'] = $userInfo->toSimpleObject();

$email = $userInfo->email;
$full_name = $userInfo->name;
$google_id = $userInfo->id;

$avatar = null;

if (!empty($userInfo->picture)) {
    $avatar = $userInfo->picture . '?sz=200';
}

// $avatar = null;

// if (!empty($userInfo->picture)) {

//     $googleAvatar = $userInfo->picture . '?sz=200';

//     // Tạo thư mục nếu chưa tồn tại
//     $uploadDir = '../uploads/avatars/';
//     if (!is_dir($uploadDir)) {
//         mkdir($uploadDir, 0777, true);
//     }

//     // Tạo tên file duy nhất
//     $fileName = uniqid('avatar_') . '.jpg';
//     $filePath = $uploadDir . $fileName;

//     // Tải ảnh từ Google
//     $imageContent = file_get_contents($googleAvatar);

//     if ($imageContent !== false) {
//         file_put_contents($filePath, $imageContent);

//         // Lưu đường dẫn tương đối vào DB
//         $avatar = 'uploads/avatars/' . $fileName;
//     }
// }
// Set session
// $_SESSION['user'] = [
//     'id'    => md5($email), // hoặc id trong DB
//     'email' => $email,
//     'name'  => $name,
//     'role'  => 'member'
// ];

// Xem thông tin json trả về
// header('Content-Type: application/json; charset=utf-8');
// echo json_encode(
//     $userInfo->toSimpleObject(),
//     JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
// );

// Kiểm tra domain
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

/* ================================================= */
/* CHECK USER CHỈ ĐỂ LƯU AVATAR */

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
    $role = 'MEMBER'; // thêm dòng này để tránh undefined

    //Nếu là email sinh viên thì cắt MSSV

    $student_code = strstr($email, '@', true);


    $stmt = $conn->prepare("
        INSERT INTO users (email, full_name, google_id, roleID, avatar_url, student_code)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssiss", $email, $full_name, $google_id, $roleID, $avatar, $student_code);
    $stmt->execute();

    $userID = $conn->insert_id;
} else {

    $user = $result->fetch_assoc();

    if ($user['status'] == 0) {
        die("Tài khoản đã bị khóa");
    }

    $userID = $user['userID'];
    $role = $user['role_name'];   // QUAN TRỌNG
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
    // CẬP NHẬT AVATAR (luôn update cho chắc)
    $update = $conn->prepare("
        UPDATE users 
        SET avatar_url = ?, full_name = ?, google_id = ?
        WHERE userID = ?
    ");
    $update->bind_param("sssi", $avatar, $full_name, $google_id, $userID);
    $update->execute();
}
/* SESSION */
$_SESSION['userID'] = $userID;
$_SESSION['email'] = $email;
$_SESSION['role'] = $role;
$_SESSION['avatar'] = $avatar;
$_SESSION['full_name'] = $full_name;

/* REDIRECT THEO QUYỀN */
if ($role === 'ADMIN') {
    header("Location: ../admin/index.php");
} elseif ($role === 'MANAGER') {
    header("Location: ../manager/dashboard.php");
} else {
    header("Location: ../member/home.php");
}
exit;
?>