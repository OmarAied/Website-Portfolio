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

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    
    if ($post_id) {
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('i', $post_id);
        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "Post deleted successfully!";
            $_SESSION['alert_type'] = 'success';
        } else {
            $_SESSION['alert_message'] = "Error deleting post: " . $stmt->error;
            $_SESSION['alert_type'] = 'error';
        }
        $stmt->close();
    }
}

$conn->close();
header("Location: viewBlog.php");
exit;
?>