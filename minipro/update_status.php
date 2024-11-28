<?php
session_start();
require_once 'db.php';

if (isset($_POST['item_id']) && isset($_POST['status']) && isset($_POST['item_type'])) {
    $item_id = $_POST['item_id'];
    $status = $_POST['status'];
    $item_type = $_POST['item_type'];

    // Determine the table based on item type
    if ($item_type == 'lost') {
        $table = 'lost_item';
    } elseif ($item_type == 'found') {
        $table = 'found_item';
    } else {
        // Invalid item type
        die('Invalid item type');
    }

    // Update status in the corresponding table
    $stmt = $pdo->prepare("UPDATE $table SET status = :status WHERE id = :item_id");
    $stmt->execute([':status' => $status, ':item_id' => $item_id]);

    header("Location: search_items.php");
    exit();
} else {
    die('Invalid input');
}
?>
