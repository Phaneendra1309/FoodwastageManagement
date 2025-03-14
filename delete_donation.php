<?php
// admin/delete_donation.php - Delete Donation Page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../database/db_connect.php';

// Get donation ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_donations.php");
    exit();
}
$donation_id = $_GET['id'];

// Delete donation from database
$stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
$stmt->bind_param("i", $donation_id);
$stmt->execute();
$stmt->close();

header("Location: manage_donations.php?success=Donation deleted successfully");
exit();
?>