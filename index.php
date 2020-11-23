<?php
  session_start();
  
  if (isset($_SESSION['loggedin']))
    {header("Location: summary.php"); exit();}
  else
    {header("Location: login.php"); exit();}
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>CompUI Redirect</title>
  </head>
</html>