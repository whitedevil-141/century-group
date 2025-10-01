<?php
// Define permissions matrix
$permissions = [
    'admin' => [
        'industries'   => ['create','read','update','delete'],
        'jobs'         => ['create','read','update','delete'],
        'applications' => ['create','read','update','delete'],
        'messages'     => ['create','read','update','delete'],
        'users'        => ['create','read','update','delete'],
    ],
    'mod' => [
        'industries'   => ['create','read','update'],
        'jobs'         => ['create','read','update'],
        'applications' => ['read','update'],
        'messages'     => ['read'],
    ],
    'hr' => [
        'jobs'         => ['create','read','update'],
        'applications' => ['read','update'],
    ]
];

/**
 * Global permission check
 */
function can($module, $action) {
    global $permissions;
    $role = $_SESSION['user']['role'] ?? null;
    return in_array($action, $permissions[$role][$module] ?? []);
}

/**
 * Middleware for API/page enforcement
 */
function enforcePermission($module, $action) {
    if (!can($module, $action)) {
        // JSON API response
        if (strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false) {
            echo json_encode(["success"=>false,"message"=>"❌ Permission denied"]);
            exit;
        }
        // Page response
        header("Location: /admin/403.php");
        exit;
    }
}
