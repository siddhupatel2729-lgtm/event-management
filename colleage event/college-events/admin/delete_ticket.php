<?php
require "../config.php";
require "auth_check.php";

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_tickets.php?msg=Ticket deleted");
exit;
?>
