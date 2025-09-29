<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

$pdo = Database::connect();
$data = $_POST;

$cv_path = "";
if (!empty($_FILES['cv']['name'])) {
    $cv_path = "../uploads/cv_" . time() . "_" . basename($_FILES['cv']['name']);
    move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
}

$stmt = $pdo->prepare("INSERT INTO applications (job_id, name, email, phone, cv_path) VALUES (?,?,?,?,?)");
$stmt->execute([$data['job_id'], $data['name'], $data['email'], $data['phone'], $cv_path]);

echo json_encode(["success" => true, "message" => "Application submitted"]);
