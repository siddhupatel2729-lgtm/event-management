<?php
require "../config.php";
require "auth_check.php";
$inAdmin = true;

$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

include "../includes/header.php";
?>

<h1>Admin Dashboard</h1>
<p><a class="btn" href="add_event.php">+ Add New Event</a>
   <a class="btn" href="manage_tickets.php">View All Tickets</a></p>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="card">
<h2>All Events</h2>
<table>
<tr>
    <th>Title</th><th>Date</th><th>Venue</th><th>Price</th><th>Seats (avail/total)</th><th>Actions</th>
</tr>
<?php while ($row = $events->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo date("d M Y", strtotime($row['event_date'])); ?></td>
    <td><?php echo htmlspecialchars($row['venue']); ?></td>
    <td>₹<?php echo number_format($row['ticket_price'], 2); ?></td>
    <td><?php echo $row['available_seats']; ?> / <?php echo $row['total_seats']; ?></td>
    <td>
        <a class="btn btn-edit" href="edit_event.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a class="btn btn-danger" href="delete_event.php?id=<?php echo $row['id']; ?>"
           onclick="return confirmDelete('Delete this event and all its tickets?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

<?php include "../includes/footer.php"; ?>
