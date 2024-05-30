<?php
require 'config/db.php';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style/register.css">
</head>
<body>
<div class="container">
    <div class="box form-box">
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo "<div class='message'>
                    <p>This email is already in use. Please try another one.</p>
                  </div> <br>";
            echo "<a href='register.php'><button class='btn'>Go Back</button>";
            exit;
        }
    } else {
        echo "Error: " . $stmt->errorInfo()[2] . "<br>"; 
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    if ($stmt->execute()) {
        echo "<div class='message'>
                <p>Registered Successfully</p>
              </div> <br>";
        echo "<a href='login.php'><button class='btn'>Login</button>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2] . "<br>"; 
    }
} else {
?>
        <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <input type="text" name="username" placeholder="Username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <input type="text" name="email" placeholder="Email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <input type="password" name="password" placeholder="Password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                
                    <input type="submit" class="btn" name="submit" value="Sign Up" required>
                </div>
                <div class="link">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
    </div>
    <?php } ?>
</div>
</body>
</html>
