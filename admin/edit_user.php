<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch user data by ID
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found!";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = :username, role = :role WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        if ($role == 'admin' || $role == 'officer') {
            header("Location: manage_admins.php");
        } else {
            header("Location: manage_members.php");
        }
        exit;
    } else {
        echo "Error updating user!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Background and font settings */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding-top: 80px;
        }

        /* Navbar */
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

        /* Content box styling */
        .content {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
            background-color: #2c2c2c;
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            background-color: #333;
            color: #fff;
        }

        button[type="submit"] {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">E-Lend</div>
        <div class="menu">
            <a href="manage_admins.php">Admin/Officer</a>
            <a href="manage_members.php">Members</a>
            <a href="manage_loan_status.php">Status</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <!-- Content section -->
    <div class="content">
        <h2>Edit User</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="officer" <?= $user['role'] == 'officer' ? 'selected' : '' ?>>Officer</option>
                <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Member</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>
