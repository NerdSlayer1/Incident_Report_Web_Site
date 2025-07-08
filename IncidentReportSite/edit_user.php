<?php
// Retrieve user ID from URL parameter
$userId = $_GET['id'];

// Connect to the database
$conn = new mysqli("localhost", "burgen24", "u6i_wzv6S8", "burgen24_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch user from the database based on user ID
$sql = "SELECT * FROM Pr_User WHERE userId = $userId";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows == 1) {
    // Fetch user data
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $rank = $_POST['rank'];

    // Update user information in the database
    $updateSql = "UPDATE Pr_User SET userName='$username', mailAddress='$email', userType='$rank' WHERE userId=$userId";
    if ($conn->query($updateSql) === TRUE) {
        echo "User updated successfully";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>

<!-- HTML form for editing user -->
<form action="edit_user.php?id=<?php echo $userId; ?>" method="post">
    Username: <input type="text" name="username" value="<?php echo htmlspecialchars($user['userName']); ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['mailAddress']); ?>" required><br>
    Rank:
    <select name="rank">
        <option value="admin" <?php if($user['userType'] == 'admin') echo 'selected'; ?>>Admin</option>
        <option value="responder" <?php if($user['userType'] == 'responder') echo 'selected'; ?>>Responder</option>
        <option value="reporter" <?php if($user['userType'] == 'reporter') echo 'selected'; ?>>Reporter</option>
    </select><br>
    <input type="submit" value="Update User">
</form>
