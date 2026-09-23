<?php
// Include this file at the top of any admin page that should be protected.
// It sends anyone who isn't logged in back to the login page.
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
