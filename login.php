<?php include 'config.php'; 

$loginError = '';
if ($_POST) {
    $userInput = $_POST['username'] ?? '';
    $passInput = $_POST['password'] ?? '';

    if (!empty($userInput) && !empty($passInput)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$userInput]);
        $user = $stmt->fetch();

        if ($user && password_verify($passInput, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Login</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Inlined CSS for login page (add to style.css if preferred) */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: #333; 
            /* NEW: Background Image + Gradient Overlay */
            background-image: linear-gradient(rgba(102, 126, 234, 0.7), rgba(118, 75, 162, 0.7)), url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1920&h=1080&fit=crop'); /* Semi-transparent gradient over image */
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed; /* Parallax effect on scroll */
        }
        .login-container { 
            max-width: 400px; 
            width: 100%; 
            padding: 2rem; 
            background: rgba(255, 255, 255, 0.95); /* Semi-transparent white for readability over bg */
            border-radius: 8px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            text-align: center; 
            animation: fadeIn 0.5s; 
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .login-container h1 { color: #4a90e2; margin: 1rem 0 0.5rem 0; }
        .login-container p { margin-bottom: 1rem; color: #666; }
        .login-container small { color: #999; }
        #loginForm { display: flex; flex-direction: column; gap: 1rem; }
        #loginForm input { padding: 0.75rem; font-size: 1rem; border: 1px solid #ddd; border-radius: 4px; }
        #loginForm button { margin-top: 1rem; background: #4a90e2; color: white; padding: 0.75rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        #loginForm button:hover { background: #357abd; }
        .error { background: red; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }

        @media (max-width: 600px) { 
            body { background-attachment: scroll; } /* No parallax on mobile for perf */
            .login-container { margin: 1rem; padding: 1.5rem; }
        }
    </style>
</head>
<body class="login-body">
    <main class="login-container">
        <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=400&h=300&fit=crop&crop=face" alt="Personal Finance Wallet Icon" class="login-image">
        <h1>Welcome to Personal Finance Tracker</h1>
        <p>Please log in to manage your finances.</p>
        
        <?php if ($loginError) { echo "<div class='error'>$loginError</div>"; } ?>

        <form id="loginForm" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" minlength="8" required>

            <button type="submit">Login</button>
        </form>

        
    </main>

    <script src="script.js"></script>
</body>
</html>