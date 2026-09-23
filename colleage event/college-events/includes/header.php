<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>College Event Management System</title>
<link rel="stylesheet" href="<?php echo isset($inAdmin) ? '../css/style.css' : 'css/style.css'; ?>">
</head>
<body>

<div class="navbar">
    <div><strong>🎓 College Events</strong></div>
    <div>
        <?php if (isset($_SESSION['admin_id'])): ?>
            <a href="<?php echo isset($inAdmin) ? 'dashboard.php' : 'admin/dashboard.php'; ?>">Dashboard</a>
            <a href="<?php echo isset($inAdmin) ? 'logout.php' : 'admin/logout.php'; ?>">Logout</a>
        <?php else: ?>
            <a href="<?php echo isset($inAdmin) ? '../index.php' : 'index.php'; ?>">Home</a>
            <a href="<?php echo isset($inAdmin) ? 'login.php' : 'admin/login.php'; ?>">Admin Login</a>
        <?php endif; ?>
    </div>
</div>
<div class="container">
