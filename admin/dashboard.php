<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // เริ่มต้น session

// ตรวจสอบการล็อกอิน
if ($_SESSION['role'] !== 'admin')  {
    header("Location: ../login.php"); // ถ้าไม่มีการล็อกอิน ให้กลับไปที่หน้าล็อกอิน
    exit;
}

// เชื่อมต่อกับฐานข้อมูล
include '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ดึงข้อมูลจากฐานข้อมูลหรือตัวแปร session
$user_id = $_SESSION['user_id'];
$username = ''; // เริ่มต้นชื่อผู้ใช้
$role = $_SESSION['role']; // ดึงบทบาทของผู้ใช้

// ตรวจสอบข้อมูลผู้ใช้
$stmt = $db->prepare("SELECT username FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $username = $user['username']; // กำหนดชื่อผู้ใช้
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* รีเซ็ตสไตล์เริ่มต้นของเบราว์เซอร์ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ตั้งค่าพื้นหลังและฟอนต์ */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding-top: 80px; /* ให้พอดีกับแถบเมนู */
        }

        /* แถบเมนูด้านบน */
        .navbar {
            width: 95%;
            background-color: #111; /* สีเข้มขึ้น */
            padding: 15px;
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border-radius: 10px; /* ขอบมน */
            border: 2px solid #444; /* ขอบของแถบเมนู */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5); /* เงาเล็กน้อยให้ดูมีมิติ */
        }

        /* การจัดสไตล์โลโก้และชื่อเว็บไซต์ */
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            padding-left: 20px;
        }

        /* การจัดสไตล์ลิงก์ในเมนู */
        .navbar .menu {
            display: flex;
            gap: 20px;
            padding-right: 20px;
        }

        .navbar .menu a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #333; /* สีพื้นหลังให้ปุ่มดูเด่นขึ้น */
        }

        .navbar .menu a:hover {
            background-color: #555;
        }

        /* กล่องเนื้อหา */
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: #fff;
        }

    </style>
</head>
<body>
    <!-- แถบเมนู -->
    <div class="navbar">
        <div class="logo">E-Lend</div>
        <div class="menu">
                    <a href="manage_admins.php">Admin/Officer</a>
                    <a href="manage_members.php">Members</a>
                    <a href="manage_loan_status.php">Loan Status</a>
                    <a href="../logout.php" class="logout-link" style="color: #ff4d4d; background-color: #333;">Logout</a>
                </div>
    </div>

    <!-- กล่องเนื้อหา -->
    <div class="content">
        <h1>Welcome to Admin Dashboard</h1>
    </div>
</body>
</html>

