<?php
    session_start();

    $servername = "localhost";
    $username = "spage65";
    $password = "Password1";
    $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)

    $conn = new mysqli($servername, $username, $password, $DB_Name); // Create connection
     
    if ($conn->connect_error) // Test connection
      {die("Not Connected: <br>" . $conn->connect_error);}
    
    if (!isset($_SESSION['name'])) // Authenticate user
      {header("Location: index.php");}
    
    echo $_GET['name']; // "Using urldecode() on an element in $_GET could have unexpected and dangerous results"
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title><?php echo $_GET['name']; ?></title>
  </head>
</html>