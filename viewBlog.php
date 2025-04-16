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
    
    $monthPicked = isset($_GET['month']) ? (int)$_GET['month'] : null;
    
    if ($monthPicked && ($monthPicked < 1 || $monthPicked > 12)) {
        $monthPicked = null;
    }

    $query = "
        SELECT p.id, p.title, p.content, p.created_at, u.name as author_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
    ";

    if ($monthPicked) {
        $query .= " WHERE MONTH(p.created_at) = :month";
        $stmtPosts = $pdo->prepare($query);
        $stmtPosts->bindValue(':month', $monthPicked, PDO::PARAM_INT);
        $stmtPosts->execute();
    } else {
        $stmtPosts = $pdo->query($query);
    }
    $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);
    
    
    $postsById = [];
    foreach ($posts as $post) {
        $postsById[$post['id']] = $post;
        $postsById[$post['id']]['comments'] = []; // Initialize comments array
    }
    
    $stmtComments = $pdo->query("
        SELECT c.id, c.post_id, c.content, c.created_at, u.name as author_name
        FROM comments c
        JOIN users u ON c.user_id = u.id
    ");
    
    while ($comment = $stmtComments->fetch(PDO::FETCH_ASSOC)) {
        if (isset($postsById[$comment['post_id']])) {
            $postsById[$comment['post_id']]['comments'][] = $comment;
        }
    }
    
    $posts = array_values($postsById);
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
    <script src="js/alert.js" defer></script>
    <script src="js/deleteConfirm.js" defer></script>
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/mobile.css">
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

        <div class="month-filter">
            <form method="GET" action="viewBlog.php">
                <label for="month">Filter by month:</label>
                <select name="month" id="month" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php
                    $months = [
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];
                    foreach ($months as $index => $month) {
                        $selected = ($monthPicked == $index + 1) ? 'selected' : '';
                        echo "<option value='".($index + 1)."' $selected>$month</option>";
                    }
                    ?>
                </select>
            </form>
        </div>
        
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

                            <div class="comments-section">
                                <h4>Comments (<?= count($post['comments']) ?>)</h4>
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <form method="POST" action="addComment.php" class="comment-form">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <textarea name="content" placeholder="Write a comment..." required></textarea>
                                        <button type="submit">Post Comment</button>
                                    </form>
                                <?php endif; ?>

                                <?php foreach($post['comments'] as $comment): ?>
                                    <div class="comment">
                                        <div class="comment-header">
                                            <span class="comment-author"><?= htmlspecialchars($comment['author_name']) ?></span>
                                            <time><?= date('F j, Y H:i', strtotime($comment['created_at'])) ?></time>
                                            
                                            <?php if($_SESSION['is_admin'] ?? false): ?>
                                                <form method="POST" action="deleteComment.php" class="delete-comment">
                                                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                    <button type="submit" class="delete-btn">X</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                        <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if($_SESSION['is_admin'] ?? false): ?>
                                <form method="POST" action="deletePost.php" class="delete-post">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="delete-btn">
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