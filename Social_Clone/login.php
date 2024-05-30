<?php
require 'config/db.php'; 
session_start();
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE email = :email";
    $stmt = $pdo->prepare($sql); 
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='message'>
                    <p>Wrong Email or Password</p>
                  </div><br>";
            echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
        }
    } else {
        echo "<div class='message'>
                <p>Account Not Found</p>
              </div><br>";
        echo "<a href='register.php'><button class='btn'>Sign Up</button></a>";
    }
} else {
    ?>

            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <input type="text" name="email" placeholder="Email" id="email" required>
                </div>

                <div class="field input">
                    <input type="password" name="password" placeholder="Password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
                <div class="link">
                    Don't have an account? <a href="register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
}
?>

