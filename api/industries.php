<?php
session_start();
header("Content-Type: application/json");

require_once __DIR__ . '/../config/db.php';

class FileHelper {
    private static $uploadDir = __DIR__ . "/../public/uploads/"; 

    public static function safeFilename($name) {
        return preg_replace("/[^a-zA-Z0-9\-\_\.]/", "_", $name);
    }

    public static function upload($file, $prefix = "") {
        if (!is_dir(self::$uploadDir)) mkdir(self::$uploadDir, 0777, true);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = ["image/jpeg", "image/png", "image/gif", "image/webp","image/svg+xml"];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mime, $allowed) || !in_array($ext, ["jpg","jpeg","png","gif","webp", "svg"])) {
            return false;
        }

        $filename = time() . "_{$prefix}_" . self::safeFilename($file['name']);
        $path = self::$uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return "uploads/" . $filename; // relative path
        }
        return false;
    }

    public static function delete($relativePath) {
        if (!$relativePath) return;
        $filePath = __DIR__ . "/../public/" . ltrim($relativePath, "/");
        if (file_exists($filePath)) unlink($filePath);
    }
}


class IndustryController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function jsonResponse($success, $message, $data = null) {
        echo json_encode([
            "success" => $success,
            "message" => $message,
            "data"    => $data
        ]);
        exit;
    }

    public function list() {
        $stmt = $this->pdo->query("SELECT * FROM industries ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['images'] = json_decode($row['image_url'], true) ?? [];
            unset($row['image_url']);
        }

        $this->jsonResponse(true, "Industries fetched", $rows);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM industries WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) $this->jsonResponse(false, "Industry not found");

        $row['images'] = json_decode($row['image_url'], true) ?? [];
        unset($row['image_url']);

        $this->jsonResponse(true, "Industry fetched", $row);
    }

    public function add($post, $files) {
        $name = trim($post['name']);
        $slug = $this->slugify($name);
        $desc = trim($post['description'] ?? "");

        $iconPath = "";
        if (!empty($files['icon']['name'])) {
            $uploaded = FileHelper::upload($files['icon'], "icon");
            if (!$uploaded) $this->jsonResponse(false, "Invalid icon file");
            $iconPath = $uploaded;
        }

        $images = [];
        if (!empty($files['images']['name'][0])) {
            foreach ($files['images']['tmp_name'] as $i => $tmp) {
                if ($files['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $uploaded = FileHelper::upload([
                        "name" => $files['images']['name'][$i],
                        "tmp_name" => $tmp
                    ], "img");
                    if ($uploaded) $images[] = $uploaded;
                }
            }
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO industries (name, slug, description, icon, image_url, status) VALUES (?,?,?,?,?,1)");
            $stmt->execute([$name, $slug, $desc, $iconPath, json_encode($images)]);

            $this->jsonResponse(true, "Industry added");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->jsonResponse(false, "Slug already exists. Please choose a different name.");
            } else {
                $this->jsonResponse(false, "Database error: " . $e->getMessage());
            }
        }
    }


    public function edit($post, $files) {
        $id   = (int) $post['id'];
        $name = trim($post['name']);
        $slug = $this->slugify($name);
        $desc = trim($post['description'] ?? "");

        $stmt = $this->pdo->prepare("SELECT image_url, icon FROM industries WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) $this->jsonResponse(false, "Industry not found");

        $oldImages = json_decode($row['image_url'], true) ?? [];
        $oldIcon   = $row['icon'];

        // Replace icon
        $iconPath = $oldIcon;
        if (!empty($files['icon']['name'])) {
            $uploaded = FileHelper::upload($files['icon'], "icon");
            if (!$uploaded) $this->jsonResponse(false, "Invalid icon file");
            FileHelper::delete($oldIcon);
            $iconPath = $uploaded;
        }

        // Add new images
        if (!empty($files['images']['name'][0])) {
            foreach ($files['images']['tmp_name'] as $i => $tmp) {
                if ($files['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $uploaded = FileHelper::upload([
                        "name" => $files['images']['name'][$i],
                        "tmp_name" => $tmp
                    ], "img");
                    if ($uploaded) $oldImages[] = $uploaded;
                }
            }
        }

        // Replace existing
        if (!empty($files['replace_image']['name'])) {
            foreach ($files['replace_image']['name'] as $key => $nameFile) {
                if ($nameFile && $files['replace_image']['error'][$key] === UPLOAD_ERR_OK) {
                    $uploaded = FileHelper::upload([
                        "name" => $nameFile,
                        "tmp_name" => $files['replace_image']['tmp_name'][$key]
                    ], "replace");
                    if ($uploaded && isset($oldImages[$key])) {
                        FileHelper::delete($oldImages[$key]);
                        $oldImages[$key] = $uploaded;
                    }
                }
            }
        }
        

        $stmt = $this->pdo->prepare("UPDATE industries SET name=?, slug=?, description=?, icon=?, image_url=? WHERE id=?");
        $stmt->execute([$name, $slug, $desc, $iconPath, json_encode(array_values($oldImages)), $id]);

        $this->jsonResponse(true, "Industry updated");
    }
    public function deleteImage($id, $key) {
        $stmt = $this->pdo->prepare("SELECT image_url FROM industries WHERE id=?");
        $stmt->execute([$id]);
        $images = json_decode($stmt->fetchColumn(), true) ?? [];

        if (!isset($images[$key])) {
            $this->jsonResponse(false, "Image not found");
        }

        $deletedFile = $images[$key];
        FileHelper::delete($deletedFile);
        unset($images[$key]);

        $stmt = $this->pdo->prepare("UPDATE industries SET image_url=? WHERE id=?");
        $stmt->execute([json_encode(array_values($images)), $id]);

        $this->jsonResponse(true, "Image deleted", ["file" => $deletedFile]);
    }


    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT icon, image_url FROM industries WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            FileHelper::delete($row['icon']);
            foreach (json_decode($row['image_url'], true) ?? [] as $img) {
                FileHelper::delete($img);
            }
        }

        $this->pdo->prepare("DELETE FROM industries WHERE id=?")->execute([$id]);
        $this->jsonResponse(true, "Industry deleted");
    }

    public function toggle($id) {
        $this->pdo->prepare("UPDATE industries SET status = IF(status=1,0,1) WHERE id=?")->execute([$id]);
        $this->jsonResponse(true, "Status toggled");
    }

    private function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text) ?: 'n-a';
    }
}

// ---------------- Dispatcher ----------------
$controller = new IndustryController();
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'list':   $controller->list(); break;
    case 'get':    $controller->get((int)($_GET['id'] ?? 0)); break;
    case 'add':    $controller->add($_POST, $_FILES); break;
    case 'edit':   $controller->edit($_POST, $_FILES); break;
    case 'delete': $controller->delete((int)($_GET['id'] ?? 0)); break;
    case 'toggle': $controller->toggle((int)($_GET['id'] ?? 0)); break;
    case 'delete_image': 
        $controller->deleteImage((int)($_GET['id'] ?? 0), (int)($_GET['imgKey'] ?? -1));
        break;
    default:       
        echo json_encode(["success"=>false,"message"=>"Invalid action"]); 
        exit;
}

