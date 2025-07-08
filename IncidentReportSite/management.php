<?php
include_once('template.php');
include('authentication_user.php');
include('authentication.php');

echo $navigation;
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['submit'])) {
    // If the form is submitted for adding a new user
    $mysqli->query("INSERT INTO Pr_User(fname, lname, userName, mailAddress, password, userType)
    values('{$_POST['fname']}','{$_POST['lname']}','{$_POST['username']}','{$_POST['email']}','{$_POST['password']}','{$_POST['userType']}')");

    // Display pop-up message for user added
    echo "<script>alert('User added');</script>";
}

// Delete user button
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $mysqli->query("DELETE FROM Pr_User WHERE userId = $userId");

    // Display pop-up message for user deleted
    echo "<script>alert('User deleted');</script>";
}

// Check if the form is submitted for editing/updating a user
if (isset($_POST['submit_edit'])) {
    // Retrieve data from the edit form
    $editUserId = $_POST['edit_user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    // Update the user's information in the database
    $sql = "UPDATE Pr_User SET userName='$username', mailAddress='$email', fname='$fname', lname='$lname', password='$password', userType='$userType' WHERE userId=$editUserId";
    if ($mysqli->query($sql) === TRUE) {
        // Display pop-up message for user updated
        echo "<script>alert('User has been updated');</script>";
    } else {
        echo "Error updating user: " . $mysqli->error;
    }
}

// Fetch user data for editing
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "SELECT * FROM Pr_User WHERE userId = $userId";
    $result = $mysqli->query($sql);
    $userData = $result->fetch_assoc();
}
?>

<div class="container mt-5">
    <h1>User Management</h1>
    <div class="content">
        <!-- List of Users -->
        <div class="mt-4">
            <h2>List of Users</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch users
                    $sql = "SELECT * FROM Pr_User";
                    $result = $mysqli->query($sql);

                    // Display users
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["userName"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["mailAddress"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["userType"]) . "</td>";
                            // Edit and Delete links
                            echo "<td>
                                    <a href='management.php?action=edit&id=" . htmlspecialchars($row["userId"]) . "' class='btn btn-primary'>Edit</a>
                                    <a href='management.php?action=delete&id=" . htmlspecialchars($row["userId"]) . "' class='btn btn-danger ml-2'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Add User Form -->
    <div class="mt-4">
        <div class="content">
            <h2>Add User</h2>
            <form action="management.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" class="form-control" id="fname" name="fname" required>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" class="form-control" id="lname" name="lname" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="userType">User Type:</label>
                    <select class="form-control" id="userType" name="userType" required>
                        <option value="admin">Admin</option>
                        <option value="responder">Responder</option>
                        <option value="reporter">Reporter</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Add User</button>
            </form>
        </div>
    </div>
    <!-- Edit User Form -->
    <div class="mt-5">
        <div class="content">
            <?php if (isset($userData)) : ?>
                <h2>Edit User</h2>
                <form action="management.php" method="post">
                    <input type="hidden" name="edit_user_id" value="<?php echo htmlspecialchars($userData["userId"]); ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userData["userName"]); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData["mailAddress"]); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($userData["fname"]); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($userData["lname"]); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="userType">User Type:</label>
                        <select class="form-control" id="userType" name="userType" required>
                            <option value="admin" <?php echo ($userData["userType"] === "admin") ? "selected" : ""; ?>>Admin</option>
                            <option value="responder" <?php echo ($userData["userType"] === "responder") ? "selected" : ""; ?>>Responder</option>
                            <option value="reporter" <?php echo ($userData["userType"] === "reporter") ? "selected" : ""; ?>>Reporter</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit_edit">Complete Edit</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Bootstrap JS (Optional, for Bootstrap components that require JavaScript) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
