<?php
include_once('template.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Group 19's Company Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script>
        function showError() {
            alert("Access denied, wrong username/password");
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylesheet.css">
</head>
<body>

<div class="w3-bar w3-black">
    <span class="branding w3-bar-item w3-center w3-mobile">Group 19's Company Page</span>
</div>

<section class="SHOWCASE">
    <div class="w3-container w3-center w3-animate-opacity">
       <h2 class="w3-animate-opacity">Log in to your account</h2>
       <hr>
        <div class="content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-container">
                <div class="w3-center w3-animate-opacity">
                    <label for="username" class="w3-text-blue"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>
                    <br><br>
                    <label class="w3-text-blue" for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>
                    <br><br>
                    <button type="submit" class="w3-button w3-blue w3-medium w3-opacity">Login</button>
                </div>
            </form>
        </div>
        <hr>
    </div>
</section>

<footer>
    <div class="w3-container w3-center w3-animate-opacity">
        <p>&copy; 2024 Group 19, Web Systems Fundamentals and Databases. All rights reserved.</p>
    </div>
</footer>

<?php
$host = "localhost";
$user = "burgen24";
$pwd = "u6i_wzv6S8";
$db = "burgen24_db";

$mysqli = new mysqli($host, $user, $pwd, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);

    $sql = "SELECT * FROM Pr_User WHERE userName='$username' AND password='$password'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        session_name('grp19');
        session_start();
        $row = $result->fetch_object();
        $_SESSION["userName"] = $username;
        $_SESSION["userType"] = $row->userType;
        $_SESSION["userId"] = $row->userId;

        header("Location: incidents.php");
        exit;
    } else {
        echo "<script>showError();</script>";
    }
}
?>

</body>
</html>
