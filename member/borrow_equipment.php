<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อฐานข้อมูล

// Query: ดึงข้อมูลอุปกรณ์ที่มีสถานะ 'available'
$sql = "SELECT 
            e.id, 
            e.title, 
            c.name AS category, 
            s.name AS status 
        FROM equipment e
        LEFT JOIN categories c ON e.category_id = c.id
        LEFT JOIN statuses s ON e.status_id = s.id
        WHERE s.name = 'available'";
$stmt = $db->query($sql);
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Equipment</title>
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

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* สไตล์ตาราง */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #2a2a2a;
            border: 1px solid #444;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #444;
            text-align: left;
            color: #fff;
        }

        table th {
            background-color: #333;
        }

        table tr:nth-child(even) {
            background-color: #3a3a3a;
        }

        /* สไตล์ฟอร์ม */
        form {
            margin-top: 20px;
            padding: 20px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 5px;
            max-width: 600px;
            margin: 0 auto;
        }

        form label {
            display: block;
            margin-bottom: 10px;
        }

        form input[type="text"],
        form input[type="date"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* ลิงก์ยกเลิก */
        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #555;
        }

        /* กำหนดขนาดของกล่องเนื้อหา */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Borrow Equipment</h1>

    <!-- ตารางแสดงรายการอุปกรณ์ที่พร้อมให้ยืม -->
    <h2>Available Equipment</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
        <?php if (!empty($equipments)): ?>
            <?php foreach ($equipments as $equipment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($equipment['id']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['title']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['category']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No equipment available at the moment.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- ฟอร์มการยืมอุปกรณ์ -->
    <form method="POST" action="process_borrow.php">
        <h2>Borrow Form</h2>
        <label>Your Name:</label>
        <input type="text" name="user_name" required>

        <label>Select Equipment:</label>
        <select name="equipment_id" required>
            <?php foreach ($equipments as $equipment): ?>
                <option value="<?php echo $equipment['id']; ?>">
                    <?php echo htmlspecialchars($equipment['title']) . " (" . htmlspecialchars($equipment['category']) . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Loan Date:</label>
        <input type="date" name="loan_date" required>

        <label>Return Date:</label>
        <input type="date" name="return_date" required>

        <input type="submit" value="Borrow Equipment">
    </form>

    <a href="dashboard.php?from=dashboard_member">Cancel</a>
</div>

</body>
</html>
