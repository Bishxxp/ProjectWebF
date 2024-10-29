<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันการจัดการข้อมูลอุปกรณ์
function addEquipment($name, $category, $status) {
    global $db;
    $sql = "INSERT INTO equipment (title, category_id, status_id) VALUES (:name, :category, :status)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':status', $status); // เพิ่ม status
    return $stmt->execute();
}

function editEquipment($id, $name, $category, $status) {
    global $db;
    $sql = "UPDATE equipment SET title = :name, category_id = :category, status_id = :status WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':status', $status); // เพิ่ม status
    return $stmt->execute();
}

function deleteEquipment($id) {
    global $db;
    $sql = "DELETE FROM equipment WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function getAllEquipments() {
    global $db;
    $sql = "SELECT e.id, e.title, c.name AS category, s.name AS status 
            FROM equipment e 
            JOIN categories c ON e.category_id = c.id
            JOIN statuses s ON e.status_id = s.id"; // ดึงสถานะด้วย
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// การจัดการคำขอจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $status = $_POST['status']; // รับค่าจากฟอร์ม status
        addEquipment($name, $category, $status);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $status = $_POST['status']; // รับค่าจากฟอร์ม status
        editEquipment($id, $name, $category, $status);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        deleteEquipment($id);
    }
}

// ดึงข้อมูลอุปกรณ์ทั้งหมด
$equipments = getAllEquipments();

// ดึงข้อมูล status ทั้งหมด
$statuses = $db->query("SELECT * FROM statuses")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Equipment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        form {
            margin-bottom: 15px;
            background-color: #2a2a2a;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
            max-width: 600px;
            margin: 0 auto;
            margin-top: 30px;
        }
        label {
            display: block;
            margin: 6px 0 2px;
            font-size: 0.9em;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 0px;
            margin-bottom: 8px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 0.9em;
            margin-top: 5px;
        }
        input[type="submit"] {
            background-color: #2196F3;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 0.9em;
        }
        input[type="submit"]:hover {
            background-color: #1E88E5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #2a2a2a;
            font-size: 0.9em;
            max-width: 600px;
            margin: 0 auto;
        }
        th, td {
            padding: 6px;
            text-align: center; /* Center align text */
            border-bottom: 1px solid #444;
        }
        th {
            background-color: #333;
        }
        tr:hover {
            background-color: #444;
        }
        a {
            color: #ff4d4d;
            text-decoration: none;
            font-size: 0.9em;
        }
        a:hover {
            text-decoration: underline;
        }
        .center-link {
            text-align: center;
            margin-top: 20px; /* เพิ่มช่องว่างด้านบน */
        }
    </style>
</head>
<body>
    <h1>Manage Equipment</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="">
        <label for="name">Equipment Name:</label>
        <input type="text" name="name" required>
        <label for="category">Category ID:</label>
        <input type="number" name="category" required>
        <label for="status">Status:</label>
        <select name="status" required>
            <?php foreach ($statuses as $status): ?>
                <option value="<?php echo $status['id']; ?>"><?php echo $status['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="add" value="Add Equipment">
    </form>
    <br><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($equipments as $equipment): ?>
        <tr>
            <td><?php echo $equipment['id']; ?></td>
            <td><?php echo $equipment['title']; ?></td>
            <td><?php echo $equipment['category']; ?></td>
            <td><?php echo $equipment['status']; ?></td>
            <td>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $equipment['id']; ?>">
                    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this equipment?');">
                </form>
                <a href="edit_equipment.php?id=<?php echo $equipment['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Link to Dashboard centered -->
    <div class="center-link">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
