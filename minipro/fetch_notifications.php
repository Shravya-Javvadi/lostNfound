<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, message FROM notifications WHERE user_id = :user_id AND is_read = 0");
$stmt->execute([':user_id' => $user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mark notifications as read
$update_stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
$update_stmt->execute([':user_id' => $user_id]);

echo json_encode($notifications);
?>
