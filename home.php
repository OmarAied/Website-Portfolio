<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portfolio</title>
    <!-- <link rel="stylesheet" href="css/reset.css"> -->
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/mobile.css">
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
                <li><a href="#education">Education</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="addPost.php">Post</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <aside class="leftSidebar">
            <section class="research">
                <h2>RESEARCH INTERESTS</h2>
                <ul>
                    <li>Artificial Intelligence</li>
                    <li>Cyber Security</li>
                    <li>Game Development</li>
                    <li>CGI</li>
                </ul>
            </section>

            <section class="skills" id="skills">
                <h2>TOP 3 SKILLS</h2>
                <ol>
                    <li><img src="images/first.png" alt="">Python</li>
                    <li><img src="images/second.png" alt="">Java</li>
                    <li><img src="images/third.png" alt="">HTML/CSS</li>
                </ol>
            </section>
        </aside>

        <main>
            <section class="profile">
                <figure>
                    <img src="images/Profile pic.png" alt="Omar Aied">
                    <figcaption>Omar Aied</figcaption>
                </figure>

                <article>
                    <p>
                        My name is Omar Aied and I am a first year
                        student studying Computer Science and AI at Queen 
                        Mary University of London. I am an Egyptian muslim 
                        who has been living in the UK my whole life with
                        my family. I am a very ambitious person who is
                        always looking to learn new things and improve
                        my skills.
                    </p>
                </article>
            </section>
        </main>

        <aside class="rightSidebar">
            
            <section class="hobbies">
                <h2>HOBBIES</h2>
                <ul>
                    <li>Programming</li>
                    <li>Volleyball</li>
                    <li>Bouldering</li>
                    <li>Gym</li>
                </ul>
            </section>

            <section class="education" id="education">
                <h2>EDUCATION</h2>
                <ul>
                    <li>2024-2028 - BSc Computer Science & AI - Queen Mary University of London</li>
                    <li>2022-2024 - A levels - Coombe Sixth Form</li>
                    <li>2017-2022 - GCSE - Coombe Boys</li>
                </ul>
            </section>

        </aside>
    </div>

    <footer class = "footer">
        <p>&copy; 2025 Omar Aied</p>
        <div class = "socials">
            <p><a href="New cv.pdf" target="_blank">CV</a></p>
            <a href="mailto:ec24360@qmul.ac.uk" target="_blank"><img src="images/email.png" alt=""></a>
            <a href="https://github.com/OmarAied" target="_blank"><img src="images/github.png" alt=""></a>
            <a href="https://www.linkedin.com/in/omar-aied-096ba5278/" target="_blank"><img src="images/linkedin.png" alt=""></a>
        </div>
    </footer>
</body>
</html>
