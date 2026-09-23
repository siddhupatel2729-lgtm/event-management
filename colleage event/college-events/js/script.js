// Ask for confirmation before deleting an event or ticket
function confirmDelete(message) {
    return confirm(message || "Are you sure you want to delete this? This cannot be undone.");
}

// Simple front-end check on the booking form so obviously
// empty fields are caught before the page is even submitted
function validateBookingForm() {
    const name = document.getElementById("student_name").value.trim();
    const email = document.getElementById("student_email").value.trim();
    const qty = document.getElementById("quantity").value;

    if (name === "" || email === "") {
        alert("Please fill in your name and email.");
        return false;
    }
    if (qty <= 0) {
        alert("Quantity must be at least 1.");
        return false;
    }
    return true;
}
