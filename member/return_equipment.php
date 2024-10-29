<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อฐานข้อมูล

// Query: ดึงรายการอุปกรณ์ที่สถานะเป็น 'unavailable' และคำนวณระยะเวลาการยืม
$sql = "SELECT 
            l.id AS loan_id, 
            e.id AS equipment_id, 
            e.title, 
            c.name AS category, 
            l.loan_date, 
            l.return_date, 
            DATEDIFF(l.return_date, l.loan_date) AS loan_duration -- คำนวณระยะเวลาการยืม
        FROM loans l
        JOIN equipment e ON l.equipment_id = e.id
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE l.status = 'Unavailable'";
$stmt = $db->query($sql);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Equipment</title>
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

        /* ปุ่มยกเลิกและ return */
        .btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .back-btn, .return-btn {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-btn:hover, .return-btn:hover {
            background-color: #555;
        }

    </style>
</head>
<body>

    <h1>Return Equipment</h1>

    <!-- ตารางแสดงรายการอุปกรณ์ที่ยังไม่ถูกคืน -->
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Loan Duration (Days)</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($loans)): ?>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['title']); ?></td>
                    <td><?php echo htmlspecialchars($loan['category']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_date']); ?></td>
                    <td><?php echo htmlspecialchars($loan['return_date']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_duration']); ?> days</td>
                    <td>
                        <form method="POST" action="process_return.php">
                            <input type="hidden" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
                            <input type="hidden" name="equipment_id" value="<?php echo $loan['equipment_id']; ?>">
                            <input type="submit" value="Return" class="return-btn">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No equipment to return.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- ปุ่ม Back ตรงกลาง -->
    <div class="btn-wrapper">
        <a href="dashboard.php" class="back-btn">Back</a>
    </div>

</body>
</html>
