<?php
// delete_phone.php - Delete a mobile phone record
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once "db_connect.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    if ($id <= 0) throw new Exception("Invalid record ID.");

    $stmt = $conn->prepare("DELETE FROM mobile_phone WHERE id=?");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['flash'] = "Phone record deleted successfully.";
    } else {
        throw new Exception("Delete failed: " . $stmt->error);
    }
} catch (Exception $e) {
    $_SESSION['flash_error'] = $e->getMessage();
}

header("Location: retrieve_all.php");
exit();
?>
