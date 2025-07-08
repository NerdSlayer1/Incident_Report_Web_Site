<?php
include_once('template.php');
if (isset($_GET['incidentId']) and $_SESSION['userId'] == "admin") {
	$query = <<<END
	DELETE FROM Pr_Incident
	WHERE incidentId = {$_GET['incidentId']}
	
	DELETE FROM Pr_Incident
	WHERE incidentId = {$_GET['incidentId']}
	
	DELETE FROM Pr_Comment
	WHERE incidentId = {$_GET['incidentId']}


END;
$mysqli->query($query);
header('Location:incidents.php');
}
echo $navigation;
?>

</body>
</html>