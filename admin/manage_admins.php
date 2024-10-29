<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch Admins
$sql = "SELECT * FROM users WHERE role IN ('admin')";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT * FROM users WHERE role IN ('member', 'admin', 'officer')";
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
        h1, h2 {
            text-align: center;
            margin-top: 10px;
            color: #fff;
        }

        /* สไตล์ตาราง */
        table {
            width: 35%;
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

        /* สไตล์ฟอร์มเพิ่มผู้ใช้ */
        .form-container {
            width: 35%;
            margin: 20px auto;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        a.button-blue:hover {
            background-color: #0056b3; /* สีเข้มขึ้นเมื่อ hover */
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

    <!-- ฟอร์มเพิ่ม Admin -->
    <div class="form-container">
        <h2>Add Admin/Officer</h2>
        <form method="POST" action="add_user.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="officer">Officer</option>
            </select>

            <button type="submit">Add User</button>
        
        </form>
    </div>


    <!-- หัวข้อการจัดการผู้ดูแลระบบ -->
    <h2>Manage Admins/Officer</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <?php if ($user['role'] === 'admin' || $user['role'] === 'officer'): ?>
            <tr>
                <td><?= $user['username'] ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> |
                    <a href="delete_user.php?id=<?= $user['id'] ?>">Delete</a> |
                    <a href="reset_password.php?id=<?= $user['id'] ?>">Reset Password</a>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- ปุ่มเพิ่มสมาชิกและกลับไปยังแดชบอร์ด -->
    <div style="text-align: center;">
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
