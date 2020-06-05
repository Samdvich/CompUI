<?php
  session_start();
  session_unset();
  session_destroy();
  header('Location: login.php');
  exit();
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>CompUI Logout</title>
  </head>
</html>