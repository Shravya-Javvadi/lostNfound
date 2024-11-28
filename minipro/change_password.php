<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php'; // Include your database connection file

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current hashed password from the database
    $query = $conn->prepare('SELECT password FROM users WHERE id = ?');
    $query->execute([$user_id]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Verify the current password
    if (password_verify($current_password, $user['password'])) {
        // Check if the new password and confirm password match
        if ($new_password === $confirm_password) {
            // Check if the new password meets the criteria
            if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $new_password)) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_query = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
                if ($update_query->execute([$hashed_password, $user_id])) {
                    $_SESSION['message'] = 'Password changed successfully!';
                    header('Location: change_password.php');
                    exit();
                } else {
                    $error = 'Failed to change password. Please try again.';
                }
            } else {
                $error = 'New password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one digit.';
            }
        } else {
            $error = 'New password and confirm password do not match.';
        }
    } else {
        $error = 'Current password is incorrect.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password - Lost'N'Found</title>
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
        <h1>Change Password</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" id="passwordForm">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('current_password')">Show</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <div class="input-group">
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('new_password')">Show</button>
                    </div>
                </div>
                <small class="form-text text-muted">New password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one digit.</small>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('confirm_password')">Show</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
        </form>
    </div>
    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleButton = passwordField.nextElementSibling.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyn23gA5Zt8DJTUMn9j7bD3ez9z1Z3+3Ebg9p6fL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.6/dist/umd/popper.min.js" integrity="sha384-R7g5vK6D5znh2vDsgG9u9IibVuPv2F6ENpeW8U58PjQQU8z+YAp0MR2aOfjt5TZn" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-5dLw1tP1LgPY8A8I5W9RRZlf0mOOH+HG04xQ0nBGT/fpHjpH7vCzklqMeW6Az2Ic" crossorigin="anonymous"></script>
</body>
</html>
