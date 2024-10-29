<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ดึงประวัติการยืมของสมาชิกคนปัจจุบัน
$user_id = $_SESSION['user_id'];
$sql = "SELECT e.title, l.loan_date, l.return_date, l.status 
        FROM loans l
        JOIN equipment e ON l.equipment_id = e.id
        WHERE l.user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$borrow_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow History</title>
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

        /* ลิงก์ยกเลิก */
        .cancel-btn {
            display: block;
            margin: 20px auto;
            text-align: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            width: 150px;
        }

        .cancel-btn:hover {
            background-color: #555;
        }

        /* กล่องเนื้อหา */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* จัดให้ปุ่ม Cancel อยู่ตรงกลาง */
        .btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Your Borrowing History</h1>

    <!-- ตารางประวัติการยืม -->
    <table>
        <tr>
            <th>Equipment Title</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Status</th>
        </tr>
        <?php if (!empty($borrow_history)): ?>
            <?php foreach ($borrow_history as $history): ?>
            <tr>
                <td><?php echo htmlspecialchars($history['title']); ?></td>
                <td><?php echo htmlspecialchars($history['loan_date']); ?></td>
                <td><?php echo $history['return_date'] ? htmlspecialchars($history['return_date']) : 'Not returned'; ?></td>
                <td><?php echo htmlspecialchars($history['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No borrowing history found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- ปุ่มยกเลิกกลับไปหน้าแดชบอร์ด -->
    <div class="btn-wrapper">
        <a href="dashboard.php?from=dashboard_member" class="cancel-btn">Cancel</a>
    </div>
</div>

</body>
</html>
