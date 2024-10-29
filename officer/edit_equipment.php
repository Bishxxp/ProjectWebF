<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันการแก้ไขอุปกรณ์
function editEquipment($id, $name, $category, $status) {
    global $db;
    $sql = "UPDATE equipment SET title = :name, category_id = :category, status_id = :status WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':status', $status);
    return $stmt->execute();
}

// ดึงข้อมูลอุปกรณ์ที่ต้องการแก้ไข
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM equipment WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $equipment = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ดึงข้อมูล status ทั้งหมด
$statuses = $db->query("SELECT * FROM statuses")->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    
    // เรียกใช้ฟังก์ชันเพื่อแก้ไขข้อมูลอุปกรณ์
    if (editEquipment($id, $name, $category, $status)) {
        header("Location: manage_equipment.php");
        exit();
    } else {
        echo "<p>Error updating equipment.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
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
            color: #ffffff;
            margin-bottom: 20px;
        }
        form {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9em;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 0px;
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
            background-color: #0056b3;
        }
        a {
            color: #2196F3;
            text-decoration: none;
            margin-top: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Edit Equipment</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $equipment['id']; ?>">
        <label for="name">Equipment Name:</label>
        <input type="text" name="name" value="<?php echo $equipment['title']; ?>" required>
        
        <label for="category">Category ID:</label>
        <input type="number" name="category" value="<?php echo $equipment['category_id']; ?>" required>
        
        <label for="status">Status:</label>
        <select name="status" required>
            <?php foreach ($statuses as $status): ?>
                <option value="<?php echo $status['id']; ?>" <?php if ($status['id'] == $equipment['status_id']) echo 'selected'; ?>>
                    <?php echo $status['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input type="submit" value="Update Equipment">
    </form>
    <a href="manage_equipment.php">Back</a>
</body>
</html>
