<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

class MessageController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function sendMessage($post) {
        $name    = trim($post['name'] ?? "");
        $email   = trim($post['email'] ?? "");
        $phone   = trim($post['phone'] ?? "");
        $message = trim($post['message'] ?? "");

        // Validate required fields
        if (!$name || !$email || !$phone || !$message) {
            echo json_encode([
                "success" => false,
                "message" => "Missing required fields",
                "debug"   => ["POST" => $post]
            ]);
            exit;
        }

        // Insert into messages table
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (name, email, phone, message, created_at)
            VALUES (?,?,?,?, NOW())
        ");
        $stmt->execute([$name, $email, $phone, $message]);

        echo json_encode([
            "success" => true,
            "message" => "Message sent successfully",
            "debug"   => ["POST" => $post]
        ]);
    }
}

// ---------------- Dispatcher ----------------
$controller = new MessageController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller->sendMessage($_POST);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
