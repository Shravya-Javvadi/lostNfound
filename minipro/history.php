<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch deleted lost items posted by the user
$lost_items_query = $conn->prepare("SELECT item_name, description, image, contact_number, reg_number FROM lost_item WHERE user_id = :user_id AND is_deleted = 1");
$lost_items_query->bindParam(':user_id', $user_id);
$lost_items_query->execute();
$lost_items = $lost_items_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch deleted found items posted by the user
$found_items_query = $conn->prepare("SELECT item_name, description, image, contact_number, reg_number FROM found_item WHERE user_id = :user_id AND is_deleted = 1");
$found_items_query->bindParam(':user_id', $user_id);
$found_items_query->execute();
$found_items = $found_items_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>History</title>
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
            border-bottom: 2px solid #4CAF50;
            display: inline-block;
            padding-bottom: 10px;
        }
        .item-list {
            margin: 20px 0;
        }
        .item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            display: flex;
            align-items: center;
            animation: slideIn 0.5s ease-in-out;
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
        .item:last-child {
            border-bottom: none;
        }
        .item img {
            max-width: 100px;
            margin-right: 20px;
            border-radius: 8px;
        }
        .item-details {
            display: inline-block;
            vertical-align: middle;
        }
        .item-details h3 {
            margin: 0;
            color: #4CAF50;
        }
        .item-details p {
            margin: 5px 0;
            color: #555;
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
        <h2>History of Deleted Lost Items</h2>
        <div class="item-list">
            <?php if (!empty($lost_items)): ?>
                <?php foreach ($lost_items as $item): ?>
                    <div class="item">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image">
                        <?php endif; ?>
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                            <p>Description: <?php echo htmlspecialchars($item['description']); ?></p>
                            <p>Contact Number: <?php echo htmlspecialchars($item['contact_number']); ?></p>
                            <p>Registration Number: <?php echo htmlspecialchars($item['reg_number']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No deleted lost items found.</p>
            <?php endif; ?>
        </div>

        <h2>History of Deleted Found Items</h2>
        <div class="item-list">
            <?php if (!empty($found_items)): ?>
                <?php foreach ($found_items as $item): ?>
                    <div class="item">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image">
                        <?php endif; ?>
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                            <p>Description: <?php echo htmlspecialchars($item['description']); ?></p>
                            <p>Contact Number: <?php echo htmlspecialchars($item['contact_number']); ?></p>
                            <p>Registration Number: <?php echo htmlspecialchars($item['reg_number']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No deleted found items found.</p>
            <?php endif; ?>
        </div>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
