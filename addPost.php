<?php
session_start();

//If not logged in then go to login page
if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    $alert_type = $_SESSION['alert_type'] ?? 'success';
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);
    echo '<div class="alert '.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_message).'</div>';
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

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $blog = trim($_POST['blog'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!empty($name) && !empty($blog)) {
        try{
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, author_name, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $name, $blog]);
        
        $_SESSION['alert_message'] = "Blog post submitted successfully!";
        $_SESSION['alert_type'] = 'success';
        // Clear form after successful submission
        header("Location: addPost.php");
        exit;
        }
        catch (PDOException $e) {
            $error = "Error submitting post. Please try again.";
            error_log("Post submission error: " . $e->getMessage());
        }
    }
    else {
        $_SESSION['alert_message'] = "Please fill in all fields";
        $_SESSION['alert_type'] = 'error';
        header("Location: addPost.php");
        exit;
    }
    
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <!-- <link rel="stylesheet" href="css/reset.css"> -->
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/loginAndPost.css">
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
                <li><a href="home.php">Skills</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="addPost.php">Post</a></li>
            </ul>
        </nav>
    </header>

    <?php if ($success): ?>
        <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <fieldset id = "main">
            <legend>BLOG POST</legend>
            <p>
                <label for="name">Name</label> <br>
                <input type="text" name="name" placeholder="John Smith" 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </p>

            <p>
                <label for="blog">Blog contents</label> <br>
                <div class="textarea-container">
                    <textarea id="blog" name="blog" placeholder="Write your blog here" 
                              rows="10" cols="50"><?php echo htmlspecialchars($_POST['blog'] ?? ''); ?></textarea>
                </div>
            </p>
            <footer>                
                <div class="buttons">
                    <br>
                    <button type="submit">Submit</button>
                    <br>
                    <button type="reset">Clear</button>
                </div>
            </footer>
        </fieldset>
    </form>
    
    <footer class = "footer">
        <p>&copy; 2025 Omar Aied</p>
        <div class = "socials">
            <p><a href="New cv.pdf" target="_blank">CV</a></p>
            <a href="mailto:ec24360@qmul.ac.uk" target="_blank"><img src="images/email.png" alt=""></a>
            <a href="https://github.com/OmarAied" target="_blank"><img src="images/github.png" alt=""></a>
            <a href="https://www.linkedin.com/in/omar-aied-096ba5278/" target="_blank"><img src="images/linkedin.png" alt=""></a>
        </div>
    </footer>

    <script src="js/alert.js"></script>
</body>
</html>