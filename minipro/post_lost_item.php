<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$success = false;
$errors = []; // Array to store validation errors

function isValidItemName($item_name) {
    return preg_match('/^(?=.*[A-Za-z])[A-Za-z0-9 ]+$/', $item_name);
}

function isValidDescription($description) {
    return preg_match('/^[A-Za-z0-9 .,!?]+$/', $description);
}

function isValidContactNumber($contact_number) {
    return preg_match('/^\+91[6789]\d{9}$/', $contact_number);
}

function isValidRegNumber($reg_number) {
    return preg_match('/^[A-Za-z0-9]{10}$/', $reg_number);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_name = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $contact_number = '+91' . trim($_POST['contact_number']);
    $reg_number = trim($_POST['reg_number']);
    $image = '';

    // Server-side validation
    if (empty($item_name) || !isValidItemName($item_name)) {
        $errors[] = "Item name is required and should contain only alphabets, numbers, and spaces. It must contain at least one alphabet.";
    }

    if (empty($description) || !isValidDescription($description)) {
        $errors[] = "Description is required and should contain only numbers, alphabets, spaces, and basic punctuation.";
    }

    if (empty($contact_number) || !isValidContactNumber($contact_number)) {
        $errors[] = "Contact number is required and should be in the format +91XXXXXXXXXX.";
    }

    if (empty($reg_number) || !isValidRegNumber($reg_number)) {
        $errors[] = "Registration number is required, should be exactly 10 characters long, and contain only numbers and alphabets.";
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors[] = "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
        } else {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO lost_item (user_id, item_name, description, image, contact_number, reg_number, post_date) 
                                VALUES (:user_id, :item_name, :description, :image, :contact_number, :reg_number, NOW())");
    
        $stmt->execute([
            ':user_id' => $user_id,
            ':item_name' => $item_name,
            ':description' => $description,
            ':image' => $image,
            ':contact_number' => $contact_number,
            ':reg_number' => $reg_number
        ]);
    
        if ($stmt) {
            // Notify other users about the new lost item
            $message = "A new lost item has been posted: $item_name";
    
            $notif_stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)");
            
            // Fetch all users except the one who posted
            $users_stmt = $pdo->prepare("SELECT id FROM users WHERE id != :current_user");
            $users_stmt->execute([':current_user' => $user_id]);
            $users_result = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($users_result as $row) {
                $notif_stmt->execute([
                    ':user_id' => $row['id'],
                    ':message' => $message
                ]);
            }
    
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
            exit();
        } else {
            $errors[] = "Error: " . implode(", ", $stmt->errorInfo());
        }
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Lost Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
            color: white;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar img {
            height: 40px;
            margin-right: 20px;
        }
        .navbar h2 {
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }
        .navbar button {
            background-color: #ffffff; /* Changed to white */
            border: none;
            color: #000000; /* Changed to black */
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .navbar button:hover {
            opacity: 0.8;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            margin: 8px 0;
        }
        .button-container {
            display: flex;
            justify-content: center;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 150px;
        }
        button[type="submit"]:hover {
            opacity: 0.8;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="logo.gif" alt="Logo">
        <h2>Post Lost Item</h2>
        <button type="button" onclick="window.location.href='dashboard.php';">Back</button>
    </div>
    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <script>
                alert("Lost item posted successfully.");
                window.location.href = window.location.pathname;
            </script>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Item Name:</label>
            <input type="text" name="item_name" required value="<?php echo isset($_POST['item_name']) ? htmlspecialchars($_POST['item_name']) : ''; ?>">
            <label>Description:</label>
            <textarea name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            <label>Contact Number:</label>
            <input type="text" name="contact_number" required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number']) : ''; ?>">
            <label>Registration Number:</label>
            <input type="text" name="reg_number" required value="<?php echo isset($_POST['reg_number']) ? htmlspecialchars($_POST['reg_number']) : ''; ?>">
            <label>Image:</label>
            <input type="file" name="image">
            <div class="button-container">
                <button type="submit">Post Lost Item</button>
            </div>
        </form>
    </div>
</body>
</html>
