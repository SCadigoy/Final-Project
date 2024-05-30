<?php
include 'includes/headerhome.php';
session_start();
require 'config/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && ($_POST['action'] == 'upload_profile' || $_POST['action'] == 'upload_cover')) {
        $image = $_FILES['image']['name'] ?? '';
        if (!empty($image)) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['image']['name']);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
                $column = $_POST['action'] == 'upload_profile' ? 'profile_picture' : 'cover_picture';

                $sql = "UPDATE users SET $column = :image WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':image', $image);
                $stmt->bindParam(':user_id', $_SESSION['user_id']);

                if ($stmt->execute()) {
                    $message = "Profile picture updated successfully!";
                } else {
                    $error = "Error updating profile picture.";
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

$sql = "SELECT username, profile_picture, cover_picture FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

$pdo = null; 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <link rel="stylesheet" href="style/change.css">
</head>
<body>
    <div class="box">
        <div class="container">
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(isset($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="profile">
                <?php if (!empty($currentUser['cover_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($currentUser['cover_picture']); ?>" alt="Cover Picture" class="cover-picture">
                <?php endif; ?>
                <?php if (!empty($currentUser['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($currentUser['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                <?php endif; ?>
                <h1><?php echo htmlspecialchars($currentUser['username']); ?></h1>
            </div>
            <h3>Sample Profile Page</h3>
            <h2>Change Profile and Cover Pictures</h2>
        </div>
        <div class="upload">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="upload-box">
                    <label for="profile_picture">Upload Profile Picture:</label>
                    <input type="file" name="image" id="profile_picture">
                    <input type="hidden" name="action" value="upload_profile">
                    <input type="submit" class="btn" value="Upload">
                </div>
            </form>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="upload-box">
                    <label for="cover_picture">Upload Cover Picture:</label>
                    <input type="file" name="image" id="cover_picture">
                    <input type="hidden" name="action" value="upload_cover">
                    <input type="submit" class="btn" value="Upload">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
