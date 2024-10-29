<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT * FROM users WHERE role IN ('member')";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
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
            background-color: #111;
            padding: 15px;
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border-radius: 10px;
            border: 2px solid #444;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
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
            background-color: #333;
        }

        .navbar .menu a:hover {
            background-color: #555;
        }

        /* สไตล์หัวข้อ */
        h1 {
            text-align: center;
            margin-top: 40px;
            color: #fff;
        }

        /* สไตล์ตาราง */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            color: #fff;
        }

        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
        }

        /* สไตล์ปุ่มลิงก์ */
        a.button {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        a.button:hover {
            background-color: #555;
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
                <a href="manage_loan_status.php">Status</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>

    <!-- เนื้อหา: การจัดการสมาชิก -->
    <h1>Manage Members</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <a href="edit_member.php?id=<?php echo $user['id']; ?>">Edit</a> |
                <a href="delete_member.php?id=<?php echo $user['id']; ?>">Delete</a> |
                <a href="reset_member.php?id=<?php echo $user['id']; ?>">Reset Password</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <!-- ปุ่มเพิ่มสมาชิกและกลับไปยังแดชบอร์ด -->
    <div style="text-align: center;">
        <a href="add_member.php" class="button">Add Member</a>
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>

