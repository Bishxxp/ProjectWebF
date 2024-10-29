<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันจัดการหมวดหมู่
function addCategory($name) {
    global $db;
    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
}

function editCategory($id, $name) {
    global $db;
    $sql = "UPDATE categories SET name = :name WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
}

function deleteCategory($id) {
    global $db;
    $sql = "DELETE FROM categories WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function getAllCategories() {
    global $db;
    $sql = "SELECT * FROM categories";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// จัดการคำขอจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        addCategory($name);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        editCategory($id, $name);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        deleteCategory($id);
    }
}

// ดึงข้อมูลหมวดหมู่ทั้งหมด
$categories = getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
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
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9em;
        }
        input[type="text"] {
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
            padding: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 0.9em;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            background-color: #2a2a2a;
            color: #ffffff;
            font-size: 0.9em;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        th {
            background-color: #333;
        }
        tr:hover {
            background-color: #444;
        }
        a {
            color: #2196F3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h1>Manage Categories</h1>

    <!-- ฟอร์มเพิ่มหมวดหมู่ -->
    <form method="POST" action="">
        <label for="name">Category Name:</label>
        <input type="text" name="name" required>
        <input type="submit" name="add" value="Add Category">
    </form>

    <!-- ตารางแสดงข้อมูลหมวดหมู่ -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo $category['name']; ?></td>
            <td class="actions">
                <!-- ปุ่มลบหมวดหมู่ -->
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <input type="button" value="Edit" onclick="window.location.href='edit_category.php?id=<?php echo $category['id']; ?>';">
                </form>
                <!-- ลิงก์แก้ไข -->
                
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
