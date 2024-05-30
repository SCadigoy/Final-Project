<?php
session_start();
require 'config/db.php';
include 'includes/headerhome.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'create_post') {

        $caption = isset($_POST['caption']) ? $_POST['caption'] : '';
        $image = $_FILES['image']['name'] ?? '';

        if (!empty($image)) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['image']['name']);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
                exit;
            }
        }

        $sql = "INSERT INTO posts (user_id, caption, image) VALUES (:user_id, :caption, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':caption', $caption);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {

        } else {
            echo "Error: " . $stmt->errorInfo()[2] . "<br>";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'add_comment') {

        $post_id = $_POST['post_id'];
        $comment = $_POST['comment'];

        $sql = "INSERT INTO comments (user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "Error: " . $stmt->errorInfo()[2] . "<br>";
        }
    } elseif (isset($_POST['action']) && ($_POST['action'] == 'upload_profile' || $_POST['action'] == 'upload_cover')) {

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

                } else {
                    echo "Error: " . $stmt->errorInfo()[2] . "<br>";
                }
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'follow_user') {

        if (isset($_POST['followed_user_id'])) {
            $sql = "SELECT COUNT(*) FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':follower_id', $_SESSION['user_id']);
            $stmt->bindParam(':followed_id', $_POST['followed_user_id']);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $sql = "INSERT INTO followers (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':follower_id', $_SESSION['user_id']);
                $stmt->bindParam(':followed_id', $_POST['followed_user_id']);

                if ($stmt->execute()) {

                } else {
                    echo "Error following user: " . $stmt->errorInfo()[2] . "<br>";
                }
            } else {
            }
        } else {
            echo "User ID to follow is missing.<br>";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'unfollow_user') {

        if (isset($_POST['unfollowed_user_id'])) {
            $unfollowedUserId = $_POST['unfollowed_user_id'];

            $sql = "DELETE FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':follower_id', $_SESSION['user_id']);
            $stmt->bindParam(':followed_id', $unfollowedUserId);

            if ($stmt->execute()) {

                $sql = "SELECT id, username FROM users WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $unfollowedUserId);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $suggestedFriends[] = $user;
                }
            } else {
                echo "Error unfollowing user: " . $stmt->errorInfo()[2] . "<br>";
            }
        } else {
            echo "User ID to unfollow is missing.<br>";
        }
    }
}    

$sql = "SELECT username, profile_picture, cover_picture FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT u.id, u.username FROM users u INNER JOIN followers f ON u.id = f.follower_id WHERE f.followed_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$followers = $stmt->fetchAll(PDO::FETCH_ASSOC); 

$sql = "SELECT u.id, u.username FROM users u INNER JOIN followers f ON u.id = f.followed_id WHERE f.follower_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$following = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT id, username FROM users WHERE id != :user_id AND id NOT IN (SELECT followed_id FROM followers WHERE follower_id = :user_id)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$suggestedFriends = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT p.*, u.username FROM posts p LEFT JOIN users u ON p.user_id = u.id WHERE p.user_id = :user_id ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT c.*, u.username FROM comments c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedComments = [];
foreach ($comments as $comment) {
    $postId = $comment['post_id'];
    if (!isset($groupedComments[$postId])) {
        $groupedComments[$postId] = [];
    }
    $groupedComments[$postId][] = $comment;
}

$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style/profile.css">
</head>
<body>
    <div class="box">
        <div class="content">
            <div class="picture">
                <?php if (!empty($currentUser['cover_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($currentUser['cover_picture']); ?>" alt="Cover Picture" class="cover-picture">
                <?php endif; ?>
                <div class="profile">
                    <?php if (!empty($currentUser['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($currentUser['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                    <?php endif; ?>
                        <h1><?php echo htmlspecialchars($currentUser['username']); ?></h1>
                </div>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="post-box">
                    <input type="text" name="caption" placeholder="What's on your mind...">
                    <input type="file" name="image">
                    <input type="hidden" name="action" value="create_post">
                    <input type="submit" class="btn" value="Post">
                </div>
            </form> 
        </div>
        <div class="each">
            <div class="follower">
                <h3>Followers</h3>
                <?php if (!empty($followers)): ?>
                    <?php foreach ($followers as $follower): ?>
                        <div class="user-detail">
                            <h3><?php echo htmlspecialchars($follower['username']); ?></h3>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No follower yet.</p>
                <?php endif; ?>
            </div>
            <div class="suggest">
                <h3>Suggested Friends</h3>
                <?php if (!empty($suggestedFriends)): ?>
                    <?php foreach ($suggestedFriends as $user): ?>
                        <div class="user-detail">
                            <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="follow_user">
                                <input type="hidden" name="followed_user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn">Follow</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No users available to follow.</p>
                <?php endif; ?>
            </div>
            <div class="following">
                <h3>Following</h3>
                    <?php if (!empty($following)): ?>
                        <?php foreach ($following as $followedUser): ?>
                            <div class="user-detail">
                                <h4><?php echo htmlspecialchars($followedUser['username']); ?></h4>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="unfollow_user">
                                    <input type="hidden" name="unfollowed_user_id" value="<?php echo $followedUser['id']; ?>">
                                    <button type="submit" class="btn">Unfollow</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>You are not following</p>
                        <p>anyone yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <b><?php echo htmlspecialchars($post['username']); ?></b>
                        <h6><?php echo htmlspecialchars($post['created_at']); ?></h6>
                        <p><?php echo htmlspecialchars($post['caption']); ?></p>
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="post-image"><br>
                        <?php endif; ?>

                    </div>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add_comment">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="text" name="comment" placeholder="Write a public comment...">
                        <input type="submit" class="btn" value="Comment">
                    </form>
                    <div class="comment">
                        <?php if (!empty($groupedComments[$post['id']])): ?>
                            <?php foreach ($groupedComments[$post['id']] as $comment): ?>
                                <b><?php echo htmlspecialchars($comment['username']); ?></b>
                                <h6><?php echo htmlspecialchars($comment['created_at']); ?></h6>
                                <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

                           

