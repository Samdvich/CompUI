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
    
    if (!isset($_SESSION['name'])) {
        header("Location: index.php");
    }
    
    echo $_GET['name']; // "Using urldecode() on an element in $_GET could have unexpected and dangerous results"
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $_GET['name']; ?></title>
    </head>
    <body>
    </body>
</html>