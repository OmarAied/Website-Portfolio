<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database configuration
$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $content = trim($_POST['content'] ?? '');

    if ($post_id && !empty($content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$post_id, $_SESSION['user_id'], $content]);
            
            $_SESSION['alert_message'] = "Comment posted successfully!";
            $_SESSION['alert_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['alert_message'] = "Error posting comment: " . $e->getMessage();
            $_SESSION['alert_type'] = 'error';
        }
    } else {
        $_SESSION['alert_message'] = "Please write a comment";
        $_SESSION['alert_type'] = 'error';
    }
}

header("Location: viewBlog.php");
exit;