<?php
session_start();

$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $blog = trim($_POST['blog'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($blog)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('iss', $user_id, $title, $blog);
        
        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "Blog post submitted successfully!";
            $_SESSION['alert_type'] = 'success';
            header("Location: viewBlog.php");
            exit;
        } else {
            $error = "Error submitting post. Please try again.";
            error_log("Post submission error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        $_SESSION['alert_message'] = "Please fill in all fields";
        $_SESSION['alert_type'] = 'error';
    }
}

$conn->close();
?>