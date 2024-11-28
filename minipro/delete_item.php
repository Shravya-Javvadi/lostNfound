<?php
session_start();
require_once 'db.php';

// Check if item_id and item_type are set
if (isset($_POST['item_id']) && isset($_POST['item_type'])) {
    $item_id = $_POST['item_id'];
    $item_type = $_POST['item_type'];

    // Determine the table based on the item type
    if ($item_type === 'lost') {
        $table = 'lost_item';
    } elseif ($item_type === 'found') {
        $table = 'found_item';
    } else {
        // Invalid item type
        header("Location: search_items.php");
        exit();
    }

    // Update the is_deleted column to 1
    $stmt = $pdo->prepare("UPDATE $table SET is_deleted = 1 WHERE id = ?");
    $stmt->execute([$item_id]);

    // Redirect back to the search items page
    header("Location: search_items.php");
    exit();
} else {
    // Redirect back if item_id or item_type is not set
    header("Location: search_items.php");
    exit();
}
?>