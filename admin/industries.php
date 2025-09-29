<!DOCTYPE html>
<html>
<head>
    <title>Industries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/assets/css/admin.css">
    <style>
        img.thumb { width: 60px; height: auto; margin-right: 5px; }
        .image-wrapper { position:relative; display:inline-block; margin:5px; border-radius:6px; overflow:hidden; }
        .image-wrapper img { width:80px; height:80px; object-fit:cover; border:1px solid #ddd; border-radius:6px; transition:transform 0.3s ease; }
        .image-wrapper:hover img { transform:scale(1.05); }
        .image-wrapper .actions { position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); display:flex; justify-content:center; align-items:center; gap:10px; opacity:0; transition:opacity 0.3s ease; }
        .image-wrapper:hover .actions { opacity:1; }
        .image-wrapper .actions button { border:none; border-radius:50%; width:28px; height:28px; display:flex; justify-content:center; align-items:center; cursor:pointer; font-size:14px; color:#fff; }
        .image-wrapper .actions .delete { background:#ef4444; }
        .image-wrapper .actions .replace { background:#f59e0b; }
    </style>
</head>
<body>
<div class="sidebar">
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="industries.php" class="active">Industries</a></li>
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="applications.php">Applications</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <h2>Industries</h2>
    <button id="openModalBtn"  class="btn-primary">➕ Add Industry</button>

    <!-- Add Modal -->
    <div id="industryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Industry</h3>
            <form id="addIndustryForm" enctype="multipart/form-data" class="form-card">
                <input type="hidden" name="action" value="add">
                <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Icon *</label><input type="file" name="icon" accept="image/*" required></div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="4"></textarea></div>
                <div class="form-group"><label>Images</label><input type="file" name="images[]" multiple></div>
                <button type="submit" class="btn-primary">Save Industry</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" >
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Edit Industry</h3>
            <form id="editIndustryForm" enctype="multipart/form-data" class="form-card">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit-id">

                <div class="form-group"><label>Name *</label><input type="text" id="edit-name" name="name" required></div>

                <div class="form-group"><label>Icon</label>
                    <div id="edit-icon-preview" style="background-color: #8d8a8aff ; width: 60px; height: 60px;"></div>
                    <input type="file" name="icon" accept="image/*">
                </div>

                <div class="form-group"><label>Description</label><textarea id="edit-description" name="description" rows="4"></textarea></div>

                <div class="form-group"><label>Existing Images</label><div id="edit-images-preview" style="display:flex;flex-wrap:wrap;gap:10px;"></div></div>

                <div class="form-group"><label>Upload More Images</label><input type="file" name="images[]" multiple></div>
                <button type="submit" class="btn-primary">Update Industry</button>
            </form>
        </div>
    </div>

    <!-- List -->
    <table id="industryTable">
        <thead>
        <tr><th>Name</th><th>Slug</th><th>Icon</th><th>Images</th><th>Description</th><th>Status</th><th>Created</th><th>Actions</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- ✅ External JS -->
<script src="../public/assets/js/industries.js"></script>
</body>
</html>
