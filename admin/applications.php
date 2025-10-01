<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireAuth(['admin','mod', 'hr']);
require_once __DIR__ . '/../config/permissions.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Applications</title>
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        textarea { width: 100%; min-height: 50px; }
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
    <h2>Applications</h2>
    <table id="appsTable">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Phone</th><th>Job</th><th>CV</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script src="../admin/assets/js/applications.js"></script>
</body>
</html>
