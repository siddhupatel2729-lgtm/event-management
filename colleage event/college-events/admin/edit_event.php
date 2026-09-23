<?php
require "../config.php";
require "auth_check.php";
$inAdmin = true;
$error = "";

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date  = $_POST['event_date'];
    $event_time  = $_POST['event_time'];
    $venue       = trim($_POST['venue']);
    $price       = floatval($_POST['ticket_price']);
    $total_seats = intval($_POST['total_seats']);

    // Keep available seats in sync: reduce/increase by however much total_seats changed
    $seat_diff = $total_seats - $event['total_seats'];
    $new_available = $event['available_seats'] + $seat_diff;
    if ($new_available < 0) $new_available = 0;

    $stmt = $conn->prepare("UPDATE events SET
        title=?, description=?, event_date=?, event_time=?, venue=?, ticket_price=?, total_seats=?, available_seats=?
        WHERE id=?");
    $stmt->bind_param("sssssdiii", $title, $description, $event_date, $event_time, $venue, $price, $total_seats, $new_available, $id);
    $stmt->execute();

    header("Location: dashboard.php?msg=Event updated successfully");
    exit;
}

include "../includes/header.php";
?>

<h1>Edit Event</h1>
<?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

<div class="card" style="max-width:500px;">
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $event['id']; ?>">

    <label>Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>

    <label>Description</label>
    <textarea name="description" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>

    <label>Date</label>
    <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required>

    <label>Time</label>
    <input type="time" name="event_time" value="<?php echo $event['event_time']; ?>" required>

    <label>Venue</label>
    <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>

    <label>Ticket Price (₹)</label>
    <input type="number" step="0.01" name="ticket_price" value="<?php echo $event['ticket_price']; ?>" min="0" required>

    <label>Total Seats</label>
    <input type="number" name="total_seats" value="<?php echo $event['total_seats']; ?>" min="1" required>

    <button type="submit" class="btn">Update Event</button>
</form>
</div>

<?php include "../includes/footer.php"; ?>
