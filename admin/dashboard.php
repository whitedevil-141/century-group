<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireLogin();

// Connect to DB
require_once __DIR__ . '/../config/db.php';
$db = Database::connect();
// Fetch counts
$industriesCount = $db->query("SELECT COUNT(*) FROM industries")->fetchColumn();
$jobsCount = $db->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
$applicationsCount = $db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$messagesCount = $db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }
        .card p {
            font-size: 16px;
            color: #666;
        }
    </style>
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
    <!-- Overview Cards -->
    <div class="card-container">
        
        <div class="card">
            <h3><?= $industriesCount; ?></h3>
            <a href="industries.php">Industries</a>
        </div>
        <div class="card">
            <h3><?= $jobsCount; ?></h3>
            <a href="jobs.php">Jobs</a>
        </div>
        <div class="card">
            <h3><?= $applicationsCount; ?></h3>
            <a href="applications.php">Applications</a>
        </div>
        <div class="card">
            <h3><?= $messagesCount; ?></h3>
            <p>Messages</p>
        </div>
    </div>
</div>
</body>
</html>
