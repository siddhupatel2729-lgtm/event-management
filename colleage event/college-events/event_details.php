<?php
require "config.php";
include "includes/header.php";

$id = intval($_GET['id'] ?? 0);

// Use a prepared statement so user input can't break/inject into the query
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    echo "<p>Event not found.</p>";
    include "includes/footer.php";
    exit;
}
?>

<div class="card">
    <h1><?php echo htmlspecialchars($event['title']); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    <p><strong>Date:</strong> <?php echo date("d M Y", strtotime($event['event_date'])); ?></p>
    <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($event['event_time'])); ?></p>
    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
    <p><strong>Price per ticket:</strong> ₹<?php echo number_format($event['ticket_price'], 2); ?></p>
    <p class="seats-left"><?php echo $event['available_seats']; ?> seats available</p>
</div>

<?php if (isset($_GET['booked'])): ?>
    <div class="alert alert-success">Ticket booked successfully! Check your email for confirmation.</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<?php if ($event['available_seats'] > 0): ?>
<div class="card">
    <h2>Book Your Ticket</h2>
    <form action="book_ticket.php" method="POST" onsubmit="return validateBookingForm()">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

        <label for="student_name">Full Name</label>
        <input type="text" id="student_name" name="student_name" required>

        <label for="student_email">Email</label>
        <input type="email" id="student_email" name="student_email" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone">

        <label for="quantity">Number of Tickets</label>
        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $event['available_seats']; ?>" value="1" required>

        <button type="submit" class="btn">Book Now</button>
    </form>
</div>
<?php else: ?>
    <p><strong>Sold out.</strong></p>
<?php endif; ?>

<?php include "includes/footer.php"; ?>
