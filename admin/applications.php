<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';
$pdo = Database::connect();

$apps = $pdo->query("SELECT a.*, j.title as job_title FROM applications a LEFT JOIN jobs j ON a.job_id=j.id ORDER BY a.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Applications</title>
    <link rel="stylesheet" href="../public/assets/css/admin.css">
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
    <table>
        <tr><th>Name</th><th>Email</th><th>Job</th><th>CV</th><th>Status</th></tr>
        <?php foreach ($apps as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['name']) ?></td>
            <td><?= htmlspecialchars($app['email']) ?></td>
            <td><?= htmlspecialchars($app['job_title']) ?></td>
            <td><a href="../<?= $app['cv_path'] ?>" target="_blank">Download CV</a></td>
            <td><?= htmlspecialchars($app['status']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
