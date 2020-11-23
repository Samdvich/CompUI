<?php
  session_start();

  include "../admin/config.php";
    
  if (!in_array($_SESSION['type'], array('admin','teacher'), true)) // Authenticate
    {header("Location: ../summary.php"); exit();} 
  else {$house_color = 'darkgray';}

  if (isset($_POST['house-change']))
    {unset($_SESSION['house']); /* Instead of resetting the entire session, only reset important variables */ $sql = "UPDATE accounts SET house='" . $_POST['house-change'] . "' WHERE userID=" . $_SESSION['id'] . ""; $_SESSION['house'] = $_POST['house-change'];
    
  if ($conn->query($sql) === TRUE)
    {echo "<p class='response'>Record updated successfully</p>";}
  else
    {echo "<p class='response'> Error updating record:</p>" . $conn->error;}}
    
  $secure = $conn->prepare('SELECT colour, members FROM houses WHERE house = ?');
  $secure->bind_param('s', $_SESSION['house']);
  $secure->execute();
  $secure->store_result();
  $secure->bind_result($house_color, $members);
  $secure->fetch();
        
  if ($_SESSION['type'] == "temp")
    {$sql = "UPDATE accounts SET house='temporary' WHERE userID=". $_SESSION['id'] .""; $conn->query($sql);}
  
  if ($result = $conn->query("SELECT SUM(`". $_SESSION['house'] ."`) AS total FROM competitions"))
    while ($row = $result->fetch_assoc())
      {$points = $row['total'];}
    else
      {$points = 0;}
      
  if ($result = $conn->query("SELECT COUNT(`". $_SESSION['house'] ."`) AS count FROM competitions WHERE (`". $_SESSION['house'] ."`) >0"))
    {while ($row = $result->fetch_assoc())
      {$events = $row['count'];}}
  else
  {$events = 0;}
      
  if ($result = $conn->query("SELECT COUNT('house') AS members FROM accounts WHERE house = '". $_SESSION['house'] ."';"))
    {while ($row = $result->fetch_assoc())
      {$members = $row['members'];}}
      
  /* (CSV) Upload Form */
  if (isset($_FILES['CSVfile'])) {
    $explode = explode(".", $_FILES['CSVfile']['name']);
    $file_name = $_FILES['CSVfile']['name'];
    $file_type = $_FILES['CSVfile']['type'];
    $file_temp = $_FILES['CSVfile']['tmp_name'];
    if ($file_type == "application/octet-stream" || $file_type == "application/vnd.ms-excel" && end($explode) == "csv") {
      echo "<div class='fileinfo'>";
      echo "File Name: <b>" . $file_name . "</b> ";
      echo "</div>";
      $csv = array_map('str_getcsv', file($file_temp));
      $rows = count($csv);
      $count = 1;
      echo "<div class='uploadcontent'>";
      echo $csv[0][0] . " "; // "Student Name"
      echo "<b>" . $csv[0][1] . "</b><br><hr>"; // "Student House"
      while ($rows !== $count) {
      if ((isset($_POST['upload'])) && ($csv[0][1] == "House")) {
        $secure = $conn->prepare("INSERT INTO `accounts` (house, type, email, password) VALUES(?, ?, ?, ?)");
        $student = "student";
        $secure->bind_param('ssss', strtolower($csv[$count][1]), $student, strtolower($csv[$count][0]), password_hash('Password.1', PASSWORD_BCRYPT));
        $secure->execute();
        $secure->close();
      }
      echo "<table><tr>
          <td id='studentname'>" . strtolower($csv[$count][0]) . "</td>
          <td id='house'><b>" . strtolower($csv[$count][1]) . "</b></td>
          </tr></table>";
      $count++;
      }
      echo "</div>";
    }
    elseif ($file_type !== "applcation/octet-stream") {
      echo "not a csv";
    } else {
      echo "plz upload file ";
    }
  }
  /* End (CSV) Upload Form */
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>CompUI Panel</title>
  </head>
  <body>
    <div class='header'>
      <a href='../summary.php' id='home' title='Home'><svg class="bi bi-house-fill" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 011.414 0l6.647 6.646a.5.5 0 01-.708.708L8 2.207 1.354 8.854a.5.5 0 11-.708-.708L7.293 1.5z" clip-rule="evenodd"/></svg></a>
      <p id='heading'><?php echo $_SESSION['house']; ?></p>
    </div>
    
    <nav class='upload'>
    <form class='uploadform' method='POST' enctype='multipart/form-data'>
      <input type='file' name='CSVfile' id='CSVfile' style='display: none;'> <!-- Hidden because it doesn't comply -->
      <label id='selectfile' for='CSVfile'><svg class="bi bi-file-earmark-arrow-up" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/><path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/><path fill-rule="evenodd" d="M5.646 8.854a.5.5 0 0 0 .708 0L8 7.207l1.646 1.647a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708z"/><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-1 0v4a.5.5 0 0 0 .5.5z"/></svg></label>
      <input type='submit' value='Upload' name='upload' id='upload'>
    </form>
    </nav>
        
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
          
      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
          
      body { grid-template-rows: 18% 50px 200px 10%; grid-template-columns: 10% 80% 10% 10%; }
          
      .header { grid-row: 1; grid-column: 1 / 4; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 8% auto 8%; }
      
      #home { grid-column: 1; color: white; margin: auto; }
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; }
      
      .fileinfo { grid-column: 1 / 4; grid-row: 2; color: lightgray; padding: 10px; font-size: 18px; margin: auto; font-family: 'Roboto', regular; }
      
      .uploadcontent { grid-column: 2; grid-row: 3; border: 2px solid black; overflow: scroll; height: 200px; white-space: pre-line; font-family: 'Roboto', regular; padding: 8px; } /* pushes display over 100% when using percentages */
      
      .upload { grid-column: 2; grid-row: 5; }
      
      .uploadform { display: grid; grid-template-rows: 1fr 1fr; }
      
      #selectfile, #CSVfile { grid-row: 1; margin: auto; cursor: pointer; }
      
      #upload { grid-row: 2; all: unset; font-family: 'Roboto', regular; border: 2px solid black; margin: auto; padding: 8px; width: 80px; text-align: center; border-radius: 30px;  } #upload:focus { text-decoration: underline; }
    </style>
  </body>
</html>