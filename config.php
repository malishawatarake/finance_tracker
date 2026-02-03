<?php
session_start();  // For login sessions

// DB config - Update for deployment (e.g., 000webhost)
$host = 'sql310.infinityfree.com';
$dbname = 'if0_40365345_epiz_12345678_finance';
$username = 'if0_40365345';  // Local: root; Deploy: your DB user
$password = 'Malisha2980';  // Local: empty; Deploy: your DB pass

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());  // Graceful error
}
?>