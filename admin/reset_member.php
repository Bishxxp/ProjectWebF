<?php
session_start();
include '../db.php';

// Check login and admin access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Check if user ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST['new_password'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the database
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            header("Location: manage_members.php?from=manage_member");
            exit;
        } else {
            echo "<p class='error'>Error resetting password!</p>";
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
        /* General Styles */
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

        /* Content Styling */
        .content {
            max-width: 500px;
            margin: 100px auto 0;
            padding: 20px;
            background-color: #2c2c2c;
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="password"] {
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

        /* Back Link */
        .back-link {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: #555;
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

    <!-- Content Section -->
    <div class="content">
        <h2>Reset Password</h2>

        <!-- Password Reset Form -->
        <form method="POST" action="">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            <button type="submit">Reset Password</button>
        </form>

        <!-- Back to Dashboard Link -->
        <a href="manage_members.php" class="back-link">Back to Manage Members</a>
    </div>
</body>
</html>
