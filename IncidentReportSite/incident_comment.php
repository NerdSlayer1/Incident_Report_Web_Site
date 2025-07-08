<?php

function setPageTitle($title) {
    echo "<title>$title</title>";
}

// Assuming $mysqli is defined elsewhere and connected to the database

require_once('template.php');
include('authentication_user.php');
setPageTitle('Incident Progress');

$content = '<h1>Incident Progress</h1>';

if (isset($_GET['incidentId'])) {

    $query1 = <<<END
    SELECT * FROM Pr_Status
    WHERE incidentId = {$_GET['incidentId']}
END;

    $res1 = $mysqli->query($query1);

    if ($res1->num_rows > 0) {

        $content .= '<div class="content">
                        <table>
                            <tr>
                                <th>Status Id</th>
                                <th>Sender Id</th>
                                <th>Receiver Id</th>
                                <th>Comment Id</th>
                                <th>Comment</th>
                                <th>Assigned Responder</th>
                                <th>Time Stamp</th>
                            </tr>';

        while ($row1 = $res1->fetch_object()) {

            $statId = $row1->statusId;

            // Move the second query here
            $query2 = <<<END
            SELECT * FROM Pr_Comment
            WHERE statusId = $statId
            ORDER BY commentId ASC
END;

            $res2 = $mysqli->query($query2);

            if ($res2->num_rows > 0) {
                while ($row2 = $res2->fetch_object()) {
                    $content .= <<<END
                            <tr>
                                <td>{$row1->statusId}</td>
                                <td>{$row2->senderId}</td>
                                <td>{$row2->receiverId}</td>
                                <td>{$row2->commentId}</td>
                                <td>{$row2->comment}</td>
                                <td>{$row1->assignedResponderId}</td>
                                <td>{$row1->timeStamp}</td>
                            </tr>
END;
                }
            }
        }

        $content .= '</table></div>';
    }
}

echo $navigation; // Assuming $navigation is defined elsewhere
echo $content;

?>
</body>
</html>
