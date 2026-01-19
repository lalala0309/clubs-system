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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-[#2A53A2] px-4">
<div class="
  bg-white
  w-full
  max-w-[550px]     
  rounded-2xl
  shadow-xl
  px-6 py-20      
  flex flex-col items-center
">

    <!-- Title -->
    <h2 class="
  text-[28px]
  md:text-[32px]
  font-bold
  tracking-tight
  uppercase
  text-gray-800
  mb-3
  text-center
  whitespace-nowrap
">


  Đăng nhập hệ thống
</h2>
<img
      src="../assets/images/line.jpg"
      alt=""
      class="w-24 mb-6"
    />


    <!-- Button -->
    <a href="../auth/google-login.php" class="flex justify-center w-full">
  <button class="
    bg-[#2A53A2]
    text-white
    px-10 py-4          
    rounded-xl
    font-bold
    text-base          
    uppercase
    hover:bg-[#1f3f82]
    transition
    shadow-md
  ">
    Đăng nhập bằng Google
  </button>
</a>

  </div>
</body>


</html>
