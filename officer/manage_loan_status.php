<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // เริ่มต้น session

// ตรวจสอบการเข้าถึงเฉพาะผู้ที่มีบทบาท officer
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

// เชื่อมต่อฐานข้อมูล
include '../db.php';

// รับค่าค้นหาจากฟอร์ม
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query: ดึงรายการอุปกรณ์พร้อมสถานะและ loan duration
$sql = "SELECT 
            e.id AS equipment_id, 
            e.title, 
            c.name AS category, 
            s.name AS status, 
            l.loan_date, 
            l.return_date, 
            DATEDIFF(l.return_date, l.loan_date) AS loan_duration
        FROM equipment e
        LEFT JOIN categories c ON e.category_id = c.id
        LEFT JOIN statuses s ON e.status_id = s.id
        LEFT JOIN loans l ON e.id = l.equipment_id
        WHERE e.title LIKE :search OR e.id LIKE :search
        ORDER BY e.id ASC";
$stmt = $db->prepare($sql);
$search_param = '%' . $search . '%';
$stmt->bindParam(':search', $search_param);
$stmt->execute();
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Loan Status</title>
    <style>
        /* รีเซ็ตสไตล์เริ่มต้นของเบราว์เซอร์ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ตั้งค่าพื้นหลังและฟอนต์ */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding-top: 80px; /* ให้พอดีกับแถบเมนู */
        }

        /* แถบเมนูด้านบน */
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

        /* การจัดสไตล์โลโก้และชื่อเว็บไซต์ */
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            padding-left: 20px;
        }

        /* การจัดสไตล์ลิงก์ในเมนู */
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

        /* สไตล์หัวข้อ */
        h1, h2 {
            text-align: center;
            margin-top: 40px;
            color: #fff;
        }

        /* สไตล์ตาราง */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            color: #fff;
        }

        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
        }

        /* สไตล์ฟอร์มค้นหา */
        form {
            text-align: center;
            margin: 20px 0;
        }

        input[type="text"] {
            padding: 10px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* สไตล์ลิงก์กลับไปยังแดชบอร์ด */
        a.button {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        a.button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <!-- แถบเมนู -->
    <div class="navbar">
        <div class="logo">E-Lend</div>
        <div class="menu">
            <a href="manage_profile.php">Edit Profile</a>
            <a href="manage_equipment.php">Manage Equipment Data</a>
            <a href="manage_categories.php">Manage Equipment Categories</a>
            <a href="manage_loan_status.php">Loan Status</a>
            <a href="../logout.php" class="logout-link" style="color: #ff4d4d; background-color: #333;">Logout</a>
        </div>
    </div>

    <h1>Manage Loan Status</h1>

    <h2>Search Equipment</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by ID or Title" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <h2>Equipment List</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Loan Duration (Days)</th>
        </tr>
        <?php if (!empty($equipments)): ?>
            <?php foreach ($equipments as $equipment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($equipment['equipment_id']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['title']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['category']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['status']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['loan_date'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($equipment['return_date'] ?? 'N/A'); ?></td>
                    <td>
                        <?php 
                            echo $equipment['loan_duration'] !== null 
                                ? htmlspecialchars($equipment['loan_duration']) . " days" 
                                : 'N/A'; 
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No equipment found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- ปุ่มกลับไปยังแดชบอร์ด -->
    <div style="text-align: center;">
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
