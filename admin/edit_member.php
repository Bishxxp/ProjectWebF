<?php
session_start();
include '../db.php';

// Check login and role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Update user details
    $sql = "UPDATE users SET username = :username, role = :role WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: manage_members.php?from=manage_member");
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
    <title>Edit Member</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Background and Font */
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

        h1 {
            margin-bottom: 20px;
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

        /* Cancel Link */
        .cancel-link {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .cancel-link:hover {
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
        <h1>Edit Member</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            
            <label for="role">Role:</label>
            <select name="role">
                <option value="member" <?php echo $user['role'] == 'member' ? 'selected' : ''; ?>>Member</option>
            </select>
            
            <button type="submit">Update</button>
        </form>

        <a href="manage_members.php?from=manage_member" class="cancel-link">Cancel</a>
    </div>
</body>
</html>
