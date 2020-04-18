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
    
    $name = urldecode($_GET['name']); // Decoding the URL encoding in admin/competitions.php (currently not needed, but precautionary)
    echo $name; // Temporary (testing accuracy of the string)
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $name; ?></title>
    </head>
    <body>
    </body>
</html>