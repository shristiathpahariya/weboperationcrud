<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["first_name"] = $row["first_name"];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>

</html>