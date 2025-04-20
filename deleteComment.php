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
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
    
    if ($comment_id) {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('i', $comment_id);
        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "Comment deleted successfully!";
            $_SESSION['alert_type'] = 'success';
        } else {
            $_SESSION['alert_message'] = "Error deleting comment: " . $stmt->error;
            $_SESSION['alert_type'] = 'error';
        }
        $stmt->close();
    }
}

$conn->close();
header("Location: viewBlog.php");
exit;
?>