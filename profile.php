<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile update and bio update
    if (isset($_POST["bio"])) {
        // Bio update
        $bio = $_POST["bio"];
        $query = "UPDATE users SET bio=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $bio, $user_id);
        $stmt->execute();
    } else {
        // Handle profile update
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $phone = $_POST["phone"];
        $profile_photo = $user["profile_photo"];

        if (!empty($_FILES["profile_photo"]["name"])) {
            $target_dir = "uploads/";
            $profile_photo = $target_dir . basename($_FILES["profile_photo"]["name"]);
            move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $profile_photo);
        }

        $query = "UPDATE users SET first_name=?, last_name=?, phone=?, profile_photo=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $profile_photo, $user_id);
        $stmt->execute();
    }

    header("Location: profile.php");
    exit();
}

if (isset($_GET['delete_bio'])) {
    // Delete bio
    $query = "UPDATE users SET bio=NULL WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="profile-page">
        <!-- Profile Header -->
        <div class="profile-header">
            <img src="<?= $user['profile_photo'] ?: 'default.png'; ?>" class="profile-pic" alt="Profile Picture">
            <div class="user-info">
                <h2><?= $user['first_name'] . ' ' . $user['last_name']; ?></h2>
                <p><?= $user['email']; ?></p>
                <!-- Bio Section -->
                <div class="bio-container">
                    <p class="bio-text"><?= $user['bio'] ? $user['bio'] : "Add your bio..." ?></p>
                    <?php if ($user['bio']): ?>
                        <button class="edit-bio-btn" onclick="toggleBioForm()">Edit Bio</button>
                        <a href="?delete_bio=true" class="delete-bio-btn">Delete Bio</a>
                    <?php else: ?>
                        <button class="edit-bio-btn" onclick="toggleBioForm()">Add Bio</button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Bio Edit Form -->
            <div id="bio-form" class="bio-edit-form" style="display: none;">
                <form method="POST" class="bio-form">
                    <textarea name="bio" placeholder="Update your bio here" class="bio-textarea"><?= $user['bio']; ?></textarea>
                    <button type="submit" class="update-bio-btn">Update Bio</button>
                </form>
            </div>
        </div>

        <script>
            function toggleBioForm() {
                const bioForm = document.getElementById('bio-form');
                if (bioForm.style.display === 'none') {
                    bioForm.style.display = 'block';
                } else {
                    bioForm.style.display = 'none';
                }
            }
        </script>
    </div>

    <!-- Profile Update Form -->
    <form method="POST" enctype="multipart/form-data" class="profile-form-container">
        <input type="file" name="profile_photo" class="profile-input-field">
        <input type="text" name="first_name" placeholder="first name" value="<?= $user['first_name']; ?>" required class="profile-input-field">
        <input type="text" name="last_name" placeholder="last name" value="<?= $user['last_name']; ?>" required class="profile-input-field">
        <input type="email" name="email" placeholder="email" value="<?= $user['email']; ?>" readonly class="profile-input-field">
        <input type="text" name="phone" placeholder="phone" value="<?= $user['phone']; ?>" class="profile-input-field">
        <button type="submit" class="profile-btn">Update Profile</button>
    </form>

</body>

</html>