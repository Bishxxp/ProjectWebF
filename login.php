<?php
session_start(); // เริ่มเซสชัน
include 'db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบผู้ใช้ในฐานข้อมูล
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพบผู้ใช้และรหัสผ่านถูกต้อง
    if ($user && password_verify($password, $user['password'])) {
        // ถ้ารหัสผ่านถูกต้อง
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id']; // หากมี id ในตาราง

        // เปลี่ยนเส้นทางไปยังแดชบอร์ดตามบทบาท
        header("Location: " . strtolower($user['role']) . "/dashboard.php");
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง.";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #333; /* สีพื้นหลัง */
        }
        .login-container {
            width: 300px;
            padding: 20px;
            background-color: #222; /* สีพื้นหลังกล่อง */
            border: 2px solid #444; /* สีขอบ */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #fff; /* สีข้อความ */
        }
        h2 {
            margin-bottom: 20px;
            color: #ddd; /* สีหัวข้อ */
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #333;
            color: #fff; /* สีข้อความในฟิลด์ */
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #777;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="username" required>
        <input type="password" name="password" placeholder="password" required>
        <button type="submit">login</button>
    </form>
</div>

</body>
</html>
