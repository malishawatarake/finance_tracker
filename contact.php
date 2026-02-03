<?php 
// Contact Us page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Finance Tracker</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
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

        /* Contact Us Section */
        .contact { 
            padding: 4rem 2rem; 
            max-width: 1200px; 
            margin: 0 auto; 
            text-align: center; 
            background: #f8f9fa; 
            margin-bottom: 2rem; 
        }
        .contact h1 { color: #2c3e50; margin-bottom: 2rem; }
        .contact p { font-size: 1.1rem; margin-bottom: 2rem; }
        .contact-info { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 2rem; 
            margin-top: 2rem; 
        }
        .contact-item { 
            background: white; 
            padding: 1.5rem; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        .contact-item h4 { color: #2c3e50; margin-bottom: 0.5rem; }
        .contact-item a { 
            color: #27ae60; 
            text-decoration: none; 
            font-weight: bold; 
        }
        .contact-item a:hover { text-decoration: underline; }
        .back-button { 
            background: #27ae60; 
            color: white; 
            padding: 0.8rem 1.5rem; 
            border: none; 
            border-radius: 4px; 
            font-size: 1rem; 
            cursor: pointer; 
            transition: background 0.3s; 
            text-decoration: none; 
            display: inline-block; 
            margin-top: 2rem; 
        }
        .back-button:hover { background: #219a52; }

        /* Footer */
        footer { 
            background: #2c3e50; 
            color: white; 
            text-align: center; 
            padding: 2rem; 
            margin-top: 4rem; 
        }

        @media (max-width: 600px) { 
            nav { flex-direction: column; gap: 1rem; } 
            header { flex-direction: column; padding: 1rem; }
            .logo { margin: 0 auto 1rem; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images\hi.jpg" alt="Finance Tracker Logo">
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About us</a>
            <a href="contact.php">Contact Us</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <!-- Contact Us Section -->
    <section class="contact">
        <h1>Contact Us</h1>
        <p>Get in touch with us for support, feedback, or inquiries.</p>
        <div class="contact-info">
            <div class="contact-item">
                <h4>Email</h4>
                <p><a href="mailto:info@financetracker.com">info@financetracker.com</a></p>
            </div>
            <div class="contact-item">
                <h4>Phone</h4>
                <p><a href="tel:+1234567890">+94 077-3003000</a></p>
            </div>
            <div class="contact-item">
                <h4>Support Line</h4>
                <p><a href="tel:+1987654321">+94 081-5554440</a></p>
            </div>
            <!-- Add more contact details here as needed -->
        </div>
        <a href="home.php" class="back-button">Back to Home</a>
    </section>

    <footer>
        <p>&copy; 2025 Finance Tracker.</p>
    </footer>

    <script>
        // jQuery for smooth animations if needed
        $(document).ready(function() {
            // Add any page-specific scripts here
        });
    </script>
</body>
</html>