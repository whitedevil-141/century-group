<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireAuth(['admin','mod', 'hr']);
require_once __DIR__ . '/../config/permissions.php';
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
        <li><a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">Dashboard</a></li>

        <?php if (can('industries','read')): ?>
            <li><a href="industries.php" class="<?= basename($_SERVER['PHP_SELF'])=='industries.php'?'active':'' ?>">Industries</a></li>
        <?php endif; ?>

        <?php if (can('jobs','read')): ?>
            <li><a href="jobs.php" class="<?= basename($_SERVER['PHP_SELF'])=='jobs.php'?'active':'' ?>">Jobs</a></li>
        <?php endif; ?>

        <?php if (can('applications','read')): ?>
            <li><a href="applications.php" class="<?= basename($_SERVER['PHP_SELF'])=='applications.php'?'active':'' ?>">Applications</a></li>
        <?php endif; ?>

        <?php if (can('messages','read')): ?>
            <li><a href="messages.php" class="<?= basename($_SERVER['PHP_SELF'])=='messages.php'?'active':'' ?>">Messages</a></li>
        <?php endif; ?>

        <?php if (can('users','read')): ?>
            <li><a href="users.php" class="<?= basename($_SERVER['PHP_SELF'])=='users.php'?'active':'' ?>">Users</a></li>
        <?php endif; ?>


        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<div class="content">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']); ?> 👋</h2>
    <p>Select a section from the sidebar to manage data.</p>
    <!-- Overview Cards -->
    <div class="card-container">
        <?php if (can('industries','read')): ?>
            <div class="card">
                <h3><?= $industriesCount; ?></h3>
                <a href="industries.php">Industries</a>
            </div>
        <?php endif; ?>

        <?php if (can('jobs','read')): ?>
            <div class="card">
                <h3><?= $jobsCount; ?></h3>
                <a href="jobs.php">Jobs</a>
            </div>
        <?php endif; ?>

        <?php if (can('applications','read')): ?>
            <div class="card">
                <h3><?= $applicationsCount; ?></h3>
                <a href="applications.php">Applications</a>
            </div>
        <?php endif; ?>

        <?php if (can('messages','read')): ?>
            <div class="card">
                <h3><?= $messagesCount; ?></h3>
                <a href="messages.php">Messages</a>
            </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
