<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'member') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = :username, role = :role WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: manage_member.php?from=manage_member");
        exit;
    } else {
        echo "Error updating user!";
    }
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* สไตล์ฟอร์ม */
        form {
            max-width: 500px;
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

        input[type="text"], select {
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
    <h1>Edit Profile</h1>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="role">Role:</label>
        <select name="role">
            <option value="member" <?php echo $user['role'] == 'member' ? 'selected' : ''; ?>>Member</option>
        </select>

        <button type="submit">Update</button>
        <div class="btn-wrapper">
            <a href="manage_member.php?from=manage_member" class="cancel-btn">Cancel</a>
        </div>
    </form>

    <!-- ปุ่ม Cancel ตรงกลาง -->
    <!--<div class="btn-wrapper">
        <a href="manage_member.php?from=manage_member" class="cancel-btn">Cancel</a>
    </div>-->

</body>
</html>
