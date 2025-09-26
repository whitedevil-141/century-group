<?php
require_once __DIR__ . '/../config/Database.php';

$pdo = Database::connect();  // ✅ always a PDO object

echo "<h1>Century Group Backend</h1>";

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS user_count FROM users");
    $row = $stmt->fetch();
    echo "✅ Database connected. Users in table: " . $row['user_count'];
} catch (Exception $e) {
    echo "❌ Query failed: " . $e->getMessage();
}
