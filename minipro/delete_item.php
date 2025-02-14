<?php
session_start();
require_once 'db.php';

if (isset($_POST['item_id']) && isset($_POST['item_type'])) {
    $item_id = filter_var($_POST['item_id'], FILTER_VALIDATE_INT);
    $item_type = $_POST['item_type'];

    if (!$item_id) {
        // Invalid ID
        header("Location: search_items.php?error=invalid_id");
        exit();
    }

    // Determine the table based on item type
    $valid_tables = ['lost' => 'lost_item', 'found' => 'found_item'];
    
    if (!array_key_exists($item_type, $valid_tables)) {
        header("Location: search_items.php?error=invalid_type");
        exit();
    }

    $table = $valid_tables[$item_type];

    // Update the is_deleted column to 1 securely
    $stmt = $pdo->prepare("UPDATE $table SET is_deleted = 1 WHERE id = :id");
    $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: search_items.php?success=deleted");
    } else {
        header("Location: search_items.php?error=not_found");
    }
    exit();
} else {
    header("Location: search_items.php?error=missing_params");
    exit();
}
?>
