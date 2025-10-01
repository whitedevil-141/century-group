<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/permissions.php';

secureSessionStart();
requireAuth(); // must be logged in

header("Content-Type: application/json");

require_once __DIR__ . '/../config/db.php';

class JobController {
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

    // ---------------- LIST ----------------
    public function list() {
        $stmt = $this->pdo->query("SELECT * FROM jobs ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->jsonResponse(true, "Jobs fetched", $rows);
    }

    // ---------------- GET ----------------
    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM jobs WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) $this->jsonResponse(false, "Job not found");
        $this->jsonResponse(true, "Job fetched", $row);
    }

    // ---------------- ADD ----------------
    public function add($post) {
        $stmt = $this->pdo->prepare("
            INSERT INTO jobs (title, department_name, location, job_type, description) 
            VALUES (?,?,?,?,?)
        ");
        $stmt->execute([
            trim($post['title']),
            trim($post['department_name']),
            trim($post['location']),
            trim($post['job_type']),
            trim($post['description'])
        ]);
        $this->jsonResponse(true, "Job added");
    }

    // ---------------- EDIT ----------------
    public function edit($post) {
        $id = (int) $post['id'];
        $stmt = $this->pdo->prepare("
            UPDATE jobs 
            SET title=?, department_name=?, location=?, job_type=?, description=? 
            WHERE id=?
        ");
        $stmt->execute([
            trim($post['title']),
            trim($post['department_name']),
            trim($post['location']),
            trim($post['job_type']),
            trim($post['description']),
            $id
        ]);
        $this->jsonResponse(true, "Job updated");
    }

    // ---------------- DELETE ----------------
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id=?");
        $stmt->execute([$id]);
        $this->jsonResponse(true, "Job deleted");
    }
}

// ---------------- Dispatcher ----------------
$controller = new JobController();
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'list':
        enforcePermission('jobs','read');
        $controller->list();
        break;

    case 'get':
        enforcePermission('jobs','read');
        $controller->get((int)($_GET['id'] ?? 0));
        break;

    case 'add':
        enforcePermission('jobs','create');
        $controller->add($_POST);
        break;

    case 'edit':
        enforcePermission('jobs','update');
        $controller->edit($_POST);
        break;

    case 'delete':
        enforcePermission('jobs','delete');
        $controller->delete((int)($_GET['id'] ?? 0));
        break;

    default:
        echo json_encode(["success"=>false,"message"=>"Invalid action"]);
        exit;
}