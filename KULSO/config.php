<?php 

    $conn = new mysqli("localhost", "team08", "gKPAGbR3VfvGQ5UgKPAGbR3VfvGQ5U", "team08");
    
    if($conn->connect_error){
       die("Connection failed! ".$conn->connect_error);
    }

?>