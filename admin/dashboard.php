<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireLogin(); // redirect if not logged in
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/assets/css/admin.css">
    
</head>
<body>
<div class="sidebar">
    <h3 style="padding:10px;">Century Admin</h3>
    <ul>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="industries.php">Industries</a></li>
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="applications.php">Applications</a></li>
        <li><a href="messages.php">Messages</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<div class="content">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']); ?> 👋</h2>
    <p>Select a section from the sidebar to manage data.</p>
</div>
</body>
</html>
