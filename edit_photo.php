<?php
session_start();
include "db.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Delete photo logic
if (isset($_GET['id'])) {
    $photo_id = $_GET['id'];
    $query = "SELECT * FROM user_photos WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $photo_id, $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $photo = $result->fetch_assoc();

    // If photo not found or invalid, redirect
    if (!$photo) {
        header("Location: index.php");
        exit();
    }

    // Delete photo from database
    $query = "DELETE FROM user_photos WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();

    // Delete the photo file from the server
    unlink($photo['photo_path']);

    header("Location: index.php"); // Redirect back after deletion
    exit();
}
