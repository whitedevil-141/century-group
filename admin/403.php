<!DOCTYPE html>
<html>
<head>
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <style>
        .error-container {
            text-align: center;
            margin-top: 100px;
        }
        .error-container h1 {
            font-size: 80px;
            color: #d9534f;
        }
        .error-container p {
            font-size: 20px;
            color: #555;
        }
        .error-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .error-container a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h3 style="padding:10px;">Century Admin</h3>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <div class="error-container">
        <h1>403</h1>
        <p>🚫 Access Denied. You don’t have permission to view this page.</p>
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</div>
</body>
</html>
