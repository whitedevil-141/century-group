<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';
$pdo = Database::connect();

// ✅ Add job
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add') {
    $stmt = $pdo->prepare("
        INSERT INTO jobs (title, department_name, location, job_type, description) 
        VALUES (?,?,?,?,?)
    ");
    $stmt->execute([
        $_POST['title'],
        $_POST['department_name'],
        $_POST['location'],
        $_POST['job_type'],
        $_POST['description']
    ]);
    header("Location: jobs.php");
    exit;
}

// ✅ Delete job
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM jobs WHERE id=?")->execute([$id]);
    header("Location: jobs.php");
    exit;
}

// ✅ Edit job
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("
        UPDATE jobs 
        SET title=?, department_name=?, location=?, job_type=?, description=? 
        WHERE id=?
    ");
    $stmt->execute([
        $_POST['title'],
        $_POST['department_name'],
        $_POST['location'],
        $_POST['job_type'],
        $_POST['description'],
        $id
    ]);
    header("Location: jobs.php");
    exit;
}

// ✅ Fetch all jobs
$jobs = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jobs</title>
    <link rel="stylesheet" href="../public/assets/css/admin.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
<div class="sidebar">
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="industries.php">Industries</a></li>
        <li><a href="jobs.php" class="active">Jobs</a></li>
        <li><a href="applications.php">Applications</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <h2>Jobs</h2>

    <!-- Add Job -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="title" placeholder="Job Title" required>
        <input type="text" name="department_name" placeholder="Department Name">
        <input type="text" name="location" placeholder="Location">

        <select name="job_type" required>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
            <option value="Internship">Internship</option>
        </select>

        <textarea name="description" placeholder="Job Description"></textarea>

        <button type="submit">Add Job</button>
    </form>

    <!-- Job List -->
    <table>
        <tr>
            <th>Title</th>
            <th>Department</th>
            <th>Location</th>
            <th>Type</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($jobs as $job): ?>
        <tr>
            <td><?= htmlspecialchars($job['title']) ?></td>
            <td><?= htmlspecialchars($job['department_name']) ?></td>
            <td><?= htmlspecialchars($job['location']) ?></td>
            <td><?= htmlspecialchars($job['job_type']) ?></td>
            <td><?= htmlspecialchars($job['created_at']) ?></td>
            <td class="actions">
                <a href="jobs.php?edit=<?= $job['id'] ?>">Edit</a>
                <a href="?delete=<?= $job['id'] ?>" onclick="return confirm('Delete this job?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Edit Form -->
    <?php if (isset($_GET['edit'])): 
        $editId = (int) $_GET['edit'];
        $editStmt = $pdo->prepare("SELECT * FROM jobs WHERE id=?");
        $editStmt->execute([$editId]);
        $job = $editStmt->fetch();
        if ($job): ?>
        <h3>Edit Job</h3>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $job['id'] ?>">

            <input type="text" name="title" value="<?= htmlspecialchars($job['title']) ?>" required>
            <input type="text" name="department_name" value="<?= htmlspecialchars($job['department_name']) ?>">
            <input type="text" name="location" value="<?= htmlspecialchars($job['location']) ?>">

            <select name="job_type" required>
                <option <?= $job['job_type']=="Full Time" ? "selected" : "" ?>>Full Time</option>
                <option <?= $job['job_type']=="Part Time" ? "selected" : "" ?>>Part Time</option>
                <option <?= $job['job_type']=="Internship" ? "selected" : "" ?>>Internship</option>
            </select>

            <textarea name="description"><?= htmlspecialchars($job['description']) ?></textarea>

            <button type="submit">Update Job</button>
        </form>
    <?php endif; endif; ?>
</div>
</body>
</html>
