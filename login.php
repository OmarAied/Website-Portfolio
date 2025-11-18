<?php
// Start session
session_start();

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
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, email, name, password, is_admin FROM users WHERE email = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = (bool)$user['is_admin'];
            
            // Redirect to a protected page
            if($_SESSION['is_admin']){
                $_SESSION['alert_message'] = 'Login successful! Welcome back, ' .$user['name']. '!
                (Admin)';
                $_SESSION['alert_type'] = 'success';
            }
            else{
                $_SESSION['alert_message'] = 'Login successful! Welcome back, ' .$user['name']. '!
                (Not admin)';
                $_SESSION['alert_type'] = 'success';
            }
            session_write_close();
            header('Location: addEntry.php');
            exit;
        } else {
            $_SESSION['alert_message'] = "Invalid email or password";
            $_SESSION['alert_type'] = 'error';
        }
    } else {
        $_SESSION['alert_message'] = "Please fill in all fields";
        $_SESSION['alert_type'] = 'error';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="js/alert.js" defer></script>
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/mobile.css">
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
                <li><a href="viewBlog.php">View Blogs</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="addEntry.php">Add Post</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <form method="POST" action="login.php">
        <fieldset id="login">
            <legend>LOGIN</legend>
            
            <?php if (isset($_SESSION['alert_message'])): ?>
                <div class="alert <?php echo $_SESSION['alert_type'] ?? 'info'; ?>">
                    <?php echo htmlspecialchars($_SESSION['alert_message']); ?>
                </div>
                <?php unset($_SESSION['alert_message']); ?>
            <?php endif; ?>
            
            <p>
                <label for="email">Email Address</label> <br>
                <input type="email" name="email" placeholder="johnsmith@email.com">
            </p>

            <p>
                <label for="password">Password</label> <br>
                <input type="password" name="password" placeholder="password123" minlength="8" maxlength="20">
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