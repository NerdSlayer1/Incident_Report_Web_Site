<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/stylesheet.css">

 
    </head>

    <body>
	
	<nav class="navbar">
	
	
	</nav>
	
	<div class="container">
    
	
	
<?php

session_name('BrkWeb');
session_start();
$host	= "localhost";
$user 	= "burgen24";
$pwd	= "u6i_wzv6S8";
$db		= "burgen24_db";
$mysqli = new mysqli($host, $user, $pwd, $db);


$navigation = <<<EOT
<nav>
<a href="incidents.php">Incidents</a>
<a href="add_incident.php">Add Incident</a>
</nav>
EOT;
?>
