<?php


	function setPageTitle($title) {
    echo "<title>$title</title>";
}
	
	include('template.php');
	
	include('authentication.php');
	
	setPageTitle('Incident Details');


$content ="";

if (isset($_GET['incidentId'])) {
	
	$query1 = <<<END
	SELECT * FROM Pr_Incident
	WHERE incidentId = '{$_GET['incidentId']}'
END;
	
	$query2 = <<<END
	
	SELECT * FROM Pr_Status
	WHERE incidentId = '{$_GET['incidentId']}'
	Order By timeStamp DESC LIMIT 1
	
END;

$res1  = $mysqli->query($query1) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
$res2  = $mysqli->query($query2) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);



if ($res1->num_rows > 0){
	
	$row1 	= $res1->fetch_object();
	$row2 	= $res2->fetch_object(); 
	
	$typeId = $row1->incidentTypeId;
	$res3 = $mysqli->query("SELECT incident FROM Pr_IncidentType
							Where incidentTypeId = $typeId") or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
							
	$row3 = $res3->fetch_object();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {


if (isset($_POST['save'])) {
		
        $incidentId = $mysqli->real_escape_string($_GET['incidentId']);
		$statusType = $mysqli->real_escape_string($_POST['statusType']);
        $userId = $mysqli->real_escape_string($_SESSION['userId']);
		
		
		$queryA = "INSERT INTO Pr_Status (incidentId, statusType, assignedResponderId) VALUES ({$incidentId}, '{$statusType}', {$userId})";
        if ($mysqli->query($queryA) === TRUE) {
            echo "Status saved successfully";
        } else {
            echo "Couldn't save status";
        }
}
	
	
	if (isset($_POST['send'])) {
	
	
if (empty($_POST["comment"])){
	
	$dateErr = "Comment is required";

}
else{
	
	$comment = $mysqli->real_escape_string($_POST['comment']);
	
	$query = <<<END
	
	INSERT INTO Pr_Comment (statusId, senderId, receiverId, comment)
	Values({$row2->statusId}, {$_SESSION['userId']}, {$row1->reporterId}, '{$comment}')
END;

if($mysqli->query($query) === TRUE){
	
	echo "Comment inserted successfully";
	
}
else{
	
	echo "Couldn't insert the comment";
	
}
	
}
}
	}	

	$content .= <<<END
	<div class = "content">
	
	Incident id: {$row1->incidentId}
	<br>
	<form method="post" action="incident_edit.php?incidentId={$_GET['incidentId']}">

    <label for="statusType">Status Type:</label>
    <select name="statusType" id="statusType">
        <option value="pending" <?php if($row2->statusType == "pending") echo "selected"; ?>Pending</option>
        <option value="in Progress" <?php if($row2->statusType == "in Progress") echo "selected"; ?>In Progress</option>
        <option value="resolved" <?php if($row2->statusType == "resolved") echo "selected"; ?>Resolved</option>
    </select>

	
</form>

<form action="edit_user.php" method="post">
                <input type="hidden" name="userId" value="<?php echo $userData['userId']; ?>">
                <div>
                    <label for="edit_username">Username:</label>
                    <input type="text" name="edit_username" value="<?php echo $userData['userName']; ?>" required>
                </div>
                <div>
                    <label for="edit_email">Email:</label>
                    <input type="email" name="edit_email" value="<?php echo $userData['mailAddress']; ?>" required>
                </div>
                <div>
                    <label for="edit_fname">First Name:</label>
                    <input type="text" name="edit_fname" value="<?php echo $userData['fname']; ?>" required>
                </div>
                <div>
                    <label for="edit_lname">Last Name:</label>
                    <input type="text" name="edit_lname" value="<?php echo $userData['lname']; ?>" required>
                </div>
                <div>
                    <label for="edit_password">Password:</label>
                    <input type="password" name="edit_password" required>
                </div>
                <div>
                    <label for="edit_userType">User Type:</label>
                    <select name="edit_userType">
                        <option value="admin" <?php if($userData['userType'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="responder" <?php if($userData['userType'] == 'responder') echo 'selected'; ?>>Responder</option>
                        <option value="reporter" <?php if($userData['userType'] == 'reporter') echo 'selected'; ?>>Reporter</option>
                    </select>
                </div>
                <button type="submit" name="submit_edit">Save Changes</button>
            </form>


    <input type="submit" id="save" name="save" value="Save">
	Time of Report: {$row1->timeOfReport}
	<br>
	Incident Type: {$row3->incident}
	<br>
	Severity Type: {$row1->severityType}
	<br>
	Description: {$row1->description}
	<br>
	Start Time of the Incident: {$row1->startTimeOfIncident}
	<br>
	End Time of the Incident: {$row1->endTimeOfIncident}
	
	<br>
	
	
	<br>
	<br>
	</div>

	
END;

}
}
echo $navigation;

echo '<br>';
echo $content;
?>

</body>
</html>