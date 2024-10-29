<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'officer') {
    header("Location: ../login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT * FROM users WHERE role = 'officer'";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Officers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding-top: 80px;
        }
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
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            padding-left: 20px;
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
        .content {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: #2c2c2c;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #333;
            color: #fff;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: center;
        }
        th {
            background-color: #444;
        }
        tr:hover {
            background-color: #555;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            color: #0056b3;
        }
        .back-link {
            display: block;
            margin: 20px auto;
            text-align: center;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Content for Manage Officers -->
    <div class="content">
        <h1>Manage Officers</h1>
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
                    <a href="edit_profile.php?id=<?php echo $user['id']; ?>">Edit</a> |
                    <a href="delete_profile.php?id=<?php echo $user['id']; ?>">Delete</a> |
                    <a href="reset_profile.php?id=<?php echo $user['id']; ?>">Reset Password</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
