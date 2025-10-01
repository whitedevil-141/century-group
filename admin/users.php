<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireAuth(['admin']);
require_once __DIR__ . '/../config/permissions.php';
require_once __DIR__ . '/../config/db.php';
$db = Database::connect();

// Handle new user form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === "add") {
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role     = $_POST['role'];

        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
        $stmt->execute([$username, $password, $role]);
    }
    if ($_POST['action'] === "edit") {
        $id       = (int) $_POST['id'];
        $username = trim($_POST['username']);
        $role     = $_POST['role'];

        $sql = "UPDATE users SET username=?, role=?";
        $params = [$username, $role];

        if (!empty($_POST['password'])) {
            $sql .= ", password=?";
            $params[] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        $sql .= " WHERE id=?";
        $params[] = $id;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }
    if ($_POST['action'] === "delete") {
        $id = (int) $_POST['id'];
        $stmt = $db->prepare("DELETE FROM users WHERE id=? AND role!='admin'");
        $stmt->execute([$id]);
    }
}

// Fetch all users
$users = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .actions form { display:inline-block; }
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
    <h2>User Management</h2>

    <!-- Add User Form -->
    <h3>Add User</h3>
    <form method="POST" class="form-card">
        <input type="hidden" name="action" value="add">
        <div><input type="text" name="username" placeholder="Username" required></div>
        <div><input type="password" name="password" placeholder="Password" required></div>
        <div>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="mod">Moderator</option>
                <option value="hr">HR</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">Add User</button>
    </form>

    <!-- Users List -->
    <h3 style="margin-top:30px;">Existing Users</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users): ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= $u['role'] ?></td>
                        <td><?= $u['created_at'] ?></td>
                        <td class="actions">
                            <!-- Edit User -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>" required>
                                <input type="password" name="password" placeholder="New Password (optional)">
                                <select name="role">
                                    <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option>
                                    <option value="mod" <?= $u['role']=='mod'?'selected':'' ?>>Moderator</option>
                                    <option value="hr" <?= $u['role']=='hr'?'selected':'' ?>>HR</option>
                                </select>
                                <button type="submit" class="btn-primary">Update</button>
                            </form>

                            <!-- Delete User (only non-admins) -->
                            <?php if ($u['role'] !== 'admin'): ?>
                                <form method="POST" onsubmit="return confirm('Delete this user?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn-danger">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
