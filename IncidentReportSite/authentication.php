<?php
if($_SESSION['userType'] !== "admin"){

echo '<script type="text/javascript">
            alert("You are not allowed to access this page");
            window.location.href = "index.php";
          </script>';
    exit(); 
}
?>