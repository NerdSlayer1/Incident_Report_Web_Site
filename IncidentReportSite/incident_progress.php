<?php

function setPageTitle($title) {
    echo "<title>$title</title>";
}


require_once('template.php');

setPageTitle('Incident Progress');

	include('authentication_user.php');
	
$content = '<h1>Incident Progress</h1>';

if (isset($_GET['incidentId'])) {

    $query1 = <<<END
    SELECT * FROM Pr_Status
    WHERE incidentId = {$_GET['incidentId']}
END;

    $query2 = <<<END
    SELECT * FROM Pr_Incident
    WHERE incidentId = {$_GET['incidentId']}
END;

	$statId = 0;
	$query3 = <<<END
	
	SELECT comment FROM Pr_Comment
	WHERE statusId = $statId
	
	
END;

    $res1 = $mysqli->query($query1);
    $res2 = $mysqli->query($query2);

    if ($res1->num_rows > 0) {

        $row2 = $res2->fetch_object();
		
        $content .= '<div class="content">
		
		
                        <table>
                            <tr>
								<th>Status Id</th>
                                <th>Incident Id</th>
                                <th>Assigned Responder</th>
                                <th>Time Stamp</th>
                            </tr>';

        while ($row1 = $res1->fetch_object()) {
        
			$content .= <<<END
                            <tr>
                                <td>{$row1->statusId}</td>
                                <td>{$row2->incidentId}</td>
                                <td>{$row1->statusType}</td>
                                <td>{$row1->timeStamp}</td>
                            </tr>
END;
        }

        $content .= <<<END
		</table>					
		<a href="incident_comment.php?incidentId={$row2->incidentId}">Status Respond History</a>
		</div>
END;
    }
}

echo $navigation; // Assuming $navigation is defined elsewhere
echo $content;

?>
</body>
</html>
