<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST['new_password'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // อัปเดตรหัสผ่านในฐานข้อมูล
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            header("Location: manage_member.php?from=manage_member");
            exit;
        } else {
            echo "Error resetting password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* สไตล์ฟอร์ม */
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #333;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #444;
            color: #fff;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            width: 100%;
        }

        button:hover {
            background-color: #555;
        }

        /* ปุ่ม Cancel ตรงกลาง */
        .btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .cancel-btn {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            text-decoration: none;
        }

        .cancel-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <h2>Reset Password</h2>

    <form method="POST" action="">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>

        <button type="submit">Reset Password</button>
    </form>

    <!-- ปุ่ม Cancel ตรงกลาง -->
    <div class="btn-wrapper">
        <a href="manage_member.php?from=manage_member" class="cancel-btn">Cancel</a>
    </div>

</body>
</html>
