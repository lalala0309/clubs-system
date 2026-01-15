<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

// $er = "";
// if (isset($_GET['rs'])){
//     $er = "Đăng nhập không thành công. Vui lòng kiểm tra thông tin đã nhập!!!";
// }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hệ thống đăng ký câu lạc bộ thể thao và đặt sân</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="item">
<table width="600" cellpadding="1" cellspacing="0" align="center" height="90%">
<tr>
        <td>
            <div class="item1">
            <h1>ĐĂNG NHẬP HỆ THỐNG </br></h1>
            <img src="../assets/images/line.jpg" width="100px" /></br>
                                <label style="font-weight:bold; color:red;"></label></a>
            <a href="../auth/google-login.php" >
                <button align="center" class="btn-primary">Đăng nhập bằng Google</button>
            </a>
            </div>
</td>
</tr>
</table>
</body>
</html>
