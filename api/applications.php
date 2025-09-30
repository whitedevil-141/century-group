<?php
session_start();
header("Content-Type: application/json");
if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../config/db.php';

class ApplicationController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function jsonResponse($success, $message, $data = null) {
        echo json_encode(["success" => $success, "message" => $message, "data" => $data]);
        exit;
    }

    public function list() {
        $stmt = $this->pdo->query("
            SELECT a.*, j.title AS job_title 
            FROM applications a 
            LEFT JOIN jobs j ON a.job_id=j.id 
            ORDER BY a.created_at DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->jsonResponse(true, "Applications fetched", $rows);
    }

    public function updateStatus($id, $status, $message) {
        $valid = ["Pending", "Reviewed", "Accepted", "Rejected"];
        if (!in_array($status, $valid)) {
            $this->jsonResponse(false, "Invalid status");
        }

        // Update DB
        $stmt = $this->pdo->prepare("UPDATE applications SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);

        // Fetch applicant email
        $stmt = $this->pdo->prepare("SELECT name, email FROM applications WHERE id=?");
        $stmt->execute([$id]);
        $app = $stmt->fetch();

        if ($app) {
            $to = $app['email'];
            $subject = "Your Application Status: $status";
            $body = "Hello {$app['name']},\n\n".
                    "Your application status has been updated to: $status.\n\n".
                    "Message from HR:\n$message\n\n".
                    "Best regards,\nHR Team";
            $headers = "From: hr@yourcompany.com";

            @mail($to, $subject, $body, $headers);
        }

        $this->jsonResponse(true, "Application status updated & email sent");
    }
}

// Dispatcher
$controller = new ApplicationController();
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case "list": 
        $controller->list(); 
        break;
    case "update_status":
        $controller->updateStatus(
            (int)($_POST['id'] ?? 0), 
            $_POST['status'] ?? "Pending", 
            $_POST['message'] ?? ""
        );
        break;
    default: 
        echo json_encode(["success"=>false,"message"=>"Invalid action"]); 
        exit;
}
