<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch lost items
$lost_items_stmt = $conn->prepare("SELECT *, CASE WHEN is_deleted = 1 THEN 'completed' ELSE 'pending' END AS status FROM lost_item WHERE user_id = :user_id");
$lost_items_stmt->bindParam(':user_id', $user_id);
$lost_items_stmt->execute();
$lost_items = $lost_items_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch found items
$found_items_stmt = $conn->prepare("SELECT *, CASE WHEN is_deleted = 1 THEN 'completed' ELSE 'pending' END AS status FROM found_item WHERE user_id = :user_id");
$found_items_stmt->bindParam(':user_id', $user_id);
$found_items_stmt->execute();
$found_items = $found_items_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Item Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            animation: slideIn 1s ease-in-out;
        }
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #45a049;
        }
        .status {
            padding: 5px 10px;
            border-radius: 4px;
        }
        .pending {
            background-color: #ffeb3b;
            color: #333;
        }
        .completed {
            background-color: #4CAF50;
            color: white;
        }
        form button {
            padding: 8px 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #1976D2;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Items</h2>

        <h3>Lost Items</h3>
        <?php if ($lost_items): ?>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Contact Number</th>
                    <th>Registration Number</th>
                    <th>Image</th>
                    <th>Posted Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($lost_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($item['reg_number']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image" width="100"></td>
                        <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                        <td><span class="status <?php echo htmlspecialchars($item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></span></td>
                        <td>
                            <?php if ($item['status'] == 'pending'): ?>
                                <form method="post" action="update_status.php">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="item_type" value="lost">
                                    <button type="submit" name="status" value="received">Received</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No lost items posted yet.</p>
        <?php endif; ?>

        <h3>Found Items</h3>
        <?php if ($found_items): ?>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Contact Number</th>
                    <th>Registration Number</th>
                    <th>Image</th>
                    <th>Posted Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($found_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($item['reg_number']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image" width="100"></td>
                        <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                        <td><span class="status <?php echo htmlspecialchars($item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></span></td>
                        <td>
                            <?php if ($item['status'] == 'pending'): ?>
                                <form method="post" action="update_status.php">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="item_type" value="found">
                                    <button type="submit" name="status" value="taken">Taken</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No found items posted yet.</p>
        <?php endif; ?>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
