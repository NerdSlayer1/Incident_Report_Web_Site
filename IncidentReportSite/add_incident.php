<?php

function setPageTitle($title) {
    echo "<title>$title</title>";
}

include_once('template.php');

setPageTitle('Add Incident');


	include('authentication_user.php');
	

$result = $mysqli->query("SELECT * FROM Pr_IncidentType");
$result2 = $mysqli->query("Select * FROM Pr_AssetType");

$incidentTypeId = $severityType = $description = $var1 = $var2 = $select_incidentType = $affectedAsset = $query1 = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $select_incidentType .= "<option value={$row['incidentTypeId']}>{$row['incident']}</option>";
    }
}

if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $affectedAsset .= "<option value={$row2['assetTypeId']}>{$row2['asset']}</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["dt1"]) OR empty($_POST["tm1"]) OR empty($_POST["dt2"]) OR empty($_POST["tm2"])) {
        $dateErr = "Date and Time is required";
    } else {
        $var1 = $_POST['dt1'] . ' '. $_POST['tm1'];
        $var2 = $_POST['dt2'] . ' ' . $_POST['tm2'];
    }

    if (empty($_POST["incidentTypeId"])) {
        $incTypeErr = "Incident type is required";
    } else {
        if (empty($_POST["severityType"])) {
            $sevTypeErr = "Severity type is required";
        } else {
            if (empty($_POST["description"])) {
                $desErr = "Description is required";
            } else {
                $query1 = <<<END
                INSERT INTO Pr_Incident(incidentTypeId, severityType, description, startTimeOfIncident, endTimeOfIncident, reporterId)
                VALUES({$_POST['incidentTypeId']}, '{$_POST['severityType']}', '{$_POST['description']}', '{$var1}', '{$var2}', {$_SESSION["userId"]})
END;

                $query2 = <<<END
                SELECT userId FROM Pr_User
                LEFT JOIN Pr_Status ON Pr_Status.assignedResponderId = Pr_User.userId
                WHERE userType = 'responder' AND Pr_Status.assignedResponderId IS NULL
END;

                if ($mysqli->query($query1) === TRUE) {
                    $last_incident_id = $mysqli->insert_id;
                    echo "New record created successfully. Last inserted ID is: " . $last_incident_id;

                    $mysqli->query("Insert into Pr_AffectedAssets(incidentId, assetTypeId) 
                                    Values({$last_incident_id}, {$_POST["assetTypeId"]})");

                    $allFilesUploaded = true;
                    $upload_dir = 'uploads/';

                    if (isset($_FILES['files'])) {
                        foreach ($_FILES['files']['name'] as $key => $file_name) {
                            if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
                                $file_tmp_name = $_FILES['files']['tmp_name'][$key];
                                $file_path = $upload_dir . basename($file_name);

                                if (move_uploaded_file($file_tmp_name, $file_path)) {
                                    $mysqli->query("INSERT INTO Pr_Attachment(incidentId, filePath) VALUES({$last_incident_id}, '{$file_path}')");
                                } else {
                                    $allFilesUploaded = false;
                                    break;
                                }
                            } else {
                                $allFilesUploaded = false;
                                break;
                            }
                        }
                    }

                    if (!$allFilesUploaded) {
                        echo "Failed to upload all files.";
                        // If file upload fails, remove the incident and affected asset entries
                        $mysqli->query("DELETE FROM Pr_AffectedAssets WHERE incidentId = {$last_incident_id}");
                        $mysqli->query("DELETE FROM Pr_Incident WHERE incidentId = {$last_incident_id}");
                    } else {
                        $responderResult = $mysqli->query($query2);
                        if ($responderResult->num_rows > 0) {
                            $assignedResponderRow = $responderResult->fetch_object();
                            $assignedResponderId = $assignedResponderRow->userId;

                            $mysqli->query("INSERT INTO Pr_Status (incidentId, statusType, assignedResponderId)
                                            VALUES($last_incident_id, 'pending', $assignedResponderId)");
                        } else {
                            $responderResult = $mysqli->query("SELECT assignedResponderId
                                                               FROM Pr_Status
                                                               WHERE statusType IN ('Pending', 'In progress')
                                                               GROUP BY assignedResponderId
                                                               ORDER BY COUNT(assignedResponderId)
                                                               LIMIT 1");
                            if ($responderResult->num_rows > 0) {
                                $assignedResponderRow = $responderResult->fetch_object();
                                $assignedResponderId = $assignedResponderRow->assignedResponderId;

                                $mysqli->query("INSERT INTO Pr_Status (incidentId, statusType, assignedResponderId)
                                                VALUES($last_incident_id, 'pending', $assignedResponderId)");
                            }
                        }
                    }
                } else {
                    echo "Error: " . $query1 . "<br>" . $mysqli->error;
                }
            }
        }
    }
}

$content = <<<END
<h1>Incident Report Form</h1>
<div class="content">
<form method="post" action="add_incident.php" enctype="multipart/form-data">        
    <br><br><br>
    <label for="timeStamp1">Beginning of the Incident</label><br>
    <input id="dt1" name="dt1" type="date" required>
    <input id="tm1" name="tm1" type="time" required>
    <br><br>
    <label for="timeStamp2">End of the Incident</label><br>
    <input id="dt2" name="dt2" type="date" required>
    <input id="tm2" name="tm2" type="time" required>
    <br><br>
    <label for="incidentTypeId">Incident Type</label><br>            
    <select name="incidentTypeId" id="incidentTypeId">
        <?php print($select_incidentType); ?>
    </select>
    <br><br>
    <label for="severity">Severity Type</label><br>            
    <div>
        <input type="radio" id="low" name="severityType" value="low" checked />
        <label for="low">Low</label>
    </div>
    <div>
        <input type="radio" id="medium" name="severityType" value="medium" />
        <label for="medium">Medium</label>
    </div>
    <div>
        <input type="radio" id="high" name="severityType" value="high" />
        <label for="high">High</label>
    </div>
    <div>
        <input type="radio" id="critical" name="severityType" value="critical" />
        <label for="critical">Critical</label>
    </div>
    <br>
    <label for="assetTypeId">Affected Asset</label><br>            
    <select name="assetTypeId" id="assetTypeId">
        <?php print($affectedAsset); ?>
    </select>
    <br><br>
    <label for="description">Incident Description</label><br>            
    <textarea id="description" name="description" placeholder="Describe the Incident" style="height:200px"></textarea>
    <br><br>
    <label for="files">Select files:</label>
    <input type="file" id="files" name="files[]" multiple><br><br>
    <br><br>
    <input type="reset"> 
    <input type="submit" name="submit" value="Submit">
</form>
</div>
END;

echo $navigation;
echo $content;
?>
</div>
</body>
</html>
