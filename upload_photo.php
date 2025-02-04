<?php
session_start();
include "db.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $user_id = $_SESSION["user_id"];
    $caption = $_POST["caption"];
    $target_dir = "uploads/";

    // Generate the file path
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    if (getimagesize($_FILES["photo"]["tmp_name"])) {
        // Move the uploaded file to the server
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Insert the photo details into the database
            $query = "INSERT INTO user_photos (user_id, caption, photo_path) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iss", $user_id, $caption, $target_file);
            $stmt->execute();
            header("Location: index.php"); // Redirect after successful upload
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload Photo</title>
</head>

<body>
    <form action="upload_photo.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="photo" accept="image/*" required>
        <input type="text" name="caption" placeholder="Enter caption" required>
        <button type="submit">Upload Photo</button>
    </form>
</body>

</html>