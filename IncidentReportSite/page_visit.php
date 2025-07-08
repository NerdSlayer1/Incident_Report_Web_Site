<?php


function setPageTitle($title) {
    echo "<title>$title</title>";
}

require_once('template.php');

setPageTitle('Page Visit Track');


include('authentication.php');



$content = '<h1>Incident Progress</h1>';


    $query1 = <<<END
    SELECT * FROM Pr_PageVisit
	order by timeStamp DESC
END;

    $res = $mysqli->query($query1);

    if ($res->num_rows > 0) {

        $content .= '<div class="content">
		
		
<br>
<a href="Page_Visit_Summary.php">Page Visit Summary</a>
		
<br>
		
		
                        <table>
                            <tr>
								<th>Visit Id</th>
                                <th>Page</th>
                                <th>Time Stamp</th>
                                <th>Host Ip</th>
                                <th>User Id</th>
                                <th>Host Web Browser</th>
                            </tr>';

        while ($row = $res->fetch_object()) {
        
		
	$query2 = <<<END
    SELECT userId FROM Pr_UserVisit
	Where visitId = $row->visitId
END;
	
    $res2 = $mysqli->query($query2);
	    if ($res->num_rows > 0) {
			$row2 = $res2->fetch_object(); 
		}
	 
		if(isset($row2->userId)){
								
			$content .= <<<END
                            <tr>
                                <td>{$row->visitId}</td>
                                <td>{$row->page}</td>
                                <td>{$row->timeStamp}</td>
								<td>{$row->hostIp}</td>
								<td>{$row2->userId}</td>
								<td>{$row->hostWebBrowser}</td>
                            </tr>
END;

}
else{
	
	
			$content .= <<<END
                            <tr>
                                <td>{$row->visitId}</td>
                                <td>{$row->page}</td>
                                <td>{$row->timeStamp}</td>
								<td>{$row->hostIp}</td>
								<td></td>
								<td>{$row->hostWebBrowser}</td>
                            </tr>
END;
	
}
        }
		
	$content .= <<<END
	
	</div>
	
END;

    }
	

echo $navigation; 
echo $content;

?>
</body>
</html>
