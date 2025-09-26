<?php

$path = __DIR__ . '/../config/Database.php';
if (!file_exists($path)) {
    die("❌ Database.php not found at: $path");
}

require_once $path;

if (!class_exists('Database')) {
    die("❌ Database class not defined in Database.php");
}

$pdo = Database::connect();

echo "<h1>Century Group Backend</h1>";

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS user_count FROM users");
    $row = $stmt->fetch();
    echo "✅ Database connected. Users in table: " . $row['user_count'];
} catch (Exception $e) {
    echo "❌ Query failed: " . $e->getMessage();
}
