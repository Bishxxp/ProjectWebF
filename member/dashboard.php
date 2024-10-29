<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php"); // ถ้าไม่ใช่สมาชิก ให้กลับไปที่หน้า login
    exit();
}
include '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
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
            align-items: center; /* จัดตำแหน่งตัวอักษรให้ตรงกลางในแนวตั้ง */
            height: 50px; /* กำหนดความสูงให้พอเหมาะ */
        }

        .navbar .menu a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px; /* ลดขนาดฟอนต์ */
            padding: 6px 10px; /* ลดการ padding */
            border-radius: 3px; /* ลดความโค้งของมุม */
            background-color: #333;
        }

        .navbar .menu a:hover {
            background-color: #555;
        }

        /* กล่องเนื้อหา */
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px); /* ปรับความสูงให้พอดี */
            text-align: center;
            color: #fff;
            padding: 20px;
        }

        /* รายละเอียดสมาชิก */
        .content h1 {
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        /* สไตล์รายการลิงก์ */
        .content ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .content ul li a {
            text-decoration: none;
            color: #fff;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .content ul li a:hover {
            background-color: #555;
        }

        /* ปุ่มออกจากระบบ */
        a.logout-link {
            display: block;
            margin: 20px auto;
            text-align: center;
            color: #ff4d4d;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            width: 150px;
        }

        a.logout-link:hover {
            background-color: #ff6666;
        }
    </style>
</head>
<body>

    <!-- แถบเมนู -->
    <div class="navbar">
        <div class="logo">E-Lend</div>
        <div class="menu">
            <a href="manage_member.php">Edit Profile</a>
            <a href="borrow_equipment.php">Borrow Equipment</a>
            <a href="borrow_member.php">Borrow History</a>
            <a href="return_equipment.php">Return Equipment</a>
            <a href="../logout.php" class="logout-link">Logout</a>
        </div>
    </div>

    <!-- กล่องเนื้อหา -->
    <div class="content">
        <div>
            <h1>Member Dashboard</h1>
            <p>Welcome, <?php echo $_SESSION['username']; ?> (Member)</p>
        </div>
    </div>

</body>
</html>
