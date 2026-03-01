<?php
session_start();
require_once "db_connect.php"; // Include DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $userid   = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        // Validate: non-empty fields
        if (empty($userid) || empty($password)) {
            throw new Exception("Username and password are required.");
        }

        // Query UserLogin table
        $stmt = $conn->prepare("SELECT * FROM UserLogin WHERE userid=? AND password=?");
        if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
        $stmt->bind_param("ss", $userid, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $_SESSION["user"] = $userid;
            header("Location: home.php");
            exit();
        } else {
            throw new Exception("Invalid credentials. Please try again.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage(); // Caught & displayed by included index.php
        include 'index.php';
    }
} else {
    header('Location: index.php');
    exit();
}
?>
