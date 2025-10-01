<?php
require_once __DIR__ . '/../config/auth.php';
secureSessionStart();
requireAuth(['admin','mod', 'hr']);
require_once __DIR__ . '/../config/permissions.php';
$role = $_SESSION['user']['role'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Jobs</title>
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
    <h2>Jobs</h2>
    <?php if (can('jobs','create')): ?>
        <button id="openModalBtn" class="btn-primary">➕ Add Job</button>
    <?php endif; ?>
    <!-- Add Modal -->
    <div id="jobModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Job</h3>
            <form id="addJobForm" class="form-card">
                <input type="hidden" name="action" value="add">
                <div class="form-group"><label>Title *</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Department</label><input type="text" name="department_name"></div>
                <div class="form-group"><label>Location</label><input type="text" name="location"></div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="job_type" required>
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="4"></textarea></div>
                <button type="submit" class="btn-primary">Save Job</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Edit Job</h3>
            <form id="editJobForm" class="form-card">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-group"><label>Title *</label><input type="text" name="title" id="edit-title" required></div>
                <div class="form-group"><label>Department</label><input type="text" name="department_name" id="edit-department"></div>
                <div class="form-group"><label>Location</label><input type="text" name="location" id="edit-location"></div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="job_type" id="edit-type" required>
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
                <div class="form-group"><label>Description</label><textarea name="description" id="edit-description" rows="4"></textarea></div>
                <button type="submit" class="btn-primary">Update Job</button>
            </form>
        </div>
    </div>

    <!-- Job List -->
    <table id="jobTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Department</th>
                <th>Location</th>
                <th>Type</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script src="../admin/assets/js/jobs.js"></script>
<script>
   const permissions = <?= json_encode($permissions[$role]) ?>;
</script>
</body>
</html>
