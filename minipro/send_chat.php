<?php
include 'config.php';
session_start();

$item_id = $_POST['item_id'];
$user_id = $_SESSION['user_id'];
$message = $_POST['message'];

$sql = "INSERT INTO chats (item_id, user_id, message) VALUES ($item_id, $user_id, '$message')";
if ($conn->query($sql) === TRUE) {
    header("Location: search_items.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>