<?php 
// Public landing page - no session required (links to login.php for app access)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Track Your Financial Journey</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Pure CSS - Hero with finance vector, features, responsive */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); 
            color: #333; 
            line-height: 1.6; 
        }
        header { 
            background: #2c3e50; 
            color: white; 
            padding: 1rem 0; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 0 2rem; 
        }
        /* FIXED: Logo on left corner */
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

        /* Hero Section - Finance Tracker Theme */
        .hero { 
            background: linear-gradient(rgba(52, 73, 94, 0.8), rgba(52, 73, 94, 0.8)), 
                        url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1920&h=1080&fit=crop'); /* Finance chart bg - replace if needed */
            background-size: cover; 
            background-position: center; 
            color: white; 
            text-align: center; 
            padding: 4rem 2rem; 
            animation: fadeIn 1s ease-in; 
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .hero h1 { font-size: 3rem; margin: 0 0 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .hero p { font-size: 1.2rem; margin: 0 0 2rem; }
        .cta-button { 
            background: #27ae60; 
            color: white; 
            padding: 1rem 2rem; 
            border: none; 
            border-radius: 4px; 
            font-size: 1.1rem; 
            cursor: pointer; 
            transition: background 0.3s, transform 0.3s; 
            text-decoration: none; 
            display: inline-block; 
        }
        .cta-button:hover { background: #219a52; transform: scale(1.05); }

        /* Features Section */
        .features { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 2rem; 
            padding: 4rem 2rem; 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        .feature-card { 
            background: white; 
            padding: 2rem; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            text-align: center; 
            transition: transform 0.3s; 
        }
        .feature-card:hover { transform: translateY(-5px); }
        .feature-card img { width: 130px; height: 80px; margin-bottom: 1rem; } /* Icon placeholders */
        .feature-card h3 { color: #2c3e50; }

        /* Footer */
        footer { 
            background: #2c3e50; 
            color: white; 
            text-align: center; 
            padding: 2rem; 
            margin-top: 4rem; 
        }

        @media (max-width: 600px) { 
            .hero h1 { font-size: 2rem; } 
            nav { flex-direction: column; gap: 1rem; } 
            header { flex-direction: column; padding: 1rem; }
            .logo { margin: 0 auto 1rem; }
        }
    </style>
</head>
<body>
    <header>
        <!-- FIXED: Logo on left corner - Upload image to 'images/logo.webp' in project folder -->
        <div class="logo">
            <img src="images\hi.jpg" alt="Finance Tracker Logo"> <!-- Relative path - upload your image here -->
        </div>
        <nav>
            <a href="home.php">Home</a>
            <a href="about.php">About us</a>
            <a href="contact.php">Contact Us</a>
            <a href="addnew.php">Quick add</a>
            <a href="login.php">Login</a> <!-- Link to your login.php -->
            <a href="register.php">Sign-in</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <h1>Track Your Financial Journey</h1>
        <p>Monitor income, expenses, and growth with our intuitive finance tracker app.</p>
        <a href="login.php" class="cta-button">Start Tracking Today</a>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="feature-card">
            <img src="images\a.jpg" alt="Expense Tracking Icon">
            <h3>Expense Tracking</h3>
            <p>Log daily expenses and categorize for better insights.</p>
        </div>
        <div class="feature-card">
            <img src="images\n.jpg" alt="Goal Setting Icon">
            <h3>Financial Goals</h3>
            <p>Set budgets and track progress toward savings targets.</p>
        </div>
        <div class="feature-card">
            <img src="images\b.png" alt="Reports Icon">
            <h3>Custom Reports</h3>
            <p>Generate charts and summaries for informed decisions.</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Finance Tracker.</p>
    </footer>

    <script>
        // jQuery for CTA hover
        $(document).ready(function() {
            $('.cta-button').hover(
                function() { $(this).css('transform', 'scale(1.05)'); },
                function() { $(this).css('transform', 'scale(1)'); }
            );
        });
    </script>
</body>
</html>