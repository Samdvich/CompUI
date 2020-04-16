<?php
    session_start();

    $servername = "localhost";
    $username = "spage65";
    $password = "Password1";
    $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)

    // Create connection
    $conn = new mysqli($servername, $username, $password, $DB_Name);
     
    // Check connection
      if ($conn->connect_error) {
        die("Not Connected: <br>" . $conn->connect_error);
    }
    else
    $database_variable = "Connected";
    
    if ($_SESSION['type'] !== "admin") {
        header("Location: ../summary.php");
    }
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Accounts | Notices</title>
  </head>
  <body>
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      
      html, body {
        display: grid;
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
      }
      
      body {
        grid-template-rows: 0;
        grid-template-columns: 0;
      }
    </style>
  </body>
</html>