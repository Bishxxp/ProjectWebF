<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// เพิ่มผู้ใช้ใหม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$stmt = $db->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center; /* จัดให้อยู่กลาง */
        }
        .container {
            max-width: 400px; /* กำหนดขนาดสูงสุดของคอนเทนเนอร์ */
            width: 100%; /* ทำให้คอนเทนเนอร์เต็มความกว้าง */
        }
        h2 {
            color: #00aaff;
            text-align: center;
        }
        h3 {
            margin-top: 20px;
            color: #ffffff;
        }
        form {
            background-color: #2a2a2a;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 2px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #ffffff;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #2a2a2a;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>

        <h3>Add User</h3>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <label for="role">Role:</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="officer">Officer</option>
                <option value="member">Member</option>
            </select>
            
            <input type="submit" name="add_user" value="Add User">
        </form>

        <h3>Users List</h3>
        <ul>
            <?php foreach ($users as $user): ?>
                <li><?php echo htmlspecialchars($user['username']); ?> - <?php echo htmlspecialchars($user['role']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>