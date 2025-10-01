<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireAuth(['admin','mod']);
require_once __DIR__ . '/../config/permissions.php';
require __DIR__ . '/../config/db.php';

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch all messages (latest first)
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; }
        .actions button { margin-right: 5px; }
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
    <h2>Messages</h2>

    <!-- Messages List -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Received At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($messages): ?>
                <?php foreach ($messages as $i => $msg): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></td>
                        <td><a href="tel:<?= htmlspecialchars($msg['phone']) ?>"><?= htmlspecialchars($msg['phone']) ?></a></td>
                        <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                        <td><?= date("M d, Y h:i A", strtotime($msg['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
