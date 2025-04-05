<?php
session_start();

// Verify admin status
if (!isset($_SESSION['user_id']) || !($_SESSION['is_admin'] ?? false)) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    
    if ($post_id) {
        try {
            // Delete the post
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$post_id]);
            
            // Set success message
            $_SESSION['alert_message'] = "Post deleted successfully!";
            $_SESSION['alert_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['alert_message'] = "Error deleting post: " . $e->getMessage();
            $_SESSION['alert_type'] = 'error';
        }
    }
}

header("Location: viewBlog.php");
exit;