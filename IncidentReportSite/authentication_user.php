<?php
if(empty($_SESSION['userId'])){

echo '<script type="text/javascript">
            alert("You are not allowed to access this page");
            window.location.href = "index.php";
          </script>';
    exit(); 
}
?>