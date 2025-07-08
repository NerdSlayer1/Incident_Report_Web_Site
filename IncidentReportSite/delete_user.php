<?php
// Retrieve user ID from URL parameter
$userId = $_GET['id'];

// Connect to the database
$conn = new mysqli("localhost", "burgen24", "u6i_wzv6S8", "burgen24_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete user from the database based on user ID
$deleteSql = "DELETE FROM Pr_User WHERE userId=$userId";
if ($conn->query($deleteSql) === TRUE) {
    echo "User deleted successfully";
} else {
    echo "Error deleting user: " . $conn->error;
}

// Redirect back to user management page
header("Location: Hejsan.php");
exit;
?>
