<?php
require 'config/db.php';
session_start();
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
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
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
    }
}

// Fetch the list of users that the logged-in user is following
$sql = "SELECT followed_id FROM followers WHERE follower_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$following = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Add the logged-in user to the list of users to fetch posts from
$following[] = $_SESSION['user_id'];

// Fetch posts from the logged-in user and their following users
$sql = "SELECT p.*, u.username FROM posts p LEFT JOIN users u ON p.user_id = u.id 
        WHERE p.user_id IN (" . implode(',', array_fill(0, count($following), '?')) . ") 
        ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($following);
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
    <title>Home</title>
    <link rel="stylesheet" href="style/home.css">
</head>
<body>
    <div class="box">
        <div class="content">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="post-box">
                    <input type="text" name="caption" placeholder="What's on your mind...">
                    <input type="file" name="image">
                    <input type="hidden" name="action" value="create_post">
                    <input type="submit" class="btn" value="Post">
                </div>
            </form>
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
                <br><br><p>No posts to display.</p>
                <p>Add more post or</p>
                <p>Follow more friends</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>