<?php
$servername = "localhost";
$username = "root"; // replace with your database username
$password = "Prav@2825"; // replace with your database password
$dbname = "lost_and_found"; // replace with your database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>