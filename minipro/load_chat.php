<?php
include 'config.php';
session_start();

$item_id = $_GET['item_id'];

$sql = "SELECT * FROM chats WHERE item_id = $item_id ORDER BY timestamp ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo '<p><strong>' . $row['user_id'] . ':</strong> ' . $row['message'] . '</p>';
}

?>
<form method="POST" action="send_chat.php">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    <textarea name="message" placeholder="Type a message..."></textarea>
    <input type="submit" value="Send">
</form>


