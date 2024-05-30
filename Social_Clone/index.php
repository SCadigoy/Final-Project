<?php
require 'config/db.php';
session_start();
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/index.css">
    <title>Document</title>
</head>
<body>
<div class="jumbotron text-center">
    <br><h1 class="display-4">Welcome to InstaFace!</h1>
    <p class="lead">Share your photos with friends and family.</p>
    <hr class="my-4">
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="center-links">
            <a class="btn" href="home.php" role="button">Go to Home</a>
        </div>
    <?php else: ?>
        <div class="link-container">
            <div class="left-links2">
                <a class="btn" href="register.php" role="button">Register</a>
            </div>
            <div class="right-links3">
                <a class="btn" href="login.php" role="button">Login</a>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
