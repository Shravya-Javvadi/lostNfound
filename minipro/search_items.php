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

// Function to calculate the expiry date (5 days after the post date)
function calculate_expiry_date($post_date) {
    $post_date = new DateTime($post_date);
    $post_date->modify('+5 days');
    return $post_date->format('Y-m-d');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Items</title>
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
        form {
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
        form input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            margin-right: 10px;
            transition: border-color 0.3s;
        }
        form input[type="text"]:hover {
            border-color: #007bff;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
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
        .item:last-child {
            border-bottom: none;
        }
        .item h3 {
            margin-top: 0;
        }
        .item img {
            max-width: 200px;
            display: block;
            margin-bottom: 10px;
        }
        .item button {
            margin-top: 10px;
            padding: 5px 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .item button:hover {
            background-color: #0056b3;
        }
        .chat {
            margin-top: 10px;
        }
        .chat h4 {
            margin: 0;
        }
        .chat textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .chat button {
            margin-top: 10px;
            padding: 5px 10px;
            border: none;
            background-color: #004085;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .chat button:hover {
            background-color: #0056b3;
        }
        .chat-messages p {
            margin: 5px 0;
            padding: 5px;
            background-color: #e9ecef;
            border-radius: 4px;
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
        .back-button {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="item_status.php">Item Status</a>
        <a href="history.php">History</a>
        <a href="post_lost_item.php">Post Lost item</a>
        <a href="post_found_item.php">Post Found item</a>
    </div>
    <div class="main-content">
        <h1>Search Items</h1>
        <form id="searchForm" method="post" action="search_items.php">
            <input type="text" name="search_term" placeholder="Search...">
            <input type="submit" name="search" value="Search">
        </form>
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
                        <p>Registration Number: <?php echo htmlspecialchars($item['reg_number']); ?></p>
                        <p>Posted on: <?php echo htmlspecialchars($item['post_date']); ?></p>
                        <?php $expiry_date = calculate_expiry_date($item['post_date']); ?>
                        <p>Available until: <?php echo htmlspecialchars($expiry_date); ?></p>
                        <form class="status-form" method="post" action="update_status.php">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="item_type" value="lost">
                            <button type="submit" name="status" value="received">Received</button>
                        </form>
                        <?php if ($item['status'] == 'received'): ?>
                            <form method="post" action="delete_item.php">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="item_type" value="lost">
                                <button type="submit">Delete</button>
                            </form>
                        <?php endif; ?>
                        <div class="chat">
                            <h4>Chat</h4>
                            <form method="post" action="send_message.php">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <textarea name="message" placeholder="Type your message..."></textarea>
                                <button type="submit">Send</button>
                            </form>
                            <?php
                            $stmt_chat = $pdo->prepare("SELECT * FROM chats WHERE item_id = ?");
                            $stmt_chat->execute([$item['id']]);
                            $chats = $stmt_chat->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="chat-messages">
                                <?php foreach ($chats as $chat): ?>
                                    <p><?php echo htmlspecialchars($chat['message']); ?> (<?php echo htmlspecialchars($chat['timestamp']); ?>)</p>
                                <?php endforeach; ?>
                            </div>
                        </div>
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
                        <p>Registration Number: <?php echo htmlspecialchars($item['reg_number']); ?></p>
                        <p>Posted on: <?php echo htmlspecialchars($item['post_date']); ?></p>
                        <?php $expiry_date = calculate_expiry_date($item['post_date']); ?>
                        <p>Available until: <?php echo htmlspecialchars($expiry_date); ?></p>
                        <form class="status-form" method="post" action="update_status.php">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="item_type" value="found">
                            <button type="submit" name="status" value="taken">Taken</button>
                        </form>
                        <?php if ($item['status'] == 'taken'): ?>
                            <form method="post" action="delete_item.php">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="item_type" value="found">
                                <button type="submit">Delete</button>
                            </form>
                        <?php endif; ?>
                        <div class="chat">
                            <h4>Chat</h4>
                            <form method="post" action="send_message.php">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <textarea name="message" placeholder="Type your message..."></textarea>
                                <button type="submit">Send</button>
                            </form>
                            <?php
                            $stmt_chat = $pdo->prepare("SELECT * FROM chats WHERE item_id = ?");
                            $stmt_chat->execute([$item['id']]);
                            $chats = $stmt_chat->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="chat-messages">
                                <?php foreach ($chats as $chat): ?>
                                    <p><?php echo htmlspecialchars($chat['message']); ?> (<?php echo htmlspecialchars($chat['timestamp']); ?>)</p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No found items found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>