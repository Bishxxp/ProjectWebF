<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันแก้ไขหมวดหมู่
function editCategory($id, $name) {
    global $db;
    $sql = "UPDATE categories SET name = :name WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
}

// ดึงข้อมูลหมวดหมู่ที่ต้องการแก้ไข
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    
    // เรียกใช้ฟังก์ชันเพื่อแก้ไขข้อมูลหมวดหมู่
    if (editCategory($id, $name)) {
        header("Location: manage_categories.php");
        exit();
    } else {
        echo "<p>Error updating category.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 20px;
        }
        form {
            background-color: #2a2a2a;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9em;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px; /* ปรับ padding */
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #ffffff;
            margin-bottom: 12px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 0.9em;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            color: #2196F3;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Edit Category</h1>
    <form method="POST" action="">
        <label for="name">Category Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        <input type="submit" value="Update Category">
    </form>
    <a href="manage_categories.php">Back</a>
</body>
</html>
