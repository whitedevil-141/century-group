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
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="industries.php">Industries</a></li>
        <li><a href="jobs.php" class="active">Jobs</a></li>
        <li><a href="applications.php">Applications</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <h2>Jobs</h2>
    <button id="openModalBtn" class="btn-primary">➕ Add Job</button>

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
</body>
</html>
