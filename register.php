<?php 
include 'config.php'; // Assume config.php has PDO $pdo; session_start() if not already called

// Avoid duplicate session_start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle POST registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Server-side validation
    if (empty($username) || empty($password) || strlen($password) < 8) {
        $error_msg = "Username and password are required. Password must be at least 8 characters.";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check duplicate username
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->execute([$username]);
        if ($checkStmt->fetch()) {
            $error_msg = "Username already taken. Please choose another.";
        } else {
            // Insert user
            $insertStmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($insertStmt->execute([$username, $hashedPassword])) {
                $success_msg = "Registration successful! <a href='login.php'>Login here</a> to start tracking your finances.";
            } else {
                $error_msg = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Register</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Adjusted CSS - Full for standalone, but links to style.css if needed */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-image: url('images/finance-bg.jpg'); /* Upload finance-themed image */
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* FIXED: Header - Logo left, Nav center/right */
        header { 
            background: #2c3e50; 
            color: white; 
            padding: 1rem 2rem; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            flex-shrink: 0; /* Prevent shrink */
        }
        .logo { 
            flex: 0 0 auto; 
        }
        .logo img { 
            width: 100px; 
            height: 70px; 
            border-radius: 4px; 
        }
        nav { 
            display: flex; 
            justify-content: center; 
            gap: 2rem; 
            flex: 1; 
        }
        nav a { 
            color: white; 
            text-decoration: none; 
            padding: 0.5rem 1rem; 
            border-radius: 4px; 
            transition: background 0.3s; 
            font-weight: bold; 
            font-size: 18px; 
        }
        nav a:hover { background: #34495e; }
        /* Main content - Centered form */
        main { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 2rem 0; 
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
            font-size: 2em;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #4a90e2;
            outline: none;
        }
        input[type="submit"] {
            background: #4a90e2;
            color: white;
            padding: 12px;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background: #357abd;
        }
        .success-msg, .error-msg {
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 1em;
        }
        .success-msg {
            color: #28a745;
            background: rgba(46, 204, 113, 0.1);
        }
        .error-msg {
            color: #e74c3c;
            background: rgba(231, 76, 60, 0.1);
        }
        .login-link {
            margin-top: 20px;
        }
        .login-link a {
            color: #4a90e2;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        /* FIXED: Footer - Fixed bottom, full width */
        footer { 
            background: #2c3e50; 
            color: white; 
            text-align: center; 
            padding: 1rem; 
            margin-top: auto; 
            flex-shrink: 0; 
        }
        @media (max-width: 600px) { 
            header { flex-direction: column; padding: 1rem; gap: 1rem; }
            .logo { order: 2; } /* Logo below nav on mobile */
            nav { order: 1; flex-direction: column; gap: 1rem; }
            .container { margin: 1rem; padding: 20px; }
        }
    </style>
</head>
<body>
    <header>
        <!-- FIXED: Logo on left corner - Use forward slash for cross-platform path -->
        <div class="logo">
            <img src="images/hi.jpg" alt="Finance Tracker Logo"> <!-- Upload 'hi.jpg' to images/ folder -->
        </div>
        <nav>
            <a href="home.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
            <a href="addnew.php">Tool</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a> <!-- Changed 'Sign-in' to 'Register' for clarity -->
        </nav>
    </header>

    <main>
        <div class="container">
            <h1>Finance Tracker - Register</h1>
            <p>Create an account to track your finances securely.</p>

            <form action="register.php" method="post" id="registerForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required minlength="3">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <input type="submit" value="Register">
            </form>

            <?php if (isset($success_msg)) { echo "<div class='success-msg'>$success_msg</div>"; } ?>
            <?php if (isset($error_msg)) { echo "<div class='error-msg'>$error_msg</div>"; } ?>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Finance Tracker | Secure Financial Management</p>
    </footer>

    <script>
        // JS Validation (client-side + format)
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                let valid = true;
                let errorMsg = '';

                const username = $('#username').val().trim();
                const password = $('#password').val();

                if (username.length < 3) {
                    valid = false;
                    errorMsg += 'Username must be at least 3 characters. ';
                }
                if (password.length < 8 || !/^[a-zA-Z0-9]+$/.test(password)) {
                    valid = false;
                    errorMsg += 'Password must be at least 8 alphanumeric characters. ';
                }

                if (!valid) {
                    e.preventDefault();
                    $('.error-msg').remove();
                    $('#registerForm').after('<div class="error-msg">' + errorMsg + '</div>');
                    return false;
                }
            });

            // Real-time password feedback
            $('#password').on('input', function() {
                const val = $(this).val();
                if (val.length < 8 || !/^[a-zA-Z0-9]+$/.test(val)) {
                    $(this).css('border-color', 'red');
                } else {
                    $(this).css('border-color', '#ddd');
                }
            });
        });
    </script>
</body>
</html>