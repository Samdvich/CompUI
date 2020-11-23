<?php
  $servername = "localhost";
  $username = "spage65";
  $password = "REDACTED";
  $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)
  
  $conn = new mysqli($servername, $username, $password, $DB_Name); # Connection
  
  if ($conn->connect_error) // Check connection
    {die("Not Connected: <br>" . $conn->connect_error);}
  else
    {$database_variable = "Connected";}
?>
