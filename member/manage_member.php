<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'member') {
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
    <title>Manage Member</title>
    <style>
        /* รีเซ็ตสไตล์เริ่มต้น */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* สไตล์พื้นหลังและฟอนต์ */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding: 20px;
        }

        /* สไตล์ของตาราง */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #2a2a2a;
            border: 1px solid #444;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #444;
            text-align: left;
            color: #fff;
        }

        table th {
            background-color: #333;
        }

        table tr:nth-child(even) {
            background-color: #3a3a3a;
        }

        /* สไตล์ของลิงก์ */
        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            background-color: #333;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #4CAF50;
            color: #fff;
        }

        /* ปุ่มย้อนกลับ */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            text-align: center;
        }

        .back-link:hover {
            background-color: #555;
        }

        /* กำหนดขนาดของกล่องเนื้อหา */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Member</h1>
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
                <a href="edit_member.php?id=<?php echo $user['id']; ?>">Edit</a>
                <a href="delete_member.php?id=<?php echo $user['id']; ?>" style="color: #f44336;">Delete</a>
                <a href="reset_member.php?id=<?php echo $user['id']; ?>">Reset Password</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>
