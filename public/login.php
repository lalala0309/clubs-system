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
  <button type="button" class="
    flex items-center 
    w-full 
    max-w-[400px] 
    bg-[#2A53A2] 
    border border-[#2A53A2]
    rounded-lg
    p-[1px]
    hover:bg-[#1f3f82] 
    transition-all 
    duration-200
    overflow-hidden
  ">
    <div class="bg-white p-2.5 rounded-l-md flex items-center justify-center">
      <img 
        src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" 
        alt="Google Logo" 
        class="w-5 h-5"
      />
    </div>
    
    <span class="
      flex-grow
      text-white
      text-[15px] 
      font-bold
      uppercase
      tracking-wider
      text-center
      pr-6
    ">
      Continue with Google
    </span>
  </button>
</a>
  </button>
</a>


  </button>
</a>

  </div>
</body>


</html>