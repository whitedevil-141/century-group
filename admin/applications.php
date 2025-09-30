<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
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
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="industries.php">Industries</a></li>
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="applications.php" class="active">Applications</a></li>
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

<script src="../public/assets/js/applications.js"></script>
</body>
</html>
