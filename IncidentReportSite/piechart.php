<?php
include_once('template.php');
include('authentication_user.php');
include('authentication.php');

// Database connection HELA BLOCKET ÄR SAMMA SOM ALLTID/ SAME AS ALWAYS HERE
$host = "localhost";
$user = "burgen24";
$pwd = "u6i_wzv6S8";
$db = "burgen24_db";
$mysqli = new mysqli($host, $user, $pwd, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to count incidents for each incident type
$sqlCountIncidents = "SELECT Pr_IncidentType.Incident AS IncidentType, COUNT(*) AS TotalIncidents
                      FROM Pr_Incident
                      INNER JOIN Pr_IncidentType ON Pr_Incident.IncidentTypeId = Pr_IncidentType.IncidentTypeId
                      GROUP BY Pr_IncidentType.Incident";
$resultCountIncidents = $mysqli->query($sqlCountIncidents);

// Check if query executed successfully
if (!$resultCountIncidents) {
    die("Error: " . $mysqli->error);
}

// Prepare data for Google Charts
$chartData = array();
$chartData[] = ['IncidentType', 'TotalIncidents'];

// Fetch data rows and add to chart data array
while($row = $resultCountIncidents->fetch_assoc()) {
    $chartData[] = [$row['IncidentType'], (int)$row['TotalIncidents']];
}

// Close database connection
$mysqli->close();

// Convert chart data to JSON format, Surprising but it works so will stick to it
$chartDataJson = json_encode($chartData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Type Pie Chart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback function to draw the chart
        function drawChart() {
            // Create the data table.
            var data = google.visualization.arrayToDataTable(<?php echo $chartDataJson; ?>);

            // Set chart options
            var options = {
                title: 'Incident Type Distribution',
                pieSliceText: 'label',
                slices: {  0: {offset: 0.2},},
                width: '100%',
                height: '400'
            };

            // Instantiate and draw the chart.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <!-- Navigation bar -->
    <div class="w3-bar w3-black">
        <span class="branding w3-bar-item w3-center w3-mobile">Group 19's Company Page</span>
        <nav class="nav">
            <a href="management.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Management</a>
            <a href="stats.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Status Type Statistic</a>
            <a href="IncidentType_statistics.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Types of Incidents</a>
            <a href="Bar-chart.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Monthly Incident Report</a>
            <a href="Page_Visit_Summary.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Page Visit Summary</a>
        </nav>
    </div>

    <!-- Display the chart -->
    <div class="container mt-4">
        <div id="chart_div"></div>
    </div>
</body>
</html>
