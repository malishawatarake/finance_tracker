<?php 
include 'config.php'; 

// Protect: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Logout handler
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Delete handler
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $userId = $_SESSION['user_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
        $stmt->execute([$deleteId, $userId]);
        if ($stmt->rowCount() > 0) {
            $success = "Transaction deleted successfully!";
        } else {
            $error = "Transaction not found or access denied.";
        }
    } catch (PDOException $e) {
        $error = "Delete error: " . $e->getMessage();
    }
    header('Location: index.php');
    exit;
}

// Fetch data
$userId = $_SESSION['user_id'];
$incomeStmt = $pdo->prepare("SELECT SUM(amount) as total FROM transactions WHERE type='Income' AND user_id = ?");
$incomeStmt->execute([$userId]);
$income = $incomeStmt->fetch()['total'] ?? 0;
$expenseStmt = $pdo->prepare("SELECT SUM(amount) as total FROM transactions WHERE type='Expense' AND user_id = ?");
$expenseStmt->execute([$userId]);
$expense = $expenseStmt->fetch()['total'] ?? 0;
$balance = $income - $expense;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Inlined CSS (add to style.css if preferred) */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            /* NEW: Background Image + Overlay */
            background: linear-gradient(rgba(245, 247, 250, 0.8), rgba(195, 207, 226, 0.8)), 
                        url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1920&h=1080&fit=crop'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed; 
            color: #333; 
        }
        .logo { 
            flex: 0 0 auto;
            margin-right: 1400px;
        }
        .logo img { 
            width: 100px; 
            height: 70px; 
            border-radius: 4px; 
        
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
    }
        header { background: #0c75deff(74, 144, 226, 0.95); color: white; padding: 0; } /* Semi-transparent header */
        
        header h1 { margin:0; padding: 0.02px; text-align: center; background: #0c75deff;}
        /* FIXED: Logo on left corner */
        
        
        /* Retained Navigation Bar */
        nav { 
            display: flex; 
            justify-content: space-around; 
            align-items: center; 
            padding: 0.5rem 1rem; 
            background: #2c3e50; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        nav a { 
            color: white; 
            text-decoration: none; 
            padding: 0.5rem 1rem; 
            border-radius: 4px; 
            transition: background 0.3s; 
            font-weight: bold; 
        }
        nav a:hover, nav a.active { 
            background: #000000ff; 
        }

        .dashboard { max-width: 800px; margin: 2rem auto; padding: 1rem; background: rgba(255, 255, 255, 0.95); border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .summary { text-align: center; margin-bottom: 2rem; }
        .stats { display: flex; justify-content: space-around; }
        .stat { font-size: 1.2em; padding: 0.5rem; background: #f0f0f0; border-radius: 4px; }
        .balance { font-weight: bold; color: green; }

        .chart-section { text-align: center; margin: 2rem 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; table-layout: fixed; }
        th, td { padding: 0.5rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2c3e50; color: white; }
        th:last-child, td:last-child { width: 150px; }

        .error { background: red; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .success { background: green; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }

        /* Action Buttons */
        .actions { text-align: center; white-space: nowrap; }
        .btn-update, .btn-delete, .btn-cancel { 
            display: inline-block; padding: 0.25rem 0.5rem; margin: 0 0.25rem; 
            border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9em; 
        }
        .btn-update { background: #28a745; color: white; }
        .btn-update:hover { background: #218838; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #c82333; }
        .btn-cancel { background: #6c757d; color: white; padding: 0.75rem; margin-left: 1rem; }
        .btn-cancel:hover { background: #5a6268; }

        form[onsubmit] button { background: #dc3545; color: white; padding: 0.25rem 0.5rem; border: none; border-radius: 4px; cursor: pointer; }

        @media (max-width: 600px) { 
            .stats { flex-direction: column; } 
            nav { flex-direction: column; gap: 0.5rem; padding: 0.5rem; }
            body { background-attachment: scroll; } /* Mobile perf */
            .dashboard { margin: 1rem; padding: 1rem; }
        }
    </style>
</head>
<body>
    <header>
        
           <h1>Personal Finance Tracker
            <div class="logo">
            <img src="images\hi.jpg" alt="Finance Tracker Logo">
           </div>
    </h1>
        <nav>
            <a href="index.php" class="active">Dashboard</a>
            <a href="add.php">Add Transaction</a>       
            <a href="home.php">Logout</a>
        </nav>
        
    </header>

    <main class="dashboard">
        <?php if (isset($success)) { echo "<div class='success'>$success</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>

        <section class="summary">
            <h2>Quick Summary</h2>
            <div class="stats">
                <div class="stat">Income: $<?php echo number_format($income, 2); ?></div>
                <div class="stat">Expenses: $<?php echo number_format($expense, 2); ?></div>
                <div class="stat balance">Balance: $<?php echo number_format($balance, 2); ?></div>
            </div>
        </section>

        <section class="chart-section">
            <canvas id="pieChart" width="300" height="300"></canvas>
        </section>

        <section class="transactions">
            <h2>Transaction History</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Date</th><th>Category</th><th>Amount</th><th>Type</th><th>Description</th><th><center>Actions</center></th></tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
                    $stmt->execute([$userId]);
                    while ($row = $stmt->fetch()) {
                        $color = $row['type'] == 'Income' ? 'green' : 'red';
                        echo "<tr style='color: $color;'>";
                        echo "<td>{$row['id']}</td><td>{$row['date']}</td><td>{$row['category']}</td><td>$" . number_format($row['amount'], 2) . "</td><td>{$row['type']}</td><td>{$row['description']}</td>";
                        echo "<td class='actions'><a href='edit.php?id={$row['id']}' class='btn-update'>Update</a> ";
                        echo "<form method='POST' style='display:inline;' onsubmit='return confirm(\"Delete this transaction?\");'><input type='hidden' name='delete_id' value='{$row['id']}'> ";
                        echo "<button type='submit' class='btn-delete'>Delete</button></form></td></tr>";
                    }
                    if ($stmt->rowCount() == 0) {
                        echo "<tr><td colspan='7'>No transactions yet. Add one!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="script.js"></script>
    <script>
        const canvas = document.getElementById('pieChart');
        const ctx = canvas.getContext('2d');
        const income = <?php echo $income; ?>;
        const expense = <?php echo $expense; ?>; 
        const total = income + expense;
        if (total > 0) {
            const incomeAngle = (income / total) * 2 * Math.PI;
            ctx.beginPath(); ctx.moveTo(150, 150); ctx.arc(150, 150, 150, 0, incomeAngle); ctx.closePath(); ctx.fillStyle = 'green'; ctx.fill();
            ctx.beginPath(); ctx.moveTo(150, 150); ctx.arc(150, 150, 150, incomeAngle, 2 * Math.PI); ctx.closePath(); ctx.fillStyle = 'red'; ctx.fill();
        } else {
            ctx.fillStyle = 'black'; ctx.fillText('No Data', 100, 150);
        }
    </script>
</body>
</html>
