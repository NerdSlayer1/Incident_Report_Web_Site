<?php

function setPageTitle($title) {
    echo "<title>$title</title>";
}

require_once('template.php');

setPageTitle('Page Visit Summary');

include('authentication.php');

// Initialize the content variable
$content = '<h1>Incident Progress</h1>';

$query1 = <<<END
SELECT page, COUNT(*) as page_count FROM Pr_PageVisit
GROUP BY page
ORDER BY timeStamp DESC
END;

$res = $mysqli->query($query1);

// Prepare data for the chart
$chartData = [];
if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        // Use basename to extract the filename from the path
        $filename = basename($row['page']);
        $chartData[] = [$filename, (int)$row['page_count']];
    }
}

// Convert PHP array to JSON
$chartDataJson = json_encode($chartData);

$content .= <<<END


<div id="chart_div"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Page');
        data.addColumn('number', 'Visits');
        
        // Add the data from PHP
        var chartData = $chartDataJson;
        data.addRows(chartData);

        // Set chart options
        var options = {'title':'Incident Page Visits',
                       'width':800,
                       'height':600};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
END;

echo $navigation;
echo $content;

?>
</body>
</html>
