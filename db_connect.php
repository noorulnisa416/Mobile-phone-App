<?php
// db_connect.php - Database Connection File
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "mobile_repo";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    // Connection successful
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>
