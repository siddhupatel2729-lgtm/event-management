<?php
require "../config.php";
require "auth_check.php";

$id     = intval($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';

if (!in_array($status, ['confirmed', 'cancelled'])) {
    header("Location: manage_tickets.php");
    exit;
}

// Get the ticket first so we know the event and quantity
$stmt = $conn->prepare("SELECT event_id, quantity, status FROM tickets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if ($ticket && $ticket['status'] !== $status) {
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        // If we're cancelling, give the seats back to the event
        if ($status === 'cancelled') {
            $stmt2 = $conn->prepare("UPDATE events SET available_seats = available_seats + ? WHERE id = ?");
            $stmt2->bind_param("ii", $ticket['quantity'], $ticket['event_id']);
            $stmt2->execute();
        }
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
    }
}

header("Location: manage_tickets.php?msg=Ticket status updated");
exit;
?>
