<?php
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $mobile_name = trim($_POST["mobile_name"]);
        $brand       = trim($_POST["brand"]);
        $price       = (float)$_POST["price"];

        // Logical validation
        if (empty($mobile_name) || empty($brand)) {
            throw new Exception("All fields are required.");
        }
        if ($price <= 0) {
            throw new Exception("Price must be a positive number.");
        }

        $stmt = $conn->prepare(
            "INSERT INTO mobile_phone (mobile_name, brand, price) VALUES (?, ?, ?)");
        if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
        $stmt->bind_param("ssd", $mobile_name, $brand, $price);

        if ($stmt->execute()) {
            $_SESSION['flash'] = "Phone &quot;" . htmlspecialchars($mobile_name) . "&quot; added successfully!";
            header("Location: retrieve_all.php");
            exit();
        } else {
            throw new Exception("Insert failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['flash_error'] = $e->getMessage();
        header("Location: add_phone.php");
        exit();
    }
} else {
    header('Location: add_phone.php');
    exit();
}
?>
