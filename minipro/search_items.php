<?php
session_start();
require_once 'db.php';

// Fetch lost items
$stmt_lost = $pdo->prepare("SELECT * FROM lost_item WHERE is_deleted = 0");
$stmt_lost->execute();
$lost_items = $stmt_lost->fetchAll(PDO::FETCH_ASSOC);

// Fetch found items
$stmt_found = $pdo->prepare("SELECT * FROM found_item WHERE is_deleted = 0");
$stmt_found->execute();
$found_items = $stmt_found->fetchAll(PDO::FETCH_ASSOC);

// Function to calculate expiry date (5 days after post date)
function calculate_expiry_date($post_date) {
    $post_date = new DateTime($post_date);
    $post_date->modify('+1 minute');  // Change to 1 minute for testing
    return $post_date->format('Y-m-d H:i:s');
}
$current_time = new DateTime();
foreach (['lost_item', 'found_item'] as $table) {
    $stmt = $pdo->prepare("UPDATE $table SET is_deleted = 1 WHERE post_date <= ? AND is_deleted = 0");
    $stmt->execute([$current_time->format('Y-m-d H:i:s')]);
}
// Delete item when marked as received or taken
if (isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];
    $table = $_POST['table'];
    $stmt = $pdo->prepare("UPDATE $table SET is_deleted = 1 WHERE id = ?");
    $stmt->execute([$item_id]);
    header("Location: search_items.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Items</title>
    <script>
        function updateTimers() {
            const now = new Date().getTime();
            document.querySelectorAll('.timer').forEach(timer => {
                const expiryTime = new Date(timer.getAttribute('data-expiry')).getTime();
                const remaining = expiryTime - now;
                
                if (remaining <= 0) {
                    timer.innerText = "Expired!";
                    timer.style.color = "red";
                } else {
                    const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
                    timer.innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds} remaining`;
                }
            });
        }
        setInterval(updateTimers, 1000);
        window.onload = updateTimers;
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            text-align: center;
            padding: 20px;
            background-color: #343a40;
            color: #fff;
            margin: 0;
        }
        .items {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .item h3 {
            margin-top: 0;
        }
        .item img {
            max-width: 200px;
            display: block;
            margin-bottom: 10px;
        }
        .item button, .whatsapp-link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            text-decoration: none;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .item button:hover, .whatsapp-link:hover {
            background-color: #0056b3;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 200px;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .sidebar .active {
            background-color: #007bff;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .main-content button {
            background-color: #ffffff;
            border: none;
            color: #000000;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .main-content button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="item_status.php">Item Status</a>
        <a href="history.php">History</a>
        <a href="post_lost_item.php">Post Lost Item</a>
        <a href="post_found_item.php">Post Found Item</a>
    </div>
    
    <div class="main-content">
        <h1>Search Items</h1>
        <button type="button" onclick="window.location.href='dashboard.php';">Back</button>

        <hr>
        <div class="items">
            <h2>Lost Items</h2>
            <?php if (!empty($lost_items)): ?>
                <?php foreach ($lost_items as $item): ?>
                    <div class="item">
                        <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php if ($item['image']): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        <?php endif; ?>
                        <p>Contact Number: <?php echo htmlspecialchars($item['contact_number']); ?></p>
                        <p>Posted on: <?php echo htmlspecialchars($item['post_date']); ?></p>
                        <p>Available until: <?php echo calculate_expiry_date($item['post_date']); ?></p>

                        <!-- WhatsApp Messaging -->
                        <?php 
                        $whatsapp_message = urlencode("Hello, I found your lost item: " . $item['item_name'] . ". Please contact me!");
                        $whatsapp_link = "https://wa.me/" . $item['contact_number'] . "?text=" . $whatsapp_message;
                        ?>
                        <a href="<?php echo $whatsapp_link; ?>" class="whatsapp-link" target="_blank">Message on WhatsApp</a>

                        <!-- Mark as Received -->
                        <form method="POST">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="table" value="lost_item">
                            <button type="submit" name="delete_item">Mark as Received</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No lost items found.</p>
            <?php endif; ?>
        </div>

        <hr>

        <div class="items">
            <h2>Found Items</h2>
            <?php if (!empty($found_items)): ?>
                <?php foreach ($found_items as $item): ?>
                    <div class="item">
                        <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php if ($item['image']): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        <?php endif; ?>
                        <p>Contact Number: <?php echo htmlspecialchars($item['contact_number']); ?></p>
                        <p>Posted on: <?php echo htmlspecialchars($item['post_date']); ?></p>
                        <p>Available until: <?php echo calculate_expiry_date($item['post_date']); ?></p>

                        <!-- WhatsApp Messaging -->
                        <?php 
                        $whatsapp_message = urlencode("Hello, I lost an item: " . $item['item_name'] . ". Please help me retrieve it!");
                        $whatsapp_link = "https://wa.me/" . $item['contact_number'] . "?text=" . $whatsapp_message;
                        ?>
                        <a href="<?php echo $whatsapp_link; ?>" class="whatsapp-link" target="_blank">Message on WhatsApp</a>

                        <!-- Mark as Taken -->
                        <form method="POST">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="table" value="found_item">
                            <button type="submit" name="delete_item">Mark as Taken</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No found items found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
