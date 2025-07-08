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
    <title>Incident Type Statistics</title>
    <!-- Link to Bootstrap CSS -->
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/stylesheet.css">
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your CSS styles here -->
    <style>
        /* CSS if and when we want */
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
    <h1>Incident Type Statistics</h1>
</div>
<div class="container">


    <!-- Display incident stats over here  -->
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Incident Type</th>
                <th>Total Incidents</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection as per usual
            $host = "localhost";
            $user = "burgen24";
            $pwd = "u6i_wzv6S8";
            $db = "burgen24_db";
            $mysqli = new mysqli($host, $user, $pwd, $db);

            // Check connection
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Query för att räkna alla olika typer av incidenter och lägg ihop med Incident table för att få fram relationen/ Query to count different types of incidents :)
            $sqlCountIncidents = "SELECT Pr_IncidentType.incident AS IncidentType, COUNT(*) AS TotalIncidents
                                  FROM Pr_Incident
                                  INNER JOIN Pr_IncidentType ON Pr_Incident.incidentTypeId = Pr_IncidentType.incidentTypeId
                                  GROUP BY Pr_IncidentType.incident";
            $resultCountIncidents = $mysqli->query($sqlCountIncidents);

            // Spotta ut data för varje rad in i tabellen
            if ($resultCountIncidents instanceof mysqli_result && $resultCountIncidents->num_rows > 0) {
                while($row = $resultCountIncidents->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["IncidentType"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["TotalIncidents"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No incident statistics available</td></tr>";
            }

            // Close database connection
            $mysqli->close();
            ?>
        </tbody>
    </table>
</div>
</div>
<!-- Link to Bootstrap JS and jQuery (optional) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
