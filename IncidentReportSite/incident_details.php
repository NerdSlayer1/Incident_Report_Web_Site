<?php

function setPageTitle($title) {
    echo "<title>$title</title>";
}

include('template.php');

setPageTitle('Incident Details');

include('authentication_user.php');
	

$content = "";

if (isset($_GET['incidentId'])) {
    
    $query1 = <<<END
    SELECT * FROM Pr_Incident
    WHERE incidentId = '{$_GET['incidentId']}'
END;
    
    $query2 = <<<END
    SELECT * FROM Pr_Status
    WHERE incidentId = '{$_GET['incidentId']}'
END;

    $query3 = <<<END
    SELECT filePath FROM Pr_Attachment
    WHERE incidentId = '{$_GET['incidentId']}'
END;

    $res1  = $mysqli->query($query1) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
    $res2  = $mysqli->query($query2) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
    $res3  = $mysqli->query($query3) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

    if ($res1->num_rows > 0) {
        $row1  = $res1->fetch_object();
        $row2  = $res2->fetch_object();
        
        $typeId = $row1->incidentTypeId;
        $res4 = $mysqli->query("SELECT incident FROM Pr_IncidentType WHERE incidentTypeId = $typeId") or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
        $row4 = $res4->fetch_object();

        $content .= <<<END
        <div class="content">
            Incident id: {$row1->incidentId}
            <br>
            Status Type: {$row2->statusType}
            <br>
            Time of Report: {$row1->timeOfReport}
            <br>
            Incident Type: {$row4->incident}
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
END;

        if ($res3->num_rows > 0) {
            $content .= "Attached Files:<br>";
            while ($row3 = $res3->fetch_object()) {
                $filePath = htmlspecialchars($row3->filePath);
                $fileName = basename($filePath);
                $content .= "<a href='$filePath' target='_blank'>$fileName</a><br>";
            }
        } else {
            $content .= "No attached files.<br>";
        }

        $content .= "</div>";
    }
}

echo $navigation;
echo '<br>';
echo $content;
?>

</body>
</html>
