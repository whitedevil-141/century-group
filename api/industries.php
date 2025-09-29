<?php
session_start();
header("Content-Type: application/json");

require_once __DIR__ . '/../config/db.php';
$pdo = Database::connect();

$action = $_POST['action'] ?? $_GET['action'] ?? null;

function uploadFile($file, $prefix = "") {
    $uploadDir = __DIR__ . "/../public/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . "_{$prefix}_" . basename($file['name']);
    $path = $uploadDir . $filename;
    move_uploaded_file($file['tmp_name'], $path);
    return "uploads/" . $filename;
}

/* ---------------- ADD ---------------- */
if ($action === 'add') {
    $name = $_POST['name'];
    $slug = strtolower(str_replace(" ", "-", $name));
    $desc = $_POST['description'] ?? "";

    // icon
    $iconPath = "";
    if (!empty($_FILES['icon']['name'])) {
        $iconPath = uploadFile($_FILES['icon'], "icon");
    }

    // images
    $images = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $images[] = uploadFile([
                    "name" => $_FILES['images']['name'][$i],
                    "tmp_name" => $tmp
                ], "img");
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO industries (name, slug, description, icon, image_url, status) VALUES (?,?,?,?,?,1)");
    $stmt->execute([$name, $slug, $desc, $iconPath, json_encode($images)]);

    echo json_encode(["success" => true, "message" => "Industry added"]);
    exit;
}

/* ---------------- EDIT ---------------- */
if ($action === 'edit') {
    $id   = (int) $_POST['id'];
    $name = $_POST['name'];
    $slug = strtolower(str_replace(" ", "-", $name));
    $desc = $_POST['description'] ?? "";

    // old images
    $stmt = $pdo->prepare("SELECT image_url, icon FROM industries WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    $oldImages = json_decode($row['image_url'], true) ?? [];
    $oldIcon   = $row['icon'];

    // icon
    $iconPath = $oldIcon;
    if (!empty($_FILES['icon']['name'])) {
        $iconPath = uploadFile($_FILES['icon'], "icon");
    }

    // add new images
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $oldImages[] = uploadFile([
                    "name" => $_FILES['images']['name'][$i],
                    "tmp_name" => $tmp
                ], "img");
            }
        }
    }

    // replace existing
    if (!empty($_FILES['replace_image']['name'])) {
        foreach ($_FILES['replace_image']['name'] as $key => $nameFile) {
            if ($nameFile && $_FILES['replace_image']['error'][$key] === UPLOAD_ERR_OK) {
                $oldImages[$key] = uploadFile([
                    "name" => $nameFile,
                    "tmp_name" => $_FILES['replace_image']['tmp_name'][$key]
                ], "replace");
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE industries SET name=?, slug=?, description=?, icon=?, image_url=? WHERE id=?");
    $stmt->execute([$name, $slug, $desc, $iconPath, json_encode(array_values($oldImages)), $id]);

    echo json_encode(["success" => true, "message" => "Industry updated"]);
    exit;
}

/* ---------------- DELETE INDUSTRY ---------------- */
if ($action === 'delete') {
    $id = (int) ($_GET['id'] ?? 0);
    $pdo->prepare("DELETE FROM industries WHERE id=?")->execute([$id]);
    echo json_encode(["success" => true, "message" => "Industry deleted"]);
    exit;
}

/* ---------------- DELETE IMAGE ---------------- */
if ($action === 'delete_image') {
    $id     = (int) $_GET['id'];
    $imgKey = (int) $_GET['imgKey'];

    $stmt = $pdo->prepare("SELECT image_url FROM industries WHERE id=?");
    $stmt->execute([$id]);
    $images = json_decode($stmt->fetchColumn(), true) ?? [];

    if (isset($images[$imgKey])) {
        $filePath = __DIR__ . "/../public/" . $images[$imgKey];
        if (file_exists($filePath)) unlink($filePath);
        unset($images[$imgKey]);
    }

    $stmt = $pdo->prepare("UPDATE industries SET image_url=? WHERE id=?");
    $stmt->execute([json_encode(array_values($images)), $id]);

    echo json_encode(["success" => true, "message" => "Image deleted"]);
    exit;
}

/* ---------------- DEFAULT ---------------- */
echo json_encode(["success" => false, "message" => "Invalid action"]);
exit;
