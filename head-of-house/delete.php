<?php
    session_start();

    include "../admin/config.php";
  
  if (isset($_SESSION['type']) && (isset($_GET['student'])))
    {
      $secure = $conn->prepare("DELETE FROM `competition_results` WHERE student_name = ?");
      $secure->bind_param("s", urldecode($_GET['student']));
      $secure->execute();
      $secure->close();
      echo $_SESSION['eventname'];
      header("Location: ../head-of-house/event.php?name=". urlencode($_SESSION['eventname']));
    }
  else
    {header("Location: ../login.php"); exit();}
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>Deleting Account</title>
  </head>
</html>