<?php

	function setPageTitle($title) {
    echo "<title>$title</title>";
}
	
	require('template.php');
		
	
	setPageTitle('Incidents');


	include('authentication_user.php');
	

$content = '<h1>Incidents</h1> 
			<div class = "content">';

$query = "";

	
	
if($_SESSION['userType'] == "reporter"){
	
	
$query .= <<<END

SELECT * FROM Pr_Incident
WHERE reporterId = {$_SESSION['userId']}
ORDER BY TimeOfReport DESC

END;
	
	$res = $mysqli->query($query);
	
	
if ($res->num_rows > 0) {

	while ($row = $res->fetch_object()) {
	
		
	$typeId = $row->incidentTypeId;
	$res2 = $mysqli->query("SELECT incident FROM Pr_IncidentType
							Where incidentTypeId = $typeId");	
	
	$row2 = $res2->fetch_object();


		$content .= <<<END

<div class="list">

		{$row->incidentId}|
		{$row2->incident}|
		{$row->severityType}|
		{$row->timeOfReport}
		
		<div class = "links">
		<a href="incident_details.php?incidentId={$row->incidentId}">Incident Details</a>|
		<a href="incident_progress.php?incidentId={$row->incidentId}">Incident Progress</a>


END;


$content .= <<<END

	</div>
</div>

<br>
<hr>
END;

	}
	
$content .= <<<END
</div>
END;




}
}
else if($_SESSION['userType'] == "responder"){

$ID = 0;
	
$resultS = $mysqli->query("SELECT DISTINCT incidentId 
						   FROM Pr_Status
						   WHERE assignedResponderId = {$_SESSION['userId']} 
						   ORDER BY timeStamp DESC");

if($resultS->num_rows > 0){

while($rowS = $resultS->fetch_object()){	
	
	$ID = $rowS->incidentId;

	$res = $mysqli->query("SELECT * FROM Pr_Incident
						   WHERE incidentId = $ID
						   ORDER BY TimeOfReport DESC");
	
if ($res->num_rows > 0) {

	while ($row = $res->fetch_object()) {

	$typeId = $row->incidentTypeId;
	$res2 = $mysqli->query("SELECT incident FROM Pr_IncidentType
							Where incidentTypeId = $typeId");	
	
	$row2 = $res2->fetch_object();


		$content .= <<<END
<div class="list">

		{$row->incidentId}|
		{$row2->incident}|
		{$row->severityType}|
		{$row->timeOfReport}
		
		<div class = "links">
		<a href="incident_details.php?incidentId={$row->incidentId}">Incident Details</a>|
		<a href="incident_progress.php?incidentId={$row->incidentId}">Incident Progress</a>|
		<a href="incident_respond.php?incidentId={$row->incidentId}">Incident Respond</a>
		



END;

	}



$content .= <<<END

	</div>
</div>

<br>
<hr>
END;



}
}
}
	
	
}
else if($_SESSION['userType'] == "admin"){
	
$query .= <<<END

SELECT * FROM Pr_Incident
ORDER BY TimeOfReport DESC

END;
	
	$res = $mysqli->query($query);
	
if ($res->num_rows > 0) {

	while ($row = $res->fetch_object()) {

	$typeId = $row->incidentTypeId;
	$res2 = $mysqli->query("SELECT incident FROM Pr_IncidentType
							Where incidentTypeId = $typeId");	
	
	$row2 = $res2->fetch_object();


		$content .= <<<END
<div class="list">

		{$row->incidentId}|
		{$row2->incident}|
		{$row->severityType}|
		{$row->timeOfReport}
		
		<div class = "links">
		<a href="incident_details.php?incidentId={$row->incidentId}">Incident Details</a>|
		<a href="incident_progress.php?incidentId={$row->incidentId}">Incident Progress</a>|
		<a href="incident_delete.php?id={$row->incidentId}" onclick="return confirm('Are you sure?')">Remove incident</a>

END;


$content .= <<<END

	</div>
</div>

<br>
<hr>
END;


	}




}
}



echo $navigation;
echo $content;

?>

</body>
</html>