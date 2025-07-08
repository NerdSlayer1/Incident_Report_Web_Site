<?php
include_once('template.php');
include('authentication.php');
include('authentication_user.php');

// Database connection
$host = "localhost";
$user = "burgen24";
$pwd = "u6i_wzv6S8";
$db = "burgen24_db";
$mysqli = new mysqli($host, $user, $pwd, $db);

// Connection good?
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to count amount of reports per month
$sqlCountIncidents = "SELECT DATE_FORMAT(timeOfReport, '%Y-%m') AS IncidentMonth,
                            COUNT(*) AS TotalIncidents
                      FROM Pr_Incident
                      GROUP BY DATE_FORMAT(timeOfReport, '%Y-%m')
                      ORDER BY DATE_FORMAT(timeOfReport, '%Y-%m') ASC";
$resultCountIncidents = $mysqli->query($sqlCountIncidents);

// Check if query went through good or we have a problem?
if (!$resultCountIncidents) {
    die("Error: " . $mysqli->error);
}

// Prepare data for Google Charts
$chartData = array();
$chartData[] = ['Month', 'Total Incidents'];

// Fetch data rows and add to chart data array
while($row = $resultCountIncidents->fetch_assoc()) {
    $chartData[] = [$row['IncidentMonth'], (int)$row['TotalIncidents']];
}

// Close database connection
$mysqli->close();

// Convert chart data to JSON format, still suprises me but it thankfully works :)
$chartDataJson = json_encode($chartData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Incident Report</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--Do we need more CSS?? --!>
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
    <h1>Monthly Incident Report</h1>
</div>
<div class="container">


    <!-- How do we want the chart to look? Display it-->
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
</div>

<!-- Link to Bootstrap JS and jQuery (optional) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Load Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load API and Core packaged, it works 
    google.charts.load('current', {'packages':['corechart']});

    // Set callback to go off when it works
    google.charts.setOnLoadCallback(drawChart);

    // Callback to draw up the chart with data
    function drawChart() {
        // Create the data table.
        var data = google.visualization.arrayToDataTable(<?php echo $chartDataJson; ?>);

        // Set chart options
        var options = {
            title: 'Monthly Incident Report',
            legend: { position: 'none' }, // Remove legend
            chartArea: { width: '80%', height: '80%' },
            hAxis: {
                title: 'Month'
            },
            vAxis: {
                title: 'Total Incidents',
                minValue: 0
            },
            bars: 'vertical' // Vertical bars
        };

        // Instantiate and draw the chart.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
	
	
</script>


	</div>
	
	</div>

</body>
</html>
