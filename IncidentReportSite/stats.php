<?php
include_once('template.php');
include('authentication.php');
include('authentication_user.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Statistics</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/stylesheet.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    
 
	
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add more CSS here if we want or feel like it? Same as other files-->
    <style>
        /* Add your CSS styles here */
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="w3-bar w3-black" style = "padding:8px;">

<span class="branding w3-bar-item w3-center w3-mobile">Group 19's Company Page</span>
<nav class = "nav">
<a href="piechart.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Turn Back</a>

</nav></div>
<br>
<div class = "content">
<div>
    <h1>Status Type Statistics</h1>

</div>
<br>

<div class="container">


    <!-- Progress stats are displayed through here -->
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Status Type</th>
                <th>Total Reports</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $host = "localhost";
            $user = "burgen24";
            $pwd = "u6i_wzv6S8";
            $db = "burgen24_db";
            $mysqli = new mysqli($host, $user, $pwd, $db);

            // Are we connected, same code as ALWAYS
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Query to count reports for each status type
            $sqlCountReports = "SELECT statusType, COUNT(*) AS TotalReports
                                  FROM Pr_Status
                                  GROUP BY statusType";
            $resultCountReports = $mysqli->query($sqlCountReports);

            // Output data of each row
            if ($resultCountReports instanceof mysqli_result && $resultCountReports->num_rows > 0) {
                while($row = $resultCountReports->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["statusType"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["TotalReports"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No progress statistics available</td></tr>";
            }

            // Close database connection
            $mysqli->close();
            ?>
        </tbody>
    </table>
</div>
</div>

<!-- Link to Bootstrap when and if we need it :) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
