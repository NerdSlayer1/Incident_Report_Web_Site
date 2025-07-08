<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
		
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/stylesheet.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    
 
    </head>

    <body>
	
	<div class="container">
    
<?php

$browser = "";
if (strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]), strtolower("MSIE"))) {
    $browser = "Internet Explorer";
} elseif (strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]), strtolower("Presto"))) {
    $browser = "Opera";
} elseif (strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]), strtolower("CHROME"))) {
    $browser = "Google Chrome";
} elseif (strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]), strtolower("SAFARI"))) {
    $browser = "Safari";
} elseif (strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]), strtolower("FIREFOX"))) {
    $browser = "FIREFOX";
} else {
    $browser = "OTHER";
}

session_name('grp19');
session_start();
$host   = "localhost";
$user   = "burgen24";
$pwd    = "u6i_wzv6S8";
$db     = "burgen24_db";
$mysqli = new mysqli($host, $user, $pwd, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Convert Unix timestamp to MySQL datetime format
$timeStamp = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
$browser = $mysqli->real_escape_string($browser);
$page = $mysqli->real_escape_string($_SERVER['SCRIPT_NAME']);
$hostIp = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);

$query = <<<END
INSERT INTO Pr_PageVisit (page, timeStamp, hostIp, hostWebBrowser)
VALUES ('$page', '$timeStamp', '$hostIp', '$browser')
END;

if (empty($_SESSION['userId'])) {
    $navigation = <<<EOT
    <div class="w3-bar w3-black">
        <span class="branding w3-bar-item w3-center w3-mobile">Group 19's Company Page</span>
        <nav class="nav">
            <a href="incidents.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Incidents</a>
            <a href="add_incident.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Add Incident</a>
            <a href="index.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Log in</a>
        </nav>
    </div>
EOT;
} else {
    $navigation = <<<EOT
    <div class="w3-bar w3-black">
        <span class="branding w3-bar-item w3-center w3-mobile">Group 19's Company Page</span>
        <nav class="nav">
            <a href="incidents.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Incidents</a>
            <a href="add_incident.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Add Incident</a>
EOT;

    if ($_SESSION['userType'] == "admin") {
        $navigation .= <<<EOT
            <a href="management.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Management Page</a>
            <a href="piechart.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Statistics</a>
            <a href="page_visit.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Page Visit Track</a>
EOT;
    }

    if ($mysqli->query($query) === TRUE) {
        $last_visit_id = $mysqli->insert_id;
        $userId = $mysqli->real_escape_string($_SESSION['userId']);
			$query2 = "INSERT INTO Pr_UserVisit (visitId, userId) VALUES ($last_visit_id, $userId)";
        if ($mysqli->query($query2) === FALSE) {
            echo "Couldn't insert the user track: " . $mysqli->error;
        }
    } else {
        echo "Couldn't insert the visit track: " . $mysqli->error;
    }

    $navigation .= <<<EOT
        <span class="log">
            <span class="branding w3-bar-item w3-center w3-mobile">Logged in as {$_SESSION['userName']} - (User Type: {$_SESSION['userType']})</span>
            <a href="logout.php" class="w3-bar-item w3-button w3-mobile w3-hover-blue">Logout</a>
        </span>
EOT;
}

$navigation .= '</nav></div>';

if (!isset($_SESSION['userId'])) {
    if ($mysqli->query($query) === FALSE) {
        echo "Couldn't insert the visit track, user hasn't logged in: " . $mysqli->error;
    }
}

?>
