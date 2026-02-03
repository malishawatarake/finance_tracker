<?php 
include 'config.php'; 

// Protect: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$transactionId = $_GET['id'] ?? 0;
$error = ''; $success = '';

// Fetch existing data for pre-fill
$transaction = null;
if ($transactionId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$transactionId, $userId]);
    $transaction = $stmt->fetch();
    if (!$transaction) {
        header('Location: index.php?error=invalid');
        exit;
    }
} else {
    header('Location: index.php?error=invalid');
    exit;
}

// Handle UPDATE on submit
if ($_POST) {
    $date = $_POST['date'] ?? '';
    $category = $_POST['category'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($date) && !empty($category) && !empty($amount) && !empty($type) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("UPDATE transactions SET date = ?, category = ?, amount = ?, type = ?, description = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$date, $category, $amount, $type, $description, $transactionId, $userId]);
            if ($stmt->rowCount() > 0) {
                header('Location: index.php?success=updated');
                exit;
            } else {
                $error = "Update failed: No changes or access denied.";
            }
        } catch (PDOException $e) {
            $error = "Update error: " . $e->getMessage();
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
    <title>Finance Tracker - Edit Transaction</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* All CSS from style.css inlined here */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #f5f7fa, #c3cfe2); color: #333; }
        header { background: #4a90e2; color: white; padding: 1rem; text-align: center; }
        header h1 { margin: 0; }
        nav { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        nav a, nav span { color: white; text-decoration: none; margin: 0 0.5rem; }
        nav a:hover { text-decoration: underline; }

        .dashboard, .form-container { max-width: 800px; margin: 2rem auto; padding: 1rem; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .summary { text-align: center; margin-bottom: 2rem; }
        .stats { display: flex; justify-content: space-around; }
        .stat { font-size: 1.2em; padding: 0.5rem; background: #f0f0f0; border-radius: 4px; }
        .balance { font-weight: bold; color: green; }

        .chart-section { text-align: center; margin: 2rem 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.5rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #4a90e2; color: white; }

        form { display: grid; gap: 1rem; }
        label { font-weight: bold; }
        input, select, textarea { padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; width: 100%; box-sizing: border-box; }
        .radio-group { display: flex; gap: 1rem; }
        button { background: #4a90e2; color: white; padding: 0.75rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; width: 100px; text-align: center; margin-left: 25rem}
        button:hover { background: #357abd; }
        .error { background: red; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .success { background: green; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }

        /* Login Styles (for consistency, even if not used here) */
        .login-body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { max-width: 400px; width: 100%; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; animation: fadeIn 0.5s; }
        .login-container h1 { color: #4a90e2; margin-bottom: 1rem; }
        .login-container p { margin-bottom: 2rem; color: #666; }
        .login-container small { color: #999; }
        #loginForm { display: flex; flex-direction: column; gap: 1rem; }
        #loginForm input { padding: 0.75rem; font-size: 1rem; }
        #loginForm button { margin-top: 1rem; background: #4a90e2; }

        /* Action Buttons (for index.php table) */
        .actions { text-align: center; white-space: nowrap; }
        .btn-update, .btn-delete, .btn-cancel { 
            display: inline-block; padding: 0.25rem 0.5rem; margin: 0 0.25rem; 
            border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9em; 
        }
        .btn-update { background: #28a745; color: white; }
        .btn-update:hover { background: #218838; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #c82333; }
        .btn-cancel { background: #6c757d; color: white; padding: 0.75rem; margin-left: 25rem; width:75px; text-align: center; }
        .btn-cancel:hover { background: #5a6268; }

        form[onsubmit] button { background: #dc3545; color: white; padding: 0.25rem 0.5rem; border: none; border-radius: 4px; cursor: pointer; }
        table { table-layout: fixed; } /* Better column sizing */
        th:last-child, td:last-child { width: 150px; } /* Space for actions */

        @media (max-width: 600px) { 
            .stats { flex-direction: column; } 
            .radio-group { flex-direction: column; } 
            nav { flex-direction: column; gap: 0.5rem; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Transaction</h1>
        <nav>
            <a href="index.php">View Dashboard</a>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="index.php?logout=1">Logout</a>
        </nav>
    </header>

    <main class="form-container">
        <?php if ($error) { echo "<div class='error'>$error</div>"; } ?>

        <form id="editForm" method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($transaction['date'] ?? ''); ?>" required>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Food" <?php echo ($transaction['category'] ?? '') == 'Food' ? 'selected' : ''; ?>>Food</option>
                <option value="Transport" <?php echo ($transaction['category'] ?? '') == 'Transport' ? 'selected' : ''; ?>>Transport</option>
                <option value="Salary" <?php echo ($transaction['category'] ?? '') == 'Salary' ? 'selected' : ''; ?>>Salary</option>
                <option value="Entertainment" <?php echo ($transaction['category'] ?? '') == 'Entertainment' ? 'selected' : ''; ?>>Entertainment</option>
                <option value="Other" <?php echo ($transaction['category'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="amount">Amount ($):</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" value="<?php echo htmlspecialchars($transaction['amount'] ?? ''); ?>" required>

            <label>Type:</label>
            <div class="radio-group">
                <label><input type="radio" name="type" value="Income" <?php echo ($transaction['type'] ?? '') == 'Income' ? 'checked' : ''; ?> required> Income</label>
                <label><input type="radio" name="type" value="Expense" <?php echo ($transaction['type'] ?? '') == 'Expense' ? 'checked' : ''; ?> required> Expense</label>
            </div>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($transaction['description'] ?? ''); ?></textarea>

            <button type="submit">Update</button>
            <a href="index.php" class="btn-cancel">Cancel</a>
        </form>
    </main>

    <script src="script.js"></script>
    <script>
        // Reuse addForm validation logic for editForm
        $(document).ready(function() {
            if ($('#editForm').length) {
                // Copy-paste the addForm submit/input handlers from script.js here, but change '#addForm' to '#editForm'
                $('#editForm').on('submit', function(e) {
                    // Same validation as add.php
                    let valid = true;
                    let errorMsg = '';
                    if (!$('#date').val() || !$('#category').val() || !$('#amount').val() || !$('input[name="type"]:checked').val() || !$('#description').val()) {
                        valid = false;
                        errorMsg += 'All fields are required. ';
                    }
                    let amount = $('#amount').val();
                    if (amount && (!/^\d+(\.\d{1,2})?$/.test(amount) || parseFloat(amount) <= 0)) {
                        valid = false;
                        errorMsg += 'Amount must be a positive number with up to 2 decimals. ';
                    }
                    let date = $('#date').val();
                    if (date && !/^\d{4}-\d{2}-\d{2}$/.test(date)) {
                        valid = false;
                        errorMsg += 'Invalid date format. ';
                    }
                    if (!valid) {
                        e.preventDefault();
                        $('.error').html(errorMsg).show();
                        return false;
                    }
                });
                $('#amount').on('input', function() {
                    let val = $(this).val();
                    if (val && (!/^\d+(\.\d{1,2})?$/.test(val) || parseFloat(val) <= 0)) {
                        $(this).css('border-color', 'red');
                    } else {
                        $(this).css('border-color', '#ddd');
                    }
                });
            }
        });
    </script>
</body>
</html>