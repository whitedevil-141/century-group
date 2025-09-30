<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

class FileHelper {
    public static function safeFilename($name) {
        return preg_replace("/[^a-zA-Z0-9\-\_\.]/", "_", $name);
    }

    public static function uploadCV($file) {
        $uploadDir = __DIR__ . "/../public/uploads/cv/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ["pdf", "doc", "docx"])) {
            return ["error" => "Invalid extension: " . $ext];
        }

        $filename = time() . "_" . self::safeFilename($file['name']);
        $path = $uploadDir . $filename;

        // debug info
        $debug = [
            "uploadDir" => $uploadDir,
            "tmp_name"  => $file['tmp_name'],
            "orig_name" => $file['name'],
            "dest"      => $path,
        ];

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return ["path" => "public/uploads/cv/" . $filename, "debug" => $debug];
        } else {
            $debug["move_failed"] = true;
            return ["error" => "Upload failed", "debug" => $debug];
        }
    }
}

class ApplyController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function apply($post, $files) {
        $jobId = (int) ($post['job_id'] ?? 0);
        $name  = trim($post['name'] ?? "");
        $email = trim($post['email'] ?? "");
        $phone = trim($post['phone'] ?? "");

        if (!$jobId || !$name || !$email || !$phone) {
            echo json_encode([
                "success" => false,
                "message" => "Missing required fields",
                "debug"   => ["POST" => $post, "FILES" => $files]
            ]);
            exit;
        }

        // check job exists
        $stmt = $this->pdo->prepare("SELECT id FROM jobs WHERE id=?");
        $stmt->execute([$jobId]);
        if (!$stmt->fetch()) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid job ID",
                "debug"   => ["job_id" => $jobId]
            ]);
            exit;
        }

        // handle CV upload
        $cvPath = "";
        $uploadDebug = null;
        if (!empty($files['cv']['name'])) {
            $result = FileHelper::uploadCV($files['cv']);
            if (isset($result['error'])) {
                echo json_encode([
                    "success" => false,
                    "message" => $result['error'],
                    "debug"   => $result['debug'] ?? []
                ]);
                exit;
            }
            $cvPath = $result['path'];
            $uploadDebug = $result['debug'];
        }

        // insert into applications
        $stmt = $this->pdo->prepare("
            INSERT INTO applications (job_id, name, email, phone, cv_path, status, created_at)
            VALUES (?,?,?,?,?, 'Pending', NOW())
        ");
        $stmt->execute([$jobId, $name, $email, $phone, $cvPath]);

        echo json_encode([
            "success" => true,
            "message" => "Application submitted successfully",
            "debug"   => [
                "POST" => $post,
                "FILES" => $files,
                "cvPath" => $cvPath,
                "uploadDebug" => $uploadDebug
            ]
        ]);
    }
}

// ---------------- Dispatcher ----------------
$controller = new ApplyController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller->apply($_POST, $_FILES);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
