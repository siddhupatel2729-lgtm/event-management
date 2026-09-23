<?php
require "../config.php";
require "auth_check.php";

$id = intval($_GET['id'] ?? 0);

// ON DELETE CASCADE (set in database.sql) automatically removes
// any tickets that belong to this event too
$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php?msg=Event deleted");
exit;
?>
