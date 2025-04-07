<?php
session_start();

if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    $alert_type = $_SESSION['alert_type'] ?? 'success';
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);
    echo '<div class="alert '.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_message).'</div>';
}

// Database configuration
$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch all blog posts with author names, newest first
    $stmt = $pdo->query("
        SELECT p.id, p.title, p.content, p.created_at, u.name as author_name 
        FROM posts p
        JOIN users u ON p.user_id = u.id
    ");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    usort($posts, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Blogs</title>
    <script src="js/alert.js">defer</script>
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/blog.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Lora:ital,wght@0,400..700;1,400..700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Omar Aied</h1>
        <nav>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <i class="fas fa-bars"></i>
            </label>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="home.php#education">Education</a></li>
                <li><a href="home.php#skills">Skills</a></li>
                <li><a href="viewBlog.php">View Blogs</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="addPost.php">Add Post</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="blog-container">
        <?php if (isset($alert_message)): ?>
            <div class="alert <?php echo htmlspecialchars($alert_type); ?>">
                <?php echo htmlspecialchars($alert_message); ?>
            </div>
        <?php endif; ?>

        <h2>Recent Blog Posts</h2>
        
        <?php if(empty($posts)): ?>
            <div class="no-posts">No blog posts yet. Be the first to post!</div>
        <?php else: ?>
            <div class="blog-posts">
                <?php foreach($posts as $post): ?>
                        <article class="blog-post">
                            <div class="post-header">
                                <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <div class="post-meta">
                                    <span class="post-author">By <?php echo htmlspecialchars($post['author_name']); ?></span>
                                    <time datetime="<?php echo $post['created_at']; ?>">
                                        <?php echo date('F j, Y H:i', strtotime($post['created_at'])); ?>
                                    </time>
                                </div>
                            </div>
                            <div class="post-content">
                                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                            </div>

                            <?php if($_SESSION['is_admin'] ?? false): ?>
                                <form method="POST" action="deletePost.php" class="delete-form">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">
                                        Delete Post
                                    </button>
                                </form>
                            <?php endif; ?>
                        </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Omar Aied</p>
        <div class="socials">
            <p><a href="New cv.pdf" target="_blank">CV</a></p>
            <a href="mailto:ec24360@qmul.ac.uk" target="_blank"><img src="images/email.png" alt="Email"></a>
            <a href="https://github.com/OmarAied" target="_blank"><img src="images/github.png" alt="GitHub"></a>
            <a href="https://www.linkedin.com/in/omar-aied-096ba5278/" target="_blank"><img src="images/linkedin.png" alt="LinkedIn"></a>
        </div>
    </footer>
</body>
</html>