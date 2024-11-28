<?php
require_once 'db.php';

$item_id = $_POST['item_id'];
$user_id = $_SESSION['user_id'];
$message = $_POST['message'];

$stmt = $pdo->prepare("INSERT INTO chats (item_id, user_id, message) VALUES (?, ?, ?)");
$stmt->execute([$item_id, $user_id, $message]);

header('Location: search_items.php');
exit();
?>