<?php
session_start();

if (!isset($_SESSION['user_id']) || !($_SESSION['is_admin'] ?? false)) {
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
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
    
    if ($comment_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);
            $_SESSION['alert_message'] = "Comment deleted successfully!";
            $_SESSION['alert_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['alert_message'] = "Error deleting comment: " . $e->getMessage();
            $_SESSION['alert_type'] = 'error';
        }
    }
}

header("Location: viewBlog.php");
exit;