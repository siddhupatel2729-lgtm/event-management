<?php
require "../config.php";
require "auth_check.php";
$inAdmin = true;

// Join tickets with events so we can show the event title next to each ticket
$sql = "SELECT tickets.*, events.title AS event_title
        FROM tickets
        JOIN events ON tickets.event_id = events.id
        ORDER BY tickets.booking_date DESC";
$tickets = $conn->query($sql);

include "../includes/header.php";
?>

<h1>All Booked Tickets</h1>
<p><a class="btn" href="dashboard.php">&laquo; Back to Dashboard</a></p>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="card">
<table>
<tr>
    <th>Event</th><th>Student</th><th>Email</th><th>Qty</th><th>Total</th><th>Status</th><th>Actions</th>
</tr>
<?php while ($row = $tickets->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['event_title']); ?></td>
    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
    <td><?php echo htmlspecialchars($row['student_email']); ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
    <td><?php echo ucfirst($row['status']); ?></td>
    <td>
        <?php if ($row['status'] === 'confirmed'): ?>
        <a class="btn btn-edit" href="update_ticket.php?id=<?php echo $row['id']; ?>&status=cancelled"
           onclick="return confirmDelete('Cancel this ticket and return the seats?')">Cancel</a>
        <?php endif; ?>
        <a class="btn btn-danger" href="delete_ticket.php?id=<?php echo $row['id']; ?>"
           onclick="return confirmDelete('Permanently delete this ticket record?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

<?php include "../includes/footer.php"; ?>
