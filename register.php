<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash password

    $query = "INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Email already exists or error occurred.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" required class="input-field">
            <input type="text" name="last_name" placeholder="Last Name" required class="input-field">
            <input type="email" name="email" placeholder="Email" required class="input-field">
            <input type="text" name="phone" placeholder="Phone Number" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <button type="submit" class="btn">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>

</html>