<?php
    session_start();

    include "config.php";
    
    if ($_SESSION['type'] !== "admin") // Authenticate
      {header("Location: ../summary.php"); exit();}
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Notices | Admin</title>
  </head>
</html>