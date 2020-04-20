<?php
    session_start();

    $servername = "localhost";
    $username = "spage65";
    $password = "Password1";
    $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)

    $conn = new mysqli($servername, $username, $password, $DB_Name); // Create connection
     
    if ($conn->connect_error) // Check connection
      {die("Not Connected: <br>" . $conn->connect_error);}
    else
      {$database_variable = "Connected";}
    
    if ($_SESSION['type'] !== "admin") // Authenticate
      {header("Location: ../summary.php");}
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Notices | Admin</title>
  </head>
</html>