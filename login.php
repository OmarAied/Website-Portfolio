<?php
// Start session
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'user_auth';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password (empty)

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (!empty($email) && !empty($password)) {
        // Prepare SQL statement
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to a protected page
            header('Location: addPost.php');
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Please fill in all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                <li><a href="home.php#skills">Skills</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="addPost.php">Post</a></li>
            </ul>
        </nav>
    </header>

    <form method="POST" action="login.php">
        <fieldset id="login">
            <legend>LOGIN</legend>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <p>
                <label for="email">Email Address</label> <br>
                <input type="email" name="email" placeholder="johnsmith@email.com" required>
            </p>

            <p>
                <label for="password">Password</label> <br>
                <input type="password" name="password" placeholder="password123" minlength="8" maxlength="20" required>
            </p>
            <footer>                
                <div class="buttons">
                    <br>
                    <button type="submit">Submit</button>
                </div>
            </footer>
        </fieldset>
    </form>

    <footer class="footer">
        <p>&copy; 2025 Omar Aied</p>
        <div class="socials">
            <p><a href="New cv.pdf" target="_blank">CV</a></p>
            <a href="mailto:ec24360@qmul.ac.uk" target="_blank"><img src="images/email.png" alt=""></a>
            <a href="https://github.com/OmarAied" target="_blank"><img src="images/github.png" alt=""></a>
            <a href="https://www.linkedin.com/in/omar-aied-096ba5278/" target="_blank"><img src="images/linkedin.png" alt=""></a>
        </div>
    </footer>
    
</body>
</html>