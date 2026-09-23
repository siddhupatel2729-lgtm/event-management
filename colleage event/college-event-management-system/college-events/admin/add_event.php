<?php
require "../config.php";
require "auth_check.php";
$inAdmin = true;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date  = $_POST['event_date'];
    $event_time  = $_POST['event_time'];
    $venue       = trim($_POST['venue']);
    $price       = floatval($_POST['ticket_price']);
    $seats       = intval($_POST['total_seats']);

    if ($title === "" || $venue === "" || $seats <= 0) {
        $error = "Please fill all required fields correctly.";
    } else {
        // available_seats starts out equal to total_seats
        $stmt = $conn->prepare("INSERT INTO events
            (title, description, event_date, event_time, venue, ticket_price, total_seats, available_seats)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdii", $title, $description, $event_date, $event_time, $venue, $price, $seats, $seats);
        $stmt->execute();

        header("Location: dashboard.php?msg=Event added successfully");
        exit;
    }
}

include "../includes/header.php";
?>

<h1>Add New Event</h1>
<?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

<div class="card" style="max-width:500px;">
<form method="POST">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Description</label>
    <textarea name="description" rows="4"></textarea>

    <label>Date</label>
    <input type="date" name="event_date" required>

    <label>Time</label>
    <input type="time" name="event_time" required>

    <label>Venue</label>
    <input type="text" name="venue" required>

    <label>Ticket Price (₹)</label>
    <input type="number" step="0.01" name="ticket_price" min="0" required>

    <label>Total Seats</label>
    <input type="number" name="total_seats" min="1" required>

    <button type="submit" class="btn">Save Event</button>
</form>
</div>

<?php include "../includes/footer.php"; ?>
