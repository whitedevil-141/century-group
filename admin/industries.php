<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';
$pdo = Database::connect();

// load industries initially
$industries = $pdo->query("SELECT * FROM industries ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Industries</title>
    <link rel="stylesheet" href="../public/assets/css/admin.css">
    <style>
        img.thumb { width:60px; height:60px; object-fit:cover; margin:3px; border-radius:4px; }
        .table-icon img { width:40px; height:40px; object-fit:cover; }
    </style>
</head>
<body>
<div class="content">
    <h2>Industries</h2>
    <button id="openModalBtn" class="btn-primary">➕ Add Industry</button>

    <!-- Add Modal -->
    <div id="industryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Industry</h3>
            <form id="addIndustryForm" enctype="multipart/form-data" class="form-card">
                <input type="hidden" name="action" value="add">
                <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Icon *</label><input type="file" name="icon" accept="image/*" required></div>
                <div class="form-group"><label>Description</label><textarea name="description"></textarea></div>
                <div class="form-group"><label>Images</label><input type="file" name="images[]" multiple></div>
                <button type="submit" class="btn-primary">Save</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Edit Industry</h3>
            <form id="editIndustryForm" enctype="multipart/form-data" class="form-card">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-group"><label>Name *</label><input type="text" id="edit-name" name="name" required></div>
                <div class="form-group"><label>Icon</label><div id="edit-icon-preview"></div><input type="file" name="icon" accept="image/*"></div>
                <div class="form-group"><label>Description</label><textarea id="edit-description" name="description"></textarea></div>
                <div class="form-group"><label>Existing Images</label><div id="edit-images-preview"></div></div>
                <div class="form-group"><label>Add More Images</label><input type="file" name="images[]" multiple></div>
                <button type="submit" class="btn-primary">Update</button>
            </form>
        </div>
    </div>

    <!-- Table -->
    <table id="industriesTable">
        <thead>
            <tr><th>Name</th><th>Slug</th><th>Icon</th><th>Images</th><th>Description</th><th>Status</th><th>Created</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($industries as $ind): ?>
            <tr data-id="<?= $ind['id'] ?>">
                <td><?= htmlspecialchars($ind['name']) ?></td>
                <td><?= htmlspecialchars($ind['slug']) ?></td>
                <td><?php if ($ind['icon']): ?><div class="table-icon"><img src="../public/<?= $ind['icon'] ?>"></div><?php endif; ?></td>
                <td><?php foreach (json_decode($ind['image_url'], true) ?? [] as $i): ?><img src="../public/<?= $i ?>" class="thumb"><?php endforeach; ?></td>
                <td><?= nl2br(htmlspecialchars($ind['description'])) ?></td>
                <td><?= $ind['status'] ? '✅ Active' : '❌ Inactive' ?></td>
                <td><?= $ind['created_at'] ?></td>
                <td>
                    <button class="editBtn"
                       data-id="<?= $ind['id'] ?>"
                       data-name="<?= htmlspecialchars($ind['name']) ?>"
                       data-icon="<?= htmlspecialchars($ind['icon']) ?>"
                       data-description="<?= htmlspecialchars($ind['description']) ?>"
                       data-images='<?= htmlspecialchars($ind['image_url']) ?>'>✏️</button>
                    <button class="deleteBtn" data-id="<?= $ind['id'] ?>">🗑</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
const API = "../api/industries.php";

// ---------- Helpers ----------
async function apiRequest(formData) {
    const res = await fetch(API, { method: "POST", body: formData });
    return res.json();
}
async function apiGet(params) {
    const res = await fetch(API + "?" + new URLSearchParams(params));
    return res.json();
}
function refreshPage(){ window.location.reload(); }

// ---------- Add ----------
document.getElementById("addIndustryForm").onsubmit = async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = await apiRequest(formData);
    if(data.success){ alert("Added!"); refreshPage(); }
    else alert(data.message);
};

// ---------- Edit Modal ----------
const editModal=document.getElementById("editModal"),
      editClose=editModal.querySelector(".close");
document.querySelectorAll(".editBtn").forEach(btn=>{
    btn.onclick=()=>{
        document.getElementById("edit-id").value=btn.dataset.id;
        document.getElementById("edit-name").value=btn.dataset.name;
        document.getElementById("edit-description").value=btn.dataset.description;

        const iconPrev=document.getElementById("edit-icon-preview");
        iconPrev.innerHTML=btn.dataset.icon?`<img src="../public/${btn.dataset.icon}" width="60">`:"";

        const cont=document.getElementById("edit-images-preview");
        cont.innerHTML="";
        let imgs=[];try{imgs=JSON.parse(btn.dataset.images);}catch(e){}
        if(Array.isArray(imgs)){
            imgs.forEach((img,k)=>{
                cont.innerHTML+=`<div>
                    <img src="../public/${img}" width="60">
                    <button type="button" onclick="deleteImage(${btn.dataset.id},${k})">❌</button>
                </div>`;
            });
        }
        editModal.style.display="flex";
    };
});
editClose.onclick=()=>editModal.style.display="none";

// ---------- Edit Submit ----------
document.getElementById("editIndustryForm").onsubmit=async e=>{
    e.preventDefault();
    const formData=new FormData(e.target);
    const data=await apiRequest(formData);
    if(data.success){ alert("Updated!"); refreshPage(); }
    else alert(data.message);
};

// ---------- Delete Industry ----------
document.querySelectorAll(".deleteBtn").forEach(btn=>{
    btn.onclick=async()=>{
        if(!confirm("Delete this industry?")) return;
        const data=await apiGet({action:"delete",id:btn.dataset.id});
        if(data.success){ alert("Deleted!"); refreshPage(); }
        else alert(data.message);
    };
});

// ---------- Delete Image ----------
async function deleteImage(id,key){
    if(!confirm("Delete this image?")) return;
    const data=await apiGet({action:"delete_image",id:id,imgKey:key});
    if(data.success){ alert("Image deleted!"); refreshPage(); }
    else alert(data.message);
}

// ---------- Add modal ----------
const modal=document.getElementById("industryModal"),
      openBtn=document.getElementById("openModalBtn"),
      span=modal.querySelector(".close");
openBtn.onclick=()=>modal.style.display="flex";
span.onclick=()=>modal.style.display="none";
</script>
</body>
</html>
