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
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $content = trim($_POST['content'] ?? '');

    if ($post_id && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param('iis', $post_id, $user_id, $content);
        
        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "Comment posted successfully!";
            $_SESSION['alert_type'] = 'success';
        } else {
            $_SESSION['alert_message'] = "Error posting comment: " . $stmt->error;
            $_SESSION['alert_type'] = 'error';
        }
        $stmt->close();
    } else {
        $_SESSION['alert_message'] = "Please write a comment";
        $_SESSION['alert_type'] = 'error';
    }
}

$conn->close();
header("Location: viewBlog.php");
exit;
?>