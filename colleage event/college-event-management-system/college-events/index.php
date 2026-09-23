<?php
require "config.php";
include "includes/header.php";

// Fetch all events, soonest first
$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
$result = $conn->query($sql);
?>

<h1>Upcoming College Events</h1>

<div class="event-grid">
<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...</p>
            <p><strong>Date:</strong> <?php echo date("d M Y", strtotime($row['event_date'])); ?>
               &nbsp; <strong>Time:</strong> <?php echo date("h:i A", strtotime($row['event_time'])); ?></p>
            <p><strong>Venue:</strong> <?php echo htmlspecialchars($row['venue']); ?></p>
            <p><strong>Price:</strong> ₹<?php echo number_format($row['ticket_price'], 2); ?></p>
            <p class="seats-left"><?php echo $row['available_seats']; ?> seats left</p>
            <a class="btn" href="event_details.php?id=<?php echo $row['id']; ?>">View & Book</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No upcoming events right now. Please check back later.</p>
<?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
