<?php 
include 'config.php'; 

// Protect: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = ''; $success = '';
if ($_POST) {
    $date = $_POST['date'] ?? '';
    $category = $_POST['category'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $userId = $_SESSION['user_id'];

    if (!empty($date) && !empty($category) && !empty($amount) && !empty($type) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (date, category, amount, type, description, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$date, $category, $amount, $type, $description, $userId]);
            header('Location: index.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = "Error adding transaction: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Add Transaction</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <header>
        <h1>Add New Transaction</h1>
        <nav>
            <a href="index.php">View Dashboard</a>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="index.php?logout=1">Logout</a>
        </nav>
    </header>

    <main class="form-container">
        <?php if ($_GET['success'] ?? false) { echo '<div class="success">Transaction added successfully!</div>'; } ?>
        <?php if ($error) { echo "<div class='error'>$error</div>"; } ?>

        <form id="addForm" method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Food">Food</option>
                <option value="Transport">Transport</option>
                <option value="Salary">Salary</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Other">Other</option>
            </select>

            <label for="amount">Amount ($):</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" required>

            <label>Type:</label>
            <div class="radio-group">
                <label><input type="radio" name="type" value="Income" required> Income</label>
                <label><input type="radio" name="type" value="Expense" required> Expense</label>
            </div>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <button type="submit">Add Transaction</button>
        </form>
    </main>

    <script src="script.js"></script>
</body>
</html>