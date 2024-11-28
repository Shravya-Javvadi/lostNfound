<?php
session_start();

// Assuming you have a function to check if a user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php'; // Include your database connection file

$user_id = $_SESSION['user_id'];

// Fetch the current user details from the database
$query = $conn->prepare('SELECT username, email, profile_picture FROM users WHERE id = ?');
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $profile_picture = $user['profile_picture'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;
    }

    $update_query = $conn->prepare('UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?');
    if ($update_query->execute([$username, $email, $profile_picture, $user_id])) {
        $_SESSION['message'] = 'Profile updated successfully!';
        header('Location: update_profile.php');
        exit();
    } else {
        $error = 'Failed to update profile. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile - Lost'N'Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            margin-top: 20px;
        }
        .form-group img {
            max-width: 100px;
            margin-top: 10px;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Profile</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" class="form-control-file">
                <?php if ($user['profile_picture']): ?>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyn23gA5Zt8DJTUMn9j7bD3ez9z1Z3+3Ebg9p6fL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.6/dist/umd/popper.min.js" integrity="sha384-R7g5vK6D5znh2vDsgG9u9IibVuPv2F6ENpeW8U58PjQQU8z+YAp0MR2aOfjt5TZn" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-5dLw1tP1LgPY8A8I5W9RRZlf0mOOH+HG04xQ0nBGT/fpHjpH7vCzklqMeW6Az2Ic" crossorigin="anonymous"></script>
</body>
</html>
