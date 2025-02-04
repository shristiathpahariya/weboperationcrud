<?php
session_start();
include "db.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Handle photo upload (Create)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
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
            $query = "INSERT INTO user_photos (user_id, caption, photo_path, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iss", $user_id, $caption, $target_file);
            $stmt->execute();
            header("Location: index.php"); // Redirect after successful upload
            exit();
        }
    }
}

// Fetch user photos from the database (Read)
$query = "SELECT * FROM user_photos WHERE user_id=? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Photos</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <h1>Upload a picture that matches your mood today with a caption</h1>

    <!-- Upload Form (Create) -->
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="photo" accept="image/*" required>
        <input type="text" name="caption" placeholder="Enter caption" required>
        <button type="submit">Upload Photo</button>
    </form>

    <h2>Your mood today</h2>

    <!-- Display each photo with its caption (Read) -->
    <?php while ($photo = $result->fetch_assoc()): ?>
        <div class="photo">
            <img src="<?= $photo['photo_path']; ?>" alt="User Photo">
            <p><?= $photo['caption']; ?></p>
            <p><small>Uploaded on: <?= date("F j, Y, g:i a", strtotime($photo['created_at'])); ?></small></p>
            <a href="edit_photo.php?id=<?= $photo['id']; ?>">Edit Caption</a> |
            <a href="delete_photo.php?id=<?= $photo['id']; ?>">Delete Photo</a>
        </div>
    <?php endwhile; ?>

    <?php include 'footer.php' ?>
</body>

</html>