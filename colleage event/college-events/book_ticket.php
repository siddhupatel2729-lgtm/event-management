<?php
require "config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$event_id = intval($_POST['event_id']);
$name     = trim($_POST['student_name']);
$email    = trim($_POST['student_email']);
$phone    = trim($_POST['phone']);
$quantity = intval($_POST['quantity']);

// Get the event so we know price and available seats
$stmt = $conn->prepare("SELECT ticket_price, available_seats FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    header("Location: index.php");
    exit;
}

if ($quantity < 1 || $quantity > $event['available_seats']) {
    header("Location: event_details.php?id=$event_id&error=Not enough seats available");
    exit;
}

$total_amount = $quantity * $event['ticket_price'];

// Use a transaction so the ticket insert and the seat-count update
// either both succeed or both fail (keeps the data consistent)
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO tickets (event_id, student_name, student_email, phone, quantity, total_amount)
                             VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssid", $event_id, $name, $email, $phone, $quantity, $total_amount);
    $stmt->execute();

    $stmt2 = $conn->prepare("UPDATE events SET available_seats = available_seats - ? WHERE id = ?");
    $stmt2->bind_param("ii", $quantity, $event_id);
    $stmt2->execute();

    $conn->commit();
    header("Location: event_details.php?id=$event_id&booked=1");
} catch (Exception $e) {
    $conn->rollback();
    header("Location: event_details.php?id=$event_id&error=Booking failed, please try again");
}
exit;
?>
